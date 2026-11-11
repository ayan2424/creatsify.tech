<?php
/** @var Core\View $this */
$accentColor = trim((string) setting('primary_accent', ''));
// The brand colours come from the stylesheet. Only a custom colour chosen in the admin settings overrides them
// (the old purple default and the brand blues are not treated as custom).
$accentOverride = $accentColor !== '' && !in_array(strtolower($accentColor), ['#8b7bff', '#2563eb', '#60a5fa', 'var(--accent)'], true);
$siteTitle = $meta_title ?? setting('site_title', 'Creatsify — Web & App Development, Branding, SEO & Digital Growth Agency');
$siteDesc = $meta_desc ?? setting('meta_description', 'Creatsify is a full-service software engineering, branding, and performance marketing agency building custom web apps, Flutter mobile apps, website redesigns, SaaS, SEO, and paid social ad campaigns.');
$siteKeywords = setting('meta_keywords', 'Creatsify, Web Development, Flutter App Development, Website Redesign, SEO, Social Media Ads, Branding, Logo Design, SaaS');
$siteIcon = setting('site_icon', '/assets/brand/favicon.png');
$customCss = setting('custom_css', '');
$headerCode = setting('custom_header_code', '');
$footerCode = setting('custom_footer_code', '');
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= e($siteTitle) ?></title>
    <meta name="description" content="<?= e($siteDesc) ?>">
    <meta name="keywords" content="<?= e($siteKeywords) ?>">
    <meta name="author" content="Creatsify Digital Agency">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <link rel="canonical" href="<?= e(url(request()->getPath())) ?>">

    <!-- OpenGraph / Social -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($siteTitle) ?>">
    <meta property="og:description" content="<?= e($siteDesc) ?>">
    <meta property="og:url" content="<?= e(url(request()->getPath())) ?>">
    <meta property="og:image" content="<?= e(url('/assets/brand/og-image.png')) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:image" content="<?= e(url('/assets/brand/og-image.png')) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($siteTitle) ?>">
    <meta name="twitter:description" content="<?= e($siteDesc) ?>">

    <!-- Brand icons -->
    <link rel="icon" href="<?= url('/favicon.ico') ?>" sizes="32x32">
    <link rel="icon" href="<?= url('/favicon.svg') ?>" type="image/svg+xml">
    <link rel="apple-touch-icon" href="<?= url('/apple-touch-icon.png') ?>">
    <link rel="manifest" href="<?= url('/site.webmanifest') ?>">
    <meta name="theme-color" content="#F6F9FE" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#07080A" media="(prefers-color-scheme: dark)">

    <!-- Google Fonts: Plus Jakarta Sans & Sora for crisp cross-platform typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Brand font: Sora (local fallback) -->
    <link rel="preload" href="<?= asset('fonts/sora-400.woff2') ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= asset('fonts/sora-700.woff2') ?>" as="font" type="font/woff2" crossorigin>

    <!-- Core Stylesheet -->
    <link rel="stylesheet" href="<?= asset_v('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset_v('css/chat.css') ?>">

    <!-- Dynamic Custom Colors & Injected Code -->
    <style>
        <?php if ($accentOverride): ?>
        :root, :root[data-theme=light] {
            --accent: <?= e($accentColor) ?>;
            --accent-glow: <?= e($accentColor) ?>33;
        }
        <?php endif; ?>
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
    <div class="vg" aria-hidden="true"></div>
    <div class="prog" id="prog" aria-hidden="true"></div>
    <div class="pre" id="pre" aria-hidden="true">
        <div class="c">
            <img src="<?= asset_v('brand/creatsify-icon.svg') ?>" alt="Creatsify" width="60" height="60" style="border-radius:14px;margin-bottom:12px">
            <b id="preN">00</b>
            <div class="bar2"><i></i></div>
        </div>
    </div>

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

    <!-- Universal Portfolio Quick Preview Lightbox Modal -->
    <?php $this->partial('partials/portfolio-lightbox'); ?>

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
    <script src="<?= asset_v('js/main.js') ?>" defer></script>
    <script src="<?= asset_v('js/chat.js') ?>" defer></script>

    <?= $footerCode ?>
</body>
</html>
