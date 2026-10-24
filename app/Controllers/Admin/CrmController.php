<?php

namespace App\Controllers\Admin;

use App\Models\CrmClient;
use App\Models\CrmTicket;
use App\Models\CrmNote;
use App\Models\Inquiry;
use App\Models\Project;
use App\Models\TeamMember;
use App\Models\User;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Agency & Portfolio CRM Controller
 * Handles Clients & Deals, Support & Project Tickets, Internal Notes, and Activity Timeline.
 */
class CrmController
{
    // =========================================================================
    // 1. CLIENTS & DEALS DIRECTORY
    // =========================================================================

    public function clients(Request $request): Response
    {
        $stage = $request->get('stage');
        $search = $request->get('search');

        $stage = !empty($stage) ? trim((string)$stage) : null;
        $search = !empty($search) ? trim((string)$search) : null;

        $clients = CrmClient::all($stage, $search);
        $stageMeta = CrmClient::stageCounts();

        return view('admin/crm/clients/index', [
            'clients' => $clients,
            'stageCounts' => $stageMeta['counts'],
            'totalPipelineValue' => $stageMeta['total_value'],
            'activeStage' => $stage,
            'search' => $search,
            'meta_title' => 'Clients & Deal Pipeline | Creatsify CRM'
        ]);
    }

    public function createClient(Request $request): Response
    {
        $inquiryId = $request->get('inquiry_id');
        $inquiry = !empty($inquiryId) ? Inquiry::findById((int)$inquiryId) : null;

        $teamMembers = TeamMember::all(true);

        return view('admin/crm/clients/create', [
            'inquiry' => $inquiry,
            'teamMembers' => $teamMembers,
            'meta_title' => 'Add New Client | Creatsify CRM'
        ]);
    }

    public function storeClient(Request $request): Response
    {
        $name = trim((string)$request->input('name', ''));
        $email = trim((string)$request->input('email', ''));

        if (empty($name) || empty($email)) {
            flash('error', 'Client name and email address are required.');
            return Response::redirect('/admin/crm/clients/create');
        }

        $phone = trim((string)$request->input('phone', ''));
        $whatsapp = trim((string)$request->input('whatsapp', ''));
        if (empty($whatsapp) && !empty($phone)) {
            $whatsapp = clean_phone_number($phone, false);
        }

        $clientId = CrmClient::create([
            'name' => $name,
            'company' => trim((string)$request->input('company', '')) ?: null,
            'email' => $email,
            'phone' => $phone ?: null,
            'whatsapp' => $whatsapp ?: null,
            'stage' => (string)$request->input('stage', 'lead'),
            'deal_value' => (float)$request->input('deal_value', 0.0),
            'currency' => trim((string)$request->input('currency', 'USD')),
            'source' => trim((string)$request->input('source', 'Direct Outreach')),
            'assigned_to' => !empty($request->input('assigned_to')) ? (int)$request->input('assigned_to') : null,
            'city' => trim((string)$request->input('city', '')) ?: null,
            'country' => trim((string)$request->input('country', '')) ?: null,
            'website' => trim((string)$request->input('website', '')) ?: null,
            'tags' => trim((string)$request->input('tags', '')) ?: null,
            'notes_summary' => trim((string)$request->input('notes_summary', '')) ?: null,
            'inquiry_id' => !empty($request->input('inquiry_id')) ? (int)$request->input('inquiry_id') : null
        ]);

        audit_log('create_client', 'crm_client', $clientId, "Created client: {$name} ({$email})");
        flash('success', "Client record created successfully.");

        return Response::redirect('/admin/crm/clients/' . $clientId);
    }

    public function showClient(Request $request, string $id): Response
    {
        $client = CrmClient::findById((int)$id);
        if (!$client) {
            flash('error', 'Client not found.');
            return Response::redirect('/admin/crm/clients');
        }

        $tickets = CrmClient::getTickets((int)$id);
        $notes = CrmClient::getNotes((int)$id);
        $projects = Project::all();

        return view('admin/crm/clients/show', [
            'client' => $client,
            'tickets' => $tickets,
            'notes' => $notes,
            'projects' => $projects,
            'meta_title' => "{$client['name']} &bull; Client File | Creatsify CRM"
        ]);
    }

    public function editClient(Request $request, string $id): Response
    {
        $client = CrmClient::findById((int)$id);
        if (!$client) {
            flash('error', 'Client not found.');
            return Response::redirect('/admin/crm/clients');
        }

        $teamMembers = TeamMember::all(true);

        return view('admin/crm/clients/edit', [
            'client' => $client,
            'teamMembers' => $teamMembers,
            'meta_title' => "Edit {$client['name']} | Creatsify CRM"
        ]);
    }

