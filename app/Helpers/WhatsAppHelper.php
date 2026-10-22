<?php

namespace App\Helpers;

/**
 * Creatsify Studio - Meta WhatsApp Cloud API v21.0 Driver
 * Modeled on Cheetah's WhatsApp architecture with automatic Pakistani/Intl E.164 normalization.
 */
class WhatsAppHelper
{
    private string $phoneNumberId;
    private string $accessToken;
    private string $apiVersion;
    private string $endpoint;

    public function __construct()
    {
        $this->phoneNumberId = (string)setting('wa_phone_number_id', '');
        $this->accessToken   = (string)setting('wa_access_token', '');
        $this->apiVersion    = 'v21.0';
        $this->endpoint      = "https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}/messages";
    }

    /**
     * Send outbound WhatsApp text message via Meta Cloud API
     */
    public function sendMessage(string $recipientPhone, string $message): array
    {
        $to = clean_phone_number($recipientPhone, false);

        if (empty($this->phoneNumberId) || empty($this->accessToken)) {
            // Log for local development / testing without live credentials
            $this->logMessage('outbound', $this->phoneNumberId ?: 'meta_sandbox', $to, $message, 'credentials_missing');
            return [
                'success' => false,
                'error' => 'WhatsApp Cloud API credentials not configured in Admin Settings.'
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'preview_url' => true,
                'body' => $message
            ]
        ];

        $ch = curl_init($this->endpoint);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $resData = json_decode((string)$response, true);
        $messageId = $resData['messages'][0]['id'] ?? null;
        $status = ($httpCode >= 200 && $httpCode < 300) ? 'sent' : 'failed';

        $this->logMessage('outbound', $this->phoneNumberId, $to, $message, $status, $messageId);

        if ($httpCode >= 200 && $httpCode < 300) {
            return [
                'success' => true,
                'message_id' => $messageId,
                'response' => $resData
            ];
        }

        return [
            'success' => false,
            'http_code' => $httpCode,
            'error' => $resData['error']['message'] ?? $curlError ?: 'Unknown Meta API error'
        ];
    }

    /**
     * Parse incoming webhook payload from Meta Cloud API
     */
    public function parseIncomingWebhook(array $payload): ?array
    {
        try {
            $entry = $payload['entry'][0]['changes'][0]['value'] ?? null;
            if (!$entry || empty($entry['messages'][0])) {
                return null;
            }

            $msg = $entry['messages'][0];
            $sender = $msg['from'] ?? '';
            $text = '';

            if (isset($msg['text']['body'])) {
                $text = $msg['text']['body'];
            } elseif (isset($msg['button']['text'])) {
                $text = $msg['button']['text'];
            } elseif (isset($msg['interactive']['button_reply']['title'])) {
                $text = $msg['interactive']['button_reply']['title'];
            }

            $messageId = $msg['id'] ?? '';
            $to = $entry['metadata']['display_phone_number'] ?? 'creatsify';

            // Store in database
            $this->logMessage('inbound', $sender, $to, $text, 'received', $messageId);

            return [
                'from' => $sender,
                'to' => $to,
                'message' => $text,
                'message_id' => $messageId,
                'name' => $entry['contacts'][0]['profile']['name'] ?? 'WhatsApp Contact'
            ];
        } catch (\Throwable $e) {
            @error_log("Error parsing WhatsApp webhook: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Record in whatsapp_messages table
     */
    public function logMessage(string $direction, string $from, string $to, string $message, string $status = 'sent', ?string $messageId = null): void
    {
        try {
            db()->insert('whatsapp_messages', [
                'direction' => $direction,
                'from_number' => $from,
                'to_number' => $to,
                'message' => $message,
                'message_id' => $messageId,
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Throwable $e) {
            @error_log("WhatsApp log error: " . $e->getMessage());
        }
    }
}
