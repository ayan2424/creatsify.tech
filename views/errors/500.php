<?php
// Standalone production 500 error page
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error | Creatsify Studio</title>
    <link rel="icon" href="<?= function_exists('url') ? url('/favicon.ico') : '/favicon.ico' ?>" sizes="32x32">
    <link rel="stylesheet" href="<?= function_exists('asset') ? asset('css/style.css') : '/assets/css/style.css' ?>">
    <style>
        body {
            background: #0A1A33;
            color: #F8FAFC;
            font-family: Sora, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .om-500-bg-glow {
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 360px;
            background: radial-gradient(circle, rgba(239,68,68,0.12) 0%, rgba(10,26,51,0) 70%);
            pointer-events: none;
            z-index: 0;
        }
        .om-500-card {
            position: relative;
            z-index: 1;
            max-width: 580px;
            padding: 48px 36px;
            background: rgba(14, 30, 56, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(16px);
        }
        .om-sec-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 9999px;
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 20px;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .om-sec-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ef4444;
        }
        h1 {
            font-size: 32px;
            font-weight: 800;
            margin: 0 0 14px;
            color: #ffffff;
            letter-spacing: -0.5px;
        }
        .om-dot-gold {
            color: #00D2EE;
        }
        p {
            color: #94A3B8;
            font-size: 15px;
            line-height: 1.65;
            margin: 0 0 32px;
        }
        .om-actions-row {
            display: flex;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
        }
        .om-btn-gold {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            border-radius: 9999px;
            background: #00D2EE;
            color: #0A1A33;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 8px 24px rgba(212, 160, 23, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .om-btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(212, 160, 23, 0.45);
        }
        .om-btn-gold svg {
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <div class="om-500-bg-glow"></div>
    <div class="om-500-card">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> 500 Server Error</div>
        <h1>Server Encountered An Issue</h1>
        <p>An unexpected condition was encountered while processing your request. Our technical operations engineering desk has been automatically alerted.</p>
        <div class="om-actions-row">
            <a href="<?= function_exists('url') ? url('/') : '/' ?>" class="om-btn-gold">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span>Return to Homepage</span>
            </a>
        </div>
    </div>
</body>
</html>
