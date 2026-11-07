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
            'meta_title'      => 'Full-Spectrum Digital Services — Web, Flutter Apps, SEO, Ads & Branding | Creatsify',
            'meta_desc'       => 'Explore Creatsify full-spectrum agency services: Custom Web Development (PHP, Node.js, Next.js), Flutter Mobile Apps, Website Redesign, SaaS Engineering, SEO, Paid Social Ads, and Brand Identity.'
        ]);
    }
}
