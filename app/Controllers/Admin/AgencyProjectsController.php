<?php

namespace App\Controllers\Admin;

use App\Models\AgencyProject;
use App\Models\CrmClient;
use App\Models\TeamMember;
use Core\Request;
use Core\Response;

/** Private delivery workspace for the agency's client work. */
class AgencyProjectsController
{
    public function index(Request $request): Response
    {
        $status = trim((string)$request->get('status', ''));
        $search = trim((string)$request->get('search', ''));
        $projects = AgencyProject::all($status ?: null, $search ?: null);

        return view('admin/agency-projects/index', [
            'projects' => $projects,
            'metrics' => AgencyProject::metrics(),
            'activeStatus' => $status,
            'search' => $search,
            'meta_title' => 'Delivery Workspace | Creatsify Admin',
        ]);
    }

    public function create(Request $request): Response
    {
        $selectedClientId = (int)$request->get('client_id', 0);
        if ($selectedClientId > 0 && !CrmClient::findById($selectedClientId)) {
            $selectedClientId = 0;
        }

        return view('admin/agency-projects/create', [
            'clients' => CrmClient::all(),
            'selectedClientId' => $selectedClientId,
            'meta_title' => 'Create Delivery Project | Creatsify Admin',
        ]);
    }

    public function store(Request $request): Response
    {
        $title = trim((string)$request->input('title', ''));
        if ($title === '') {
            flash('error', 'A delivery project title is required.');
            return Response::redirect('/admin/agency-projects/create');
        }

        $clientId = (int)$request->input('client_id', 0);
        if ($clientId > 0 && !CrmClient::findById($clientId)) {
            flash('error', 'Choose a valid CRM client for this delivery project.');
            return Response::redirect('/admin/agency-projects/create');
        }

        $status = $this->allowed((string)$request->input('status', 'planning'), AgencyProject::STATUSES, 'planning');
        $priority = $this->allowed((string)$request->input('priority', 'medium'), AgencyProject::PRIORITIES, 'medium');
        $projectId = AgencyProject::create([
            'project_code' => $this->nextProjectCode(),
            'client_id' => $clientId ?: null,
            'title' => $title,
            'project_type' => trim((string)$request->input('project_type', 'Custom Development')) ?: 'Custom Development',
            'summary' => trim((string)$request->input('summary', '')) ?: null,
            'scope_notes' => trim((string)$request->input('scope_notes', '')) ?: null,
            'status' => $status,
            'priority' => $priority,
            'deal_value' => max(0, (float)$request->input('deal_value', 0)),
            'currency' => substr(trim((string)$request->input('currency', 'USD')) ?: 'USD', 0, 10),
            'start_date' => $this->dateOrNull($request->input('start_date')),
            'target_date' => $this->dateOrNull($request->input('target_date')),
            'delivered_at' => $status === 'delivered' ? date('Y-m-d H:i:s') : null,
            'created_by' => (int)(auth()['id'] ?? 0) ?: null,
        ]);

        audit_log('create_agency_project', 'agency_project', $projectId, "Created delivery project: {$title}");
        flash('success', 'Delivery workspace created. Add the assigned team and first milestones next.');
        return Response::redirect('/admin/agency-projects/' . $projectId);
    }

    public function show(Request $request, string $id): Response
    {
        $project = $this->projectOrRedirect((int)$id);
        if (!$project) {
            return Response::redirect('/admin/agency-projects');
        }

        return view('admin/agency-projects/show', [
            'project' => $project,
            'members' => AgencyProject::members((int)$id),
            'teamMembers' => TeamMember::allActive(),
            'milestones' => AgencyProject::milestones((int)$id),
            'tasks' => AgencyProject::tasks((int)$id),
            'notes' => AgencyProject::notes((int)$id),
            'meta_title' => $project['title'] . ' | Delivery Workspace',
        ]);
    }

    public function updateStatus(Request $request, string $id): Response
    {
        $project = $this->projectOrRedirect((int)$id);
        if (!$project) {
            return Response::redirect('/admin/agency-projects');
        }
        $status = $this->allowed((string)$request->input('status'), AgencyProject::STATUSES, $project['status']);
        AgencyProject::updateStatus((int)$id, $status);
        audit_log('update_agency_project_status', 'agency_project', (int)$id, "Moved project to {$status}");
        flash('success', 'Project status updated.');
        return Response::redirect('/admin/agency-projects/' . $id);
    }

    public function assignMember(Request $request, string $id): Response
    {
        if (!$this->projectOrRedirect((int)$id)) {
            return Response::redirect('/admin/agency-projects');
        }
        $teamMemberId = (int)$request->input('team_member_id', 0);
        $member = TeamMember::findById($teamMemberId);
        if (!$member || !(int)$member['is_active']) {
            flash('error', 'Choose an active agency team member.');
            return Response::redirect('/admin/agency-projects/' . $id);
        }
        $role = trim((string)$request->input('delivery_role', '')) ?: $member['role_title'];
        AgencyProject::assignMember((int)$id, $teamMemberId, substr($role, 0, 120), (int)$request->input('allocation_percent', 100));
        audit_log('assign_agency_project_member', 'agency_project', (int)$id, "Assigned {$member['name']} to delivery project");
        flash('success', "{$member['name']} is assigned to this project.");
        return Response::redirect('/admin/agency-projects/' . $id);
    }

    public function removeMember(Request $request, string $id, string $memberId): Response
    {
        AgencyProject::removeMember((int)$id, (int)$memberId);
        audit_log('remove_agency_project_member', 'agency_project', (int)$id, "Removed assignment #{$memberId}");
        flash('success', 'Team assignment removed.');
        return Response::redirect('/admin/agency-projects/' . $id);
    }

