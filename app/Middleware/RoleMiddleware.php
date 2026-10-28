<?php

namespace App\Middleware;

use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Role-Based Access Control Middleware (Cheezify Architecture)
 */
class RoleMiddleware
{
    public function handle(Request $request, callable $next, string ...$allowedRoles): Response
    {
        $user = auth();
        if (!$user) {
            return Response::redirect('/admin/login');
        }

        // Super Admin always has full access
        if (($user['role'] ?? '') === 'super_admin') {
            return $next($request);
        }

        if (!empty($allowedRoles) && !in_array($user['role'] ?? '', $allowedRoles, true)) {
            if ($request->isAjax()) {
                return Response::json(['success' => false, 'error' => 'Unauthorized. Insufficient permissions.'], 403);
            }
            flash('error', 'You do not have permission to access that area.');
            $fallback = ($user['role'] ?? '') === 'editor' ? '/admin/posts' : '/admin';
            if ($request->getPath() === $fallback || $request->getPath() === '/admin') {
                return ($user['role'] ?? '') === 'editor' ? Response::redirect('/admin/posts') : Response::redirect('/');
            }
            return Response::redirect($fallback);
        }

        return $next($request);
    }
}
