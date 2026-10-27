<?php

namespace App\Helpers;

/**
 * Creatsify Studio - Multi-Channel Notification Dispatcher
 * Modeled on Cheetah's unified notification engine.
 */
class NotificationDispatcher
{
    private WhatsAppHelper $whatsApp;
    private SmsHelper $sms;
    private Mailer $mailer;

    public function __construct()
    {
        $this->whatsApp = new WhatsAppHelper();
        $this->sms      = new SmsHelper();
        $this->mailer   = new Mailer();
    }

    /**
     * Dispatch new inquiry event across all configured channels
     */
    public function dispatchNewInquiry(array $inquiry): void
    {
        $name = $inquiry['name'] ?? 'Potential Client';
        $email = $inquiry['email'] ?? 'Not provided';
        $phone = $inquiry['phone'] ?? 'Not provided';
        $projectType = $inquiry['project_type'] ?? 'General';
        $message = $inquiry['message'] ?? '';
        $company = $inquiry['company'] ?? '';
        $timeline = $inquiry['timeline'] ?? '';
        $inquiryKind = $inquiry['inquiry_kind'] ?? 'general';

        // 1. In-App Notification
        $this->recordInApp(
            'inquiry',
            "New Client Inquiry: {$name}",
            "{$name} ({$email}) submitted an inquiry regarding '{$projectType}'.",
            '/admin/inquiries'
        );

        // 2. WhatsApp Alert to Admin
        $adminWaPhone = (string)setting('wa_admin_notification_phone', '');
        if (!empty($adminWaPhone)) {
            $waText = "[ALERT] *New Creatsify Inquiry Received!*\n\n"
                    . "• *Client:* {$name}\n"
                    . "• *Email:* {$email}\n"
                    . "• *Phone:* {$phone}\n"
                    . "• *Project:* {$projectType}\n"
                    . "• *Message:* " . substr($message, 0, 200) . "\n\n"
                    . "» *Open Admin:* " . url('/admin/inquiries');
            $this->whatsApp->sendMessage($adminWaPhone, $waText);
        }

        // 3. SMS Alert to Admin
        $adminSmsPhone = (string)setting('sms_admin_phone', '');
        if (!empty($adminSmsPhone)) {
            $smsText = "Creatsify Alert: New inquiry from {$name} ({$projectType}). Check dashboard: " . url('/admin/inquiries');
            $this->sms->sendSms($adminSmsPhone, $smsText);
        }

        // 4. Email Alert to Admin
        $defaultHost = $_SERVER['HTTP_HOST'] ?? 'example.com';
        $adminEmail = (string)setting('site_email', 'admin@' . $defaultHost);
        $siteName = (string)setting('site_name', 'Creatsify Studio');
        if (!empty($adminEmail)) {
            $html = "<p>A new prospective client submitted a project inquiry on <strong>" . e($siteName) . "</strong>.</p>"
                  . "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>"
                  . "<tr><td style='padding: 8px; font-weight: bold;'>Name:</td><td style='padding: 8px;'>" . e($name) . "</td></tr>"
                  . "<tr><td style='padding: 8px; font-weight: bold;'>Email:</td><td style='padding: 8px;'><a href='mailto:" . e($email) . "'>" . e($email) . "</a></td></tr>"
                  . "<tr><td style='padding: 8px; font-weight: bold;'>Phone:</td><td style='padding: 8px;'>" . e($phone) . "</td></tr>"
                  . "<tr><td style='padding: 8px; font-weight: bold;'>Company:</td><td style='padding: 8px;'>" . e($company ?: 'Not provided') . "</td></tr>"
                  . "<tr><td style='padding: 8px; font-weight: bold;'>Inquiry type:</td><td style='padding: 8px;'>" . e($inquiryKind) . "</td></tr>"
                  . "<tr><td style='padding: 8px; font-weight: bold;'>Project Type:</td><td style='padding: 8px;'>" . e($projectType) . "</td></tr>"
                  . "<tr><td style='padding: 8px; font-weight: bold;'>Timeline:</td><td style='padding: 8px;'>" . e($timeline ?: 'Not specified') . "</td></tr>"
                  . "<tr><td style='padding: 8px; font-weight: bold;'>Message:</td><td style='padding: 8px;'>" . nl2br(e($message)) . "</td></tr>"
                  . "</table>"
                  . "<p><a href='" . url('/admin/inquiries') . "' style='display:inline-block; padding: 12px 24px; background: #8b7bff; color: #fff; text-decoration: none; border-radius: 6px;'>View in Admin Panel</a></p>";
            $this->mailer->send($adminEmail, "New Project Inquiry from {$name}", $html);
        }
    }

