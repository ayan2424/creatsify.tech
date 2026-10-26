<?php

namespace App\Controllers;

use App\Helpers\NotificationDispatcher;
use App\Models\CrmTicket;
use App\Models\Project;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Dedicated First-Party Script Support & Deployment Portal
 * Provides direct buyer assistance for software scripts licensed on Gumroad.
 */
class SupportController
{
    public function index(Request $request): Response
    {
        $projects = Project::all();
        $selectedScript = trim((string)$request->input('script', $request->input('project', '')));
        $ticketSuccess = trim((string)$request->input('ticket', ''));
        $isSuccess = $request->input('success') === '1' && !empty($ticketSuccess);

        return view('support/index', [
            'meta_title' => 'In-House Script Support & Deployment Portal | ' . setting('site_name', 'Creatsify'),
            'meta_desc' => 'Direct technical support for white-label software scripts purchased on our Gumroad store. aaPanel/Nginx configuration, database setup, and bug diagnostics.',
            'projects' => $projects,
            'selectedScript' => $selectedScript,
            'ticketSuccess' => $ticketSuccess,
            'isSuccess' => $isSuccess
        ]);
    }

    public function submit(Request $request): Response
    {
        $isApi = $request->isAjax() || str_starts_with($request->getPath(), '/api');

        // Anti-Spam Honeypot
        $honeypot = trim((string)$request->input('_hp_check', ''));
        if (!empty($honeypot)) {
            if ($isApi) {
                return Response::json(['success' => true, 'message' => 'Support ticket received.']);
            }
            flash('success', 'Thank you! Your support ticket has been received.');
            return Response::redirect('/support');
        }

        $name = trim((string)$request->input('name', ''));
        $email = trim((string)$request->input('email', ''));
        $whatsapp = trim((string)$request->input('whatsapp', $request->input('phone', '')));
        $scriptName = trim((string)$request->input('script_name', 'Cheetah Enterprise'));
        $orderId = trim((string)$request->input('order_id', ''));
        $serverEnv = trim((string)$request->input('server_environment', 'aaPanel + Nginx'));
        $domainUrl = trim((string)$request->input('domain_url', ''));
        $issueCategory = trim((string)$request->input('issue_category', 'Installation & Setup'));
        $priority = trim((string)$request->input('priority', 'medium'));
        $subject = trim((string)$request->input('subject', ''));
        $message = trim((string)$request->input('message', ''));

        // Basic validation
        if (empty($name) || empty($email) || empty($orderId) || empty($subject) || empty($message)) {
            $err = 'Please fill out all required fields: Name, Email, Gumroad Order ID, Subject, and Problem Description.';
            if ($isApi) {
                return Response::json(['success' => false, 'error' => $err], 422);
            }
            flash('error', $err);
            return Response::redirect('/support?script=' . urlencode($scriptName));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err = 'Please enter a valid email address so we can reply to your ticket.';
            if ($isApi) {
                return Response::json(['success' => false, 'error' => $err], 422);
            }
            flash('error', $err);
            return Response::redirect('/support?script=' . urlencode($scriptName));
        }

        // Validate priority enum
        if (!in_array($priority, ['low', 'medium', 'high', 'urgent'], true)) {
            $priority = 'medium';
        }

        try {
            // 1. Locate or create Client in crm_clients
            $client = db()->fetchOne("SELECT * FROM `crm_clients` WHERE `email` = :email LIMIT 1", ['email' => $email]);
            if (!$client) {
                $clientId = (int)db()->insert('crm_clients', [
                    'name' => $name,
                    'company' => !empty($domainUrl) ? parse_url($domainUrl, PHP_URL_HOST) ?? $domainUrl : 'Independent Operator',
                    'email' => $email,
                    'whatsapp' => $whatsapp ?: null,
                    'phone' => $whatsapp ?: null,
                    'stage' => 'active_client',
                    'source' => 'Gumroad Direct Store',
                    'tags' => 'Gumroad Buyer, ' . $scriptName,
                    'notes_summary' => "Script buyer support request for {$scriptName}. Order ID: {$orderId}. Domain: {$domainUrl}",
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                $clientId = (int)$client['id'];
                // Update phone if previously empty
                if (empty($client['whatsapp']) && !empty($whatsapp)) {
                    db()->update('crm_clients', [
                        'whatsapp' => $whatsapp,
                        'phone' => $whatsapp,
                        'updated_at' => date('Y-m-d H:i:s')
                    ], '`id` = :id', ['id' => $clientId]);
                }
            }

            // 2. Identify matching Project ID if available
            $projectId = null;
            $matchedProject = db()->fetchOne(
                "SELECT `id` FROM `projects` WHERE `title` LIKE :title LIMIT 1",
                ['title' => '%' . $scriptName . '%']
            );
            if ($matchedProject) {
                $projectId = (int)$matchedProject['id'];
            }

            // 3. Format Ticket Details
            $ticketNumber = CrmTicket::generateTicketNumber();
            $fullSubject = "[Script Support] " . $scriptName . ": " . $subject;

            $description = "### Software Product:\n" . $scriptName . "\n\n"
                         . "### Gumroad Order / License Key:\n" . $orderId . "\n\n"
                         . "### Server Environment:\n" . $serverEnv . "\n\n"
                         . "### Installation Domain / URL:\n" . ($domainUrl ?: 'Not specified') . "\n\n"
                         . "### Issue Category:\n" . $issueCategory . "\n\n"
                         . "### Problem Description & Logs:\n" . $message;

            $ticketId = CrmTicket::create([
                'ticket_number' => $ticketNumber,
                'client_id' => $clientId,
                'client_name' => $name,
                'client_email' => $email,
                'client_phone' => $whatsapp ?: null,
                'project_id' => $projectId,
                'subject' => $fullSubject,
                'category' => 'script_support',
                'priority' => $priority,
                'status' => 'open',
                'description' => $description
            ]);

            // 4. Dispatch Multi-Channel Notifications (In-App DB + WhatsApp Admin Alert)
            try {
                $dispatcher = new NotificationDispatcher();
                $dispatcher->dispatchScriptSupport([
                    'id' => $ticketId,
                    'ticket_number' => $ticketNumber,
                    'subject' => $subject,
                    'script_name' => $scriptName,
                    'order_id' => $orderId
                ], [
                    'name' => $name,
                    'email' => $email,
                    'whatsapp' => $whatsapp
                ]);
            } catch (\Throwable $e) {
                @error_log("Failed to dispatch script support notification: " . $e->getMessage());
            }

            if ($isApi) {
                return Response::json([
                    'success' => true,
                    'message' => "Support ticket #{$ticketNumber} created successfully. Our team will review your deployment details shortly.",
                    'ticket_number' => $ticketNumber
                ]);
            }

            flash('success', "Support ticket #{$ticketNumber} created successfully! Our engineering team in Karachi is reviewing your logs and will contact you via email or WhatsApp within 2-6 hours.");
            return Response::redirect('/support?success=1&ticket=' . urlencode($ticketNumber));

        } catch (\Throwable $e) {
            @error_log("SupportController submit error: " . $e->getMessage());
            if ($isApi) {
                return Response::json(['success' => false, 'error' => 'An error occurred while creating your support ticket. Please try again or message our WhatsApp.'], 500);
            }
            flash('error', 'We encountered an error processing your ticket: ' . $e->getMessage());
            return Response::redirect('/support');
        }
    }
}
