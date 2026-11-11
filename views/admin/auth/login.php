<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($meta_title ?? 'Sign In | Creatsify Admin Command Desk') ?></title>
    <link rel="icon" href="<?= url('/favicon.ico') ?>" sizes="32x32">
    <link rel="icon" href="<?= url('/favicon.svg') ?>" type="image/svg+xml">
    <meta name="theme-color" content="#081426">
    <style>
        @font-face{font-family:'Sora';font-weight:400;font-display:swap;src:url('<?= asset('fonts/sora-400.woff2') ?>') format('woff2')}
        @font-face{font-family:'Sora';font-weight:500;font-display:swap;src:url('<?= asset('fonts/sora-500.woff2') ?>') format('woff2')}
        @font-face{font-family:'Sora';font-weight:600 700;font-display:swap;src:url('<?= asset('fonts/sora-700.woff2') ?>') format('woff2')}
    </style>

    <style>
        :root {
            --bg: #081426;
            --surface: #0C1B33;
            --surface-hover: #11233F;
            --line: #1B3253;
            --line-strong: #2A4670;
            --text: #EEF3FA;
            --muted: #8FA3BF;
            --accent: #60A5FA;
            --accent-glow: rgba(96,165,250,0.25);
            --success: #3ecf8e;
            --danger: #ef4444;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: Sora, -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Background Mesh */
        .login-mesh-bg {
            position: fixed;
            inset: 0;
            pointer-events: none;
            background: 
                radial-gradient(ellipse 600px 500px at 20% 30%, color-mix(in srgb,var(--accent) 12%,transparent), transparent 70%),
                radial-gradient(ellipse 500px 450px at 80% 70%, rgba(212,160,23,0.08), transparent 70%),
                radial-gradient(circle 800px at 50% 50%, rgba(15, 16, 23, 0.95), #06070b);
            z-index: 0;
        }

        .login-grid-pattern {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 36px 36px;
            pointer-events: none;
            z-index: 1;
        }

        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 960px;
            background: color-mix(in srgb, var(--surface) 85%, transparent);
            border: 1px solid var(--line);
            border-radius: 20px;
            box-shadow: 0 30px 80px -20px rgba(0, 0, 0, 0.8), 0 0 40px -10px var(--accent-glow);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
        }

        @media (max-width: 820px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 480px;
            }
            .login-hero-panel {
                display: none;
            }
        }

        /* Left Hero / Brand Panel */
        .login-hero-panel {
            padding: 48px;
            background: linear-gradient(145deg, color-mix(in srgb,var(--accent) 8%,transparent) 0%, rgba(18, 19, 27, 0.9) 100%);
            border-right: 1px solid var(--line);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .hero-panel-top .brand-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 36px;
        }

        .brand-logo-img {
            border-radius: 10px;
            border: 1px solid var(--line);
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }

        .brand-text {
            font-family: Sora, sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.02em;
        }

        .brand-text span {
            color: var(--accent);
        }

        .hero-panel-headline {
            font-family: Sora, sans-serif;
            font-size: 32px;
            font-weight: 800;
            line-height: 1.15;
            color: var(--text);
            letter-spacing: -0.03em;
            margin-bottom: 14px;
        }

        .hero-panel-desc {
            font-size: 14.5px;
            color: var(--muted);
            line-height: 1.65;
            margin-bottom: 32px;
        }

        .telemetry-pills-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .telemetry-pill {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--line);
            font-size: 13px;
            color: var(--muted);
        }

        .telemetry-pill-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 6px;
            background: color-mix(in srgb,var(--accent) 15%,transparent);
            color: var(--accent);
            flex-shrink: 0;
        }

        .telemetry-pill b {
            color: var(--text);
            font-weight: 600;
        }

        .hero-panel-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: var(--muted);
        }

        .status-indicator-live {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--success);
            font-weight: 600;
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--success);
            box-shadow: 0 0 8px var(--success);
        }

        /* Right Form Panel */
        .login-form-panel {
            padding: 48px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .form-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s, transform 0.2s;
        }

        .back-link:hover {
            color: var(--text);
            transform: translateX(-3px);
        }

        .login-form-title {
            font-family: Sora, sans-serif;
            font-size: 26px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .login-form-subtitle {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.5;
            margin-bottom: 26px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
        }

        .input-icon-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 14px;
            color: var(--muted);
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px 12px 42px;
            border-radius: 10px;
            background: #08090e;
            border: 1px solid var(--line);
            color: var(--text);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px color-mix(in srgb,var(--accent) 25%,transparent);
            background: #0b0c13;
        }

        .form-control::placeholder {
            color: rgba(142, 147, 170, 0.45);
        }

        .password-toggle-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--muted);
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: color 0.2s, background 0.2s;
        }

        .password-toggle-btn:hover {
            color: var(--text);
            background: rgba(255, 255, 255, 0.08);
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 13px 20px;
            background: var(--accent);
            color: #040508;
            font-size: 14.5px;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.2s, background 0.2s;
            margin-top: 6px;
        }

        .btn-submit:hover {
            background: #9d8fff;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px -6px color-mix(in srgb,var(--accent) 60%,transparent);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .demo-box {
            margin-top: 26px;
            padding-top: 22px;
            border-top: 1px dashed var(--line);
            text-align: center;
        }

        .demo-box-hint {
            font-size: 12.5px;
            color: var(--muted);
            margin-bottom: 12px;
        }

        .btn-demo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 11px 18px;
            border-radius: 10px;
            background: color-mix(in srgb,var(--accent) 8%,transparent);
            border: 1px solid color-mix(in srgb,var(--accent) 25%,transparent);
            color: var(--text);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-demo:hover {
            background: color-mix(in srgb,var(--accent) 18%,transparent);
            border-color: var(--accent);
            transform: translateY(-1px);
        }

        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 22px;
        }

        .alert-danger {
            background: rgba(212,160,23,0.12);
            color: #E2B42C;
            border: 1px solid rgba(212,160,23,0.3);
        }

        .alert-success {
            background: rgba(62, 207, 142, 0.12);
            color: #3ecf8e;
            border: 1px solid rgba(62, 207, 142, 0.3);
        }
    </style>
