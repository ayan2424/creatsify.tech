<?php

/**
 * Creatsify Studio - 1-Click Graphical Web Installer
 * Designed for marketplace buyers (Codester / Coderobotics) and automated hosting setup.
 */

declare(strict_types=1);

$baseDir = file_exists(__DIR__ . '/core/App.php') ? __DIR__ : dirname(__DIR__);
$lockFile = $baseDir . '/storage/installed.lock';

// 1. Check if already installed
if (file_exists($lockFile)) {
    die('<!DOCTYPE html><html><head><title>Creatsify - Already Installed</title><style>body{background:#07080d;color:#eef0f7;font-family:sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;}div{background:#12131b;border:1px solid #252736;padding:40px;border-radius:12px;text-align:center;max-width:450px;}h2{color:#8b7bff;}a{color:#8b7bff;text-decoration:none;font-weight:bold;}</style></head><body><div><h2>Platform Installed</h2><p>Creatsify Studio is already configured. To re-run the installer, remove the file <code>storage/installed.lock</code>.</p><p><a href="/">Visit Website &rarr;</a> &bull; <a href="/admin">Admin Desk &rarr;</a></p></div></body></html>');
}

$step = (int)($_GET['step'] ?? 1);
$error = null;
$success = null;

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 2) {
        // Test Database Connection
        $driver = $_POST['db_driver'] ?? 'mysql';
        $host = $_POST['db_host'] ?? '127.0.0.1';
        $port = $_POST['db_port'] ?? '3306';
        $dbname = $_POST['db_name'] ?? 'creatsify_db';
        $user = $_POST['db_user'] ?? 'root';
        $pass = $_POST['db_pass'] ?? '';

        try {
            if ($driver === 'sqlite') {
                $pdo = new PDO("sqlite:" . $baseDir . "/storage/database.sqlite");
            } else {
                $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
                $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                // Create database if not exists
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `{$dbname}`");
            }

            // Save temp DB session
            session_start();
            $_SESSION['install_db'] = [
                'driver' => $driver,
                'host' => $host,
                'port' => $port,
                'database' => $dbname,
                'username' => $user,
                'password' => $pass
            ];

            header("Location: install.php?step=3");
            exit;
        } catch (Throwable $e) {
            $error = "Database Connection Failed: " . $e->getMessage();
        }
    } elseif ($step === 3) {
        // Finalize Setup: Run Schema, Seeds, and Generate Admin
        session_start();
        $dbConfig = $_SESSION['install_db'] ?? null;
        if (!$dbConfig) {
            header("Location: install.php?step=2");
            exit;
        }

        $adminName = trim($_POST['admin_name'] ?? 'Muhammad Ayan Shamim');
        $adminEmail = trim($_POST['admin_email'] ?? 'ayan@creatsify.tech');
        $adminPass = (string)($_POST['admin_password'] ?? 'admin123');
        $siteTitle = trim($_POST['site_title'] ?? 'Creatsify — Full-stack web, mobile and AI studio');
        $siteUrl = rtrim($_POST['site_url'] ?? 'http://' . $_SERVER['HTTP_HOST'], '/');

        if (empty($adminEmail) || empty($adminPass)) {
            $error = "Admin email and password are required.";
        } else {
            try {
                // Connect to DB
                $driver = $dbConfig['driver'];
                if ($driver === 'sqlite') {
                    $pdo = new PDO("sqlite:" . $baseDir . "/storage/database.sqlite");
                } else {
                    $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset=utf8mb4";
                    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                }

                // 1. Run Schema
                $schemaSql = file_get_contents($baseDir . '/database/schema.sql');
                $pdo->exec($schemaSql);

                // 2. Run Seeds
                $seedsSql = file_get_contents($baseDir . '/database/seeds.sql');
                $pdo->exec($seedsSql);

                // 3. Update Super Admin & Title
                $hashedPass = password_hash($adminPass, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE `users` SET `name` = :n, `email` = :e, `password` = :p WHERE `role` = 'super_admin' LIMIT 1");
                $stmt->execute(['n' => $adminName, 'e' => $adminEmail, 'p' => $hashedPass]);

                $stmt = $pdo->prepare("UPDATE `settings` SET `value` = :v WHERE `key` = 'site_title'");
                $stmt->execute(['v' => $siteTitle]);

                $stmt = $pdo->prepare("UPDATE `settings` SET `value` = :v WHERE `key` = 'site_email'");
                $stmt->execute(['v' => $adminEmail]);

                // 4. Generate .env file
                $envContent = <<<ENV
APP_NAME="{$siteTitle}"
APP_ENV=production
APP_DEBUG=false
APP_URL="{$siteUrl}"
APP_TIMEZONE="Asia/Karachi"

DB_CONNECTION={$dbConfig['driver']}
DB_HOST={$dbConfig['host']}
DB_PORT={$dbConfig['port']}
DB_DATABASE={$dbConfig['database']}
DB_USERNAME={$dbConfig['username']}
DB_PASSWORD="{$dbConfig['password']}"
ENV;
                file_put_contents($baseDir . '/.env', $envContent);

                // 5. Create Installer Lock
                if (!is_dir($baseDir . '/storage')) {
                    mkdir($baseDir . '/storage', 0755, true);
                }
                file_put_contents($lockFile, date('Y-m-d H:i:s'));

                header("Location: install.php?step=4");
                exit;
            } catch (Throwable $e) {
                $error = "Setup Error: " . $e->getMessage();
            }
        }
    }
}

