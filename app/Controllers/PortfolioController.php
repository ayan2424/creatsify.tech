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
        $category = (string)$request->get('cat', 'all');
        if ($category === 'software') {
            return redirect(url('/software'));
        }
        $normalizedCategory = Project::normalizeCategory($category);
        $portfolioWorks = Project::portfolioWorks($normalizedCategory);

        return view('portfolio/index', [
            'projects'       => $portfolioWorks,
            'activeCategory' => $normalizedCategory,
            'meta_title'     => 'Our Work & Delivered Client Case Studies | Creatsify Digital Agency',
            'meta_desc'      => 'Explore our portfolio of delivered full-stack web platforms, Flutter mobile apps, high-converting website redesigns, and high-ROAS marketing campaigns.',
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
