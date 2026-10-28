<?php

namespace App\Middleware;

use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Authentication Middleware
 */
class Authenticate
{
    public function handle(Request $request, callable $next): Response
    {
        $user = auth();
        if (!$user) {
            if ($request->isAjax() || str_starts_with($request->getPath(), '/api')) {
                return Response::json(['success' => false, 'error' => 'Unauthenticated'], 401);
            }
            return Response::redirect('/admin/login');
        }

        // Verify active database status if user ID exists
        if (!empty($user['id'])) {
            try {
                $dbUser = \App\Models\User::findById((int)$user['id']);
                if (!$dbUser || ($dbUser['status'] ?? 'active') !== 'active') {
                    session()->remove('auth_user');
                    flash('error', 'Your account has been deactivated or suspended.');
                    if ($request->isAjax() || str_starts_with($request->getPath(), '/api')) {
                        return Response::json(['success' => false, 'error' => 'Account suspended.'], 403);
                    }
                    return Response::redirect('/admin/login');
                }
            } catch (\Throwable $e) {}
        }

        return $next($request);
    }
}