// Environmental Pre-Flight Checks
$reqs = [
    'PHP 8.2+' => version_compare(PHP_VERSION, '8.2.0', '>='),
    'PDO Extension' => extension_loaded('pdo'),
    'PDO MySQL / SQLite' => extension_loaded('pdo_mysql') || extension_loaded('pdo_sqlite'),
    'OpenSSL Extension' => extension_loaded('openssl'),
    'cURL Extension' => extension_loaded('curl'),
    'mbstring Extension' => extension_loaded('mbstring'),
    'JSON Extension' => extension_loaded('json'),
    'storage/ Writable' => is_writable($baseDir . '/storage') || is_writable($baseDir)
];
$allPassed = !in_array(false, $reqs, true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creatsify Studio - 1-Click Web Installer</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #07080d; --surface: #12131b; --line: #252736; --text: #eef0f7; --muted: #a3a7bb; --accent: #8b7bff; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .wizard-card { width: 100%; max-width: 580px; background: var(--surface); border: 1px solid var(--line); border-radius: 16px; padding: 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.6); }
        h1 { font-family: 'Bricolage Grotesque', sans-serif; font-size: 24px; margin-bottom: 6px; }
        .steps { display: flex; gap: 8px; margin: 24px 0; }
        .step-pill { flex: 1; height: 4px; background: var(--line); border-radius: 2px; }
        .step-pill.active { background: var(--accent); }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .form-control, input, select { width: 100%; padding: 10px 14px; border-radius: 8px; background: #090a10; border: 1px solid var(--line); color: var(--text); font-size: 14px; outline: none; }
        .form-control:focus, input:focus, select:focus { border-color: var(--accent); }
        .btn { display: inline-block; width: 100%; padding: 12px; border-radius: 8px; background: var(--accent); color: #fff; font-weight: 600; text-align: center; border: none; cursor: pointer; text-decoration: none; font-size: 14px; }
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 13.5px; margin-bottom: 20px; }
        .alert-danger { background: rgba(255,77,109,0.15); color: #ff4d6d; border: 1px solid rgba(255,77,109,0.3); }
        .req-list { list-style: none; margin: 20px 0; border: 1px solid var(--line); border-radius: 10px; overflow: hidden; }
        .req-item { display: flex; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid var(--line); font-size: 13.5px; }
        .req-item:last-child { border-bottom: none; }
        .passed { color: #00c853; font-weight: 600; }
        .failed { color: #ff4d6d; font-weight: 600; }
    </style>
</head>
<body>
    <div class="wizard-card">
        <h1>Creatsify Studio Installer</h1>
        <p style="color:var(--muted); font-size:14px;">Deploy your production PHP MVC agency & software studio in seconds.</p>

        <div class="steps">
            <div class="step-pill <?= $step >= 1 ? 'active' : '' ?>"></div>
            <div class="step-pill <?= $step >= 2 ? 'active' : '' ?>"></div>
            <div class="step-pill <?= $step >= 3 ? 'active' : '' ?>"></div>
            <div class="step-pill <?= $step >= 4 ? 'active' : '' ?>"></div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger">&excl; <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- STEP 1: Pre-Flight Requirements Check -->
        <?php if ($step === 1): ?>
            <h3 style="font-size:16px; margin-top:20px;">Server Pre-Flight Checks</h3>
            <ul class="req-list">
                <?php foreach ($reqs as $name => $passed): ?>
                    <li class="req-item">
                        <span><?= $name ?></span>
                        <span class="<?= $passed ? 'passed' : 'failed' ?>"><?= $passed ? '&check; Supported' : '&times; Missing' ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php if ($allPassed): ?>
                <a href="install.php?step=2" class="btn">Requirements Verified: Continue to Database &rarr;</a>
            <?php else: ?>
                <div class="alert alert-danger">Please enable missing PHP extensions or directory permissions to proceed.</div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- STEP 2: Database Configuration -->
        <?php if ($step === 2): ?>
            <h3 style="font-size:16px; margin-bottom:15px;">Database Connection</h3>
            <form action="install.php?step=2" method="POST">
                <div class="form-group">
                    <label>Database Driver</label>
                    <select name="db_driver" class="form-control">
                        <option value="mysql">MySQL / MariaDB</option>
                        <option value="sqlite">SQLite 3 (Zero Config)</option>
                    </select>
                </div>
                <div style="display:grid; grid-template-columns: 2fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label>Host</label>
                        <input type="text" name="db_host" value="127.0.0.1" required>
                    </div>
                    <div class="form-group">
                        <label>Port</label>
                        <input type="text" name="db_port" value="3306" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Database Name</label>
                    <input type="text" name="db_name" value="creatsify_db" required>
                </div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="db_user" value="root" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="db_pass" value="">
                    </div>
                </div>
                <button type="submit" class="btn">Test Connection & Proceed &rarr;</button>
            </form>
        <?php endif; ?>

        <!-- STEP 3: Super Admin & Studio Setup -->
        <?php if ($step === 3): ?>
            <h3 style="font-size:16px; margin-bottom:15px;">Super Administrator & Branding</h3>
            <form action="install.php?step=3" method="POST">
                <div class="form-group">
                    <label>Studio / Website Title</label>
                    <input type="text" name="site_title" value="Creatsify — Full-stack web, mobile and AI development studio" required>
                </div>
                <div class="form-group">
                    <label>Website URL</label>
                    <input type="text" name="site_url" value="http://<?= $_SERVER['HTTP_HOST'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Super Admin Full Name</label>
                    <input type="text" name="admin_name" value="Muhammad Ayan Shamim" required>
                </div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label>Admin Email</label>
                        <input type="email" name="admin_email" value="ayan@creatsify.tech" required>
                    </div>
                    <div class="form-group">
                        <label>Admin Password</label>
                        <input type="password" name="admin_password" value="admin123" required>
                    </div>
                </div>
                <button type="submit" class="btn">Run Migration & Finish Setup &rarr;</button>
            </form>
        <?php endif; ?>

        <!-- STEP 4: Success & Launch -->
        <?php if ($step === 4): ?>
            <div style="text-align:center; padding: 20px 0;">
                <div style="font-size:48px; color:#00c853; margin-bottom:10px;">&check;</div>
                <h2>Installation Complete!</h2>
                <p style="color:var(--muted); font-size:14px; margin: 12px 0 24px;">
                    Your database schema has migrated, default seed data is loaded, and your security lock file is in place.
                </p>

                <div style="display:flex; flex-direction:column; gap:10px;">
                    <a href="/admin/login" class="btn">Log in to Admin Desk &rarr;</a>
                    <a href="/" class="btn" style="background:var(--surface); border:1px solid var(--line);">View Live Website &rarr;</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
