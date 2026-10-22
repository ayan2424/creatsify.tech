<?php

namespace App\Controllers\Admin;

use App\Models\Service;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Services Manager
 */
class ServicesController
{
    public function index(Request $request): Response
    {
        $services = Service::all();

        return view('admin/services/index', [
            'services' => $services,
            'meta_title' => 'Manage Services | Creatsify Admin'
        ]);
    }

    public function create(Request $request): Response
    {
        return view('admin/services/create', [
            'meta_title' => 'New Service | Creatsify Admin'
        ]);
    }

    public function store(Request $request): Response
    {
        $title = trim((string)$request->input('title', ''));
        $slug = trim((string)$request->input('slug', '')) ?: strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
        $icon = trim((string)$request->input('icon', 'server'));
        $shortDesc = trim((string)$request->input('short_description', ''));
        $fullDesc = trim((string)$request->input('full_description', ''));
        $deliverables = trim((string)$request->input('deliverables', ''));
        $sortOrder = (int)$request->input('sort_order', 0);
        $isActive = (int)$request->input('is_active', 1);

        if (empty($title)) {
            flash('error', 'Service title is required.');
            return Response::redirect('/admin/services/create');
        }

        $id = Service::create([
            'title' => $title,
            'slug' => $slug,
            'icon' => $icon,
            'short_description' => $shortDesc,
            'full_description' => $fullDesc,
            'deliverables' => $deliverables,
            'sort_order' => $sortOrder,
            'is_active' => $isActive
        ]);

        audit_log('create_service', 'service', $id, "Created service: {$title}");
        flash('success', "Service '{$title}' created successfully!");

        return Response::redirect('/admin/services');
    }

    public function edit(Request $request, string $id): Response
    {
        $service = Service::findById((int)$id);
        if (!$service) {
            flash('error', 'Service not found.');
            return Response::redirect('/admin/services');
        }

        return view('admin/services/edit', [
            'service' => $service,
            'meta_title' => "Edit {$service['title']} | Creatsify Admin"
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        $title = trim((string)$request->input('title', ''));
        $slug = trim((string)$request->input('slug', ''));
        $icon = trim((string)$request->input('icon', 'server'));
        $shortDesc = trim((string)$request->input('short_description', ''));
        $fullDesc = trim((string)$request->input('full_description', ''));
        $deliverables = trim((string)$request->input('deliverables', ''));
        $sortOrder = (int)$request->input('sort_order', 0);
        $isActive = (int)$request->input('is_active', 1);

        Service::update((int)$id, [
            'title' => $title,
            'slug' => $slug,
            'icon' => $icon,
            'short_description' => $shortDesc,
            'full_description' => $fullDesc,
            'deliverables' => $deliverables,
            'sort_order' => $sortOrder,
            'is_active' => $isActive
        ]);

        audit_log('update_service', 'service', (int)$id, "Updated service: {$title}");
        flash('success', "Service updated successfully!");

        return Response::redirect('/admin/services');
    }

    public function delete(Request $request, string $id): Response
    {
        Service::delete((int)$id);
        audit_log('delete_service', 'service', (int)$id, "Deleted service #{$id}");
        flash('success', 'Service deleted successfully.');
        return Response::redirect('/admin/services');
    }
}
