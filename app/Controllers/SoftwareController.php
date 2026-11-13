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
            'meta_desc' => 'Production-ready software with full source code, white-label rights and optional customization. Licensed via Gumroad — explore live demos.',
        ]);
    }
}
