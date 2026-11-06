<?php
/** @var Core\View $this */
$logoDark = asset_v(setting('site_logo', 'brand/creatsify-logo-dark.svg'));
$logoLight = asset_v(setting('site_logo_light', 'brand/creatsify-logo-light.svg'));
$icon = asset_v(setting('site_icon', 'brand/creatsify-icon.svg'));

$waPhone = clean_phone_number(setting('whatsapp_number', setting('site_phone', '923002521199')), false);
$waMsg = urlencode("Hello, I would like to discuss a project with " . setting('site_name', 'Creatsify Studio'));
?>

<!-- Omoway Redesigned Studio Footer -->
<footer class="om-footer">
    <div class="wrap">
        <!-- Pre-Footer Heroic Callout Box (Omoway "Crafting Since 2024" Pattern) -->
        <div class="om-ft-cta-box rv s">
            <div class="om-ft-cta-left">
                <div class="om-sec-tag dark"><span class="om-sec-dot"></span> Got A Project? Need Direct Co-Founder Setup?</div>
                <h2 class="om-ft-cta-title">Crafting Scalable Software For Growing Businesses<span class="om-dot-gold">.</span></h2>
                <p class="om-ft-cta-desc">License battle-tested courier logistics systems and pure PHP 8.4 store engines with 100% full source code, or collaborate directly with our three co-founders to build bespoke software with zero middle-men.</p>
                <div class="om-ft-proof-pills">
                    <span>&#10003; 100% Unlocked Source Code</span>
                    <span>&#10003; Pure PHP 8.4 MVC</span>
                    <span>&#10003; Direct Co-Founder Support</span>
                </div>
            </div>
            <div class="om-ft-cta-right">
                <a class="om-btn-gold" href="<?= url('/software') ?>">
                    <span>Browse Software Catalog</span>
                    <span class="om-btn-circle-arrow">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                </a>
                <a class="om-ft-wa-btn" href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener">
                    <span class="om-ft-wa-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                    </span>
                    <span>Talk to Co-Founders on WhatsApp</span>
                    <span class="om-ft-wa-arrow">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                </a>
            </div>
        </div>

        <!-- Main Multi-Column Studio Footer -->
        <div class="om-ft-main-grid">
            <!-- Col 1: Studio Brand & Co-Founders -->
            <div class="om-ft-col brand-col">
                <div class="om-ft-brand-head">
                    <img src="<?= e($icon) ?>" width="40" height="40" alt="<?= e(setting('site_name', 'Creatsify')) ?> emblem" class="om-ft-emblem">
                    <span class="om-ft-brand-name"><?= e(setting('site_name', 'Creatsify')) ?><span class="om-dot-gold">.</span></span>
                </div>
                <p class="om-ft-brand-desc">
                    Independent software studio led by three co-founders. We engineer white-label courier logtech platforms, multi-branch store engines, and high-velocity web platforms for businesses worldwide.
                </p>
                <div class="om-ft-leadership-badge">
                    <span class="om-pulse-dot"></span>
                    <span><b>3 Co-Founders:</b> Ayan &bull; Sukaina &bull; Moiz</span>
                </div>
                <div class="om-ft-location-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span>Worldwide, Remote Clients</span>
                </div>
            </div>

            <!-- Col 2: Production Software for Sale -->
            <div class="om-ft-col">
                <h4 class="om-ft-col-title">Software For Sale</h4>
                <ul class="om-ft-links">
                    <li><a href="<?= url('/portfolio/cheetah-enterprise') ?>">Cheetah Enterprise <span class="om-ft-price">$99</span></a></li>
                    <li><a href="<?= url('/portfolio/cheetah-lite') ?>">Cheetah Lite Courier <span class="om-ft-price">$49</span></a></li>
                    <li><a href="<?= url('/portfolio/cheezify') ?>">Cheezify Food &amp; POS <span class="om-ft-price">$59</span></a></li>
                    <li><a href="https://cheetah.ayan24.me" target="_blank" rel="noopener">Cheetah Live Demo ↗</a></li>
                    <li><a href="<?= url('/software') ?>">Browse Full Catalog &rarr;</a></li>
                </ul>
            </div>

            <!-- Col 3: Agency Capabilities -->
            <div class="om-ft-col">
                <h4 class="om-ft-col-title">Agency Services</h4>
                <ul class="om-ft-links">
                    <li><a href="<?= url('/services') ?>">Pure PHP 8.4 MVC Systems</a></li>
                    <li><a href="<?= url('/services') ?>">Courier &amp; Logistics Backbones</a></li>
                    <li><a href="<?= url('/services') ?>">Custom High-Speed Admin Panels</a></li>
                    <li><a href="<?= url('/services') ?>">WhatsApp Cloud API Webhooks</a></li>
                    <li><a href="<?= url('/services') ?>">WooCommerce &amp; Store Setup</a></li>
                    <li><a href="<?= url('/services') ?>">aaPanel &amp; Linux Server Ops</a></li>
                </ul>
            </div>

            <!-- Col 4: Studio & Desk Links -->
            <div class="om-ft-col">
                <h4 class="om-ft-col-title">Studio Desk</h4>
                <ul class="om-ft-links">
                    <li><a href="<?= url('/team') ?>">Meet The 3 Co-Founders</a></li>
                    <li><a href="<?= url('/about') ?>">About Creatsify Studio</a></li>
                    <li><a href="<?= url('/portfolio') ?>">Work &amp; Case Studies</a></li>
                    <li><a href="<?= url('/support') ?>">Customer Support Desk</a></li>
                    <li><a href="<?= url('/contact') ?>">Start A Project Brief</a></li>
                    <li><a href="<?= url('/privacy') ?>">Privacy &amp; Terms</a></li>
                </ul>
            </div>
        </div>

        <!-- Sub-Footer Bottom Bar -->
        <div class="om-ft-bottom-bar">
            <div class="om-ft-copyright">
                &copy; <?= date('Y') ?> <b><?= e(setting('site_name', 'Creatsify')) ?> Studio</b>. Handcrafted with zero third-party framework overhead. Built with pure PHP 8.4 MVC &amp; Vanilla ES6. Worldwide, remote.
            </div>
            <div class="om-ft-bottom-right">
                <a class="om-ft-top-btn" href="#top">
                    <span>Back to top</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
                </a>
            </div>
        </div>

        <!-- Big Display Signature Watermark (Omoway Typographic Statement) -->
        <div class="om-ft-watermark" aria-hidden="true">
            <span>Creatsify.</span>
        </div>
    </div>
</footer>
