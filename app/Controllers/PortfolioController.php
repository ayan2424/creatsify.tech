<?php

namespace App\Controllers;

use App\Models\ClientReview;
use App\Models\Project;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Portfolio & Projects Controller
 */
class PortfolioController
{
    public function index(Request $request): Response
    {
        $category = $request->get('cat', 'all');
        $allProjects = Project::all();

        $filtered = array_filter($allProjects, function ($p) use ($category) {
            if ($category === 'all') {
                return true;
            }
            return ($p['category'] === $category);
        });

        return view('portfolio/index', [
            'projects' => $filtered,
            'activeCategory' => $category,
            'meta_title' => 'Our Work | Creatsify',
            'meta_desc' => 'Courier software, an online store with its own admin panel, and a WordPress store built by Creatsify.'
        ]);
    }

    public function show(Request $request, string $slug): Response
    {
        $project = Project::findBySlug($slug);
        if (!$project) {
            return view('errors/404', ['message' => 'Project not found'])->setStatusCode(404);
        }

        $clientReview = ClientReview::forProject($slug, (string)($project['category'] ?? ''));

        return view('portfolio/show', [
            'project'      => $project,
            'clientReview' => $clientReview,
            'meta_title'   => "{$project['title']} | Creatsify Case Study",
            'meta_desc'    => $project['summary']
        ]);
    }
}
