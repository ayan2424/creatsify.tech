<?php

namespace App\Controllers\Admin;

use App\Models\MemberPortfolio;
use App\Models\TeamMember;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Agency Team & Member Portfolios Controller
 * Manages workers, designers, engineers and their individual portfolios.
 */
class TeamController
{
    public function index(Request $request): Response
    {
        $members = TeamMember::all();

        return view('admin/team/index', [
            'members' => $members,
            'meta_title' => 'Agency Team & Member Portfolios | Creatsify Admin'
        ]);
    }

    public function create(Request $request): Response
    {
        return view('admin/team/create', [
            'meta_title' => 'Add Agency Team Member | Creatsify Admin'
        ]);
    }

    public function store(Request $request): Response
    {
        $name = trim((string)$request->input('name', ''));
        $roleTitle = trim((string)$request->input('role_title', ''));
        $slug = trim((string)$request->input('slug', '')) ?: strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $bio = trim((string)$request->input('bio', ''));
        $avatar = trim((string)$request->input('avatar', '/assets/brand/creatsify-icon.png'));
        $skills = trim((string)$request->input('skills', ''));
        $email = trim((string)$request->input('email', ''));
        $phone = trim((string)$request->input('phone', ''));
        $githubUrl = trim((string)$request->input('github_url', ''));
        $linkedinUrl = trim((string)$request->input('linkedin_url', ''));
        $portfolioUrl = trim((string)$request->input('portfolio_url', ''));
        $sortOrder = (int)$request->input('sort_order', 0);
        $isActive = (int)$request->input('is_active', 1);

        if (empty($name) || empty($roleTitle)) {
            flash('error', 'Name and Role Title are required.');
            return Response::redirect('/admin/team/create');
        }

        $id = TeamMember::create([
            'name' => $name,
            'slug' => $slug,
            'role_title' => $roleTitle,
            'bio' => $bio,
            'avatar' => $avatar,
            'skills' => $skills,
            'email' => $email,
            'phone' => $phone,
            'github_url' => $githubUrl,
            'linkedin_url' => $linkedinUrl,
            'portfolio_url' => $portfolioUrl,
            'sort_order' => $sortOrder,
            'is_active' => $isActive
        ]);

        audit_log('create_team_member', 'team_member', $id, "Added team member: {$name} ({$roleTitle})");
        flash('success', "Team member '{$name}' created successfully!");

        return Response::redirect('/admin/team');
    }

