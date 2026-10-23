<?php

namespace App\Controllers\Admin;

use App\Models\Project;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Projects & Portfolio Manager
 */
class ProjectsController
{
    public function index(Request $request): Response
    {
        $category = $request->get('category');
        $category = !empty($category) ? trim((string)$category) : null;
        $projects = Project::allByCategory($category);
        $counts = Project::categoryCounts();

        return view('admin/projects/index', [
            'projects' => $projects,
            'counts' => $counts,
            'activeCategory' => $category,
            'meta_title' => 'Manage Portfolio Projects | Creatsify Admin'
        ]);
    }

    public function create(Request $request): Response
    {
        return view('admin/projects/create', [
            'meta_title' => 'New Portfolio Project | Creatsify Admin'
        ]);
    }

    public function store(Request $request): Response
    {
        $title = trim((string)$request->input('title', ''));
        $slug = trim((string)$request->input('slug', '')) ?: strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
        $category = trim((string)$request->input('category', 'saas'));
        $client = trim((string)$request->input('client', ''));
        $featured = (int)$request->input('featured', 0);
        $summary = trim((string)$request->input('summary', ''));
        $description = trim((string)$request->input('description', ''));
        $imageUrl = trim((string)$request->input('image_url', '/assets/brand/creatsify-icon.png'));
        $liveUrl = trim((string)$request->input('live_url', ''));
        $techStack = trim((string)$request->input('tech_stack', ''));
        $status = trim((string)$request->input('status', 'Shipped'));
        $sortOrder = (int)$request->input('sort_order', 0);

        if (empty($title)) {
            flash('error', 'Project title is required.');
            return Response::redirect('/admin/projects/create');
        }

        $id = Project::create([
            'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'client' => $client,
            'featured' => $featured,
            'summary' => $summary,
            'description' => $description,
            'image_url' => $imageUrl,
            'live_url' => $liveUrl,
            'tech_stack' => $techStack,
            'status' => $status,
            'sort_order' => $sortOrder
        ]);

        audit_log('create_project', 'project', $id, "Created project: {$title}");
        flash('success', "Project '{$title}' created successfully!");

        return Response::redirect('/admin/projects');
    }

    public function edit(Request $request, string $id): Response
    {
        $project = Project::findById((int)$id);
        if (!$project) {
            flash('error', 'Project not found.');
            return Response::redirect('/admin/projects');
        }

        return view('admin/projects/edit', [
            'project' => $project,
            'meta_title' => "Edit {$project['title']} | Creatsify Admin"
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        $project = Project::findById((int)$id);
        if (!$project) {
            flash('error', 'Project not found.');
            return Response::redirect('/admin/projects');
        }

        $title = trim((string)$request->input('title', ''));
        $slug = trim((string)$request->input('slug', '')) ?: $project['slug'];
        $category = trim((string)$request->input('category', 'saas'));
        $client = trim((string)$request->input('client', ''));
        $featured = (int)$request->input('featured', 0);
        $summary = trim((string)$request->input('summary', ''));
        $description = trim((string)$request->input('description', ''));
        $imageUrl = trim((string)$request->input('image_url', '/assets/brand/creatsify-icon.png'));
        $liveUrl = trim((string)$request->input('live_url', ''));
        $techStack = trim((string)$request->input('tech_stack', ''));
        $status = trim((string)$request->input('status', 'Shipped'));
        $sortOrder = (int)$request->input('sort_order', 0);

        Project::update((int)$id, [
            'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'client' => $client,
            'featured' => $featured,
            'summary' => $summary,
            'description' => $description,
            'image_url' => $imageUrl,
            'live_url' => $liveUrl,
            'tech_stack' => $techStack,
            'status' => $status,
            'sort_order' => $sortOrder
        ]);

        audit_log('update_project', 'project', (int)$id, "Updated project: {$title}");
        flash('success', "Project '{$title}' updated successfully!");

        return Response::redirect('/admin/projects');
    }

    public function toggleFeatured(Request $request, string $id): Response
    {
        $newState = Project::toggleFeatured((int)$id);
        audit_log('toggle_featured_project', 'project', (int)$id, "Toggled featured state to " . ($newState ? 'ON' : 'OFF'));
        flash('success', 'Project featured state updated.');
        return Response::redirect('/admin/projects');
    }

    public function delete(Request $request, string $id): Response
    {
        Project::delete((int)$id);
        audit_log('delete_project', 'project', (int)$id, "Deleted project #{$id}");
        flash('success', 'Project deleted successfully.');
        return Response::redirect('/admin/projects');
    }
}