    public function storeMilestone(Request $request, string $id): Response
    {
        if (!$this->projectOrRedirect((int)$id)) {
            return Response::redirect('/admin/agency-projects');
        }
        $title = trim((string)$request->input('title', ''));
        if ($title === '') {
            flash('error', 'Milestone title is required.');
            return Response::redirect('/admin/agency-projects/' . $id);
        }
        $milestoneId = AgencyProject::createMilestone([
            'agency_project_id' => (int)$id,
            'title' => $title,
            'description' => trim((string)$request->input('description', '')) ?: null,
            'status' => 'not_started',
            'amount' => $request->input('amount', '') === '' ? null : max(0, (float)$request->input('amount')),
            'due_date' => $this->dateOrNull($request->input('due_date')),
            'sort_order' => (int)$request->input('sort_order', 0),
        ]);
        audit_log('create_agency_milestone', 'agency_project_milestone', $milestoneId, "Added milestone: {$title}");
        flash('success', 'Milestone added.');
        return Response::redirect('/admin/agency-projects/' . $id . '#milestones');
    }

    public function updateMilestoneStatus(Request $request, string $id, string $milestoneId): Response
    {
        $status = $this->allowed((string)$request->input('status'), AgencyProject::MILESTONE_STATUSES, 'not_started');
        AgencyProject::updateMilestoneStatus((int)$id, (int)$milestoneId, $status);
        audit_log('update_agency_milestone_status', 'agency_project_milestone', (int)$milestoneId, "Moved milestone to {$status}");
        flash('success', 'Milestone status updated.');
        return Response::redirect('/admin/agency-projects/' . $id . '#milestones');
    }

    public function storeTask(Request $request, string $id): Response
    {
        if (!$this->projectOrRedirect((int)$id)) {
            return Response::redirect('/admin/agency-projects');
        }
        $title = trim((string)$request->input('title', ''));
        if ($title === '') {
            flash('error', 'Task title is required.');
            return Response::redirect('/admin/agency-projects/' . $id . '#tasks');
        }
        $milestoneId = (int)$request->input('milestone_id', 0);
        $validMilestoneIds = array_map(static fn(array $milestone): int => (int)$milestone['id'], AgencyProject::milestones((int)$id));
        if (!in_array($milestoneId, $validMilestoneIds, true)) {
            $milestoneId = 0;
        }

        $assigneeId = (int)$request->input('assigned_team_member_id', 0);
        $validAssigneeIds = array_map(static fn(array $member): int => (int)$member['team_member_id'], AgencyProject::members((int)$id));
        if (!in_array($assigneeId, $validAssigneeIds, true)) {
            $assigneeId = 0;
        }

        $taskId = AgencyProject::createTask([
            'agency_project_id' => (int)$id,
            'milestone_id' => $milestoneId ?: null,
            'assigned_team_member_id' => $assigneeId ?: null,
            'title' => $title,
            'description' => trim((string)$request->input('description', '')) ?: null,
            'status' => 'todo',
            'priority' => $this->allowed((string)$request->input('priority', 'medium'), AgencyProject::PRIORITIES, 'medium'),
            'due_date' => $this->dateOrNull($request->input('due_date')),
        ]);
        audit_log('create_agency_task', 'agency_project_task', $taskId, "Added task: {$title}");
        flash('success', 'Task added.');
        return Response::redirect('/admin/agency-projects/' . $id . '#tasks');
    }

    public function updateTaskStatus(Request $request, string $id, string $taskId): Response
    {
        $status = $this->allowed((string)$request->input('status'), AgencyProject::TASK_STATUSES, 'todo');
        AgencyProject::updateTaskStatus((int)$id, (int)$taskId, $status);
        audit_log('update_agency_task_status', 'agency_project_task', (int)$taskId, "Moved task to {$status}");
        flash('success', 'Task status updated.');
        return Response::redirect('/admin/agency-projects/' . $id . '#tasks');
    }

    public function storeNote(Request $request, string $id): Response
    {
        if (!$this->projectOrRedirect((int)$id)) {
            return Response::redirect('/admin/agency-projects');
        }
        $body = trim((string)$request->input('body', ''));
        if ($body === '') {
            flash('error', 'Write a note before saving it.');
            return Response::redirect('/admin/agency-projects/' . $id . '#notes');
        }
        $user = auth();
        $noteId = AgencyProject::addNote((int)$id, (int)($user['id'] ?? 0) ?: null, (string)($user['name'] ?? 'Creatsify Team'), $body);
        audit_log('create_agency_project_note', 'agency_project_note', $noteId, 'Added internal delivery note');
        flash('success', 'Internal note saved.');
        return Response::redirect('/admin/agency-projects/' . $id . '#notes');
    }

    private function projectOrRedirect(int $id): ?array
    {
        $project = AgencyProject::findById($id);
        if (!$project) {
            flash('error', 'Delivery project not found.');
        }
        return $project;
    }

    private function nextProjectCode(): string
    {
        AgencyProject::ensureSchema();
        $prefix = 'AGY-' . date('Y') . '-';
        $last = db()->fetchValue('SELECT project_code FROM agency_projects WHERE project_code LIKE :prefix ORDER BY id DESC LIMIT 1', ['prefix' => $prefix . '%']);
        $next = $last && preg_match('/(\d+)$/', (string)$last, $matches) ? (int)$matches[1] + 1 : 1;
        return $prefix . str_pad((string)$next, 4, '0', STR_PAD_LEFT);
    }

    private function allowed(string $value, array $allowed, string $fallback): string
    {
        return in_array($value, $allowed, true) ? $value : $fallback;
    }

    private function dateOrNull(mixed $value): ?string
    {
        $date = trim((string)$value);
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) ? $date : null;
    }
}
