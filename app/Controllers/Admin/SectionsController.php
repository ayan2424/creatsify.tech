<?php

namespace App\Controllers\Admin;

use App\Models\Section;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Homepage Section Builder Controller (Cheezify Architecture)
 */
class SectionsController
{
    public function index(Request $request): Response
    {
        $sections = Section::all();

        return view('admin/sections/index', [
            'sections' => $sections,
            'meta_title' => 'Homepage Section Builder | Creatsify Admin'
        ]);
    }

    public function edit(Request $request, string $key): Response
    {
        $section = Section::findByKey($key);
        if (!$section) {
            flash('error', 'Section not found.');
            return Response::redirect('/admin/sections');
        }

        $metadata = [];
        if (!empty($section['metadata']) && is_string($section['metadata'])) {
            $metadata = json_decode($section['metadata'], true) ?: [];
        }

        return view('admin/sections/edit', [
            'section' => $section,
            'metadata' => $metadata,
            'meta_title' => "Edit {$section['section_key']} | Section Builder"
        ]);
    }

    public function update(Request $request, string $key): Response
    {
        $title = trim((string)$request->input('title', ''));
        $subtitle = trim((string)$request->input('subtitle', ''));
        $content = trim((string)$request->input('content', ''));
        $isActive = (int)$request->input('is_active', 1);
        $sortOrder = (int)$request->input('sort_order', 0);
        $metadataJson = $request->input('metadata');

        // Validate JSON if provided
        if (!empty($metadataJson) && is_string($metadataJson)) {
            $decoded = json_decode($metadataJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                flash('error', 'Invalid JSON syntax in metadata field.');
                return Response::redirect("/admin/sections/{$key}/edit");
            }
        } else {
            $metadataJson = '{}';
        }

        Section::updateByKey($key, [
            'title' => $title,
            'subtitle' => $subtitle,
            'content' => $content,
            'metadata' => $metadataJson,
            'is_active' => $isActive,
            'sort_order' => $sortOrder
        ]);

        audit_log('update_section', 'section', null, "Updated section: {$key}");
        flash('success', "Section '{$key}' updated successfully!");

        return Response::redirect('/admin/sections');
    }
}