    public function updateClient(Request $request, string $id): Response
    {
        $client = CrmClient::findById((int)$id);
        if (!$client) {
            flash('error', 'Client not found.');
            return Response::redirect('/admin/crm/clients');
        }

        $name = trim((string)$request->input('name', ''));
        $email = trim((string)$request->input('email', ''));

        if (empty($name) || empty($email)) {
            flash('error', 'Client name and email address are required.');
            return Response::redirect('/admin/crm/clients/' . $id . '/edit');
        }

        $phone = trim((string)$request->input('phone', ''));
        $whatsapp = trim((string)$request->input('whatsapp', ''));
        if (empty($whatsapp) && !empty($phone)) {
            $whatsapp = clean_phone_number($phone, false);
        }

        CrmClient::update((int)$id, [
            'name' => $name,
            'company' => trim((string)$request->input('company', '')) ?: null,
            'email' => $email,
            'phone' => $phone ?: null,
            'whatsapp' => $whatsapp ?: null,
            'stage' => (string)$request->input('stage', 'lead'),
            'deal_value' => (float)$request->input('deal_value', 0.0),
            'currency' => trim((string)$request->input('currency', 'USD')),
            'source' => trim((string)$request->input('source', 'Direct Outreach')),
            'assigned_to' => !empty($request->input('assigned_to')) ? (int)$request->input('assigned_to') : null,
            'city' => trim((string)$request->input('city', '')) ?: null,
            'country' => trim((string)$request->input('country', '')) ?: null,
            'website' => trim((string)$request->input('website', '')) ?: null,
            'tags' => trim((string)$request->input('tags', '')) ?: null,
            'notes_summary' => trim((string)$request->input('notes_summary', '')) ?: null
        ]);

        audit_log('update_client', 'crm_client', (int)$id, "Updated client #{$id}: {$name}");
        flash('success', 'Client profile updated.');

        return Response::redirect('/admin/crm/clients/' . $id);
    }

    public function updateClientStage(Request $request, string $id): Response
    {
        $stage = trim((string)$request->input('stage', 'lead'));
        CrmClient::update((int)$id, ['stage' => $stage]);

        audit_log('update_client_stage', 'crm_client', (int)$id, "Moved client #{$id} to stage: {$stage}");
        flash('success', "Client stage updated to {$stage}.");

        $redirect = $request->input('redirect', '/admin/crm/clients/' . $id);
        return Response::redirect($redirect);
    }

    public function deleteClient(Request $request, string $id): Response
    {
        CrmClient::delete((int)$id);
        audit_log('delete_client', 'crm_client', (int)$id, "Deleted client #{$id}");
        flash('success', 'Client record and associated notes removed.');
        return Response::redirect('/admin/crm/clients');
    }

    public function convertInquiry(Request $request, string $id): Response
    {
        $clientId = CrmClient::createFromInquiry((int)$id);
        if ($clientId) {
            flash('success', 'Inquiry successfully converted to CRM Client Profile.');
            return Response::redirect('/admin/crm/clients/' . $clientId);
        }

        flash('error', 'Unable to convert inquiry.');
        return Response::redirect('/admin/inquiries/' . $id);
    }

    // =========================================================================
    // 2. SUPPORT & PROJECT TICKETS
    // =========================================================================

    public function tickets(Request $request): Response
    {
        $status = $request->get('status');
        $priority = $request->get('priority');
        $search = $request->get('search');

        $status = !empty($status) ? trim((string)$status) : null;
        $priority = !empty($priority) ? trim((string)$priority) : null;
        $search = !empty($search) ? trim((string)$search) : null;

        $tickets = CrmTicket::all($status, $priority, $search);
        $statusCounts = CrmTicket::statusCounts();

        return view('admin/crm/tickets/index', [
            'tickets' => $tickets,
            'statusCounts' => $statusCounts,
            'activeStatus' => $status,
            'activePriority' => $priority,
            'search' => $search,
            'meta_title' => 'Client Support & Project Tickets | Creatsify CRM'
        ]);
    }

    public function createTicket(Request $request): Response
    {
        $clientId = $request->get('client_id');
        $client = !empty($clientId) ? CrmClient::findById((int)$clientId) : null;

        $clients = CrmClient::all();
        $projects = Project::all();
        $users = User::all();

        return view('admin/crm/tickets/create', [
            'preselectedClient' => $client,
            'clients' => $clients,
            'projects' => $projects,
            'users' => $users,
            'ticketNumber' => CrmTicket::generateTicketNumber(),
            'meta_title' => 'Open New Support Ticket | Creatsify CRM'
        ]);
    }

