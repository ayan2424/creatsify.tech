<?php

namespace App\Controllers\Admin;

use App\Models\Media;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Media Library Controller (Cheezify Architecture)
 */
class MediaController
{
    public function index(Request $request): Response
    {
        $media = Media::all();

        return view('admin/media/index', [
            'media' => $media,
            'meta_title' => 'Media Library & Assets | Creatsify Admin'
        ]);
    }

    public function upload(Request $request): Response
    {
        $file = $request->file('file');
        if (!$file || empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            if ($request->isAjax()) {
                return Response::json(['success' => false, 'error' => 'No file uploaded or upload error.'], 400);
            }
            flash('error', 'Upload failed. Please check the file.');
            return Response::redirect('/admin/media');
        }

        $uploadDir = app()->getBasePath() . '/public/assets/uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $originalName = basename($file['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'pdf', 'mp4', 'ico'];

        if (!in_array($ext, $allowedExts, true)) {
            if ($request->isAjax()) {
                return Response::json(['success' => false, 'error' => 'File extension not supported.'], 422);
            }
            flash('error', 'Unsupported file type.');
            return Response::redirect('/admin/media');
        }

        // Detect real MIME type via finfo
        $realMime = 'application/octet-stream';
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $detected = finfo_file($finfo, $file['tmp_name']);
            if ($detected) {
                $realMime = $detected;
            }
            finfo_close($finfo);
        } elseif (function_exists('mime_content_type')) {
            $realMime = mime_content_type($file['tmp_name']) ?: 'application/octet-stream';
        }

        // SVG Security Sanitization (Stored XSS Prevention)
        if ($ext === 'svg' || str_contains($realMime, 'svg') || str_contains($realMime, 'xml')) {
            $svgContent = file_get_contents($file['tmp_name']) ?: '';
            $disallowedPatterns = [
                '/<script\b[^>]*>/i',
                '/<iframe\b[^>]*>/i',
                '/<embed\b[^>]*>/i',
                '/<object\b[^>]*>/i',
                '/<!ENTITY\b/i',
                '/\bon\w+\s*=/i', // Matches onclick, onload, onerror, etc.
                '/href=["\']\s*javascript:/i',
                '/xlink:href=["\']\s*javascript:/i'
            ];
            foreach ($disallowedPatterns as $pattern) {
                if (preg_match($pattern, $svgContent)) {
                    if ($request->isAjax()) {
                        return Response::json(['success' => false, 'error' => 'SVG file contains prohibited scripts or tags.'], 422);
                    }
                    flash('error', 'Malicious SVG code detected. Upload rejected.');
                    return Response::redirect('/admin/media');
                }
            }
        }

        $newFileName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $destPath = $uploadDir . '/' . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            $webPath = '/assets/uploads/' . $newFileName;
            $id = Media::create([
                'filename' => $newFileName,
                'original_name' => $originalName,
                'mime_type' => $realMime,
                'file_size' => $file['size'],
                'path' => $webPath
            ]);

            audit_log('upload_media', 'media', $id, "Uploaded media file: {$originalName}");

            if ($request->isAjax()) {
                return Response::json([
                    'success' => true,
                    'url' => asset($webPath),
                    'id' => $id,
                    'filename' => $newFileName
                ]);
            }

            flash('success', 'File uploaded successfully!');
        } else {
            flash('error', 'Could not save file to disk.');
        }

        return Response::redirect('/admin/media');
    }

    public function delete(Request $request, string $id): Response
    {
        Media::delete((int)$id);
        audit_log('delete_media', 'media', (int)$id, "Deleted media #{$id}");
        flash('success', 'Media asset deleted.');
        return Response::redirect('/admin/media');
    }
}
