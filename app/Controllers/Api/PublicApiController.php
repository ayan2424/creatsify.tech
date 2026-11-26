<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\AnalyticsVisit;
use App\Models\ClientReview;
use App\Models\Post;
use App\Models\Project;
use App\Models\Section;
use App\Models\Service;
use App\Models\ServicePackage;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Headless Public JSON API v1
 *
 * Bridges the existing Custom PHP 8.2/8.4 MVC backend & MySQL database
 * directly to the Next.js App Router frontend without altering /admin or
 * any existing server-side controllers.
 */
class PublicApiController
{
    private const OUTCOME_MAP = [
        'apex-retail-redesign'       => '+184% Conversion Lift',
        'lumina-fleet-app'           => '4.9★ App Store Rating',
        'kinetix-brand-system'       => '3.8x Meta & Google ROAS',
        'cheetah-enterprise'         => '120k+ Parcels/Mo SLA',
        'cheetah-lite'               => '12-Day Custom Launch',
        'cheezify'                   => '+92% Repeat Order Rate',
        'restromaster-kds-interface' => '0.8s Kitchen Order Sync',
        'viddmate-video-suite'       => '4.2x Faster Encoding',
        'pulserank-geo-seo'          => '+310% AI Citation Lift',
        'novagrowth-performance-ads' => '4.1x Verified Ad ROAS',
    ];

    private const IMAGE_MAP = [
        'cheetah-enterprise'         => '/assets/images/cheetah-showcase.webp',
        'cheetah-lite'               => '/assets/images/cheetah-lite-preview.webp',
        'cheezify'                   => '/assets/images/cheezify-preview.webp',
        'restromaster-kds-interface' => '/assets/images/restromaster-preview.webp',
        'viddmate-video-suite'       => '/assets/images/viddmate-preview.webp',
        'apex-retail-redesign'       => '/assets/images/apex-preview.webp',
        'lumina-fleet-app'           => '/assets/images/lumina-preview.webp',
        'kinetix-brand-system'       => '/assets/images/kinetix-preview.webp',
        'pulserank-geo-seo'          => '/assets/images/pulserank-preview.webp',
        'novagrowth-performance-ads' => '/assets/images/novagrowth-preview.webp',
    ];

    public function csrf(Request $request): Response
    {
        return Response::json([
            'success'    => true,
            'csrf_token' => csrf_token(),
        ], 200, ['Cache-Control' => 'no-store, private']);
    }

    public function bootstrap(Request $request): Response
    {
        $reviewStats = $this->safeReviewStats();
        $footerProducts = [];
        try {
            $rows = db()->fetchAll(
                "SELECT `slug`, `title`, `price`, `gumroad_url` FROM `projects` WHERE `status` = 'For sale' OR `is_software` = 1 ORDER BY `featured` DESC, `sort_order` ASC LIMIT 4"
            );
            $footerProducts = $rows;
        } catch (\Throwable $e) {
            $footerProducts = [];
        }

        return Response::json([
            'success'         => true,
            'site'            => $this->siteConfig(),
            'csrf_token'      => csrf_token(),
            'categories'      => Project::categories(),
            'review_stats'    => $reviewStats,
            'footer_products' => $footerProducts,
        ], 200, ['Cache-Control' => 'no-store, private']);
    }

    public function home(Request $request): Response
    {
        $sections = [];
        try {
            foreach (Section::allActive() as $sec) {
                if (!empty($sec['metadata']) && is_string($sec['metadata'])) {
                    $sec['metadata'] = json_decode($sec['metadata'], true) ?: [];
                }
                $sections[$sec['section_key']] = $sec;
            }
        } catch (\Throwable $e) {
        }

        try {
            $portfolioWorks    = array_map([$this, 'hydrateProject'], Project::portfolioWorks());
            $readyMadeProducts = array_map([$this, 'hydrateProject'], Project::readyMadeProducts());
            $projects          = array_map([$this, 'hydrateProject'], Project::all());
        } catch (\Throwable $e) {
            $portfolioWorks    = [];
            $readyMadeProducts = [];
            $projects          = [];
        }

        try {
            $services = Service::allActive();
        } catch (\Throwable $e) {
            $services = [];
        }

        try {
            $packagesByTrackRaw = ServicePackage::allActiveGroupedByTrack();
            $packagesByTrack = [];
            foreach ($packagesByTrackRaw as $track => $items) {
                $packagesByTrack[$track] = array_map([$this, 'hydratePackage'], $items);
            }
            $packageTracks = ServicePackage::tracks();
        } catch (\Throwable $e) {
            $packagesByTrack = ['dev' => [], 'design' => [], 'growth' => []];
            $packageTracks   = ServicePackage::tracks();
        }

        try {
            $posts = Post::allPublished();
        } catch (\Throwable $e) {
            $posts = [];
        }

        try {
            $reviews = ClientReview::allApproved();
        } catch (\Throwable $e) {
            $reviews = [];
        }

        return Response::json([
            'success'             => true,
            'site'                => $this->siteConfig(),
            'sections'            => $sections,
            'categories'          => Project::categories(),
            'projects'            => $projects,
            'portfolio_works'     => $portfolioWorks,
            'ready_made_products' => $readyMadeProducts,
            'services'            => $services,
            'packages_by_track'   => $packagesByTrack,
            'package_tracks'      => $packageTracks,
            'posts'               => $posts,
            'reviews'             => $reviews,
            'review_stats'        => $this->safeReviewStats(),
        ]);
    }

