<?php

namespace App\Controllers;

use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - About Studio & Leadership Controller
 */
class AboutController
{
    public function index(Request $request): Response
    {
        return view('about/index', [
            'meta_title' => 'About Creatsify & Muhammad Ayan Shamim',
            'meta_desc' => 'Discover how Creatsify designs, ships, and scales digital software from Karachi to global marketplaces.'
        ]);
    }
}
