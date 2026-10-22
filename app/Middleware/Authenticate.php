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
        if (!auth()) {
            if ($request->isAjax() || str_starts_with($request->getPath(), '/api')) {
                return Response::json(['success' => false, 'error' => 'Unauthenticated'], 401);
            }
            return Response::redirect('/admin/login');
        }

        return $next($request);
    }
}