    public function projects(Request $request): Response
    {
        $cat = trim((string)$request->get('cat', ''));
        $softwareOnly = $request->get('software') === '1';

        try {
            if ($softwareOnly) {
                $rows = Project::readyMadeProducts();
            } elseif ($cat !== '' && $cat !== 'all') {
                $rows = Project::portfolioWorks($cat);
            } else {
                $rows = Project::portfolioWorks();
            }
            $projects = array_map([$this, 'hydrateProject'], $rows);
            $software = array_map([$this, 'hydrateProject'], Project::readyMadeProducts());
        } catch (\Throwable $e) {
            $projects = [];
            $software = [];
        }

        return Response::json([
            'success'         => true,
            'categories'      => Project::categories(),
            'active_category' => $cat !== '' ? Project::normalizeCategory($cat) : 'web-dev',
            'projects'        => $projects,
            'software'        => $software,
        ]);
    }

    public function projectShow(Request $request, string $slug): Response
    {
        try {
            $project = Project::findBySlug($slug);
        } catch (\Throwable $e) {
            $project = null;
        }

        if (!$project) {
            return Response::json(['success' => false, 'error' => 'Project not found.'], 404);
        }

        $hydrated = $this->hydrateProject($project);
        $clientReview = null;
        try {
            $clientReview = ClientReview::forProject($slug, (string)($project['category'] ?? ''));
        } catch (\Throwable $e) {
        }

        $related = [];
        try {
            $all = Project::portfolioWorks();
            foreach ($all as $item) {
                if (($item['slug'] ?? '') !== $slug) {
                    $related[] = $this->hydrateProject($item);
                }
                if (count($related) >= 3) {
                    break;
                }
            }
        } catch (\Throwable $e) {
        }

        return Response::json([
            'success'       => true,
            'project'       => $hydrated,
            'client_review' => $clientReview,
            'related'       => $related,
        ]);
    }

    public function services(Request $request): Response
    {
        try {
            $services = Service::allActive();
        } catch (\Throwable $e) {
            $services = [];
        }

        return Response::json([
            'success'  => true,
            'services' => $services,
        ]);
    }

    public function packages(Request $request): Response
    {
        try {
            $grouped = ServicePackage::allActiveGroupedByTrack();
            $hydrated = [];
            foreach ($grouped as $track => $items) {
                $hydrated[$track] = array_map([$this, 'hydratePackage'], $items);
            }
            $all = array_map([$this, 'hydratePackage'], ServicePackage::allActive());
        } catch (\Throwable $e) {
            $hydrated = ['dev' => [], 'design' => [], 'growth' => []];
            $all = [];
        }

        return Response::json([
            'success'           => true,
            'tracks'            => ServicePackage::tracks(),
            'packages_by_track' => $hydrated,
            'packages'          => $all,
        ]);
    }

    public function reviews(Request $request): Response
    {
        $category = trim((string)($request->get('category') ?: $request->get('cat', 'all')));
        $allowedCategories = [
            'Web Development',
            'Mobile App Development',
            'Website Redesign & UI/UX',
            'SaaS & Custom Software',
            'SEO & Social Media Ads',
            'Branding & Graphic Design',
            'White-Label Customization',
        ];
        if ($category !== 'all' && !in_array($category, $allowedCategories, true)) {
            $category = 'all';
        }

        try {
            $reviews = ClientReview::allApproved($category);
        } catch (\Throwable $e) {
            $reviews = [];
        }

        return Response::json([
            'success'         => true,
            'active_category' => $category,
            'categories'      => $allowedCategories,
            'reviews'         => $reviews,
            'stats'           => $this->safeReviewStats(),
        ]);
    }

    public function posts(Request $request): Response
    {
        try {
            $posts = Post::allPublished();
        } catch (\Throwable $e) {
            $posts = [];
        }

        return Response::json([
            'success' => true,
            'posts'   => $posts,
        ]);
    }

