<?php
/** @var Core\View $this */
$accentColor = setting('primary_accent', '#8b7bff');
$siteTitle = $meta_title ?? setting('site_title', 'Creatsify — Full-stack web, mobile and AI development studio');
$siteDesc = $meta_desc ?? setting('meta_description', 'Creatsify is a high-performance software studio in Karachi building full-stack web, mobile, and AI solutions.');
$siteKeywords = setting('meta_keywords', 'Creatsify, Courier SaaS, Cheetah, PHP MVC, Laravel, Flutter, Karachi, Pakistan');
$siteIcon = setting('site_icon', '/assets/brand/favicon.png');
$customCss = setting('custom_css', '');
$headerCode = setting('custom_header_code', '');
$footerCode = setting('custom_footer_code', '');
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= e($siteTitle) ?></title>
    <meta name="description" content="<?= e($siteDesc) ?>">
    <meta name="keywords" content="<?= e($siteKeywords) ?>">
    <meta name="author" content="<?= e(setting('founder_name', 'Creatsify Studio')) ?>">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <link rel="canonical" href="<?= e(url(request()->getPath())) ?>">

    <!-- OpenGraph / Social -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($siteTitle) ?>">
    <meta property="og:description" content="<?= e($siteDesc) ?>">
    <meta property="og:url" content="<?= e(url(request()->getPath())) ?>">
    <meta property="og:image" content="<?= e(url('/assets/brand/creatsify-wordmark.png')) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($siteTitle) ?>">
    <meta name="twitter:description" content="<?= e($siteDesc) ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= asset($siteIcon) ?>">
    <link rel="apple-touch-icon" href="<?= asset($siteIcon) ?>">

    <!-- Fonts: Bricolage Grotesque & DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600..800&family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&display=swap" rel="stylesheet">

    <!-- Core Stylesheet -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=2.8">
    <link rel="stylesheet" href="<?= asset('css/chat.css') ?>?v=2.8">
    <link rel="stylesheet" href="<?= asset('css/cinematic.css') ?>?v=2.8">

    <!-- Dynamic Custom Colors & Injected Code -->
    <style>
        :root {
            --accent: <?= e($accentColor) ?>;
            --accent-glow: <?= e($accentColor) ?>33;
        }
        <?= $customCss ?>
    </style>

    <?= $headerCode ?>

    <!-- Theme Initialization Script to avoid FOUC and enable CSS animations -->
    <script>
        (function() {
            try {
                var t = localStorage.getItem('creatsify-theme') || localStorage.getItem('creatsify_theme') || (window.matchMedia('(prefers-color-scheme:light)').matches ? 'light' : 'dark');
                document.documentElement.dataset.theme = t;
                document.documentElement.setAttribute('data-theme', t);
                document.documentElement.classList.add('js');
            } catch(e) {}
        })();
    </script>
</head>
<body>
    <div class="bg" aria-hidden="true"></div>
    <canvas id="glCanvas" aria-hidden="true"></canvas>
    <div class="vg" aria-hidden="true"></div>
    <div class="prog" id="prog" aria-hidden="true"></div>
    <div class="pre" id="pre" aria-hidden="true">
        <div class="c">
            <img src="<?= asset('brand/creatsify-icon.png') ?>" alt="Creatsify" width="60" height="60" style="border-radius:14px;margin-bottom:12px">
            <b id="preN">00</b>
            <div class="bar2"><i></i></div>
        </div>
    </div>
    <div id="cr" aria-hidden="true"></div>
    <div id="cd" aria-hidden="true"></div>

    <!-- Header Navigation -->
    <?php $this->partial('partials/header'); ?>

    <!-- Main Content Injection -->
    <main id="top">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <?php $this->partial('partials/footer'); ?>

    <!-- Floating Native Live Chat Widget -->
    <?php $this->partial('partials/chat_widget'); ?>

    <!-- Notification Chime Audio -->
    <audio id="notifChime" preload="auto">
        <source src="data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU..." type="audio/wav">
    </audio>

    <!-- Core Client Runtime Config -->
    <script>
        window.CREATSIFY_BASE_URL = <?= json_encode(url('/')) ?>;
        window.CREATSIFY_API_URL = <?= json_encode(url('/api/contact')) ?>;
        window.CREATSIFY_CSRF_TOKEN = <?= json_encode(csrf_token()) ?>;
    </script>

    <!-- Scripts -->
    <script src="<?= asset('js/main.js') ?>?v=2.8" defer></script>
    <script src="<?= asset('js/chat.js') ?>?v=2.7" defer></script>
    <script src="<?= asset('js/cinematic.js') ?>?v=2.7" defer></script>

    <?= $footerCode ?>
</body>
</html>