    /**
     * Dispatch live chat human handoff alert
     */
    public function dispatchChatHandoff(array $session, string $latestMessage): void
    {
        $name = $session['visitor_name'] ?? 'Visitor';
        $ip = $session['ip_address'] ?? 'Unknown';

        // In-App Notification
        $this->recordInApp(
            'chat_handoff',
            "[URGENT] Human Support Requested in Live Chat",
            "{$name} ({$ip}) is requesting a human agent: \"{$latestMessage}\"",
            '/admin/chat'
        );

        // WhatsApp Alert to Admin
        $adminWaPhone = (string)setting('wa_admin_notification_phone', '');
        if (!empty($adminWaPhone)) {
            $waText = "[CHAT] *Creatsify Live Chat: Agent Requested!*\n\n"
                    . "• Visitor: {$name}\n"
                    . "• Question: \"{$latestMessage}\"\n\n"
                    . "» *Take over live chat:* " . url('/admin/chat');
            $this->whatsApp->sendMessage($adminWaPhone, $waText);
        }
    }

    /**
     * Dispatch script support ticket alert across channels
     */
    public function dispatchScriptSupport(array $ticket, array $client): void
    {
        $this->dispatchSupportTicket([
            'id' => $ticket['id'] ?? null,
            'ticket_number' => $ticket['ticket_number'] ?? 'TCK-NEW',
            'subject' => $ticket['subject'] ?? 'Script Support',
            'support_type' => 'Purchased software technical support',
            'offering_name' => $ticket['script_name'] ?? 'Software script',
            'purchase_reference' => $ticket['order_id'] ?? '',
        ], $client);
    }

    /**
     * Dispatch any first-party service or software support ticket.
     */
    public function dispatchSupportTicket(array $ticket, array $client): void
    {
        $ticketNumber = $ticket['ticket_number'] ?? 'TCK-NEW';
        $name = $client['name'] ?? 'Customer';
        $email = $client['email'] ?? '';
        $subject = $ticket['subject'] ?? 'Customer Support';
        $supportType = $ticket['support_type'] ?? 'Customer support';
        $offeringName = $ticket['offering_name'] ?? 'Creatsify product or service';
        $purchaseReference = $ticket['purchase_reference'] ?? '';
        $link = '/admin/crm/tickets/' . ($ticket['id'] ?? '');

        // 1. In-App Notification
        $this->recordInApp(
            'ticket_created',
            "New Customer Support Ticket #{$ticketNumber}",
            "{$name} ({$email}) submitted {$supportType} request for {$offeringName}.",
            $link
        );

        // 2. WhatsApp Alert to Admin
        $adminWaPhone = (string)setting('wa_admin_notification_phone', '');
        if (!empty($adminWaPhone)) {
            $waText = "🛠️ *New Creatsify Customer Support Ticket!*\n\n"
                    . "• *Ticket:* #{$ticketNumber}\n"
                    . "• *Request:* {$supportType}\n"
                    . "• *Product / service:* {$offeringName}\n"
                    . "• *Customer:* {$name} ({$email})\n"
                    . ($purchaseReference !== '' ? "• *Reference:* {$purchaseReference}\n" : '')
                    . "• *Subject:* {$subject}\n\n"
                    . "» *Open Admin Desk:* " . url($link);
            $this->whatsApp->sendMessage($adminWaPhone, $waText);
        }

        if ($email !== '') {
            $siteName = (string)setting('site_name', 'Creatsify Studio');
            $html = '<p>Hello ' . e($name) . ',</p>'
                . '<p>We received your support request for <strong>' . e($offeringName) . '</strong>.</p>'
                . '<p>Your ticket number is <strong>' . e($ticketNumber) . '</strong>. Keep it handy if you contact us again.</p>'
                . '<p>Our team will review the request and reply through email or WhatsApp.</p>'
                . '<p>— ' . e($siteName) . '</p>';
            $this->mailer->send($email, 'We received your support ticket #' . $ticketNumber, $html);
        }
    }

    /**
     * Record in-app notification in DB
     */
    public function recordInApp(string $type, string $title, string $message, ?string $link = null): void
    {
        try {
            db()->insert('notifications', [
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Throwable $e) {
            @error_log("Failed to insert notification: " . $e->getMessage());
        }
    }
}
