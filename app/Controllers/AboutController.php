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
            'meta_title'  => 'About Creatsify | Full-Service Web, App, Branding & Digital Growth Agency',
            'meta_desc'   => 'Discover how Creatsify engineers custom web and mobile applications, executes high-converting website redesigns, crafts brand identities, and scales businesses through SEO and paid social ads.',
        ]);
    }
}
