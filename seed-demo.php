<?php

/**
 * Creatsify Studio - One-Step CLI & Local Demo Seeder
 * Initializes SQLite database, creates storage directories, migrates schema and seeds demo data.
 */

declare(strict_types=1);

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    die("Direct web access denied. Run via command line: php seed-demo.php\n");
}

$baseDir = file_exists(__DIR__ . '/core/App.php') ? __DIR__ : dirname(__DIR__);

echo "========================================\n";
echo "Creatsify Studio - Local Demo Seeder\n";
echo "========================================\n\n";

// Ensure storage directories exist
$dirs = [
    $baseDir . '/storage',
    $baseDir . '/storage/logs',
    $baseDir . '/storage/cache',
    $baseDir . '/public/assets/uploads'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "Created: {$dir}\n";
    }
}

// Check database configuration
$dbFile = $baseDir . '/storage/database.sqlite';
$driver = 'sqlite';

try {
    $pdo = new PDO("sqlite:" . $dbFile, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connected to SQLite database: {$dbFile}\n";

    // Migrate Schema
    $schemaSql = file_get_contents($baseDir . '/database/schema.sql');
    // For SQLite, convert AUTO_INCREMENT to AUTOINCREMENT or strip MySQL-specific engine syntax
    $sqliteSchema = preg_replace('/(INT|BIGINT)\s+AUTO_INCREMENT\s+PRIMARY\s+KEY/i', 'INTEGER PRIMARY KEY AUTOINCREMENT', $schemaSql);
    $sqliteSchema = preg_replace('/ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci/i', '', $sqliteSchema);
    $sqliteSchema = preg_replace('/ON UPDATE CURRENT_TIMESTAMP/i', '', $sqliteSchema);
    $sqliteSchema = preg_replace('/ENUM\([^)]+\)/i', 'VARCHAR(50)', $sqliteSchema);
    $sqliteSchema = preg_replace('/UNIQUE\s+KEY\s+`?[^`\s]+`?\s*\(([^)]+)\)/i', 'UNIQUE ($1)', $sqliteSchema);
    $sqliteSchema = preg_replace('/INDEX `[^`]+` \([^)]+\),?/i', '', $sqliteSchema);
    // Remove trailing commas before closing parenthesis
    $sqliteSchema = preg_replace('/,\s*\)/', ')', $sqliteSchema);

    $pdo->exec($sqliteSchema);
    echo "Database schema migrated successfully.\n";

    // Run Seeds
    $seedsSql = file_get_contents($baseDir . '/database/seeds.sql');
    $sqliteSeeds = preg_replace('/ON DUPLICATE KEY UPDATE `[^`]+`=VALUES\(`[^`]+`\);/i', ';', $seedsSql);
    $sqliteSeeds = str_replace("\\'", "''", $sqliteSeeds);

    // Split and execute statements
    $queries = explode(";\n", $sqliteSeeds);
    foreach ($queries as $q) {
        $trimmed = trim($q);
        if (!empty($trimmed)) {
            try {
                $pdo->exec($trimmed);
            } catch (Throwable $ignore) {
                // Ignore duplicate keys on reseeding
            }
        }
    }
    echo "Seed data loaded successfully.\n";

    // Generate .env if not exists
    $envPath = $baseDir . '/.env';
    if (!file_exists($envPath)) {
        $env = <<<ENV
APP_NAME="Creatsify Studio"
APP_ENV=development
APP_DEBUG=true
APP_URL="http://localhost:8000"
APP_TIMEZONE="Asia/Karachi"

DB_CONNECTION=sqlite
DB_DATABASE="{$dbFile}"
ENV;
        file_put_contents($envPath, $env);
        echo "Generated .env file with SQLite driver.\n";
    }

    // Place installed.lock
    file_put_contents($baseDir . '/storage/installed.lock', date('Y-m-d H:i:s'));
    echo "Created storage/installed.lock file.\n\n";

    echo "Demo ready!\n";
    echo "Super Admin Credentials:\n";
    echo "Email:    ayan@creatsify.tech\n";
    echo "Password: admin123\n";
    echo "Start server with: php -S localhost:8000 -t public\n";
    echo "========================================\n";

} catch (Throwable $e) {
    echo "Seeding failed: " . $e->getMessage() . "\n";
    exit(1);
}
