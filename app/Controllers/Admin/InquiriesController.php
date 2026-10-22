<?php

namespace App\Controllers\Admin;

use App\Models\Inquiry;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Client Inquiries Controller
 */
class InquiriesController
{
    public function index(Request $request): Response
    {
        $inquiries = Inquiry::all();

        return view('admin/inquiries/index', [
            'inquiries' => $inquiries,
            'meta_title' => 'Client Inquiries | Creatsify Admin'
        ]);
    }

    public function show(Request $request, string $id): Response
    {
        $inquiry = Inquiry::findById((int)$id);
        if (!$inquiry) {
            flash('error', 'Inquiry not found.');
            return Response::redirect('/admin/inquiries');
        }

        return view('admin/inquiries/show', [
            'inquiry' => $inquiry,
            'meta_title' => "Inquiry from {$inquiry['name']} | Creatsify Admin"
        ]);
    }

    public function updateStatus(Request $request, string $id): Response
    {
        $status = trim((string)$request->input('status', 'in_discussion'));
        Inquiry::updateStatus((int)$id, $status);

        audit_log('update_inquiry_status', 'inquiry', (int)$id, "Changed inquiry status to {$status}");
        flash('success', "Inquiry marked as {$status}.");

        return Response::redirect('/admin/inquiries');
    }

    public function delete(Request $request, string $id): Response
    {
        Inquiry::delete((int)$id);
        audit_log('delete_inquiry', 'inquiry', (int)$id, "Deleted inquiry #{$id}");
        flash('success', 'Inquiry removed.');
        return Response::redirect('/admin/inquiries');
    }
}
