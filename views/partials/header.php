<?php
/** @var Core\View $this */
$logoDark = asset_v(setting('site_logo', 'brand/creatsify-logo-dark.svg'));
$logoLight = asset_v(setting('site_logo_light', 'brand/creatsify-logo-light.svg'));
$currentPath = request()->getPath();

$navItems = [
    ['href' => '/',          'label' => 'Home',     'on' => $currentPath === '/'],
    ['href' => '/services',  'label' => 'Services', 'on' => str_starts_with($currentPath, '/services')],
    ['href' => '/packages',  'label' => 'Packages', 'on' => str_starts_with($currentPath, '/packages')],
    ['href' => '/portfolio', 'label' => 'Work',     'on' => str_starts_with($currentPath, '/portfolio') || str_starts_with($currentPath, '/work')],
    ['href' => '/software',  'label' => 'Products', 'on' => str_starts_with($currentPath, '/software'), 'hot' => true],
    ['href' => '/reviews',   'label' => 'Reviews',  'on' => str_starts_with($currentPath, '/reviews')],
    ['href' => '/support',   'label' => 'Support',  'on' => str_starts_with($currentPath, '/support')],
];
?>
<header>
    <div class="wrap bar">
        <a href="<?= url('/') ?>" class="brand-link" aria-label="Creatsify Home">
            <img class="brand-logo logo-dark" src="<?= e($logoDark) ?>" alt="Creatsify" height="46">
            <img class="brand-logo logo-light" src="<?= e($logoLight) ?>" alt="Creatsify" height="46">
        </a>
        <nav id="nav" aria-label="Main">
            <?php foreach ($navItems as $item): ?>
                <a href="<?= url($item['href']) ?>" class="<?= trim(($item['hot'] ?? false ? 'nav-hot ' : '') . ($item['on'] ? 'act' : '')) ?>"<?= $item['on'] ? ' aria-current="page"' : '' ?>><?= e($item['label']) ?></a>
            <?php endforeach; ?>
        </nav>
        <div class="header-actions">
            <button class="btn icon" id="theme" type="button" aria-label="Switch light or dark theme">
                <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </button>
            <a class="btn p desktop-cta<?= str_starts_with($currentPath, '/contact') ? ' act' : '' ?>" href="<?= url('/contact') ?>">
                <span>Free Consultation</span>
                <span class="btn-arrow-circle">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </span>
            </a>

            <!-- Mobile Navigation Hamburger Toggle Button -->
            <button class="btn icon mobile-menu-toggle" id="mobileMenuBtn" type="button" aria-label="Toggle navigation menu" aria-expanded="false">
                <svg class="icon-menu-bars" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                <svg class="icon-menu-cross" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Navigation Drawer & Backdrop -->
<div class="mobile-drawer-backdrop" id="mobileDrawerBackdrop"></div>
<div class="mobile-drawer-sheet" id="mobileDrawer" aria-hidden="true" inert>
    <div class="mobile-drawer-top">
        <a href="<?= url('/') ?>" class="brand-link" aria-label="Creatsify Home">
            <img class="brand-logo logo-dark" src="<?= e($logoDark) ?>" alt="Creatsify" height="38">
            <img class="brand-logo logo-light" src="<?= e($logoLight) ?>" alt="Creatsify" height="38">
        </a>
        <button type="button" class="btn icon mobile-drawer-close-btn" id="mobileDrawerClose" aria-label="Close navigation menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    <nav class="mobile-drawer-links" aria-label="Main">
        <?php foreach ($navItems as $item): ?>
            <a href="<?= url($item['href']) ?>" class="mobile-link <?= $item['on'] ? 'act' : '' ?>">
                <span><?= e($item['label']) ?></span>
                <span class="mobile-link-arrow"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>
        <?php endforeach; ?>
        <a href="<?= url('/contact') ?>" class="mobile-link <?= str_starts_with($currentPath, '/contact') ? 'act' : '' ?>">
            <span>Contact &amp; Quote</span>
            <span class="mobile-link-arrow"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="9 18 15 12 9 6"/></svg></span>
        </a>
        <div class="mobile-more">
            <a href="<?= url('/about') ?>">About Agency</a>
            <a href="<?= url('/blog') ?>">Insights</a>
            <a href="<?= url('/privacy') ?>">Privacy</a>
        </div>
    </nav>

    <div class="mobile-drawer-bottom">
        <a class="om-btn-gold" href="<?= url('/contact') ?>" style="width:100%; justify-content:center;">
            <span>Start Your Project</span>
            <span class="om-btn-circle-arrow">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
        </a>
        <div style="margin-top: 14px; text-align:center; font-size: 12px; color: var(--muted);">
            Web &bull; Mobile Apps &bull; Branding &bull; SEO &amp; Ads
        </div>
    </div>
</div>
