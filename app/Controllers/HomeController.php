<?php

namespace App\Controllers;

use App\Models\Project;
use App\Models\Section;
use App\Models\Service;
use App\Models\Post;
use App\Models\TeamMember;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Homepage Controller
 */
class HomeController
{
    public function index(Request $request): Response
    {
        // Fetch active sections
        $sectionsRaw = Section::allActive();
        $sections = [];
        foreach ($sectionsRaw as $sec) {
            $sections[$sec['section_key']] = $sec;
            if (!empty($sec['metadata']) && is_string($sec['metadata'])) {
                $sections[$sec['section_key']]['metadata'] = json_decode($sec['metadata'], true) ?: [];
            }
        }

        // Fetch portfolio projects
        $projects = Project::all();

        // Fetch services
        $services = Service::allActive();

        // Fetch recent posts
        $posts = Post::allPublished();

        // Fetch agency team members
        $teamMembers = TeamMember::allActive();

        return view('home/index', [
            'sections' => $sections,
            'projects' => $projects,
            'services' => $services,
            'posts' => $posts,
            'teamMembers' => $teamMembers,
            'meta_title' => setting('site_title', 'Creatsify — Full-stack web, mobile and AI development studio'),
            'meta_desc' => setting('meta_description', 'Creatsify builds courier platforms, SaaS, online stores and mobile apps.')
        ]);
    }
}
