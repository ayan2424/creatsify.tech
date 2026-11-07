<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ClientReview;
use App\Models\Post;
use App\Models\Project;
use App\Models\Section;
use App\Models\Service;
use App\Models\ServicePackage;
use Core\Request;
use Core\Response;

/**
 * Creatsify Digital Agency - Homepage Controller
 */
class HomeController
{
    public function index(Request $request): Response
    {
        $sectionsRaw = Section::allActive();
        $sections = [];
        foreach ($sectionsRaw as $sec) {
            $sections[$sec['section_key']] = $sec;
            if (!empty($sec['metadata']) && is_string($sec['metadata'])) {
                $sections[$sec['section_key']]['metadata'] = json_decode($sec['metadata'], true) ?: [];
            }
        }

        // Fetch portfolio & software products
        $projects = Project::all();

        // Fetch active agency services (self-healed 8 pillars)
        $services = Service::allActive();

        // Fetch active service packages grouped by track (dev, design, growth)
        $packagesByTrack = ServicePackage::allActiveGroupedByTrack();
        $packageTracks   = ServicePackage::tracks();

        // Fetch recent blog posts
        $posts = Post::allPublished();

        // Fetch featured verified client reviews & aggregate rating stats
        $reviews = ClientReview::featured(6);
        $reviewStats = ClientReview::stats();

        return view('home/index', [
            'sections'        => $sections,
            'projects'        => $projects,
            'services'        => $services,
            'packagesByTrack' => $packagesByTrack,
            'packageTracks'   => $packageTracks,
            'posts'           => $posts,
            'reviews'         => $reviews,
            'reviewStats'     => $reviewStats,
            'meta_title'      => 'Creatsify — Web & App Development, Branding, SEO & Digital Growth Agency',
            'meta_desc'       => 'Creatsify is a full-service digital engineering, branding, and performance marketing agency building custom web apps, Flutter mobile apps, website redesigns, SaaS, SEO, and paid social ad engines.',
        ]);
    }
}