    public function edit(Request $request, string $id): Response
    {
        $member = TeamMember::findById((int)$id);
        if (!$member) {
            flash('error', 'Team member not found.');
            return Response::redirect('/admin/team');
        }

        return view('admin/team/edit', [
            'member' => $member,
            'meta_title' => "Edit {$member['name']} | Creatsify Admin"
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        $member = TeamMember::findById((int)$id);
        if (!$member) {
            flash('error', 'Team member not found.');
            return Response::redirect('/admin/team');
        }

        $name = trim((string)$request->input('name', ''));
        $roleTitle = trim((string)$request->input('role_title', ''));
        $slug = trim((string)$request->input('slug', '')) ?: $member['slug'];
        $bio = trim((string)$request->input('bio', ''));
        $avatar = trim((string)$request->input('avatar', '/assets/brand/creatsify-icon.png'));
        $skills = trim((string)$request->input('skills', ''));
        $email = trim((string)$request->input('email', ''));
        $phone = trim((string)$request->input('phone', ''));
        $githubUrl = trim((string)$request->input('github_url', ''));
        $linkedinUrl = trim((string)$request->input('linkedin_url', ''));
        $portfolioUrl = trim((string)$request->input('portfolio_url', ''));
        $sortOrder = (int)$request->input('sort_order', 0);
        $isActive = (int)$request->input('is_active', 1);

        TeamMember::update((int)$id, [
            'name' => $name,
            'slug' => $slug,
            'role_title' => $roleTitle,
            'bio' => $bio,
            'avatar' => $avatar,
            'skills' => $skills,
            'email' => $email,
            'phone' => $phone,
            'github_url' => $githubUrl,
            'linkedin_url' => $linkedinUrl,
            'portfolio_url' => $portfolioUrl,
            'sort_order' => $sortOrder,
            'is_active' => $isActive
        ]);

        audit_log('update_team_member', 'team_member', (int)$id, "Updated team member: {$name}");
        flash('success', "Team member '{$name}' updated successfully!");

        return Response::redirect('/admin/team');
    }

    public function toggle(Request $request, string $id): Response
    {
        $newState = TeamMember::toggleActive((int)$id);
        audit_log('toggle_team_member', 'team_member', (int)$id, "Toggled team member active state to " . ($newState ? 'ACTIVE' : 'INACTIVE'));
        flash('success', 'Member status updated.');
        return Response::redirect('/admin/team');
    }

    public function delete(Request $request, string $id): Response
    {
        TeamMember::delete((int)$id);
        audit_log('delete_team_member', 'team_member', (int)$id, "Deleted team member #{$id}");
        flash('success', 'Team member removed.');
        return Response::redirect('/admin/team');
    }

    // ==========================================
    // MEMBER INDIVIDUAL PORTFOLIO MANAGEMENT
    // ==========================================

    public function portfolio(Request $request, string $id): Response
    {
        $member = TeamMember::findById((int)$id);
        if (!$member) {
            flash('error', 'Team member not found.');
            return Response::redirect('/admin/team');
        }

        $items = MemberPortfolio::forMember((int)$id);

        return view('admin/team/portfolio', [
            'member' => $member,
            'items' => $items,
            'meta_title' => "{$member['name']}'s Portfolio Desk | Creatsify Admin"
        ]);
    }

    public function createPortfolio(Request $request, string $memberId): Response
    {
        $member = TeamMember::findById((int)$memberId);
        if (!$member) {
            flash('error', 'Team member not found.');
            return Response::redirect('/admin/team');
        }

        return view('admin/team/portfolio_create', [
            'member' => $member,
            'meta_title' => "Add Portfolio Item for {$member['name']} | Creatsify Admin"
        ]);
    }

    public function storePortfolio(Request $request, string $memberId): Response
    {
        $member = TeamMember::findById((int)$memberId);
        if (!$member) {
            flash('error', 'Team member not found.');
            return Response::redirect('/admin/team');
        }

        $title = trim((string)$request->input('title', ''));
        $slug = trim((string)$request->input('slug', '')) ?: strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
        $category = trim((string)$request->input('category', 'Full-Stack Development'));
        $client = trim((string)$request->input('client', ''));
        $roleInProject = trim((string)$request->input('role_in_project', $member['role_title']));
        $summary = trim((string)$request->input('summary', ''));
        $description = trim((string)$request->input('description', ''));
        $imageUrl = trim((string)$request->input('image_url', '/assets/images/hero-artwork.jpg'));
        $projectUrl = trim((string)$request->input('project_url', ''));
        $techStack = trim((string)$request->input('tech_stack', ''));
        $completionDate = trim((string)$request->input('completion_date', date('Y')));
        $isFeatured = (int)$request->input('is_featured', 0);
        $sortOrder = (int)$request->input('sort_order', 0);

        if (empty($title)) {
            flash('error', 'Project title is required.');
            return Response::redirect("/admin/team/{$memberId}/portfolio/create");
        }

        $id = MemberPortfolio::create([
            'member_id' => (int)$memberId,
            'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'client' => $client,
            'role_in_project' => $roleInProject,
            'summary' => $summary,
            'description' => $description,
            'image_url' => $imageUrl,
            'project_url' => $projectUrl,
            'tech_stack' => $techStack,
            'completion_date' => $completionDate,
            'is_featured' => $isFeatured,
            'sort_order' => $sortOrder
        ]);

        audit_log('create_member_portfolio', 'member_portfolio', $id, "Added portfolio project '{$title}' for {$member['name']}");
        flash('success', "Portfolio item '{$title}' added successfully!");

        return Response::redirect("/admin/team/{$memberId}/portfolio");
    }

    public function editPortfolio(Request $request, string $memberId, string $itemId): Response
    {
        $member = TeamMember::findById((int)$memberId);
        $item = MemberPortfolio::findById((int)$itemId);
        if (!$member || !$item) {
            flash('error', 'Portfolio item or team member not found.');
            return Response::redirect('/admin/team');
        }

        return view('admin/team/portfolio_edit', [
            'member' => $member,
            'item' => $item,
            'meta_title' => "Edit {$item['title']} | Creatsify Admin"
        ]);
    }

    public function updatePortfolio(Request $request, string $memberId, string $itemId): Response
    {
        $member = TeamMember::findById((int)$memberId);
        $item = MemberPortfolio::findById((int)$itemId);
        if (!$member || !$item) {
            flash('error', 'Portfolio item or team member not found.');
            return Response::redirect('/admin/team');
        }

        $title = trim((string)$request->input('title', ''));
        $slug = trim((string)$request->input('slug', '')) ?: $item['slug'];
        $category = trim((string)$request->input('category', 'Full-Stack Development'));
        $client = trim((string)$request->input('client', ''));
        $roleInProject = trim((string)$request->input('role_in_project', $item['role_in_project']));
        $summary = trim((string)$request->input('summary', ''));
        $description = trim((string)$request->input('description', ''));
        $imageUrl = trim((string)$request->input('image_url', $item['image_url']));
        $projectUrl = trim((string)$request->input('project_url', ''));
        $techStack = trim((string)$request->input('tech_stack', ''));
        $completionDate = trim((string)$request->input('completion_date', $item['completion_date']));
        $isFeatured = (int)$request->input('is_featured', 0);
        $sortOrder = (int)$request->input('sort_order', 0);

        MemberPortfolio::update((int)$itemId, [
            'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'client' => $client,
            'role_in_project' => $roleInProject,
            'summary' => $summary,
            'description' => $description,
            'image_url' => $imageUrl,
            'project_url' => $projectUrl,
            'tech_stack' => $techStack,
            'completion_date' => $completionDate,
            'is_featured' => $isFeatured,
            'sort_order' => $sortOrder
        ]);

        audit_log('update_member_portfolio', 'member_portfolio', (int)$itemId, "Updated portfolio project '{$title}' for {$member['name']}");
        flash('success', "Portfolio item '{$title}' updated successfully!");

        return Response::redirect("/admin/team/{$memberId}/portfolio");
    }

    public function togglePortfolioFeatured(Request $request, string $memberId, string $itemId): Response
    {
        $newState = MemberPortfolio::toggleFeatured((int)$itemId);
        audit_log('toggle_member_portfolio_featured', 'member_portfolio', (int)$itemId, "Toggled portfolio featured state to " . ($newState ? 'ON' : 'OFF'));
        flash('success', 'Portfolio featured status updated.');
        return Response::redirect("/admin/team/{$memberId}/portfolio");
    }

    public function deletePortfolio(Request $request, string $memberId, string $itemId): Response
    {
        MemberPortfolio::delete((int)$itemId);
        audit_log('delete_member_portfolio', 'member_portfolio', (int)$itemId, "Deleted portfolio item #{$itemId}");
        flash('success', 'Portfolio project removed.');
        return Response::redirect("/admin/team/{$memberId}/portfolio");
    }
}
