<?php

declare(strict_types=1);

/**
 * Root Front-Controller Proxy
 * Ensures Creatsify works seamlessly whether the web server document root
 * points to the repository root or to /public.
 */
require __DIR__ . '/public/index.php';
