<?php

namespace App\Controllers;

use App\Helpers\NotificationDispatcher;
use App\Models\Inquiry;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Contact & Project Inquiries Controller
 */
class ContactController
{
    public function index(Request $request): Response
    {
        return view('contact/index', [
            'meta_title' => 'Contact Creatsify | Start Your Project',
            'meta_desc' => 'Tell us what you want to build. Get in direct contact with founder Muhammad Ayan Shamim.'
        ]);
    }

    public function submit(Request $request): Response
    {
        $isApi = $request->isAjax() || str_starts_with($request->getPath(), '/api');
        $name = trim((string)$request->input('name', $request->input('n', '')));
        $email = trim((string)$request->input('email', $request->input('e', '')));
        $phone = trim((string)$request->input('phone', $request->input('p', '')));
        $projectType = trim((string)$request->input('project_type', $request->input('t', 'Website or SaaS')));
        $message = trim((string)$request->input('message', $request->input('m', '')));

        // Basic validation
        if (empty($name) || empty($email) || empty($message)) {
            if ($isApi) {
                return Response::json(['success' => false, 'error' => 'Please provide your name, email, and project message.'], 422);
            }
            flash('error', 'Please fill out all required fields.');
            return Response::redirect('/contact');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($isApi) {
                return Response::json(['success' => false, 'error' => 'Please enter a valid email address.'], 422);
            }
            flash('error', 'Please enter a valid email address.');
            return Response::redirect('/contact');
        }

        // Store inquiry in database
        $inquiryId = Inquiry::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'project_type' => $projectType,
            'message' => $message,
            'status' => 'new',
            'ip_address' => $request->getIp()
        ]);

        $inquiryData = [
            'id' => $inquiryId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'project_type' => $projectType,
            'message' => $message
        ];

        // Dispatch unified notification (WhatsApp, SMS, Email, In-App)
        $dispatcher = new NotificationDispatcher();
        $dispatcher->dispatchNewInquiry($inquiryData);

        if ($isApi) {
            return Response::json([
                'success' => true,
                'message' => 'Thank you for reaching out! We will review your project and get back to you within 24 hours.'
            ]);
        }

        flash('success', 'Your inquiry has been received! We will be in touch shortly.');
        return Response::redirect('/contact');
    }
}
