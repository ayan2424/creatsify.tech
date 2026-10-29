<?php
/** @var Core\View $this */
$logoDark = asset(setting('site_logo', 'brand/creatsify-wordmark.png'));
$logoLight = asset(setting('site_logo_light', 'brand/creatsify-wordmark-light.png'));
$currentPath = request()->getPath();
?>
<header>
    <div class="wrap bar">
        <a href="<?= url('/') ?>" class="brand-link" aria-label="Creatsify Home">
            <img class="brand-logo logo-dark" src="<?= e($logoDark) ?>" alt="Creatsify" height="38">
            <img class="brand-logo logo-light" src="<?= e($logoLight) ?>" alt="Creatsify" height="38">
        </a>
        <nav id="nav">
            <a href="<?= url('/') ?>" class="<?= $currentPath === '/' ? 'act' : '' ?>">Home</a>
            <a href="<?= url('/portfolio') ?>" class="<?= str_starts_with($currentPath, '/portfolio') ? 'act' : '' ?>">Software</a>
            <a href="<?= url('/services') ?>" class="<?= str_starts_with($currentPath, '/services') ? 'act' : '' ?>">Services</a>
            <a href="<?= url('/team') ?>" class="<?= str_starts_with($currentPath, '/team') ? 'act' : '' ?>">Team</a>
            <a href="<?= url('/about') ?>" class="<?= str_starts_with($currentPath, '/about') ? 'act' : '' ?>">About</a>
            <a href="<?= url('/blog') ?>" class="<?= str_starts_with($currentPath, '/blog') ? 'act' : '' ?>">Blog</a>
            <a href="<?= url('/support') ?>" class="<?= str_starts_with($currentPath, '/support') ? 'act' : '' ?>">Customer Support</a>
            <a href="<?= url('/contact') ?>" class="<?= str_starts_with($currentPath, '/contact') ? 'act' : '' ?>">Contact</a>
        </nav>
        <button class="btn icon sound-toggle-btn" id="soundToggle" type="button" aria-label="Toggle sound effects" title="Toggle sound effects">
            <svg class="icon-sound-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:18px;height:18px;"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
            <svg class="icon-sound-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:18px;height:18px;display:none;"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><line x1="23" y1="9" x2="17" y2="15"/><line x1="17" y1="9" x2="23" y2="15"/></svg>
        </button>
        <button class="btn icon" id="theme" type="button" aria-label="Switch light or dark theme">
            <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
            <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>
        <a class="btn p desktop-cta" href="<?= url('/contact') ?>">Start a project</a>

        <!-- Mobile Navigation Hamburger Toggle Button -->
        <button class="btn icon mobile-menu-toggle" id="mobileMenuBtn" type="button" aria-label="Toggle navigation menu" aria-expanded="false">
            <svg class="icon-menu-bars" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            <svg class="icon-menu-cross" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
</header>

<!-- Mobile Navigation Drawer & Backdrop -->
<div class="mobile-drawer-backdrop" id="mobileDrawerBackdrop"></div>
<div class="mobile-drawer-sheet" id="mobileDrawer" aria-hidden="true">
    <div class="mobile-drawer-top">
        <a href="<?= url('/') ?>" class="brand-link" aria-label="Creatsify Home">
            <img class="brand-logo logo-dark" src="<?= e($logoDark) ?>" alt="Creatsify" height="32">
            <img class="brand-logo logo-light" src="<?= e($logoLight) ?>" alt="Creatsify" height="32">
        </a>
        <button type="button" class="btn icon mobile-drawer-close-btn" id="mobileDrawerClose" aria-label="Close navigation menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    <nav class="mobile-drawer-links">
        <a href="<?= url('/') ?>" class="mobile-link <?= $currentPath === '/' ? 'act' : '' ?>">
            <span>Home</span>
            <span class="mobile-link-arrow">&rarr;</span>
        </a>
        <a href="<?= url('/portfolio') ?>" class="mobile-link <?= str_starts_with($currentPath, '/portfolio') ? 'act' : '' ?>">
            <span>Software & Case Studies</span>
            <span class="mobile-link-arrow">&rarr;</span>
        </a>
        <a href="<?= url('/services') ?>" class="mobile-link <?= str_starts_with($currentPath, '/services') ? 'act' : '' ?>">
            <span>Engineering Services</span>
            <span class="mobile-link-arrow">&rarr;</span>
        </a>
        <a href="<?= url('/team') ?>" class="mobile-link <?= str_starts_with($currentPath, '/team') ? 'act' : '' ?>">
            <span>Agency Creators & Team</span>
            <span class="mobile-link-arrow">&rarr;</span>
        </a>
        <a href="<?= url('/about') ?>" class="mobile-link <?= str_starts_with($currentPath, '/about') ? 'act' : '' ?>">
            <span>About Studio & Founder</span>
            <span class="mobile-link-arrow">&rarr;</span>
        </a>
        <a href="<?= url('/blog') ?>" class="mobile-link <?= str_starts_with($currentPath, '/blog') ? 'act' : '' ?>">
            <span>Technical Blog</span>
            <span class="mobile-link-arrow">&rarr;</span>
        </a>
        <a href="<?= url('/support') ?>" class="mobile-link <?= str_starts_with($currentPath, '/support') ? 'act' : '' ?>">
            <span>Customer Support Desk</span>
            <span class="mobile-link-arrow">&rarr;</span>
        </a>

        <a href="<?= url('/contact') ?>" class="mobile-link <?= str_starts_with($currentPath, '/contact') ? 'act' : '' ?>">
            <span>Contact & Quotations</span>
            <span class="mobile-link-arrow">&rarr;</span>
        </a>
    </nav>

    <div class="mobile-drawer-bottom">
        <a class="btn p btn-block" href="<?= url('/contact') ?>" style="display:block; text-align:center; padding:13px 20px; font-weight:700;">
            Start a project &rarr;
        </a>
        <div style="margin-top: 14px; text-align:center; font-size: 12px; color: var(--muted);">
            Full-Stack Software Studio
        </div>
    </div>
</div>
