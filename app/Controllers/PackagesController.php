<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ClientReview;
use App\Models\ServicePackage;
use Core\Request;
use Core\Response;

/**
 * Creatsify Digital Agency - Public Service Packages & Pricing Controller
 */
class PackagesController
{
    public function index(Request $request): Response
    {
        $packagesByTrack = ServicePackage::allActiveGroupedByTrack();
        $packageTracks   = ServicePackage::tracks();
        $reviewStats     = ClientReview::stats();
        $reviews         = ClientReview::featured(3);

        return view('packages/index', [
            'packagesByTrack' => $packagesByTrack,
            'packageTracks'   => $packageTracks,
            'reviewStats'     => $reviewStats,
            'reviews'         => $reviews,
            'meta_title'      => 'Service Packages & Pricing | Creatsify',
            'meta_desc'       => 'Clear packages for web and app development, branding and SEO — or build a custom bundle estimate with our scope calculator.',
        ]);
    }
}
