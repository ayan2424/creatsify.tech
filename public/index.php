<?php

/**
 * Creatsify Studio - Production Front Controller
 * Zero-framework PHP 8.2+ MVC Architecture
 */

declare(strict_types=1);

// Define start time for performance profiling
define('CREATSIFY_START', microtime(true));

// Base path of project root
$basePath = dirname(__DIR__);
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $basePath);
}

// Check if vendor/autoload.php exists (Composer), else core fallback autoloader will be used
if (file_exists($basePath . '/vendor/autoload.php')) {
    require_once $basePath . '/vendor/autoload.php';
}

// Require Core App Kernel
require_once $basePath . '/core/App.php';

// Bootstrap & Execute Application
$app = new \Core\App($basePath);
$app->run();
