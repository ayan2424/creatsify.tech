<?php
// Standalone production 500 error page
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error</title>
    <link rel="stylesheet" href="<?= function_exists('asset') ? asset('css/style.css') : '/assets/css/style.css' ?>">
    <style>
        body { background: #07080e; color: #eef0f7; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; text-align: center; }
        .error-card { max-width: 580px; padding: 48px; background: #12131c; border: 1px solid #252736; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.5); }
        h1 { font-size: 36px; margin: 16px 0 10px; color: #ff4d6d; }
        p { color: #a3a7bb; font-size: 15px; line-height: 1.6; margin-bottom: 24px; }
        .btn { display: inline-block; padding: 12px 24px; background: #8b7bff; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; transition: opacity 0.2s; }
        .btn:hover { opacity: 0.9; }
    </style>
</head>
<body>
    <div class="error-card">
        <span style="display:inline-block; padding: 4px 12px; background: rgba(255,77,109,0.15); color: #ff4d6d; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 0.5px;">500 Internal Error</span>
        <h1>Server Encountered an Issue</h1>
        <p>An unexpected condition was encountered while processing your request. The technical team has been alerted.</p>
        <a href="<?= function_exists('url') ? url('/') : '/' ?>" class="btn">Return to Homepage &rarr;</a>
    </div>
</body>
</html>
