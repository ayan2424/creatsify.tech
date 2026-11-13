<?php
/** @var Core\View $this */
$logoDark = asset_v(setting('site_logo', 'brand/creatsify-logo-dark.svg'));
$logoLight = asset_v(setting('site_logo_light', 'brand/creatsify-logo-light.svg'));
$icon = asset_v(setting('site_icon', 'brand/creatsify-icon.svg'));

$waPhone = clean_phone_number(setting('whatsapp_number', setting('site_phone', '923002521199')), false);
$waMsg = urlencode("Hello! I would like to discuss a project or package with Creatsify Digital Agency.");
?>

<!-- Creatsify Digital Agency Footer -->
<footer class="om-footer">
    <div class="wrap">
        <!-- Pre-Footer Heroic Callout Box -->
        <div class="om-ft-cta-box rv s">
            <div class="om-ft-cta-left">
                <div class="om-sec-tag dark"><span class="om-sec-dot"></span> Ready when you are</div>
                <h2 class="om-ft-cta-title">Let’s build something your customers will love</h2>
                <p class="om-ft-cta-desc">Tell us about your idea &mdash; a new website, a mobile app, a redesign, a brand or a growth campaign &mdash; and we’ll reply with a clear plan, timeline and quote.</p>
                <div class="om-ft-proof-pills">
                    <span>&#10003; Websites &amp; mobile apps</span>
                    <span>&#10003; Brand &amp; UI/UX design</span>
                    <span>&#10003; SEO &amp; paid ads</span>
                    <span>&#10003; Full code ownership</span>
                </div>
            </div>
            <div class="om-ft-cta-right">
                <a class="om-btn-gold" href="<?= url('/contact') ?>">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span>Start a project</span>
                </a>
                <a class="om-ft-wa-btn" href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                    <span>Chat on WhatsApp</span>
                </a>
            </div>
        </div>

        <!-- Main Multi-Column Agency Footer -->
        <div class="om-ft-main-grid">
            <!-- Col 1: Agency Brand & Rating -->
            <div class="om-ft-col brand-col">
                <a href="<?= url('/') ?>" class="om-ft-brand-head" aria-label="<?= e(setting('site_name', 'Creatsify')) ?> Home" style="display:inline-block;text-decoration:none;">
                    <img src="<?= e($logoDark) ?>" height="40" alt="<?= e(setting('site_name', 'Creatsify')) ?>" style="height:40px;width:auto;display:block;">
                </a>
                <p class="om-ft-brand-desc">
                    A digital studio for websites, mobile apps, branding and growth. We design, build and market products for businesses worldwide &mdash; with direct access to the people doing the work.</p>
                <a href="<?= url('/reviews') ?>" class="om-ft-leadership-badge" style="text-decoration:none;">
                    <span class="om-pulse-dot"></span>
                    <span>Read what our clients say</span>
                </a>
                <div class="om-ft-location-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span>Working with clients worldwide</span>
                </div>
            </div>

            <!-- Col 2: Core Agency Services -->
            <div class="om-ft-col">
                <h4 class="om-ft-col-title">Services</h4>
                <ul class="om-ft-links">
                    <li><a href="<?= url('/services') ?>">Custom Web Development</a></li>
                    <li><a href="<?= url('/services') ?>">Mobile apps</a></li>
                    <li><a href="<?= url('/services') ?>">Website redesign</a></li>
                    <li><a href="<?= url('/services') ?>">SaaS development</a></li>
                    <li><a href="<?= url('/services') ?>">SEO</a></li>
                    <li><a href="<?= url('/services') ?>">Social media &amp; ads</a></li>
                    <li><a href="<?= url('/services') ?>">Branding &amp; logo design</a></li>
                </ul>
            </div>

            <!-- Col 3: Ready-to-Launch & Customizable Software -->
            <div class="om-ft-col">
                <h4 class="om-ft-col-title">Ready-made platforms</h4>
                <ul class="om-ft-links">
                    <li><a href="<?= url('/portfolio/cheetah-enterprise') ?>">Cheetah Enterprise <span class="om-ft-price">$99</span></a></li>
                    <li><a href="<?= url('/portfolio/cheetah-lite') ?>">Cheetah Lite Courier <span class="om-ft-price">$49</span></a></li>
                    <li><a href="<?= url('/portfolio/cheezify') ?>">Cheezify Food &amp; POS <span class="om-ft-price">$59</span></a></li>
                    <li><a href="<?= url('/contact?kind=custom_tailoring&offering=Customize+Ready-Made+Software#inquiry-form') ?>">Request customization</a></li>
                    <li><a href="<?= url('/software') ?>">Browse all platforms</a></li>
                </ul>
            </div>

            <!-- Col 4: Agency Links & Client Desk -->
            <div class="om-ft-col">
                <h4 class="om-ft-col-title">Company</h4>
                <ul class="om-ft-links">
                    <li><a href="<?= url('/portfolio') ?>">Our work</a></li>
                    <li><a href="<?= url('/#packages') ?>">Packages &amp; pricing</a></li>
                    <li><a href="<?= url('/reviews') ?>">Client reviews</a></li>
                    <li><a href="<?= url('/about') ?>">About Creatsify</a></li>
                    <li><a href="<?= url('/support') ?>">Support</a></li>
                    <li><a href="<?= url('/contact') ?>">Book a free consultation</a></li>
                    <li><a href="<?= url('/privacy') ?>">Privacy &amp; terms</a></li>
                </ul>
            </div>
        </div>

        <!-- Sub-Footer Bottom Bar -->
        <div class="om-ft-bottom-bar">
            <div class="om-ft-copyright" style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                <a href="<?= url('/') ?>" aria-label="<?= e(setting('site_name', 'Creatsify')) ?> Home" style="display:inline-flex;align-items:center;text-decoration:none;">
                    <img src="<?= e($logoDark) ?>" height="26" alt="<?= e(setting('site_name', 'Creatsify')) ?>" style="height:26px;width:auto;display:block;">
                </a>
                <span>&copy; <?= date('Y') ?> Creatsify. All rights reserved.</span>
            </div>
            <div class="om-ft-bottom-right">
                <a class="om-ft-top-btn" href="#top" aria-label="Back to top">
                    <span>Back to top</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
                </a>
            </div>
        </div>
    </div>
</footer>
