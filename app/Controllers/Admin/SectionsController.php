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
        if (!empty($section['metadata'])) {
            $metadata = is_array($section['metadata']) ? $section['metadata'] : (json_decode($section['metadata'], true) ?: []);
        }

        return view('admin/sections/edit', [
            'section' => $section,
            'metadata' => $metadata,
            'meta_title' => "Edit {$section['section_key']} | Section Builder"
        ]);
    }

    public function update(Request $request, string $key): Response
    {
        $section = Section::findByKey($key);
        if (!$section) {
            flash('error', 'Section not found.');
            return Response::redirect('/admin/sections');
        }

        $title = trim((string)$request->input('title', ''));
        $subtitle = trim((string)$request->input('subtitle', ''));
        $content = trim((string)$request->input('content', ''));
        $isActive = (int)$request->input('is_active', 1);
        $sortOrder = (int)$request->input('sort_order', 0);

        // Cheezify Architecture: Merge structured visual inputs or fall back to raw JSON
        $existingMeta = is_array($section['metadata']) ? $section['metadata'] : (json_decode($section['metadata'] ?: '{}', true) ?: []);
        $metaInputs = $request->input('meta');

        if (is_array($metaInputs)) {
            // Process structured inputs
            if (isset($metaInputs['rotating_words'])) {
                $existingMeta['rotating_words'] = array_values(array_filter(array_map('trim', explode(',', (string)$metaInputs['rotating_words']))));
            }
            if (isset($metaInputs['chips'])) {
                $existingMeta['chips'] = array_values(array_filter(array_map('trim', explode(',', (string)$metaInputs['chips']))));
            }
            if (isset($metaInputs['tracker_code'])) {
                $existingMeta['tracker']['code'] = trim((string)$metaInputs['tracker_code']);
            }
            if (isset($metaInputs['tracker_from'])) {
                $existingMeta['tracker']['from'] = trim((string)$metaInputs['tracker_from']);
            }
            if (isset($metaInputs['tracker_to'])) {
                $existingMeta['tracker']['to'] = trim((string)$metaInputs['tracker_to']);
            }
            if (isset($metaInputs['tracker_steps'])) {
                $existingMeta['tracker']['steps'] = array_values(array_filter(array_map('trim', explode(',', (string)$metaInputs['tracker_steps']))));
            }
            if (isset($metaInputs['badge'])) {
                $existingMeta['badge'] = trim((string)$metaInputs['badge']);
            }
            $finalMetaJson = json_encode($existingMeta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            // Fall back to advanced raw JSON editor
            $rawJson = $request->input('metadata');
            if (!empty($rawJson) && is_string($rawJson)) {
                $decoded = json_decode($rawJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    flash('error', 'Invalid JSON syntax in metadata field.');
                    return Response::redirect("/admin/sections/{$key}/edit");
                }
                $finalMetaJson = $rawJson;
            } else {
                $finalMetaJson = '{}';
            }
        }

        Section::updateByKey($key, [
            'title' => $title,
            'subtitle' => $subtitle,
            'content' => $content,
            'metadata' => $finalMetaJson,
            'is_active' => $isActive,
            'sort_order' => $sortOrder
        ]);

        audit_log('update_section', 'section', null, "Updated section: {$key}");
        flash('success', "Section '{$key}' updated successfully!");

        return Response::redirect('/admin/sections');
    }

    /**
     * 1-Click visibility toggle (Cheezify Architecture)
     */
    public function toggle(Request $request, string $key): Response
    {
        $section = Section::findByKey($key);
        if (!$section) {
            flash('error', 'Section not found.');
            return Response::redirect('/admin/sections');
        }

        $newActive = $section['is_active'] ? 0 : 1;
        Section::updateByKey($key, ['is_active' => $newActive]);

        $statusLabel = $newActive ? 'visible' : 'hidden';
        audit_log('toggle_section', 'section', null, "Toggled section {$key} to {$statusLabel}");
        flash('success', "Section '{$key}' is now {$statusLabel} on storefront.");

        return Response::redirect('/admin/sections');
    }
}