    public function storeTicket(Request $request): Response
    {
        $subject = trim((string)$request->input('subject', ''));
        $description = trim((string)$request->input('description', ''));
        $clientName = trim((string)$request->input('client_name', ''));
        $clientEmail = trim((string)$request->input('client_email', ''));

        $clientId = !empty($request->input('client_id')) ? (int)$request->input('client_id') : null;
        if ($clientId && (empty($clientName) || empty($clientEmail))) {
            $client = CrmClient::findById($clientId);
            if ($client) {
                $clientName = $client['name'];
                $clientEmail = $client['email'];
            }
        }

        if (empty($subject) || empty($description) || empty($clientName)) {
            flash('error', 'Subject, description, and client name are required.');
            return Response::redirect('/admin/crm/tickets/create');
        }

        $ticketId = CrmTicket::create([
            'client_id' => $clientId,
            'client_name' => $clientName,
            'client_email' => $clientEmail,
            'client_phone' => trim((string)$request->input('client_phone', '')) ?: null,
            'subject' => $subject,
            'category' => (string)$request->input('category', 'maintenance'),
            'priority' => (string)$request->input('priority', 'medium'),
            'status' => 'open',
            'assigned_to' => !empty($request->input('assigned_to')) ? (int)$request->input('assigned_to') : (int)(auth()['id'] ?? 1),
            'project_id' => !empty($request->input('project_id')) ? (int)$request->input('project_id') : null,
            'description' => $description,
            'due_date' => !empty($request->input('due_date')) ? (string)$request->input('due_date') : null
        ]);

        $ticket = CrmTicket::findById($ticketId);

        // Add initial ticket opening message
        CrmTicket::addReply([
            'ticket_id' => $ticketId,
            'user_id' => (int)(auth()['id'] ?? 1),
            'author_name' => auth()['name'] ?? 'Super Admin',
            'author_type' => 'staff',
            'message' => $description,
            'is_internal_note' => 0
        ]);

        audit_log('create_ticket', 'crm_ticket', $ticketId, "Created ticket {$ticket['ticket_number']}: {$subject}");
        flash('success', "Ticket {$ticket['ticket_number']} opened successfully.");

        return Response::redirect('/admin/crm/tickets/' . $ticketId);
    }

    public function showTicket(Request $request, string $id): Response
    {
        $ticket = CrmTicket::findById((int)$id);
        if (!$ticket) {
            flash('error', 'Ticket not found.');
            return Response::redirect('/admin/crm/tickets');
        }

        $replies = CrmTicket::getReplies((int)$id);
        $users = User::all();

        return view('admin/crm/tickets/show', [
            'ticket' => $ticket,
            'replies' => $replies,
            'users' => $users,
            'meta_title' => "Ticket {$ticket['ticket_number']} &bull; {$ticket['subject']} | Creatsify CRM"
        ]);
    }

    public function updateTicketStatus(Request $request, string $id): Response
    {
        $status = trim((string)$request->input('status', 'in_progress'));
        $assignedTo = !empty($request->input('assigned_to')) ? (int)$request->input('assigned_to') : null;
        $priority = !empty($request->input('priority')) ? (string)$request->input('priority') : null;

        $updateData = ['status' => $status];
        if ($assignedTo !== null) {
            $updateData['assigned_to'] = $assignedTo;
        }
        if ($priority !== null) {
            $updateData['priority'] = $priority;
        }

        CrmTicket::update((int)$id, $updateData);

        audit_log('update_ticket_status', 'crm_ticket', (int)$id, "Updated ticket #{$id} status to: {$status}");
        flash('success', "Ticket status updated to {$status}.");

        return Response::redirect('/admin/crm/tickets/' . $id);
    }

