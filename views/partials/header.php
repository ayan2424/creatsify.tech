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
            <a href="<?= url('/portfolio') ?>" class="<?= str_starts_with($currentPath, '/portfolio') ? 'act' : '' ?>">Work</a>
            <a href="<?= url('/services') ?>" class="<?= str_starts_with($currentPath, '/services') ? 'act' : '' ?>">Services</a>
            <a href="<?= url('/about') ?>" class="<?= str_starts_with($currentPath, '/about') ? 'act' : '' ?>">About</a>
            <a href="<?= url('/blog') ?>" class="<?= str_starts_with($currentPath, '/blog') ? 'act' : '' ?>">Blog</a>
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
        <a class="btn p" href="<?= url('/contact') ?>">Start a project</a>
    </div>
</header>