</head>
<body>
    <div class="login-mesh-bg"></div>
    <div class="login-grid-pattern"></div>

    <div class="login-container">
        <!-- Left Hero / Brand Panel -->
        <div class="login-hero-panel">
            <div class="hero-panel-top">
                <div class="brand-row">
                    <img class="brand-logo-img" src="<?= asset_v('brand/creatsify-icon.svg') ?>" alt="Creatsify" width="42" height="42">
                    <div class="brand-text">Creatsify<span>.tech</span></div>
                </div>

                <h2 class="hero-panel-headline">Engineered for Direct Execution Speed.</h2>
                <p class="hero-panel-desc">Secure administrative gateway for Creatsify Digital Studio, multi-tenant databases, and omnichannel customer telemetry.</p>

                <div class="telemetry-pills-list">
                    <div class="telemetry-pill">
                        <div class="telemetry-pill-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        </div>
                        <div><b>Zero-Framework Native MVC</b> · PHP 8.3 Kernel</div>
                    </div>
                    <div class="telemetry-pill">
                        <div class="telemetry-pill-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                        </div>
                        <div><b>MySQL 8.0 Architecture</b> · 78 Tables Normalized</div>
                    </div>
                    <div class="telemetry-pill">
                        <div class="telemetry-pill-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                        <div><b>CSRF & Session Hardened</b> · 256-Bit SSL Auth</div>
                    </div>
                </div>
            </div>

            <div class="hero-panel-footer">
                <div class="status-indicator-live">
                    <span class="live-dot"></span>
                    <span>Production Command Node</span>
                </div>
                <div>Global Node (UTC 24/7)</div>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="login-form-panel">
            <div>
                <div class="form-panel-header">
                    <a href="<?= url('/') ?>" class="back-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        <span>Back to Storefront</span>
                    </a>
                    <span style="font-size: 11.5px; color: var(--muted); font-family: monospace;">v2.5.0</span>
                </div>

                <h1 class="login-form-title">Command Desk Login</h1>
                <p class="login-form-subtitle">Authenticate to access project management, database metrics, and client communications.</p>

                <?php if ($err = flash('error')): ?>
                    <div class="alert alert-danger">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span><?= e($err) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($msg = flash('success')): ?>
                    <div class="alert alert-success">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span><?= e($msg) ?></span>
                    </div>
                <?php endif; ?>

                <form action="<?= url('/admin/login') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="email">Administrator Email or Username</label>
                        <div class="input-icon-wrapper">
                            <span class="input-icon-left">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            </span>
                            <input type="text" id="email" name="email" class="form-control" placeholder="admin@creatsify.tech or admin" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-icon-wrapper">
                            <span class="input-icon-left">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            </span>
                            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••••••" style="padding-right: 42px;" required>
                            <button type="button" class="password-toggle-btn" id="passwordToggleBtn" aria-label="Toggle password visibility" title="Show/Hide password">
                                <svg class="eye-open" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg class="eye-closed" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                    </div>

                    <div class="credentials-hint-box" style="margin-bottom: 20px; padding: 10px 14px; border-radius: 10px; background: rgba(37,99,235,0.08); border: 1px solid rgba(37,99,235,0.22); font-size: 12px; color: #93C5FD; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                        <span>Default: <strong style="color:#FFFFFF;">admin@creatsify.tech</strong> / <strong style="color:#FFFFFF;">admin123</strong></span>
                        <button type="button" id="autoFillAdminBtn" style="background: rgba(37,99,235,0.25); border: 1px solid rgba(96,165,250,0.4); color: #FFFFFF; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 6px; cursor: pointer; transition: all 0.2s;">Auto-fill</button>
                    </div>

                    <button type="submit" class="btn-submit">
                        <span>Authenticate to Command Desk</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </form>
            </div>

            <?php 
            $canShowDemo = env('DEMO_MODE', false) === true 
                || env('APP_ENV', '') === 'development' 
                || env('APP_ENV', '') === 'local' 
                || env('APP_DEBUG', false) === true;
            if ($canShowDemo): 
            ?>
                <div class="demo-box">
                    <p class="demo-box-hint">1-Click Demo Evaluation Workspace is ready.</p>
                    <a href="<?= url('/admin/demo-login') ?>" class="btn-demo">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                        <span>Open Demo Workspace</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const pwdInput = document.getElementById('password');
        const pwdToggle = document.getElementById('passwordToggleBtn');
        if (pwdInput && pwdToggle) {
            pwdToggle.addEventListener('click', function() {
                const isPassword = pwdInput.type === 'password';
                pwdInput.type = isPassword ? 'text' : 'password';
                const eyeOpen = pwdToggle.querySelector('.eye-open');
                const eyeClosed = pwdToggle.querySelector('.eye-closed');
                if (eyeOpen && eyeClosed) {
                    eyeOpen.style.display = isPassword ? 'none' : 'block';
                    eyeClosed.style.display = isPassword ? 'block' : 'none';
                }
            });
        }
        const autoFillBtn = document.getElementById('autoFillAdminBtn');
        if (autoFillBtn) {
            autoFillBtn.addEventListener('click', function() {
                const emailEl = document.getElementById('email');
                const pwdEl = document.getElementById('password');
                if (emailEl) emailEl.value = 'admin@creatsify.tech';
                if (pwdEl) pwdEl.value = 'admin123';
            });
        }
    </script>
</body>
</html>
