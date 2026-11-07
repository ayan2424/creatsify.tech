<?php

/**
 * Creatsify Studio - Production Front Controller
 * Zero-framework PHP 8.2+ MVC Architecture
 */

declare(strict_types=1);

// 1. Built-in PHP Dev Server Static File Passthrough
if (PHP_SAPI === 'cli-server') {
    $reqPath = rawurldecode((string)(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'));
    $staticCandidate = __DIR__ . $reqPath;
    if ($reqPath !== '/' && is_file($staticCandidate)) {
        return false;
    }
}

// 2. Fallback Static Asset Server (in case Nginx/Apache routes /assets/* to index.php)
$reqUriPath = rawurldecode((string)(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'));
if ($reqUriPath !== '/' && !str_ends_with($reqUriPath, '.php')) {
    $publicFile = __DIR__ . '/' . ltrim($reqUriPath, '/');
    if (is_file($publicFile)) {
        $ext = strtolower(pathinfo($publicFile, PATHINFO_EXTENSION));
        $mimeMap = [
            'css'   => 'text/css; charset=UTF-8',
            'js'    => 'application/javascript; charset=UTF-8',
            'svg'   => 'image/svg+xml',
            'png'   => 'image/png',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'webp'  => 'image/webp',
            'ico'   => 'image/x-icon',
            'woff2' => 'font/woff2',
            'woff'  => 'font/woff',
            'json'  => 'application/json',
            'webmanifest' => 'application/manifest+json',
            'txt'   => 'text/plain; charset=UTF-8',
            'xml'   => 'application/xml; charset=UTF-8',
        ];
        if (isset($mimeMap[$ext])) {
            header('Content-Type: ' . $mimeMap[$ext]);
            header('Cache-Control: public, max-age=86400');
            readfile($publicFile);
            exit;
        }
    }
}

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
