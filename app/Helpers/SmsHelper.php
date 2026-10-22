<?php

namespace App\Helpers;

/**
 * Creatsify Studio - Multi-Gateway SMS Engine
 * Supports Twilio and Pakistani local SMS gateways with automatic logging.
 */
class SmsHelper
{
    private string $gateway;

    public function __construct()
    {
        $this->gateway = (string)setting('sms_gateway', 'twilio');
    }

    /**
     * Send SMS notification
     */
    public function sendSms(string $recipientPhone, string $message): array
    {
        $phone = clean_phone_number($recipientPhone, true);

        if ($this->gateway === 'twilio') {
            return $this->sendViaTwilio($phone, $message);
        }

        return $this->sendViaPakistaniGateway($phone, $message);
    }

    /**
     * Twilio REST API integration
     */
    private function sendViaTwilio(string $to, string $message): array
    {
        $sid   = (string)setting('sms_twilio_sid', '');
        $token = (string)setting('sms_twilio_token', '');
        $from  = (string)setting('sms_twilio_from', '');

        if (empty($sid) || empty($token) || empty($from)) {
            $this->logSms('outbound', $to, $message, 'credentials_missing', 'Twilio SID/Token not set');
            return [
                'success' => false,
                'error' => 'Twilio SMS credentials are not configured.'
            ];
        }

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";
        $data = [
            'From' => $from,
            'To'   => $to,
            'Body' => $message
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_USERPWD => "{$sid}:{$token}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        $json = json_decode((string)$response, true);
        $status = ($httpCode >= 200 && $httpCode < 300) ? 'sent' : 'failed';
        $this->logSms('outbound', $to, $message, $status, $response);

        if ($httpCode >= 200 && $httpCode < 300) {
            return [
                'success' => true,
                'sid' => $json['sid'] ?? null
            ];
        }

        return [
            'success' => false,
            'error' => $json['message'] ?? $err ?: 'Twilio dispatch failed'
        ];
    }

    /**
     * Local Pakistani Gateway (e.g., Brandverse, SMS Gateway PK, etc.)
     */
    private function sendViaPakistaniGateway(string $to, string $message): array
    {
        // Clean to 923xx format without +
        $localTo = clean_phone_number($to, false);
        $apiKey = (string)setting('sms_pak_api_key', '');
        $senderId = (string)setting('sms_pak_sender', 'Creatsify');

        if (empty($apiKey)) {
            $this->logSms('outbound', $to, $message, 'credentials_missing', 'Pakistani SMS API Key not configured');
            return [
                'success' => false,
                'error' => 'Pakistani SMS gateway API key not set.'
            ];
        }

        // Standard HTTP GET/POST API structure common to Pakistani aggregators
        $url = "https://sms.gateway.pk/api/send?key=" . urlencode($apiKey) . "&receiver=" . urlencode($localTo) . "&sender=" . urlencode($senderId) . "&msg=" . urlencode($message);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $status = ($httpCode >= 200 && $httpCode < 300) ? 'sent' : 'failed';
        $this->logSms('outbound', $to, $message, $status, $response);

        return [
            'success' => ($status === 'sent'),
            'response' => $response
        ];
    }

    /**
     * Record SMS transaction
     */
    public function logSms(string $direction, string $phone, string $message, string $status = 'sent', ?string $gatewayResponse = null): void
    {
        try {
            db()->insert('sms_messages', [
                'direction' => $direction,
                'phone_number' => $phone,
                'message' => $message,
                'status' => $status,
                'gateway_response' => $gatewayResponse,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Throwable $e) {
            @error_log("SMS log error: " . $e->getMessage());
        }
    }
}
