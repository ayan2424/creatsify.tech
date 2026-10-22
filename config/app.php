<?php

/**
 * Creatsify Studio - Application Configuration
 */
return [
    'name' => env('APP_NAME', 'Creatsify Studio'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', true),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'Asia/Karachi'),
    'locale' => 'en',
    'version' => '1.0.0',
    'installed' => file_exists(dirname(__DIR__) . '/storage/installed.lock'),
];
