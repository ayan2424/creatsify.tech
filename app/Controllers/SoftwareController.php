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
        $products = array_values(array_filter(Project::all(), static fn ($p) => ($p['status'] ?? '') === 'For sale'));

        return view('software/index', [
            'products' => $products,
            'meta_title' => 'White-label Software for Sale | Creatsify',
            'meta_desc' => 'Ready-made courier and online store software with full source code, white-label rights and help with setup. Try the live demos first.',
        ]);
    }
}
