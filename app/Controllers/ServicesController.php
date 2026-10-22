<?php

namespace App\Controllers;

use App\Models\Service;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Services Controller
 */
class ServicesController
{
    public function index(Request $request): Response
    {
        $services = Service::allActive();

        return view('services/index', [
            'services' => $services,
            'meta_title' => 'Engineering Services | Creatsify',
            'meta_desc' => 'High-velocity software engineering, cloud SaaS architecture, mobile applications, and AI integrations.'
        ]);
    }
}
