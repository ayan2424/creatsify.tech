<?php

namespace App\Middleware;

use App\Models\AnalyticsVisit;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Live Traffic & Analytics Middleware
 */
class AnalyticsMiddleware
{
    public function handle(Request $request, callable $next): Response
    {
        // Execute request first
        $response = $next($request);

        // Record visit asynchronously / non-blocking
        if (!str_starts_with($request->getPath(), '/admin')) {
            AnalyticsVisit::log(
                $request->getPath(),
                $request->getIp(),
                $request->getReferrer(),
                $request->getUserAgent()
            );
        }

        return $response;
    }
}
