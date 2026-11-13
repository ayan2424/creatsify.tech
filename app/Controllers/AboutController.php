<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ClientReview;
use Core\Request;
use Core\Response;

/**
 * Creatsify Digital Agency - About Agency Controller
 */
class AboutController
{
    public function index(Request $request): Response
    {
        $reviewStats = ClientReview::stats();

        return view('about/index', [
            'reviewStats' => $reviewStats,
            'meta_title'  => 'About Creatsify | Web, App & Brand Studio',
            'meta_desc'   => 'Meet the team behind Creatsify: developers, designers and marketers building websites, apps, brands and growth campaigns for clients worldwide.',
        ]);
    }
}
