<?php

namespace App\Helpers;

/**
 * Creatsify Studio - High-Performance Zero-Dependency SMTP Mailer
 * Connects directly over TLS/SSL sockets to any standard SMTP server (Gmail, Mailtrap, Postmark, cPanel).
 */
class Mailer
{
    private string $host;
    private int $port;
    private string $username;
    private string $password;
    private string $encryption;
    private string $fromEmail;
    private string $fromName;

    public function __construct()
    {
        $this->host       = (string)setting('smtp_host', 'localhost');
        $this->port       = (int)setting('smtp_port', 25);
        $this->username   = (string)setting('smtp_user', '');
        $this->password   = (string)setting('smtp_pass', '');
        $this->encryption = strtolower((string)setting('smtp_encryption', 'tls'));
        $this->fromEmail  = (string)setting('smtp_from_email', 'noreply@creatsify.tech');
        $this->fromName   = (string)setting('smtp_from_name', 'Creatsify Studio');
    }

    /**
     * Send HTML Email
     */
    public function send(string $toEmail, string $subject, string $htmlBody, string $textBody = ''): bool
    {
        // Wrap body in modern dark/light luxury email frame
        $fullHtml = $this->buildTemplate($subject, $htmlBody);

        // Fallback to native PHP mail if host is empty or localhost without credentials
        if (empty($this->host) || ($this->host === 'localhost' && empty($this->username))) {
            return $this->sendNativeMail($toEmail, $subject, $fullHtml);
        }

        try {
            return $this->sendSmtp($toEmail, $subject, $fullHtml, $textBody ?: strip_tags($htmlBody));
        } catch (\Throwable $e) {
            @error_log("SMTP Error: " . $e->getMessage() . " - Falling back to native mail()");
            return $this->sendNativeMail($toEmail, $subject, $fullHtml);
        }
    }

    private function sendNativeMail(string $to, string $subject, string $body): bool
    {
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . $this->fromName . ' <' . $this->fromEmail . '>',
            'Reply-To: ' . $this->fromEmail,
            'X-Mailer: CreatsifyStudio/1.0'
        ];
        return @mail($to, $subject, $body, implode("\r\n", $headers));
    }

    private function sendSmtp(string $to, string $subject, string $htmlBody, string $textBody): bool
    {
        $timeout = 15;
        $prefix = ($this->encryption === 'ssl') ? 'ssl://' : '';
        $socket = @fsockopen($prefix . $this->host, $this->port, $errno, $errstr, $timeout);

        if (!$socket) {
            throw new \RuntimeException("Could not connect to SMTP host {$this->host}:{$this->port} ({$errstr})");
        }

        $this->readSmtp($socket);
        $this->writeSmtp($socket, "EHLO " . gethostname());

        if ($this->encryption === 'tls') {
            $this->writeSmtp($socket, "STARTTLS");
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT)) {
                throw new \RuntimeException("Could not start TLS encryption.");
            }
            $this->writeSmtp($socket, "EHLO " . gethostname());
        }

        if (!empty($this->username)) {
            $this->writeSmtp($socket, "AUTH LOGIN");
            $this->writeSmtp($socket, base64_encode($this->username));
            $this->writeSmtp($socket, base64_encode($this->password));
        }

        $this->writeSmtp($socket, "MAIL FROM: <{$this->fromEmail}>");
        $this->writeSmtp($socket, "RCPT TO: <{$to}>");
        $this->writeSmtp($socket, "DATA");

        $boundary = "==Multipart_Boundary_x" . md5((string)time()) . "x";

        $headers  = "From: =?UTF-8?B?" . base64_encode($this->fromName) . "?= <{$this->fromEmail}>\r\n";
        $headers .= "To: <{$to}>\r\n";
        $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
        $headers .= "Date: " . date('r') . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n\r\n";

        $content  = "--{$boundary}\r\n";
        $content .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $content .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $content .= chunk_split(base64_encode($textBody)) . "\r\n";

        $content .= "--{$boundary}\r\n";
        $content .= "Content-Type: text/html; charset=UTF-8\r\n";
        $content .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $content .= chunk_split(base64_encode($htmlBody)) . "\r\n";
        $content .= "--{$boundary}--\r\n";

        $this->writeSmtp($socket, $headers . $content . "\r\n.");
        $this->writeSmtp($socket, "QUIT");
        fclose($socket);

        return true;
    }

    private function writeSmtp($socket, string $command): string
    {
        fwrite($socket, $command . "\r\n");
        return $this->readSmtp($socket);
    }

    private function readSmtp($socket): string
    {
        $response = "";
        while ($str = fgets($socket, 515)) {
            $response .= $str;
            if (substr($str, 3, 1) === " ") {
                break;
            }
        }
        return $response;
    }

    private function buildTemplate(string $title, string $body): string
    {
        $primary = setting('primary_accent', '#8b7bff');
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{$title}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #07080d; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #eef0f7;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #07080d; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #12131b; border: 1px solid #252736; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    <tr>
                        <td style="padding: 30px 40px; border-bottom: 1px solid #252736; background: linear-gradient(135deg, rgba(139,123,255,0.08), rgba(255,79,163,0.04));">
                            <span style="font-size: 20px; font-weight: 700; color: #ffffff; letter-spacing: -0.5px;">Creatsify<span style="color: {$primary};">.tech</span></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px; font-size: 15px; line-height: 1.6; color: #c4c8da;">
                            <h2 style="margin-top: 0; font-size: 20px; color: #ffffff; font-weight: 600;">{$title}</h2>
                            {$body}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px 40px; background-color: #0d0e15; border-top: 1px solid #252736; font-size: 12px; color: #70758f; text-align: center;">
                            &copy; 2026 Creatsify Studio &bull; Karachi, Pakistan &bull; <a href="https://creatsify.tech" style="color: {$primary}; text-decoration: none;">creatsify.tech</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }
}
