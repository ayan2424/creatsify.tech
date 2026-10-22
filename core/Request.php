<?php

namespace Core;

/**
 * Creatsify Studio - HTTP Request Representation
 */
class Request
{
    private array $get;
    private array $post;
    private array $server;
    private array $cookies;
    private array $files;
    private ?array $json = null;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->cookies = $_COOKIE;
        $this->files = $_FILES;

        // Parse JSON body if Content-Type is application/json
        $contentType = $this->getHeader('Content-Type') ?? '';
        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input');
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $this->json = $decoded;
            }
        }
    }

    public function getMethod(): string
    {
        $method = strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
        if ($method === 'POST') {
            // Check method spoofing (_method)
            $override = $this->input('_method');
            if ($override && in_array(strtoupper($override), ['PUT', 'PATCH', 'DELETE'])) {
                return strtoupper($override);
            }
        }
        return $method;
    }

    public function getUri(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }

    public function getPath(): string
    {
        $uri = $this->getUri();
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        // Auto-strip base subdirectory prefix if hosted in XAMPP / subfolder
        $base = function_exists('app_base_path') ? app_base_path() : '';
        if ($base !== '') {
            $bLen = strlen($base);
            if (strncasecmp($path, $base . '/', $bLen + 1) === 0) {
                $path = substr($path, $bLen);
            } elseif (strcasecmp($path, $base) === 0) {
                $path = '/';
            }
        }

        // Strip leading /public if accessed through subdirectory with public in URI
        if (strncasecmp($path, '/public/', 8) === 0) {
            $path = substr($path, 7);
        } elseif (strcasecmp($path, '/public') === 0) {
            $path = '/';
        }

        // Remove trailing slashes for uniformity (except root)
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }
        return $path;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        if ($this->json !== null && array_key_exists($key, $this->json)) {
            return $this->json[$key];
        }
        if (array_key_exists($key, $this->post)) {
            return $this->post[$key];
        }
        if (array_key_exists($key, $this->get)) {
            return $this->get[$key];
        }
        return $default;
    }

    public function all(): array
    {
        $all = array_merge($this->get, $this->post);
        if ($this->json !== null) {
            $all = array_merge($all, $this->json);
        }
        return $all;
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function getHeader(string $name): ?string
    {
        $header = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        if (isset($this->server[$header])) {
            return $this->server[$header];
        }
        if (isset($this->server['CONTENT_TYPE']) && strcasecmp($name, 'Content-Type') === 0) {
            return $this->server['CONTENT_TYPE'];
        }
        return null;
    }

    public function isAjax(): bool
    {
        return ($this->getHeader('X-Requested-With') === 'XMLHttpRequest') ||
               str_contains($this->getHeader('Accept') ?? '', 'application/json');
    }

    public function getIp(): string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR'
        ];
        foreach ($headers as $h) {
            if (!empty($this->server[$h])) {
                $ips = explode(',', $this->server[$h]);
                return trim($ips[0]);
            }
        }
        return '127.0.0.1';
    }

    public function getUserAgent(): string
    {
        return $this->server['HTTP_USER_AGENT'] ?? 'Unknown';
    }

    public function getReferrer(): ?string
    {
        return $this->server['HTTP_REFERER'] ?? null;
    }

    public function isSecure(): bool
    {
        return (!empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off') ||
               ($this->server['SERVER_PORT'] ?? 0) == 443 ||
               ($this->getHeader('X-Forwarded-Proto') === 'https');
    }
}
