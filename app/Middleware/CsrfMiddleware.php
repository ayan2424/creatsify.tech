<?php

namespace App\Middleware;

use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - CSRF Protection Middleware
 */
class CsrfMiddleware
{
    private array $except = [
        '/api/webhooks/whatsapp',
        '/api/chat/send',
        '/api/chat/messages',
        '/api/chat/poll',
        '/api/contact'
    ];

    public function handle(Request $request, callable $next): Response
    {
        $method = $request->getMethod();
        $path = $request->getPath();

        // Safe methods do not require CSRF verification
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'])) {
            return $next($request);
        }

        // Whitelisted endpoints
        foreach ($this->except as $exempt) {
            if ($path === $exempt || str_starts_with($path, $exempt)) {
                return $next($request);
            }
        }

        $token = $request->input('_csrf_token') ?? $request->getHeader('X-CSRF-Token');

        if (!session()->validateToken($token)) {
            if ($request->isAjax()) {
                return Response::json(['success' => false, 'error' => 'Invalid or expired CSRF token.'], 419);
            }
            flash('error', 'Session expired. Please try again.');
            return Response::redirect($request->getUri() ?: '/');
        }

        return $next($request);
    }
}
