<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($meta_title ?? 'Sign In | Creatsify Admin') ?></title>
    <link rel="icon" type="image/png" href="<?= asset('brand/favicon.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600..700&family=DM+Sans:ital,opsz,wght@0,9..40,400..600;1,9..40,400..600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #07080d;
            --surface: #12131b;
            --line: #252736;
            --text: #eef0f7;
            --muted: #a3a7bb;
            --accent: #8b7bff;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.6);
        }

        .login-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
        }

        .login-title {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            background: #090a10;
            border: 1px solid var(--line);
            color: var(--text);
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(139,123,255,0.2);
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            border: none;
            text-align: center;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .demo-box {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px dashed var(--line);
            text-align: center;
        }

        .btn-demo {
            background: rgba(139,123,255,0.15);
            color: var(--accent);
            border: 1px solid rgba(139,123,255,0.3);
            margin-top: 10px;
        }

        .btn-demo:hover {
            background: rgba(139,123,255,0.25);
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: rgba(255,77,109,0.15);
            color: #ff4d6d;
            border: 1px solid rgba(255,77,109,0.3);
        }

        .alert-success {
            background: rgba(0,200,83,0.15);
            color: #00c853;
            border: 1px solid rgba(0,200,83,0.3);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-brand">
            <img src="<?= asset('brand/favicon.png') ?>" alt="Creatsify" width="32" height="32">
            <span style="font-family:'Bricolage Grotesque'; font-size: 20px; font-weight: 700;">Creatsify<span style="color:var(--accent);">.tech</span></span>
        </div>

        <h1 class="login-title">Admin Sign In</h1>
        <p class="login-subtitle">Authenticate to access the live command desk, communication inboxes, and CMS controls.</p>

        <?php if ($err = flash('error')): ?>
            <div class="alert alert-danger">&excl; <?= e($err) ?></div>
        <?php endif; ?>
        <?php if ($msg = flash('success')): ?>
            <div class="alert alert-success">&check; <?= e($msg) ?></div>
        <?php endif; ?>

        <form action="<?= url('/admin/login') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="email">Admin Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="ayan@creatsify.tech" value="ayan@creatsify.tech" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" value="admin123" required>
            </div>

            <button type="submit" class="btn btn-primary">Sign in to Desk &rarr;</button>
        </form>

        <div class="demo-box">
            <p style="font-size: 12px; color: var(--muted);">Testing or evaluating on a marketplace?</p>
            <a href="<?= url('/admin/demo-login') ?>" class="btn btn-demo">
                ⚡ 1-Click Super Admin Login
            </a>
        </div>
    </div>
</body>
</html>
