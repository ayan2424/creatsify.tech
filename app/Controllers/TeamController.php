<?php

namespace App\Controllers;

use App\Models\MemberPortfolio;
use App\Models\TeamMember;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Public Agency Team & Creator Portfolios Controller
 */
class TeamController
{
    public function index(Request $request): Response
    {
        $members = TeamMember::allActive();

        return view('team/index', [
            'members' => $members,
            'meta_title' => 'Agency Creators & Engineering Team | Creatsify Studio',
            'meta_desc' => 'Meet the engineers, architects, and designers behind Creatsify Studio. Explore individual team portfolios, skills, and shipped systems.'
        ]);
    }

    public function show(Request $request, string $slug): Response
    {
        $member = TeamMember::findBySlug($slug);
        if (!$member || !$member['is_active']) {
            flash('error', 'Team member profile not found.');
            return Response::redirect('/team');
        }

        $portfolios = MemberPortfolio::forMember((int)$member['id']);

        return view('team/show', [
            'member' => $member,
            'portfolios' => $portfolios,
            'meta_title' => "{$member['name']} — {$member['role_title']} | Creatsify Portfolio",
            'meta_desc' => "Explore {$member['name']}'s personal software portfolio, shipped architecture, tech stack, and case studies at Creatsify Studio."
        ]);
    }
}
