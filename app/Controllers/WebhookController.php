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

            $configuredToken = (string)env('WHATSAPP_VERIFY_TOKEN', setting('wa_verify_token', ''));

            if ($configuredToken !== '' && $mode === 'subscribe' && is_string($token) && hash_equals($configuredToken, $token)) {
                return Response::make((string)$challenge, 200, ['Content-Type' => 'text/plain']);
            }

            return Response::make('Forbidden', 403);
        }

        // 2. Incoming WhatsApp Message Dispatch
        $appSecret = (string)env('WHATSAPP_APP_SECRET', '');
        $signature = (string)($request->getHeader('X-Hub-Signature-256') ?? '');

        if ($appSecret === '') {
            return Response::json(['error' => 'Webhook signature verification is not configured.'], 503);
        }

        $expectedSignature = 'sha256=' . hash_hmac('sha256', $request->getRawBody(), $appSecret);
        if ($signature === '' || !hash_equals($expectedSignature, $signature)) {
            return Response::json(['error' => 'Invalid webhook signature.'], 401);
        }

        $payload = json_decode($request->getRawBody(), true);
        if (!is_array($payload)) {
            return Response::json(['error' => 'Invalid JSON payload.'], 400);
        }
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
