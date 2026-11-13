<?php
/** @var Core\View $this */
$accentColor = trim((string) setting('primary_accent', ''));
// The brand colours come from the stylesheet. Only a custom colour chosen in the admin settings overrides them
// (the old purple default and the brand blues are not treated as custom).
$accentOverride = $accentColor !== '' && !in_array(strtolower($accentColor), ['#8b7bff', '#2563eb', '#60a5fa', 'var(--accent)'], true);
$siteTitle = $meta_title ?? setting('site_title', 'Creatsify — Web, Mobile & SaaS Development Agency');
$siteDesc = $meta_desc ?? setting('meta_description', 'Custom web apps, Flutter mobile apps, SaaS, website redesigns, branding and SEO — built by a senior team with fixed milestones and full source-code ownership.');
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
    <meta name="author" content="Creatsify Digital Agency">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <link rel="canonical" href="<?= e(abs_url(request()->getPath())) ?>">

    <!-- OpenGraph / Social -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($siteTitle) ?>">
    <meta property="og:description" content="<?= e($siteDesc) ?>">
    <meta property="og:url" content="<?= e(abs_url(request()->getPath())) ?>">
    <meta property="og:image" content="<?= e(abs_url('/assets/brand/og-image.png')) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:image" content="<?= e(abs_url('/assets/brand/og-image.png')) ?>">
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

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
    <audio id="notifChime" preload="none">
        <source src="data:audio/wav;base64,UklGRsQFAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YaAFAACAgICAgH9+fn5/gYKCgX98e3x/goWFgn56eXp+g4eHhH55dnh9hImKhn94dHZ8hIqMiH93cnN6hIyOioB2cHF4hI2RjIJ2bm52g46Tj4N2bGx0go+VkYV2a2lygZCXlId2amdvgJCZl4l3aWRtfpCbmYx4aGJqfJCcnI56Z2Bneo+enpF7Z15kd46foZR9Z1xhdI2fo5eAaFtfcougppqCaVlcb4mgqJ2FalhZa4egqqCHa1dWaIWgrKOLbFdUZYKfraaObldRYYCerqmRcVhRX32braqTdFpRXXqYrKuWd1xRXHeWq6uYel5SWnSTqayafWBSWXGQp6ycf2NTWG+NpqyegmVUV2yLpKyghWhVVmqIoqyiiGpWVWiFoKyjim1YVWaCnqukjW9ZVWR/m6qmj3JbVWJ9mammknVdVWB6lqinlHhfVV94lKeolnphVV11kaaomH1jVlxzj6SomoBlV1twjKKpnIJnWFpuiqGonYVqWVpsh5+on4dsWllqhZ2ooIpuW1logpunoYxxXVhmgJmmoo5zXlhlfZemo5B2YFhje5SlpJJ4YllieJKjpJR7Y1lgdpCipZZ9ZVpfdI2hpZh/Z1pecoufpZmCaVtecImepZuEbFxdboacpJyGbl1dbISapJ2JcF9caoKYo56LcmBcaYCWo5+NdGFcZ32UoqCPd2NcZnuSoaCQeWVdZXmQoKGSe2ZdZHeOn6GUfWheY3WMnaGVf2peYnOKnKGXgmxfYXGIm6GYhG5gYXCGmaGZhnBhYG6El6CaiHJiYGyClqCbiXRjYGt/lJ+ci3ZlYGp+kp6cjXhmYGl8kJ2dj3pnYGh6jpydkHxpYWd4jZudkX5rYWZ2i5qdk39sYmV0iZmdlIFuY2Vzh5edlYNwZGRxhZadloVxZGRwg5Wcl4dzZmRvgZOcmIh1Z2Rtf5GbmIp3aGRsfpCamYt5aWRrfI5kb4eZl4JrZXOLmpR9aWZ4j5qQeWdofJOajHRma4GVmIhwZW+Gl5aDbWZzipmTfmpnd46ZkHpoaXyRmYx2Z2yAlJeIcmdvhJaWhG9nc4iXk4BsaHeMmJB7amp7j5eMd2lsf5KWiXRob4OUlYVwaHOHlpOAbml2i5aQfGxreo6WjHlqbX6RlYl1aW+Ck5SFcmlzhpSSgW9qdomVj31ta3qMlYx6bG19j5SJdmtwgZGThnNrc4WTkYJxa3aIk49+b2x5i5SMe21ufY6TiXhscICQkoZ1bHOEkZGDcmx2h5KOf3BteYqSjHxvb3yMkol5bnF/jpGGdm1zg5CQg3RudoaQjoBybnmIkYx9cHB8i5GJem9xf42QhndvdIKOj4N1b3aFj42Ac3B5h4+LfnJxe4mPiXtxcn6Lj4Z4cHSBjY6EdnB2hI6MgXRxeYaOi35zcnuIjol8cnN+io6GenJ1gIuNhHdyd4OMjIF2cnmFjYp/dHN7h42IfXN0fYmMhntzdYCKjIR5c3eCi4uCd3N5hIuJgHZ0e4aLiH11dX2Hi4Z7dHZ/iYuEenR4gYmKgnh0eYOKiIB3dXuFiod+dnZ9hoqFfHZ3f4eJhHt2eIGIiYJ5dnqCiYiAeHZ7hImGf3h3fYWJhX13eH+GiIR8d3mAh4iCend6goeHgHp3fIOHhn95eH2Eh4R+eHl+hYeDfHh6gIaGgnt4e4GGhoF7eXyChoV/enl9g4aEfnp6foSGg316e4CEhYJ8enyBhYWBfHp8gYWEgHt6fYKFg397e36DhIJ+e3x/g4SBfXt8gIOEgX17fYGEg4B8fH6Bg4J/fHx/goOCfnx9f4KDgX58fYCCgoB+fH6AgoKAfX1+gYKBf319f4GCgX99fn+BgoB/fn6AgYGAfn5+gIGBgH5+f4CBgX9+fn+AgYB/f39/gICAf39/gICAgH9/f4CAgIB/f3+AgIA=" type="audio/wav">
    </audio>

    <!-- Core Client Runtime Config -->
    <script>
        window.CREATSIFY_BASE_URL = <?= json_encode(url('/')) ?>;
        window.CREATSIFY_API_URL = <?= json_encode(url('/api/contact')) ?>;
        window.CREATSIFY_CONTACT_EMAIL = <?= json_encode(setting('site_email', 'hello@creatsify.tech')) ?>;
        window.CREATSIFY_CSRF_TOKEN = <?= json_encode(csrf_token()) ?>;
    </script>

    <!-- Scripts -->
    <script src="<?= asset_v('js/main.js') ?>" defer></script>
    <script src="<?= asset_v('js/enhance.js') ?>" defer></script>
    <script src="<?= asset_v('js/chat.js') ?>" defer></script>

    <?= $footerCode ?>
</body>
</html>
