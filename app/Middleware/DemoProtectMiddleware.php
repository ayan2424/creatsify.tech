<?php

namespace App\Middleware;

use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Live Marketplace Demo Mode Protection Middleware
 * Prevents malicious or accidental modification/deletion of core data during buyer previews.
 */
class DemoProtectMiddleware
{
    /**
     * Protected routes pattern / prefixes under demo mode
     */
    private array $blockedActions = [
        'POST'   => ['/admin/settings', '/admin/users/create', '/admin/users'],
        'PUT'    => ['/admin/users', '/admin/settings'],
        'DELETE' => ['/admin/users', '/admin/projects', '/admin/posts', '/admin/media', '/admin/crm']
    ];

    public function handle(Request $request, callable $next): Response
    {
        // Only enforce when DEMO_MODE is explicitly enabled
        if (env('DEMO_MODE', false) !== true) {
            return $next($request);
        }

        $method = $request->getMethod();
        $path = $request->getPath();

        // Safe methods (read-only) are always permitted
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'])) {
            return $next($request);
        }

        // Always permit staff/admin live chat replies and internal notes in demo mode
        if (str_starts_with($path, '/admin/chat/reply') || str_starts_with($path, '/admin/crm/tickets/') && str_ends_with($path, '/reply')) {
            return $next($request);
        }

        // Block destructive or critical admin actions
        $isBlocked = false;
        if (isset($this->blockedActions[$method])) {
            foreach ($this->blockedActions[$method] as $prefix) {
                if (str_starts_with($path, $prefix)) {
                    $isBlocked = true;
                    break;
                }
            }
        }

        // Special check: block changing settings or deleting users
        if ($method === 'POST' && str_contains($path, 'delete')) {
            $isBlocked = true;
        }

        if ($isBlocked) {
            $msg = 'Action disabled in Live Demo Mode. Marketplace evaluators can browse and test all read operations.';
            if ($request->isAjax()) {
                return Response::json(['success' => false, 'error' => $msg], 403);
            }
            flash('error', $msg);
            $referrer = $_SERVER['HTTP_REFERER'] ?? '/admin';
            return Response::redirect($referrer);
        }

        return $next($request);
    }
}