    public function replyTicket(Request $request, string $id): Response
    {
        $ticket = CrmTicket::findById((int)$id);
        if (!$ticket) {
            flash('error', 'Ticket not found.');
            return Response::redirect('/admin/crm/tickets');
        }

        $message = trim((string)$request->input('message', ''));
        if (empty($message)) {
            flash('error', 'Message cannot be empty.');
            return Response::redirect('/admin/crm/tickets/' . $id);
        }

        $isInternal = (int)($request->input('is_internal_note', 0));
        $updateStatus = trim((string)$request->input('update_status', ''));

        CrmTicket::addReply([
            'ticket_id' => (int)$id,
            'user_id' => (int)(auth()['id'] ?? 1),
            'author_name' => auth()['name'] ?? 'Super Admin',
            'author_type' => 'staff',
            'message' => $message,
            'is_internal_note' => $isInternal
        ]);

        if (!empty($updateStatus) && $updateStatus !== $ticket['status']) {
            CrmTicket::update((int)$id, ['status' => $updateStatus]);
        }

        audit_log('reply_ticket', 'crm_ticket', (int)$id, ($isInternal ? "Added internal note to ticket #{$id}" : "Replied to ticket #{$id}"));
        flash('success', $isInternal ? 'Internal staff note recorded.' : 'Response posted to ticket.');

        return Response::redirect('/admin/crm/tickets/' . $id);
    }

    public function deleteTicket(Request $request, string $id): Response
    {
        CrmTicket::delete((int)$id);
        audit_log('delete_ticket', 'crm_ticket', (int)$id, "Deleted ticket #{$id}");
        flash('success', 'Ticket and replies removed.');
        return Response::redirect('/admin/crm/tickets');
    }

    // =========================================================================
    // 3. CRM NOTES, MEETING LOGS & ACTIVITY
    // =========================================================================

    public function notes(Request $request): Response
    {
        $type = $request->get('type');
        $clientId = $request->get('client_id');
        $search = $request->get('search');

        $type = !empty($type) ? trim((string)$type) : null;
        $clientId = !empty($clientId) ? (int)$clientId : null;
        $search = !empty($search) ? trim((string)$search) : null;

        $notes = CrmNote::all($type, $clientId, $search);
        $typeCounts = CrmNote::typeCounts();
        $pendingFollowUps = CrmNote::pendingFollowUps();
        $clients = CrmClient::all();

        return view('admin/crm/notes/index', [
            'notes' => $notes,
            'typeCounts' => $typeCounts,
            'pendingFollowUps' => $pendingFollowUps,
            'clients' => $clients,
            'activeType' => $type,
            'activeClientId' => $clientId,
            'search' => $search,
            'meta_title' => 'Internal Notes & Activity Logs | Creatsify CRM'
        ]);
    }

    public function storeNote(Request $request): Response
    {
        $title = trim((string)$request->input('title', ''));
        $content = trim((string)$request->input('content', ''));

        if (empty($title) || empty($content)) {
            flash('error', 'Note title and content are required.');
            $back = $request->input('redirect', '/admin/crm/notes');
            return Response::redirect($back);
        }

        $clientId = !empty($request->input('client_id')) ? (int)$request->input('client_id') : null;
        $ticketId = !empty($request->input('ticket_id')) ? (int)$request->input('ticket_id') : null;
        $inquiryId = !empty($request->input('inquiry_id')) ? (int)$request->input('inquiry_id') : null;

        $noteId = CrmNote::create([
            'client_id' => $clientId,
            'ticket_id' => $ticketId,
            'inquiry_id' => $inquiryId,
            'user_id' => (int)(auth()['id'] ?? 1),
            'author_name' => auth()['name'] ?? 'Super Admin',
            'type' => (string)$request->input('type', 'note'),
            'title' => $title,
            'content' => $content,
            'follow_up_date' => !empty($request->input('follow_up_date')) ? (string)$request->input('follow_up_date') : null,
            'is_pinned' => (int)($request->input('is_pinned', 0))
        ]);

        audit_log('create_crm_note', 'crm_note', $noteId, "Added CRM note: {$title}");
        flash('success', 'CRM note recorded successfully.');

        $redirect = $request->input('redirect', '/admin/crm/notes');
        return Response::redirect($redirect);
    }

    public function toggleNotePin(Request $request, string $id): Response
    {
        CrmNote::togglePin((int)$id);
        flash('success', 'Note pin status updated.');
        $redirect = $request->input('redirect', '/admin/crm/notes');
        return Response::redirect($redirect);
    }

    public function toggleFollowUp(Request $request, string $id): Response
    {
        CrmNote::toggleFollowUp((int)$id);
        flash('success', 'Follow-up status marked as complete.');
        $redirect = $request->input('redirect', '/admin/crm/notes');
        return Response::redirect($redirect);
    }

    public function deleteNote(Request $request, string $id): Response
    {
        CrmNote::delete((int)$id);
        audit_log('delete_crm_note', 'crm_note', (int)$id, "Deleted CRM note #{$id}");
        flash('success', 'Note removed.');
        $redirect = $request->input('redirect', '/admin/crm/notes');
        return Response::redirect($redirect);
    }
}
