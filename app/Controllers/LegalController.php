<?php

namespace App\Controllers;

use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Privacy notice
 */
class LegalController
{
    public function privacy(Request $request): Response
    {
        return view('legal/privacy', [
            'meta_title' => 'Privacy Notice | Creatsify',
            'meta_desc' => 'What information Creatsify collects when you use this website, why, and the choices you have.',
        ]);
    }
}
