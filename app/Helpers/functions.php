<?php

use Core\App;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\Session;
use Core\View;

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $val = $_ENV[$key] ?? $_SERVER[$key] ?? null;
        if ($val === null && function_exists('getenv')) {
            $g = @getenv($key);
            if ($g !== false) {
                $val = $g;
            }
        }
        if ($val === null) {
            return $default;
        }
        return match (strtolower((string)$val)) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'empty', '(empty)' => '',
            'null', '(null)' => null,
            default => $val,
        };
    }
}

if (!function_exists('app')) {
    function app(): App
    {
        return App::getInstance();
    }
}

if (!function_exists('db')) {
    function db(): Database
    {
        return app()->getDb();
    }
}

if (!function_exists('request')) {
    function request(): Request
    {
        return app()->getRequest();
    }
}

if (!function_exists('session')) {
    function session(): Session
    {
        return app()->getSession();
    }
}

if (!function_exists('view')) {
    function view(string $path, array $data = []): Response
    {
        return Response::html(View::make($path, $data));
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url, int $statusCode = 302): Response
    {
        return Response::redirect($url, $statusCode);
    }
}

if (!function_exists('json')) {
    function json(mixed $data, int $statusCode = 200): Response
    {
        return Response::json($data, $statusCode);
    }
}

if (!function_exists('app_base_path')) {
    function app_base_path(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $uriPath = parse_url($uri, PHP_URL_PATH) ?: $uri;

        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        $scriptDir = str_replace('\\', '/', $scriptDir);
        if ($scriptDir !== '/' && $scriptDir !== '.' && $scriptDir !== '') {
            $trimmed = rtrim($scriptDir, '/');
            if (str_ends_with(strtolower($trimmed), '/public')) {
                $parent = substr($trimmed, 0, -7);
                if ($parent !== '' && (strncasecmp($uriPath, $parent . '/', strlen($parent) + 1) === 0 || strcasecmp($uriPath, $parent) === 0)) {
                    return substr($uriPath, 0, strlen($parent));
                }
            }
            if (strncasecmp($uriPath, $trimmed . '/', strlen($trimmed) + 1) === 0 || strcasecmp($uriPath, $trimmed) === 0) {
                return substr($uriPath, 0, strlen($trimmed));
            }
        }
        return '';
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $sub = app_base_path();
        $path = '/' . ltrim($path, '/');
        return $sub ? $sub . $path : $path;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $cleanPath = '/' . ltrim(str_replace('\\', '/', $path), '/');
        if (!str_starts_with($cleanPath, '/assets/')) {
            $cleanPath = '/assets/' . ltrim($cleanPath, '/');
        }
        return url($cleanPath);
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('asset_v')) {
    /**
     * Asset URL with the file's modification time as ?v=, so browsers always load the newest CSS, JS and models.
     */
    function asset_v(string $path): string
    {
        $file = dirname(__DIR__, 2) . '/public/assets/' . ltrim(preg_replace('#^/?assets/#', '', str_replace('\\', '/', $path)), '/');
        $version = is_file($file) ? filemtime($file) : 1;
        return asset($path) . '?v=' . $version;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return session()->token();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('method_field')) {
    function method_field(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . e(strtoupper($method)) . '">';
    }
}

if (!function_exists('flash')) {
    function flash(string $key, mixed $value = null): mixed
    {
        if ($value === null) {
            return session()->getFlash($key);
        }
        session()->flash($key, $value);
        return null;
    }
}

if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        static $settingsCache = null;
        if ($settingsCache === null) {
            $settingsCache = [];
            try {
                $rows = db()->fetchAll("SELECT `key`, `value` FROM `settings`");
                foreach ($rows as $row) {
                    $settingsCache[$row['key']] = $row['value'];
                }
            } catch (\Throwable $e) {
                // Return default if DB is not installed yet
                return $default;
            }
        }
        return $settingsCache[$key] ?? $default;
    }
}

if (!function_exists('auth')) {
    function auth(): ?array
    {
        return session()->get('auth_user');
    }
}

if (!function_exists('auth_id')) {
    function auth_id(): ?int
    {
        $user = auth();
        return $user ? (int)$user['id'] : null;
    }
}

if (!function_exists('has_role')) {
    function has_role(string ...$roles): bool
    {
        $user = auth();
        if (!$user || !isset($user['role'])) {
            return false;
        }
        return in_array($user['role'], $roles, true);
    }
}

if (!function_exists('audit_log')) {
    function audit_log(string $action, ?string $entityType = null, ?int $entityId = null, mixed $details = null): void
    {
        try {
            $userId = auth_id();
            $ip = request()->getIp();
            $userAgent = request()->getUserAgent();
            $detailStr = is_string($details) ? $details : json_encode($details, JSON_UNESCAPED_UNICODE);

            db()->insert('audit_logs', [
                'user_id' => $userId,
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'details' => $detailStr,
                'ip_address' => $ip,
                'user_agent' => substr((string)$userAgent, 0, 255),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Throwable $e) {
            @error_log("Failed to write audit log: " . $e->getMessage());
        }
    }
}

if (!function_exists('clean_phone_number')) {
    /**
     * Normalize international phone numbers (E.164 compatible):
     * Strips non-digit formatting and ensures clean standard output.
     */
    function clean_phone_number(?string $phone, bool $withPlus = false): string
    {
        if (empty($phone)) {
            return '';
        }
        $cleaned = preg_replace('/[^\d+]/', '', (string)$phone);

        if (str_starts_with($cleaned, '+')) {
            $cleaned = substr($cleaned, 1);
        } elseif (str_starts_with($cleaned, '00')) {
            $cleaned = substr($cleaned, 2);
        }

        return $withPlus ? '+' . $cleaned : $cleaned;
    }
}

if (!function_exists('format_phone_display')) {
    /**
     * Format international and national phone numbers for human display.
     */
    function format_phone_display(?string $phone): string
    {
        if (empty($phone)) {
            return '—';
        }
        $raw = trim((string)$phone);
        if (str_starts_with($raw, '+')) {
            return $raw;
        }
        $cleaned = clean_phone_number($raw, false);
        if (strlen($cleaned) >= 10 && strlen($cleaned) <= 15) {
            return '+' . $cleaned;
        }
        return $raw;
    }
}

if (!function_exists('sanitize_html')) {
    /**
     * Sanitize rich-text HTML content against Stored XSS while preserving legitimate markup.
     */
    function sanitize_html(?string $html): string
    {
        if (empty($html)) {
            return '';
        }
        // Allowed tags
        $allowed = '<p><br><strong><b><em><i><u><s><h1><h2><h3><h4><h5><h6><ul><ol><li><blockquote><pre><code><a><img><hr><table><thead><tbody><tr><th><td><div><span>';
        $cleaned = strip_tags($html, $allowed);

        // Strip dangerous event attributes like onclick, onload, onerror, onmouseover, etc.
        $cleaned = preg_replace('/\s+on\w+\s*=\s*(["\']).*?\1/is', '', $cleaned);
        $cleaned = preg_replace('/\s+on\w+\s*=\s*[^>\s]+/is', '', $cleaned);

        // Strip javascript: pseudoprotocol in links and image sources
        $cleaned = preg_replace('/(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/is', '$1="#"', $cleaned);

        return $cleaned;
    }
}
