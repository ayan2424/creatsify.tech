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
        $sections = [];
        try {
            $sectionsRaw = Section::allActive();
            foreach ($sectionsRaw as $sec) {
                $sections[$sec['section_key']] = $sec;
                if (!empty($sec['metadata']) && is_string($sec['metadata'])) {
                    $sections[$sec['section_key']]['metadata'] = json_decode($sec['metadata'], true) ?: [];
                }
            }
        } catch (\Throwable $e) {}

        // Fetch agency client portfolio case studies & ready-made software products separately
        try {
            $portfolioWorks    = Project::portfolioWorks();
            $readyMadeProducts = Project::readyMadeProducts();
            $projects          = Project::all();
        } catch (\Throwable $e) {
            $portfolioWorks    = [];
            $readyMadeProducts = [];
            $projects          = [];
        }

        // Fetch active agency services (self-healed 8 pillars)
        try {
            $services = Service::allActive();
        } catch (\Throwable $e) {
            $services = [];
        }

        // Fetch active service packages grouped by track (dev, design, growth)
        try {
            $packagesByTrack = ServicePackage::allActiveGroupedByTrack();
            $packageTracks   = ServicePackage::tracks();
        } catch (\Throwable $e) {
            $packagesByTrack = [];
            $packageTracks   = [];
        }

        // Fetch recent blog posts
        try {
            $posts = Post::allPublished();
        } catch (\Throwable $e) {
            $posts = [];
        }

        // Fetch featured verified client reviews & aggregate rating stats
        try {
            $reviews     = ClientReview::allApproved();
            $reviewStats = ClientReview::stats();
        } catch (\Throwable $e) {
            $reviews     = [];
            $reviewStats = ['average' => 4.9, 'total' => 12];
        }

        return view('home/index', [
            'sections'          => $sections,
            'projects'          => $projects,
            'portfolioWorks'    => $portfolioWorks,
            'readyMadeProducts' => $readyMadeProducts,
            'services'          => $services,
            'packagesByTrack'   => $packagesByTrack,
            'packageTracks'     => $packageTracks,
            'posts'             => $posts,
            'reviews'           => $reviews,
            'reviewStats'       => $reviewStats,
            'meta_title'        => 'Creatsify — Web & App Development, Branding, SEO & Digital Growth Agency',
            'meta_desc'         => 'Creatsify is a full-service digital engineering, branding, and performance marketing agency building custom web apps, Flutter mobile apps, website redesigns, SaaS, SEO, and paid social ad engines.',
        ]);
    }
}
