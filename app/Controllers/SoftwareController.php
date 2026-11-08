<?php

namespace App\Controllers;

use App\Models\Project;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - White-label software that is sold directly
 */
class SoftwareController
{
    public function index(Request $request): Response
    {
        $products = Project::readyMadeProducts();

        return view('software/index', [
            'products' => $products,
            'meta_title' => 'Ready-Made Software & Codebase Licenses | Creatsify',
            'meta_desc' => 'Production-ready software platforms sold directly via Gumroad with 100% unlocked source code, white-label rights, and custom tailoring support. Explore live demos.',
        ]);
    }
}
