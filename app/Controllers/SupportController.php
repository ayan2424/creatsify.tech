<?php

namespace App\Controllers;

use App\Helpers\NotificationDispatcher;
use App\Models\CrmTicket;
use App\Models\Project;
use App\Models\Service;
use Core\Request;
use Core\Response;

/**
 * First-party customer support for agency services and licensed software.
 */
class SupportController
{
    private const SUPPORT_TYPES = [
        'service_delivery' => ['label' => 'Existing agency service or project', 'category' => 'service_delivery', 'source' => 'Existing agency client', 'reference_required' => false],
        'retainer_support' => ['label' => 'Maintenance or retainer support', 'category' => 'retainer_support', 'source' => 'Agency retainer client', 'reference_required' => false],
        'software_support' => ['label' => 'Purchased software technical support', 'category' => 'software_support', 'source' => 'Direct software customer', 'reference_required' => true],
        'software_deployment' => ['label' => 'Software installation or deployment', 'category' => 'software_deployment', 'source' => 'Direct software customer', 'reference_required' => true],
        'billing_license' => ['label' => 'License, billing, or purchase receipt', 'category' => 'billing_license', 'source' => 'Direct software customer', 'reference_required' => true],
        'general_support' => ['label' => 'Other existing-customer help', 'category' => 'general_support', 'source' => 'Existing customer', 'reference_required' => false],
    ];

    public function index(Request $request): Response
    {
        $selectedOffering = $this->limit((string)$request->input('offering', $request->input('project', $request->input('script', ''))), 150);
        $selectedType = (string)$request->input('type', 'software_support');
        if (!isset(self::SUPPORT_TYPES[$selectedType])) {
            $selectedType = 'software_support';
        }

        $ticketSuccess = $this->limit((string)$request->input('ticket', ''), 30);
        $isSuccess = $request->input('success') === '1' && $ticketSuccess !== '';

        return view('support/index', [
            'meta_title' => 'Customer Support & Delivery Desk | ' . setting('site_name', 'Creatsify'),
            'meta_desc' => 'Direct help for Creatsify agency services, ongoing client work, and licensed software products.',
            'projects' => Project::all(),
            'services' => Service::allActive(),
            'selectedOffering' => $selectedOffering,
            'selectedType' => $selectedType,
            'supportTypes' => self::SUPPORT_TYPES,
            'ticketSuccess' => $ticketSuccess,
            'isSuccess' => $isSuccess,
        ]);
    }