    public function postShow(Request $request, string $slug): Response
    {
        try {
            $post = Post::findBySlug($slug);
            if ($post && !empty($post['id'])) {
                Post::incrementViews((int)$post['id']);
            }
        } catch (\Throwable $e) {
            $post = null;
        }

        if (!$post) {
            return Response::json(['success' => false, 'error' => 'Article not found.'], 404);
        }

        return Response::json([
            'success' => true,
            'post'    => $post,
        ]);
    }

    public function analyticsHit(Request $request): Response
    {
        $path = trim((string)$request->input('path', '/'));
        if ($path === '' || !str_starts_with($path, '/')) {
            $path = '/';
        }
        if (!str_starts_with($path, '/admin')) {
            try {
                AnalyticsVisit::log(
                    mb_substr($path, 0, 255),
                    $request->getIp(),
                    $request->input('referrer') ? mb_substr((string)$request->input('referrer'), 0, 255) : $request->getReferrer(),
                    $request->getUserAgent()
                );
            } catch (\Throwable $e) {
            }
        }

        return Response::json(['success' => true]);
    }

    private function siteConfig(): array
    {
        $waPhone = function_exists('clean_phone_number')
            ? clean_phone_number((string)setting('whatsapp_number', setting('site_phone', '923002521199')), false)
            : '923002521199';

        return [
            'name'             => (string)setting('site_name', 'Creatsify'),
            'title'            => (string)setting('site_title', 'Creatsify — Web, Mobile & SaaS Development Agency'),
            'meta_description' => (string)setting('meta_description', 'Custom web apps, Flutter mobile apps, SaaS, website redesigns, branding and SEO — built by a senior team with fixed milestones and full source-code ownership.'),
            'email'            => (string)setting('site_email', 'hello@creatsify.tech'),
            'whatsapp_number'  => $waPhone,
            'logo_dark'        => '/assets/brand/creatsify-logo-dark.svg',
            'logo_light'       => '/assets/brand/creatsify-logo-light.svg',
            'icon'             => '/assets/brand/creatsify-icon.svg',
        ];
    }

    private function safeReviewStats(): array
    {
        try {
            return ClientReview::stats();
        } catch (\Throwable $e) {
            return [
                'average'      => '4.9',
                'avg_rating'   => '4.9',
                'total'        => 8,
                'five_star'    => 8,
                'four_star'    => 0,
                'three_star'   => 0,
                'two_star'     => 0,
                'one_star'     => 0,
                'distribution' => [
                    5 => ['count' => 8, 'percent' => 100],
                    4 => ['count' => 0, 'percent' => 0],
                    3 => ['count' => 0, 'percent' => 0],
                    2 => ['count' => 0, 'percent' => 0],
                    1 => ['count' => 0, 'percent' => 0],
                ],
            ];
        }
    }

    private function hydrateProject(array $project): array
    {
        $slug = (string)($project['slug'] ?? '');
        $normCat = Project::normalizeCategory((string)($project['category'] ?? 'web-dev'));
        $techRaw = (string)($project['tech_stack'] ?? '');
        $techList = array_values(array_filter(array_map('trim', explode(',', $techRaw))));

        $imageUrl = $this->resolveProjectImage($slug, (string)($project['image_url'] ?? ''));

        $project['category']       = $normCat;
        $project['category_label'] = Project::categoryLabel($normCat);
        $project['is_software']    = !empty($project['is_software']) || (($project['status'] ?? '') === 'For sale');
        $project['tech_list']      = $techList;
        $project['image_resolved'] = $imageUrl;
        $project['outcome_metric'] = self::OUTCOME_MAP[$slug] ?? '';

        return $project;
    }

    private function hydratePackage(array $pkg): array
    {
        $pkg['features_list'] = ServicePackage::parseFeatures((string)($pkg['features'] ?? ''));
        $pkg['is_popular']    = !empty($pkg['is_popular']);
        return $pkg;
    }

    private function resolveProjectImage(string $slug, string $rawUrl): string
    {
        if ($rawUrl !== '' && !str_contains($rawUrl, 'creatsify-icon.png')) {
            if (str_starts_with($rawUrl, 'http://') || str_starts_with($rawUrl, 'https://') || str_starts_with($rawUrl, '/')) {
                return $rawUrl;
            }
            return '/assets/' . ltrim($rawUrl, '/');
        }

        if (isset(self::IMAGE_MAP[$slug])) {
            return self::IMAGE_MAP[$slug];
        }

        $assetDir = dirname(__DIR__, 3) . '/public/assets/images/';
        if (is_file($assetDir . $slug . '-preview.webp')) {
            return '/assets/images/' . $slug . '-preview.webp';
        }
        if (is_file($assetDir . $slug . '-preview.jpg')) {
            return '/assets/images/' . $slug . '-preview.jpg';
        }

        return '';
    }
}
