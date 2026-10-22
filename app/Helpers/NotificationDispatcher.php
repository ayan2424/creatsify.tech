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
            $waText = "🚀 *New Creatsify Inquiry Received!*\n\n"
                    . "👤 *Client:* {$name}\n"
                    . "📧 *Email:* {$email}\n"
                    . "📱 *Phone:* {$phone}\n"
                    . "💼 *Project:* {$projectType}\n"
                    . "💬 *Message:* " . substr($message, 0, 200) . "\n\n"
                    . "🔗 Open Admin: " . url('/admin/inquiries');
            $this->whatsApp->sendMessage($adminWaPhone, $waText);
        }

        // 3. SMS Alert to Admin
        $adminSmsPhone = (string)setting('sms_admin_phone', '');
        if (!empty($adminSmsPhone)) {
            $smsText = "Creatsify Alert: New inquiry from {$name} ({$projectType}). Check dashboard: " . url('/admin/inquiries');
            $this->sms->sendSms($adminSmsPhone, $smsText);
        }

        // 4. Email Alert to Admin
        $adminEmail = (string)setting('site_email', 'ayan@creatsify.tech');
        if (!empty($adminEmail)) {
            $html = "<p>A new prospective client submitted a project inquiry on <strong>creatsify.tech</strong>.</p>"
                  . "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>"
                  . "<tr><td style='padding: 8px; font-weight: bold;'>Name:</td><td style='padding: 8px;'>{$name}</td></tr>"
                  . "<tr><td style='padding: 8px; font-weight: bold;'>Email:</td><td style='padding: 8px;'><a href='mailto:{$email}'>{$email}</a></td></tr>"
                  . "<tr><td style='padding: 8px; font-weight: bold;'>Phone:</td><td style='padding: 8px;'>{$phone}</td></tr>"
                  . "<tr><td style='padding: 8px; font-weight: bold;'>Project Type:</td><td style='padding: 8px;'>{$projectType}</td></tr>"
                  . "<tr><td style='padding: 8px; font-weight: bold;'>Message:</td><td style='padding: 8px;'>" . nl2br(e($message)) . "</td></tr>"
                  . "</table>"
                  . "<p><a href='" . url('/admin/inquiries') . "' style='display:inline-block; padding: 12px 24px; background: #8b7bff; color: #fff; text-decoration: none; border-radius: 6px;'>View in Admin Panel</a></p>";
            $this->mailer->send($adminEmail, "🚀 New Project Inquiry from {$name}", $html);
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
            "⚡ Human Support Requested in Live Chat",
            "{$name} ({$ip}) is requesting a human agent: \"{$latestMessage}\"",
            '/admin/chat'
        );

        // WhatsApp Alert to Admin
        $adminWaPhone = (string)setting('wa_admin_notification_phone', '');
        if (!empty($adminWaPhone)) {
            $waText = "⚡ *Creatsify Live Chat: Agent Requested!*\n\n"
                    . "Visitor: {$name}\n"
                    . "Question: \"{$latestMessage}\"\n\n"
                    . "👉 Click to take over live chat: " . url('/admin/chat');
            $this->whatsApp->sendMessage($adminWaPhone, $waText);
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