    public function submit(Request $request): Response
    {
        $isApi = $request->isAjax() || str_starts_with($request->getPath(), '/api');
        $honeypot = trim((string)$request->input('_hp_check', ''));
        if ($honeypot !== '') {
            return $this->successResponse($isApi, 'Support ticket received.');
        }

        $name = $this->limit((string)$request->input('name', ''), 120);
        $email = $this->limit((string)$request->input('email', ''), 150);
        $company = $this->limit((string)$request->input('company', ''), 150);
        $whatsapp = $this->limit((string)$request->input('whatsapp', $request->input('phone', '')), 50);
        $supportType = (string)$request->input('support_type', 'software_support');
        if (!isset(self::SUPPORT_TYPES[$supportType])) {
            $supportType = 'general_support';
        }
        $typeConfig = self::SUPPORT_TYPES[$supportType];

        $offeringType = (string)$request->input('offering_type', 'software');
        if (!in_array($offeringType, ['service', 'software', 'other'], true)) {
            $offeringType = 'other';
        }
        $offeringName = $this->limit((string)$request->input('offering_name', $request->input('script_name', '')), 150);
        $purchaseReference = $this->limit((string)$request->input('purchase_reference', $request->input('order_id', '')), 150);
        $serverEnvironment = $this->limit((string)$request->input('server_environment', ''), 150);
        $domainUrl = $this->limit((string)$request->input('domain_url', ''), 255);
        $issueCategory = $this->limit((string)$request->input('issue_category', 'General support'), 100);
        $priority = (string)$request->input('priority', 'medium');
        $subject = $this->limit((string)$request->input('subject', ''), 180);
        $message = $this->limit((string)$request->input('message', ''), 6000);

        if ($name === '' || $email === '' || $offeringName === '' || $subject === '' || $message === '') {
            return $this->failureResponse($isApi, 'Please provide your name, email, product or service, ticket subject, and a description of the issue.', $offeringName);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->failureResponse($isApi, 'Please enter a valid email address so our team can reply to you.', $offeringName);
        }
        if ($typeConfig['reference_required'] && $purchaseReference === '') {
            return $this->failureResponse($isApi, 'Please add your purchase receipt, order ID, or license key so we can verify the software request.', $offeringName);
        }
        if (!in_array($priority, ['low', 'medium', 'high', 'urgent'], true)) {
            $priority = 'medium';
        }

        try {
            $client = db()->fetchOne("SELECT * FROM `crm_clients` WHERE `email` = :email LIMIT 1", ['email' => $email]);
            if (!$client) {
                $clientId = (int)db()->insert('crm_clients', [
                    'name' => $name,
                    'company' => $company ?: null,
                    'email' => $email,
                    'whatsapp' => $whatsapp ?: null,
                    'phone' => $whatsapp ?: null,
                    'stage' => $supportType === 'retainer_support' ? 'retained' : 'active_client',
                    'source' => $typeConfig['source'],
                    'tags' => $typeConfig['label'] . ', ' . $offeringName,
                    'notes_summary' => "Customer support request for {$offeringName}. Reference: " . ($purchaseReference ?: 'Not supplied') . ".",
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                $clientId = (int)$client['id'];
                $updates = ['updated_at' => date('Y-m-d H:i:s')];
                if (empty($client['whatsapp']) && $whatsapp !== '') {
                    $updates['whatsapp'] = $whatsapp;
                    $updates['phone'] = $whatsapp;
                }
                if (empty($client['company']) && $company !== '') {
                    $updates['company'] = $company;
                }
                if (count($updates) > 1) {
                    db()->update('crm_clients', $updates, '`id` = :id', ['id' => $clientId]);
                }
            }

            $projectId = null;
            if ($offeringType === 'software') {
                $matchedProject = db()->fetchOne(
                    "SELECT `id` FROM `projects` WHERE `title` LIKE :title LIMIT 1",
                    ['title' => '%' . $offeringName . '%']
                );
                if ($matchedProject) {
                    $projectId = (int)$matchedProject['id'];
                }
            }

            $ticketNumber = CrmTicket::generateTicketNumber();
            $description = "### Request type:\n{$typeConfig['label']}\n\n"
                . "### Product or service:\n{$offeringName}\n\n"
                . "### Account / company:\n" . ($company ?: 'Not specified') . "\n\n"
                . "### Purchase reference / license:\n" . ($purchaseReference ?: 'Not applicable or not supplied') . "\n\n"
                . "### Server environment:\n" . ($serverEnvironment ?: 'Not applicable or not supplied') . "\n\n"
                . "### Related URL:\n" . ($domainUrl ?: 'Not specified') . "\n\n"
                . "### Issue category:\n{$issueCategory}\n\n"
                . "### Customer message:\n{$message}";

            $ticketId = CrmTicket::create([
                'ticket_number' => $ticketNumber,
                'client_id' => $clientId,
                'client_name' => $name,
                'client_email' => $email,
                'client_phone' => $whatsapp ?: null,
                'project_id' => $projectId,
                'subject' => '[' . $typeConfig['label'] . '] ' . $subject,
                'category' => $typeConfig['category'],
                'priority' => $priority,
                'status' => 'open',
                'description' => $description,
            ]);

            try {
                (new NotificationDispatcher())->dispatchSupportTicket([
                    'id' => $ticketId,
                    'ticket_number' => $ticketNumber,
                    'subject' => $subject,
                    'support_type' => $typeConfig['label'],
                    'offering_name' => $offeringName,
                    'purchase_reference' => $purchaseReference,
                ], [
                    'name' => $name,
                    'email' => $email,
                    'whatsapp' => $whatsapp,
                ]);
            } catch (\Throwable $e) {
                @error_log('Failed to dispatch support notification: ' . $e->getMessage());
            }

            $success = "Support ticket #{$ticketNumber} has been created. We will reply through email or WhatsApp after reviewing it.";
            if ($isApi) {
                return Response::json(['success' => true, 'message' => $success, 'ticket_number' => $ticketNumber]);
            }

            flash('success', $success);
            return Response::redirect('/support?success=1&ticket=' . urlencode($ticketNumber));
        } catch (\Throwable $e) {
            @error_log('SupportController submit error: ' . $e->getMessage());
            return $this->failureResponse($isApi, 'We could not create your ticket right now. Please try again or contact us on WhatsApp.', $offeringName, 500);
        }
    }

    private function successResponse(bool $isApi, string $message): Response
    {
        if ($isApi) {
            return Response::json(['success' => true, 'message' => $message]);
        }
        flash('success', $message);
        return Response::redirect('/support');
    }

    private function failureResponse(bool $isApi, string $message, string $offering = '', int $status = 422): Response
    {
        if ($isApi) {
            return Response::json(['success' => false, 'error' => $message], $status);
        }
        flash('error', $message);
        $suffix = $offering !== '' ? '?offering=' . urlencode($offering) : '';
        return Response::redirect('/support' . $suffix);
    }

    private function limit(string $value, int $length): string
    {
        $value = trim($value);
        return function_exists('mb_substr') ? mb_substr($value, 0, $length) : substr($value, 0, $length);
    }
}
