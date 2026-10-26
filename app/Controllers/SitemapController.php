<?php

namespace App\Controllers;

use App\Models\MemberPortfolio;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Dynamic XML Sitemap Generator
 */
class SitemapController
{
    public function index(Request $request): Response
    {
        $baseUrl = rtrim(url('/'), '/');
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') ? 'https://' : 'http://';
        if (str_starts_with($baseUrl, '/')) {
            $baseUrl = $protocol . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $baseUrl;
        }

        $staticPages = [
            ['path' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['path' => '/portfolio', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['path' => '/services', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['path' => '/team', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['path' => '/about', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['path' => '/blog', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['path' => '/contact', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        $urls = [];
        $now = date('Y-m-d');

        foreach ($staticPages as $p) {
            $urls[] = [
                'loc' => $baseUrl . $p['path'],
                'lastmod' => $now,
                'changefreq' => $p['changefreq'],
                'priority' => $p['priority']
            ];
        }

        // Dynamic Projects
        try {
            $projects = Project::all();
            foreach ($projects as $proj) {
                if (!empty($proj['slug'])) {
                    $urls[] = [
                        'loc' => $baseUrl . '/portfolio/' . urlencode($proj['slug']),
                        'lastmod' => substr($proj['updated_at'] ?? $now, 0, 10),
                        'changefreq' => 'monthly',
                        'priority' => '0.8'
                    ];
                }
            }
        } catch (\Throwable $e) {}

        // Dynamic Blog Posts
        try {
            $posts = Post::published();
            foreach ($posts as $post) {
                if (!empty($post['slug'])) {
                    $urls[] = [
                        'loc' => $baseUrl . '/blog/' . urlencode($post['slug']),
                        'lastmod' => substr($post['published_at'] ?? $now, 0, 10),
                        'changefreq' => 'weekly',
                        'priority' => '0.7'
                    ];
                }
            }
        } catch (\Throwable $e) {}

        // Dynamic Team Members
        try {
            $members = TeamMember::active();
            foreach ($members as $m) {
                if (!empty($m['slug'])) {
                    $urls[] = [
                        'loc' => $baseUrl . '/team/' . urlencode($m['slug']),
                        'lastmod' => substr($m['updated_at'] ?? $now, 0, 10),
                        'changefreq' => 'monthly',
                        'priority' => '0.6'
                    ];
                }
            }
        } catch (\Throwable $e) {}

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= "    <lastmod>{$u['lastmod']}</lastmod>\n";
            $xml .= "    <changefreq>{$u['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$u['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= "</urlset>";

        return Response::make($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
