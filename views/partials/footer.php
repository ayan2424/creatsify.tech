<?php
/** @var Core\View $this */
$logoDark = asset(setting('site_logo', 'brand/creatsify-wordmark.png'));
$logoLight = asset(setting('site_logo_light', 'brand/creatsify-wordmark-light.png'));
$icon = asset(setting('site_icon', 'brand/creatsify-icon.png'));
?>
<footer>
    <div class="wrap">
        <div class="ft">
            <div style="display:flex;gap:12px;align-items:center">
                <img id="femb" src="<?= e($icon) ?>" width="42" height="42" alt="Creatsify emblem">
                <span>© <?= date('Y') ?> Creatsify · creatsify.tech · Karachi, Pakistan</span>
            </div>
            <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap">
                <a href="<?= url('/portfolio') ?>">Work</a>
                <a href="<?= url('/services') ?>">Services</a>
                <a href="<?= url('/team') ?>">Team</a>
                <a href="<?= url('/about') ?>">About</a>
                <a href="<?= url('/blog') ?>">Blog</a>
                <a href="<?= url('/admin/login') ?>">Admin</a>
                <a class="btn" href="#top">Back to top ↑</a>
            </div>
        </div>

        <!-- Crisp Vector Brand Logos -->
        <div class="foot-wordmark-wrap">
            <img class="foot-wordmark-img logo-dark" src="<?= e($logoDark) ?>" alt="Creatsify Digital Studio" loading="lazy">
            <img class="foot-wordmark-img logo-light" src="<?= e($logoLight) ?>" alt="Creatsify Digital Studio" loading="lazy">
        </div>
    </div>
</footer>
