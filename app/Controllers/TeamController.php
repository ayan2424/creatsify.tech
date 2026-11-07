<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Request;
use Core\Response;

/**
 * Creatsify Digital Agency - Legacy Team Route Redirector
 * Public team/founder profiles are disabled by agency policy; redirects cleanly to /about.
 */
class TeamController
{
    public function index(Request $request): Response
    {
        return Response::redirect(url('/about'));
    }

    public function show(Request $request, string $slug): Response
    {
        return Response::redirect(url('/about'));
    }
}
