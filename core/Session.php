<?php

namespace Core;

/**
 * Creatsify Studio - Secure Session & Flash Manager
 */
class Session
{
    private const FLASH_KEY = '_creatsify_flash';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_only_cookies', '1');
            ini_set('session.use_strict_mode', '1');
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_samesite', 'Lax');

            session_start();
        }

        // Age out flash data
        $this->ageFlashData();
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function flash(string $key, mixed $value): void
    {
        $_SESSION[self::FLASH_KEY]['new'][$key] = $value;
    }

    public function getFlash(string $key, mixed $default = null): mixed
    {
        return $_SESSION[self::FLASH_KEY]['old'][$key] ?? $default;
    }

    private function ageFlashData(): void
    {
        $_SESSION[self::FLASH_KEY]['old'] = $_SESSION[self::FLASH_KEY]['new'] ?? [];
        $_SESSION[self::FLASH_KEY]['new'] = [];
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function destroy(): void
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    /**
     * Anti-CSRF Protection
     */
    public function token(): string
    {
        if (!$this->has('_csrf_token')) {
            $this->set('_csrf_token', bin2hex(random_bytes(32)));
        }
        return (string) $this->get('_csrf_token');
    }

    public function validateToken(?string $token): bool
    {
        $sessionToken = $this->get('_csrf_token');
        if (!$sessionToken || !$token) {
            return false;
        }
        return hash_equals((string)$sessionToken, (string)$token);
    }
}
