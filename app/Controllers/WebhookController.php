<?php

namespace App\Controllers;

use App\Helpers\NotificationDispatcher;
use App\Helpers\WhatsAppHelper;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - WhatsApp Cloud API Webhook Receiver
 */
class WebhookController
{
    /**
     * Handle Meta Webhook Verification (GET) and Incoming Notifications (POST)
     */
    public function whatsapp(Request $request): Response
    {
        // 1. Meta Webhook Verification Handshake
        if ($request->getMethod() === 'GET') {
            $mode = $request->get('hub_mode') ?? $request->get('hub.mode');
            $token = $request->get('hub_verify_token') ?? $request->get('hub.verify_token');
            $challenge = $request->get('hub_challenge') ?? $request->get('hub.challenge');

            $configuredToken = (string)setting('wa_verify_token', 'creatsify_wa_webhook_token_secure');

            if ($mode === 'subscribe' && $token === $configuredToken) {
                return Response::make((string)$challenge, 200, ['Content-Type' => 'text/plain']);
            }

            return Response::make('Forbidden', 403);
        }

        // 2. Incoming WhatsApp Message Dispatch
        $payload = $request->all();
        $helper = new WhatsAppHelper();
        $parsed = $helper->parseIncomingWebhook($payload);

        if ($parsed) {
            // Trigger in-app alert for admin
            $dispatcher = new NotificationDispatcher();
            $dispatcher->recordInApp(
                'whatsapp',
                "New WhatsApp from {$parsed['name']} ({$parsed['from']})",
                $parsed['message'],
                '/admin/whatsapp'
            );

            // Optional: Auto-reply if enabled
            if ((int)setting('wa_auto_reply_enabled', 1) === 1) {
                $autoReply = "Hello {$parsed['name']}! Thank you for messaging Creatsify Studio. An engineer has received your message and will reply shortly.";
                $helper->sendMessage($parsed['from'], $autoReply);
            }
        }

        return Response::json(['status' => 'EVENT_RECEIVED']);
    }
}
