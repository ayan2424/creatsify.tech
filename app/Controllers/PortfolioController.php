<?php

namespace App\Controllers;

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
            'meta_title' => 'Portfolio & Case Studies | Creatsify',
            'meta_desc' => 'Explore production software, SaaS platforms, and mobile apps engineered by Creatsify.'
        ]);
    }

    public function show(Request $request, string $slug): Response
    {
        $project = Project::findBySlug($slug);
        if (!$project) {
            return Response::html(view('errors/404', ['message' => 'Project not found']), 404);
        }

        return view('portfolio/show', [
            'project' => $project,
            'meta_title' => "{$project['title']} | Creatsify Case Study",
            'meta_desc' => $project['summary']
        ]);
    }
}
