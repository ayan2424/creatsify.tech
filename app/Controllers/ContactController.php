<?php

namespace App\Controllers;

use App\Helpers\NotificationDispatcher;
use App\Models\Inquiry;
use App\Models\Project;
use App\Models\Service;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Contact & Project Inquiries Controller
 */
class ContactController
{
    public function index(Request $request): Response
    {
        $selectedOffering = $this->limit((string)$request->input('offering', $request->input('service', '')), 150);
        $selectedKind = (string)$request->input('kind', 'custom_service');
        if (!array_key_exists($selectedKind, $this->inquiryKinds())) {
            $selectedKind = 'custom_service';
        }

        return view('contact/index', [
            'meta_title' => 'Contact ' . setting('site_name', 'Creatsify') . ' | Start Your Project',
            'meta_desc' => 'Start a custom project, discuss an agency service, or ask a pre-purchase software question.',
            'services' => Service::allActive(),
            'projects' => Project::all(),
            'selectedOffering' => $selectedOffering,
            'selectedKind' => $selectedKind,
            'inquiryKinds' => $this->inquiryKinds(),
        ]);
    }

    public function submit(Request $request): Response
    {
        $isApi = $request->isAjax() || str_starts_with($request->getPath(), '/api');

        // Anti-Spam Honeypot Check
        $honeypot = trim((string)$request->input('_hp_check', ''));
        if (!empty($honeypot)) {
            // Silently drop bot submission
            if ($isApi) {
                return Response::json(['success' => true, 'message' => 'Thank you for reaching out!']);
            }
            flash('success', 'Thank you for reaching out! We will contact you shortly.');
            return Response::redirect('/contact');
        }

        $name = $this->limit((string)$request->input('name', $request->input('n', '')), 120);
        $email = $this->limit((string)$request->input('email', $request->input('e', '')), 150);
        $phone = $this->limit((string)$request->input('phone', $request->input('p', '')), 50);
        $company = $this->limit((string)$request->input('company', ''), 150);
        $projectType = $this->limit((string)$request->input('project_type', $request->input('t', 'General inquiry')), 100);
        $budget = $this->limit((string)$request->input('budget', ''), 50);
        $timeline = $this->limit((string)$request->input('timeline', ''), 80);
        $inquiryKind = (string)$request->input('inquiry_kind', 'general');
        if (!array_key_exists($inquiryKind, $this->inquiryKinds())) {
            $inquiryKind = 'general';
        }
        $message = $this->limit((string)$request->input('message', $request->input('m', '')), 5000);

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
            'company' => $company ?: null,
            'project_type' => $projectType,
            'budget' => $budget ?: null,
            'timeline' => $timeline ?: null,
            'inquiry_kind' => $inquiryKind,
            'message' => $message,
            'status' => 'new',
            'ip_address' => $request->getIp()
        ]);

        $inquiryData = [
            'id' => $inquiryId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'company' => $company,
            'project_type' => $projectType,
            'budget' => $budget,
            'timeline' => $timeline,
            'inquiry_kind' => $inquiryKind,
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

    private function inquiryKinds(): array
    {
        return [
            'custom_service' => 'New custom project or agency service',
            'software_presale' => 'Ready-made software, licensing, or demo question',
            'partnership' => 'Partnership or collaboration',
            'general' => 'General business inquiry',
        ];
    }

    private function limit(string $value, int $length): string
    {
        $value = trim($value);
        return function_exists('mb_substr') ? mb_substr($value, 0, $length) : substr($value, 0, $length);
    }
}
