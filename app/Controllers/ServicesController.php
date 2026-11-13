<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ClientReview;
use App\Models\Service;
use App\Models\ServicePackage;
use Core\Request;
use Core\Response;

/**
 * Creatsify Digital Agency - Services Controller
 */
class ServicesController
{
    public function index(Request $request): Response
    {
        $services        = Service::allActive();
        $packagesByTrack = ServicePackage::allActiveGroupedByTrack();
        $packageTracks   = ServicePackage::tracks();
        $reviewStats     = ClientReview::stats();

        return view('services/index', [
            'services'        => $services,
            'packagesByTrack' => $packagesByTrack,
            'packageTracks'   => $packageTracks,
            'reviewStats'     => $reviewStats,
            'meta_title'      => 'Web, App, SEO & Branding Services | Creatsify',
            'meta_desc'       => 'Custom web development, Flutter mobile apps, website redesign, SaaS, SEO, paid ads and branding — delivered by one team with clear milestones.'
        ]);
    }
}
