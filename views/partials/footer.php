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
                <div class="om-sec-tag dark"><span class="om-sec-dot"></span> Ready To Build, Redesign Or Scale?</div>
                <h2 class="om-ft-cta-title">Let’s Engineer, Brand &amp; Grow Your Next Digital Winner<span class="om-head-dot">.</span></h2>
                <p class="om-ft-cta-desc">Whether you need a custom PHP, Node.js, or Next.js web platform, a cross-platform Flutter mobile app, a high-converting website redesign, memorable brand identity, or high-ROAS SEO &amp; paid social ads—our senior agency divisions deliver end-to-end.</p>
                <div class="om-ft-proof-pills">
                    <span>&#10003; Full-Stack Web &amp; Flutter Apps</span>
                    <span>&#10003; Branding, UI/UX &amp; Redesign</span>
                    <span>&#10003; Technical SEO &amp; Paid Social Ads</span>
                    <span>&#10003; 4.9&#9733; Verified Client Rating</span>
                </div>
            </div>
            <div class="om-ft-cta-right">
                <a class="om-btn-gold" href="<?= url('/contact') ?>">
                    <span>Start Your Project</span>
                    <span class="om-btn-circle-arrow">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                </a>
                <a class="om-ft-wa-btn" href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener">
                    <span class="om-ft-wa-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                    </span>
                    <span>Chat With Our Agency Desk</span>
                    <span class="om-ft-wa-arrow">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
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
                    Full-spectrum digital engineering, creative design, and performance marketing agency. We build high-velocity web &amp; mobile applications, craft iconic brands, and scale revenue through SEO and paid social ads.
                </p>
                <a href="<?= url('/reviews') ?>" class="om-ft-leadership-badge" style="text-decoration:none;">
                    <span class="om-pulse-dot"></span>
                    <span><b>4.9 / 5.0 &#9733;</b> Verified Client Rating &rarr;</span>
                </a>
                <div class="om-ft-location-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span>Worldwide Digital Agency &bull; 24/7 Desk</span>
                </div>
            </div>

            <!-- Col 2: Core Agency Services -->
            <div class="om-ft-col">
                <h4 class="om-ft-col-title">Agency Services</h4>
                <ul class="om-ft-links">
                    <li><a href="<?= url('/services') ?>">Custom Web Development</a></li>
                    <li><a href="<?= url('/services') ?>">Mobile Apps (Flutter &amp; Dart)</a></li>
                    <li><a href="<?= url('/services') ?>">Website Redesign &amp; UI/UX</a></li>
                    <li><a href="<?= url('/services') ?>">SaaS Product Engineering</a></li>
                    <li><a href="<?= url('/services') ?>">Search Engine Optimization (SEO)</a></li>
                    <li><a href="<?= url('/services') ?>">Social Media Marketing &amp; Ads</a></li>
                    <li><a href="<?= url('/services') ?>">Branding, Logo &amp; Graphic Design</a></li>
                </ul>
            </div>

            <!-- Col 3: Ready-to-Launch & Customizable Software -->
            <div class="om-ft-col">
                <h4 class="om-ft-col-title">Ready-to-Ship Products</h4>
                <ul class="om-ft-links">
                    <li><a href="<?= url('/portfolio/cheetah-enterprise') ?>">Cheetah Enterprise <span class="om-ft-price">$99</span></a></li>
                    <li><a href="<?= url('/portfolio/cheetah-lite') ?>">Cheetah Lite Courier <span class="om-ft-price">$49</span></a></li>
                    <li><a href="<?= url('/portfolio/cheezify') ?>">Cheezify Food &amp; POS <span class="om-ft-price">$59</span></a></li>
                    <li><a href="<?= url('/contact?kind=custom_tailoring&offering=Customize+Ready-Made+Software#inquiry-form') ?>">Request Custom Tailoring &rarr;</a></li>
                    <li><a href="<?= url('/software') ?>">Browse All Products &rarr;</a></li>
                </ul>
            </div>

            <!-- Col 4: Agency Links & Client Desk -->
            <div class="om-ft-col">
                <h4 class="om-ft-col-title">Agency &amp; Client Desk</h4>
                <ul class="om-ft-links">
                    <li><a href="<?= url('/portfolio') ?>">Featured Work &amp; Case Studies</a></li>
                    <li><a href="<?= url('/#packages') ?>">Service Packages &amp; Pricing</a></li>
                    <li><a href="<?= url('/reviews') ?>">Client Reviews &amp; Feedback &#9733;</a></li>
                    <li><a href="<?= url('/about') ?>">About Creatsify Agency</a></li>
                    <li><a href="<?= url('/support') ?>">Client &amp; Technical Support Desk</a></li>
                    <li><a href="<?= url('/contact') ?>">Book Free Consultation</a></li>
                    <li><a href="<?= url('/privacy') ?>">Privacy &amp; Terms</a></li>
                </ul>
            </div>
        </div>

        <!-- Sub-Footer Bottom Bar -->
        <div class="om-ft-bottom-bar">
            <div class="om-ft-copyright">
                &copy; <?= date('Y') ?> <b><?= e(setting('site_name', 'Creatsify')) ?> Digital Agency</b>. Full-Spectrum Engineering, Brand Identity, UI/UX, SEO &amp; Performance Marketing. All rights reserved.
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
