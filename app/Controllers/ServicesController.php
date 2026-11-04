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
            'meta_title' => 'Services | Creatsify',
            'meta_desc' => 'Custom websites, admin panels, WordPress stores, courier systems and WhatsApp or SMS alerts from Creatsify.'
        ]);
    }
}
