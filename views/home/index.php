<?php
/** @var Core\View $this */
$this->layout('layouts/main');

$assetDir = dirname(__DIR__, 2) . '/public/assets/images/';
$imgFor = static function (string $slug) use ($assetDir) {
    $map = [
        'cheetah-enterprise'         => 'images/cheetah-showcase.jpg',
        'cheetah-lite'               => 'images/cheetah-lite-preview.jpg',
        'cheezify'                   => 'images/cheezify-preview.jpg',
        'restromaster-kds-interface' => 'images/restromaster-preview.jpg',
        'viddmate-video-suite'       => 'images/viddmate-preview.jpg',
        'apex-retail-redesign'       => 'images/apex-preview.jpg',
        'lumina-fleet-app'           => 'images/lumina-preview.jpg',
        'kinetix-brand-system'       => 'images/kinetix-preview.jpg',
        'pulserank-geo-seo'          => 'images/pulserank-preview.jpg',
        'novagrowth-performance-ads' => 'images/novagrowth-preview.jpg',
    ];
    if (isset($map[$slug]) && is_file($assetDir . basename($map[$slug]))) {
        return $map[$slug];
    }
    if (is_file($assetDir . $slug . '-preview.webp')) {
        return 'images/' . $slug . '-preview.webp';
    }
    $file = $slug . '-preview.jpg';
    if (is_file($assetDir . $file)) {
        return 'images/' . $file;
    }
    return null;
};

$outcomeMap = [
    'apex-retail-redesign'       => '+184% Conversion Lift',
    'lumina-fleet-app'           => '4.9★ App Store Rating',
    'kinetix-brand-system'       => '3.8x Meta & Google ROAS',
    'cheetah-enterprise'         => '120k+ Parcels/Mo SLA',
    'cheetah-lite'               => '12-Day Custom Launch',
    'cheezify'                   => '+92% Repeat Order Rate',
    'restromaster-kds-interface' => '0.8s Kitchen Order Sync',
    'viddmate-video-suite'       => '4.2x Faster Encoding',
    'pulserank-geo-seo'          => '+310% AI Citation Lift',
    'novagrowth-performance-ads' => '4.1x Verified Ad ROAS',
];

$waPhone = clean_phone_number(setting('whatsapp_number', setting('site_phone', '923002521199')), false);
$waMsg = urlencode("Hello! I would like to discuss a project or service package with Creatsify Digital Agency.");

$totalReviews = (int)($reviewStats['total'] ?? 0);
$avgRating = $totalReviews > 0 ? number_format((float)($reviewStats['avg_rating'] ?? $reviewStats['average'] ?? 0), 1) : null;
?>

<!-- JSON-LD Rich Snippet Schema: ProfessionalService + AggregateRating -->
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ProfessionalService',
    'name' => setting('site_name', 'Creatsify') . ' Digital Agency',
    'url' => abs_url('/'),
    'logo' => absolutize(asset('brand/creatsify-logo-dark.png')),
    'description' => 'Full-service Web & Mobile App Development, Website Redesign, SaaS Engineering, Branding & Logo Design, Technical SEO, and Performance Marketing Agency.',
    'priceRange' => '$$',
] + ($totalReviews > 0 && $avgRating ? ['aggregateRating' => [
    '@type' => 'AggregateRating',
    'ratingValue' => (string)$avgRating,
    'bestRating' => '5',
    'worstRating' => '1',
    'reviewCount' => (string)$totalReviews,
]] : []), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

<!-- ======================================================================
     STAGE 1: FULL-SPECTRUM DIGITAL AGENCY HERO (#hero)
     ====================================================================== -->
<section class="om-hero" id="hero">
    <div class="wrap om-hero-wrap">
        <!-- Left Side: High-Authority Agency Value Proposition -->
        <div class="om-hero-content hf">
            <div class="om-hero-tag">
                <span class="om-tag-beacon"></span>
                <span>Web, apps, brand &amp; growth &mdash; one team</span>
            </div>

            <h1 class="om-hero-headline">
                <span class="om-head-top">Websites, apps and brands</span>
                <span class="om-head-bot">that help you grow.</span>
            </h1>

            <p class="om-hero-sub">
                We design and build fast websites, mobile apps and brand systems &mdash; then help the right customers find you through SEO and paid ads. You work directly with the people doing the work.</p>

            <div class="om-cta-group">
                <a class="om-btn-gold" href="#contact">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span>Start a project</span>
                </a>
                <a class="om-btn-glass" href="#work">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span>See our work</span>
                </a>
                <a class="om-hero-wa-link" href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                    <span>Chat on WhatsApp</span>
                </a>
            </div>

            <div class="om-proof-bar">
                <?php if ($avgRating): ?><span class="om-proof-item"><i class="om-proof-check">&#9733;</i> <?= e($avgRating) ?>/5 from client reviews</span><?php endif; ?>
                <span class="om-proof-item"><i class="om-proof-check">&#10003;</i> You own all the code</span>
                <span class="om-proof-item"><i class="om-proof-check">&#10003;</i> Fixed milestones</span>
                <span class="om-proof-item"><i class="om-proof-check">&#10003;</i> Direct access to senior builders</span>
            </div>
        </div>

        <!-- Right Side: Multi-Discipline Agency Command Stage -->
        <div class="om-hero-visual-stage hf" style="--d:.25s" id="omVisualStage">
            <div class="om-stage-glow" aria-hidden="true"></div>

            <!-- Centerpiece Interactive Agency Command Card -->
            <div class="om-centerpiece-card om-agency-command-card">
                <div class="om-card-browser-bar">
                    <div class="om-browser-dots">
                        <span class="dot d-red"></span>
                        <span class="dot d-yellow"></span>
                        <span class="dot d-green"></span>
                    </div>
                    <div class="om-browser-url">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                        <span>creatsify.tech / studio</span>
                    </div>
                    <span class="om-browser-badge"><i class="om-live-dot"></i> One studio</span>
                </div>

                <!-- 3-Division Live Showcase -->
                <div class="ag-stage-body">
                    <!-- Division 1: Full-Stack Web & Mobile Apps -->
                    <div class="ag-pillar-row">
                        <div class="ag-pillar-icon cyan">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        </div>
                        <div class="ag-pillar-info">
                            <div class="ag-pillar-top">
                                <strong>Web &amp; mobile apps</strong>
                                <span class="ag-metric-pill emerald">Built to scale</span>
                            </div>
                            <p>PHP 8.4 &bull; Node.js &bull; Next.js &bull; Flutter &amp; Dart &bull; MySQL</p>
                        </div>
                    </div>

                    <!-- Division 2: Branding, Logo & Website Redesign -->
                    <div class="ag-pillar-row">
                        <div class="ag-pillar-icon cobalt">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>
                        </div>
                        <div class="ag-pillar-info">
                            <div class="ag-pillar-top">
                                <strong>Brand &amp; UI/UX design</strong>
                                <span class="ag-metric-pill cyan">UX-first</span>
                            </div>
                            <p>3D &amp; Vector Logos &bull; Brand Guidelines &bull; Figma Design Systems</p>
                        </div>
                    </div>

                    <!-- Division 3: Technical SEO & Paid Social Ads -->
                    <div class="ag-pillar-row">
                        <div class="ag-pillar-icon purple">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                        </div>
                        <div class="ag-pillar-info">
                            <div class="ag-pillar-top">
                                <strong>SEO &amp; paid ads</strong>
                                <span class="ag-metric-pill gold">Growth-led</span>
                            </div>
                            <p>Technical SEO &bull; Meta Ads &bull; Google Ads &bull; Funnels</p>
                        </div>
                    </div>

                    <!-- Live Agency KPI Strip inside Command Card -->
                    <div class="ag-command-kpis">
                        <div class="ag-kpi-box">
                            <span class="ag-kpi-val">100%</span>
                            <span class="ag-kpi-lbl">Code ownership</span>
                        </div>
                        <div class="ag-kpi-box">
                            <span class="ag-kpi-val">24h</span>
                            <span class="ag-kpi-lbl">Reply time</span>
                        </div>
                        <div class="ag-kpi-box">
                            <span class="ag-kpi-val">Fixed</span>
                            <span class="ag-kpi-lbl">Milestone pricing</span>
                        </div>
                    </div>

                    <!-- Live Stack & Conversion Telemetry Footer inside Stage -->
                    <div class="ag-stage-footer">
                        <div class="ag-stage-chips">
                            <span>Next.js</span>
                            <span>PHP 8.4</span>
                            <span>Flutter</span>
                            <span>Tailwind</span>
                            <span>SEO &amp; Ads</span>
                        </div>
                        <a href="#work" class="ag-stage-link">Case Studies</a>
                    </div>
                </div>
            </div>

            <!-- Corner Floating Badge 1: Top Right - 4 Specialized Divisions -->
            <div class="om-float-card om-float-divisions">
                <div class="ag-division-icons">
                    <span class="ag-div-icon code" title="Web & SaaS Engineering">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                    </span>
                    <span class="ag-div-icon design" title="UI/UX & Branding">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    </span>
                    <span class="ag-div-icon growth" title="SEO & Paid Social Ads">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    </span>
                </div>
                <div class="om-founders-meta">
                    <strong>4 specialist teams</strong>
                    <span>Code &bull; Mobile &bull; Brand &bull; Growth</span>
                </div>
            </div>

            <!-- Corner Floating Badge 2: Bottom Left - Verified Star Rating -->
            <?php if ($avgRating): ?>
            <a href="#reviews" class="om-float-card om-float-quality" style="text-decoration:none;">
                <div class="om-quality-top">
                    <span class="om-quality-step" style="color:#f59e0b;font-size:13px;letter-spacing:1px;">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                    <div class="om-quality-check">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </div>
                <strong class="om-quality-heading"><?= e($avgRating) ?> / 5.0 Rating</strong>
                <span class="om-quality-sub">Client reviews</span>
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ======================================================================
     STAGE 2: COMPLETE TECH, DESIGN & MARKETING ARSENAL CAROUSEL
     ====================================================================== -->
<div class="om-partners-bar ag-arsenal-section">
    <div class="ag-arsenal-marquee" aria-label="Core Technology & Marketing Stack">
        <div class="ag-arsenal-track">
            <!-- Set 1: Full-Stack Tech & Creative Arsenal -->
            <div class="om-partner-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                <span>PHP 8.4 MVC</span>
            </div>
            <div class="om-partner-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                <span>Next.js &amp; Node.js</span>
            </div>
            <div class="om-partner-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                <span>Flutter &amp; Dart</span>
            </div>
            <div class="om-partner-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="M9 9h6v6H9z"/></svg>
                <span>Tailwind CSS</span>
            </div>
            <div class="om-partner-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
                <span>MySQL 8 &amp; Redis</span>
            </div>
            <div class="om-partner-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <span>Technical &amp; Local SEO</span>
            </div>
            <div class="om-partner-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                <span>Meta, Google &amp; TikTok Ads</span>
            </div>
            <div class="om-partner-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/></svg>
                <span>Logo &amp; brand identity</span>
            </div>
            <div class="om-partner-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                <span>Figma Systems</span>
            </div>
            <div class="om-partner-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                <span>REST &amp; Webhooks</span>
            </div>

            <!-- Set 2: Duplicate for Seamless Continuous Loop -->
            <div class="om-partner-item" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                <span>PHP 8.4 MVC</span>
            </div>
            <div class="om-partner-item" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                <span>Next.js &amp; Node.js</span>
            </div>
            <div class="om-partner-item" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                <span>Flutter &amp; Dart</span>
            </div>
            <div class="om-partner-item" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="M9 9h6v6H9z"/></svg>
                <span>Tailwind CSS</span>
            </div>
            <div class="om-partner-item" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
                <span>MySQL 8 &amp; Redis</span>
            </div>
            <div class="om-partner-item" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <span>Technical &amp; Local SEO</span>
            </div>
            <div class="om-partner-item" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                <span>Meta, Google &amp; TikTok Ads</span>
            </div>
            <div class="om-partner-item" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/></svg>
                <span>Logo &amp; brand identity</span>
            </div>
            <div class="om-partner-item" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                <span>Figma Systems</span>
            </div>
            <div class="om-partner-item" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                <span>REST &amp; Webhooks</span>
            </div>
        </div>
    </div>
</div>

<!-- Facts & Proof Bar -->
<div class="wrap om-facts-wrap">
    <div class="om-facts-bar rv s">
        <div class="om-fact-item">
            <b data-n="50">50+</b>
            <span>Projects delivered</span>
        </div>
        <?php if ($avgRating): ?>
        <div class="om-fact-item">
            <b><?= e($avgRating) ?>&#9733;</b>
            <span>Average client rating</span>
        </div>
        <?php endif; ?>
        <div class="om-fact-item">
            <b data-n="15">15+</b>
            <span>Technologies we use</span>
        </div>
        <div class="om-fact-item">
            <b data-n="100">100%</b>
            <span>Code &amp; IP ownership</span>
        </div>
    </div>
</div>

<!-- ======================================================================
     STAGE 3: THE 6 CORE AGENCY SERVICES BENTO GRID (#services)
     ====================================================================== -->
<section id="services" class="om-services-sec">
    <div class="wrap">
        <div class="om-sec-center">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> What we do</div>
            <h2 class="om-sec-heading">Everything you need to launch and grow online</h2>
            <p class="om-sec-sub">Instead of juggling separate freelancers and agencies, work with one team that handles development, design and marketing together &mdash; so nothing gets lost in hand-offs.</p>

            <!-- Interactive Service Category Filter Pills -->
            <div class="om-filter-bar ag-svc-filter" style="margin-top:24px;" role="group" aria-label="Filter agency services">
                <button type="button" class="om-filter-pill active" data-svc-filter="all" aria-pressed="true">All services</button>
                <button type="button" class="om-filter-pill" data-svc-filter="engineering" aria-pressed="false">Web &amp; Mobile Apps</button>
                <button type="button" class="om-filter-pill" data-svc-filter="creative" aria-pressed="false">Branding &amp; Redesign</button>
                <button type="button" class="om-filter-pill" data-svc-filter="growth" aria-pressed="false">SEO &amp; paid ads</button>
            </div>
        </div>

        <div class="ag-services-bento" style="margin-top:40px;">
            <!-- 1. Custom Web & SaaS Development -->
            <article class="ag-svc-bento-card rv" data-svc-cat="engineering" style="--d:0.05s">
                <div class="ag-svc-top">
                    <div class="om-sc-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                    </div>
                    <span class="ag-svc-badge">Full-Stack Web</span>
                </div>
                <h3>Custom websites &amp; SaaS</h3>
                <p>Business websites, customer portals, admin dashboards and multi-user web apps &mdash; built from scratch with clean, secure code that&rsquo;s easy to grow.</p>
                <div class="om-sc-chips">
                    <span>PHP 8.4</span><span>Node.js</span><span>Next.js</span><span>Tailwind</span><span>MySQL 8</span><span>REST APIs</span>
                </div>
                <a href="#contact" data-select-package="Custom Web Development" class="ag-svc-cta">
                    <span>Plan your web project</span>
                    <span class="ag-svc-arrow"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg></span>
                </a>
            </article>

            <!-- 2. Mobile App Development (Flutter & Dart) -->
            <article class="ag-svc-bento-card rv" data-svc-cat="engineering" style="--d:0.1s">
                <div class="ag-svc-top">
                    <div class="om-sc-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                    </div>
                    <span class="ag-svc-badge">iOS &amp; Android</span>
                </div>
                <h3>Mobile apps for iOS &amp; Android</h3>
                <p>One Flutter codebase, two polished apps. Smooth animations, live tracking, push notifications and offline support when you need them.</p>
                <div class="om-sc-chips">
                    <span>Flutter</span><span>Dart</span><span>iOS + Android</span><span>REST APIs</span><span>Push Alerts</span>
                </div>
                <a href="#contact" data-select-package="Mobile App Development (Flutter & Dart)" class="ag-svc-cta">
                    <span>Build your app</span>
                    <span class="ag-svc-arrow"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg></span>
                </a>
            </article>

            <!-- 3. Website Redesign & UI/UX Modernization -->
            <article class="ag-svc-bento-card rv" data-svc-cat="creative" style="--d:0.15s">
                <div class="ag-svc-top">
                    <div class="om-sc-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    </div>
                    <span class="ag-svc-badge">UI/UX &amp; CRO</span>
                </div>
                <h3>Website redesign</h3>
                <p>Turn a slow or dated site into a fast, mobile-first experience designed to convert more visitors into enquiries and customers.</p>
                <div class="om-sc-chips">
                    <span>UI/UX Overhaul</span><span>Fast loading</span><span>Mobile-First</span><span>Figma Systems</span>
                </div>
                <a href="#contact" data-select-package="Website Redesign & Modernization" class="ag-svc-cta">
                    <span>Refresh your site</span>
                    <span class="ag-svc-arrow"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg></span>
                </a>
            </article>

            <!-- 4. Branding, Logo & Graphic Design -->
            <article class="ag-svc-bento-card rv" data-svc-cat="creative" style="--d:0.2s">
                <div class="ag-svc-top">
                    <div class="om-sc-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/></svg>
                    </div>
                    <span class="ag-svc-badge">Brand Identity</span>
                </div>
                <h3>Brand identity &amp; logo design</h3>
                <p>Logos, colour and type systems, brand guidelines and pitch decks &mdash; one cohesive identity you can use everywhere, from your website to your social profiles.</p>
                <div class="om-sc-chips">
                    <span>Master Logo</span><span>Brand Guidelines</span><span>3D Marks</span><span>Figma Tokens</span>
                </div>
                <a href="#contact" data-select-package="Branding, Logo & Graphic Design" class="ag-svc-cta">
                    <span>Shape your brand</span>
                    <span class="ag-svc-arrow"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></span>
                </a>
            </article>

            <!-- 5. Search Engine Optimization (SEO) -->
            <article class="ag-svc-bento-card rv" data-svc-cat="growth" style="--d:0.25s">
                <div class="ag-svc-top">
                    <div class="om-sc-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    <span class="ag-svc-badge">Organic growth</span>
                </div>
                <h3>SEO that builds lasting traffic</h3>
                <p>Technical fixes, structured data, site speed and content planning that help the right customers find you on Google &mdash; locally and globally.</p>
                <div class="om-sc-chips">
                    <span>Technical Schema</span><span>Keyword Silos</span><span>Site speed</span><span>Local &amp; Global SEO</span>
                </div>
                <a href="#contact" data-select-package="Search Engine Optimization (SEO)" class="ag-svc-cta">
                    <span>Improve your rankings</span>
                    <span class="ag-svc-arrow"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
                </a>
            </article>

            <!-- 6. Social Media Marketing & Paid Ads -->
            <article class="ag-svc-bento-card rv" data-svc-cat="growth" style="--d:0.3s">
                <div class="ag-svc-top">
                    <div class="om-sc-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    </div>
                    <span class="ag-svc-badge">Paid growth</span>
                </div>
                <h3>Paid ads &amp; funnels</h3>
                <p>Meta, Google and TikTok campaigns with fresh creative, clear tracking and landing pages built to convert &mdash; so every ad dollar is accountable.</p>
                <div class="om-sc-chips">
                    <span>Meta FB/IG Ads</span><span>Google PPC</span><span>TikTok Ads</span><span>Conversion tracking</span>
                </div>
                <a href="#contact" data-select-package="Social Media Marketing & Paid Ads" class="ag-svc-cta">
                    <span>Grow with ads</span>
                    <span class="ag-svc-arrow"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></span>
                </a>
            </article>
        </div>

        <!-- Highlight Banner: Ready-Made Software Integration -->
        <div class="ag-services-footer-banner rv s" style="margin-top:36px;">
            <div class="ag-sfb-content">
                <div class="om-sec-tag dark" style="margin-bottom:6px;"><span class="om-sec-dot"></span> Ready-made software</div>
                <strong>Need to launch quickly?</strong>
                <p>Start from a tested courier, logistics or POS platform with full source code &mdash; or have us tailor it to your business.</p>
            </div>
            <a href="#products" class="om-btn-gold">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                <span>Browse ready-made platforms</span>
            </a>
        </div>
    </div>
</section>

<!-- ======================================================================
     STAGE 4: FEATURED AGENCY WORK & CASE STUDIES (#work)
     ====================================================================== -->
<section id="work" class="ag-work-sec">
    <div class="wrap">
        <div class="om-sec-center">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Selected work</div>
            <h2 class="om-sec-heading">Projects we&rsquo;re proud of</h2>
            <p class="om-sec-sub">A look at recent websites, apps, redesigns and campaigns &mdash; each built around a real business goal.</p>

            <!-- Interactive Work Filter Pills (The 7 Official Disciplines) -->
            <div class="om-filter-bar ag-work-filter" style="margin-top:24px;" role="group" aria-label="Filter case studies">
                <button type="button" class="om-filter-pill active" data-work-filter="all" aria-pressed="true">All Work</button>
                <button type="button" class="om-filter-pill" data-work-filter="web-dev" aria-pressed="false">Web Design &amp; Development</button>
                <button type="button" class="om-filter-pill" data-work-filter="mobile-app" aria-pressed="false">Mobile App Development</button>
                <button type="button" class="om-filter-pill" data-work-filter="saas-crm" aria-pressed="false">SaaS &amp; CRM</button>
                <button type="button" class="om-filter-pill" data-work-filter="software-eng" aria-pressed="false">Software &amp; Product Engineering</button>
                <button type="button" class="om-filter-pill" data-work-filter="seo-geo" aria-pressed="false">SEO &amp; GEO</button>
                <button type="button" class="om-filter-pill" data-work-filter="digital-marketing" aria-pressed="false">Digital Marketing</button>
                <button type="button" class="om-filter-pill" data-work-filter="ui-ux-branding" aria-pressed="false">UI/UX Design &amp; Branding</button>
            </div>
        </div>

        <div class="ag-case-grid" style="margin-top:40px;">
            <?php
            $caseProjects = !empty($projects) ? $projects : ($portfolioWorks ?? []);
            foreach ($caseProjects as $idx => $pr):
                $slug = $pr['slug'];
                $shot = $imgFor($slug);
                $tech = array_filter(array_map('trim', explode(',', (string)($pr['tech_stack'] ?? ''))));
                $live = $pr['live_url'] ?? '';
                $hasLive = !empty($live) && $live !== '#';
                $cat = \App\Models\Project::normalizeCategory($pr['category'] ?? 'web-dev');
                $catName = \App\Models\Project::categoryLabel($cat);
                $catLabel = !empty($pr['subcategory']) ? $pr['subcategory'] : $catName;
                $isSoftware = !empty($pr['is_software']) || ($pr['status'] ?? '') === 'For sale';
                $priceStr = !empty($pr['price']) ? $pr['price'] : '';
                $desc = !empty($pr['summary']) ? $pr['summary'] : (!empty($pr['description']) ? $pr['description'] : '');
            ?>
                <article class="ag-case-card rv on" data-work-cat="<?= e($cat) ?>" style="--d:<?= ($idx * 0.08) ?>s;">
                    <!-- 1. The Clean, Crisp Screenshot Image -->
                    <?php if ($shot): ?>
                        <img src="<?= asset($shot) ?>"<?php if (str_ends_with($shot, '.webp')): ?> srcset="<?= asset(str_replace('.webp', '-640.webp', $shot)) ?> 640w, <?= asset($shot) ?> 1376w" sizes="(min-width:1024px) 33vw, (min-width:641px) 50vw, 100vw"<?php endif; ?> width="1376" height="768" loading="lazy" decoding="async" alt="<?= e($pr['title']) ?>" class="ag-card-img">
                    <?php else: ?>
                        <div class="ag-case-img-fallback">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            <span><?= e($pr['title']) ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- 2. macOS 3 Traffic Light Dots (Always Visible at Top-Left) -->
                    <div class="ag-card-dots" aria-hidden="true">
                        <span class="ag-dot d-red"></span>
                        <span class="ag-dot d-yellow"></span>
                        <span class="ag-dot d-green"></span>
                    </div>

                    <!-- 3. Details & Action Buttons (Hidden by Default, Revealed on Hover & Focus with Progressive Blur) -->
                    <div class="ag-card-body">
                        <h3 class="ag-card-title">
                            <a href="<?= url('/portfolio/' . $slug) ?>"><?= e($pr['title']) ?></a>
                        </h3>

                        <div class="ag-card-tags">
                            <span class="ag-card-pill"><?= e($catLabel) ?></span>
                            <?php if ($isSoftware): ?>
                                <span class="ag-case-tag-sw" title="Ready-Made Commercial Software Available For Direct Purchase Or Customization">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                                    <span>Ready-Made Software<?= $priceStr !== '' ? ' &bull; ' . e($priceStr) : '' ?></span>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if ($desc): ?>
                            <p class="ag-card-desc"><?= e($desc) ?></p>
                        <?php endif; ?>

                        <div class="ag-card-actions">
                            <button type="button" 
                                    class="ag-btn-preview js-open-lightbox" 
                                    data-img="<?= asset($shot) ?>" 
                                    data-title="<?= e($pr['title']) ?>" 
                                    data-slug="<?= e($slug) ?>" 
                                    data-badge="<?= e($catLabel) ?>" 
                                    data-live="<?= e($live) ?>" 
                                    data-software="<?= $isSoftware ? '1' : '0' ?>"
                                    data-price="<?= e($priceStr) ?>"
                                    data-gumroad="<?= e($pr['gumroad_url'] ?? '') ?>"
                                    data-desc="<?= e($desc) ?>"
                                    data-tech="<?= e($pr['tech_stack'] ?? '') ?>"
                                    data-client="<?= e($pr['client'] ?? '') ?>"
                                    title="Quick Preview &amp; Specs">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                                <span>Preview</span>
                            </button>

                            <a href="<?= url('/portfolio/' . $slug) ?>" class="ag-btn-details" title="View Full Case Study">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <span>Case Details</span>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
<div style="text-align:center;margin-top:36px;">
            <a class="om-btn-glass" href="<?= url('/portfolio') ?>">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>View all projects</span>
            </a>
        </div>
    </div>
</section>



<!-- ======================================================================
     STAGE 5: WHY CREATSIFY — THE UNFAIR ADVANTAGE (#why-us)
     ====================================================================== -->
<section id="why-us" class="om-agency-sec">
    <div class="wrap">
        <div class="om-sec-center">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Why Creatsify</div>
            <h2 class="om-sec-heading">One team. Zero hand-off headaches.</h2>
            <p class="om-sec-sub">When developers, designers and marketers work together, your product, your brand and your growth all pull in the same direction.</p>
        </div>

        <!-- 4 Core Advantages Cards -->
        <div class="ag-strength-grid" style="margin-top:40px;">
            <div class="ag-strength-card rv" style="--d:0.05s">
                <span class="ag-str-num">01</span>
                <h3>Build, design and market together</h3>
                <p>Developers, designers and marketers share one plan, so your site, brand and campaigns reinforce each other instead of pulling apart.</p>
            </div>
            <div class="ag-strength-card rv" style="--d:0.1s">
                <span class="ag-str-num">02</span>
                <h3>Hand-built, not template-built</h3>
                <p>No heavy themes or page-builder plugins. We write clean, purposeful code so your site loads quickly and stays easy to maintain.</p>
            </div>
            <div class="ag-strength-card rv" style="--d:0.15s">
                <span class="ag-str-num">03</span>
                <h3>Talk to the people who build it</h3>
                <p>No account-manager middle layer. You speak directly with the developers and designers working on your project.</p>
            </div>
            <div class="ag-strength-card rv" style="--d:0.2s">
                <span class="ag-str-num">04</span>
                <h3>You own everything</h3>
                <p>Full source code and commercial rights are yours at handover, with clear milestone pricing and a live staging link so you can follow progress every week.</p>
            </div>
        </div>

        <!-- Comparison Table: Creatsify vs Traditional Agencies vs Freelancers -->
        <div class="ag-compare-wrap rv s" style="margin-top:44px;">
            <div class="ag-compare-table-scroll" tabindex="0" role="region" aria-label="Comparison table">
                <table class="ag-compare-table">
                    <thead>
                        <tr>
                            <th>What you get</th>
                            <th class="highlight-col">
                                <span class="ag-cmp-badge">Creatsify</span>
                                Creatsify Digital Agency
                            </th>
                            <th>Large agencies</th>
                            <th>Separate freelancers</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><b>Scope</b></td>
                            <td class="highlight-col">&#10003; <b>Web, mobile, brand, SEO &amp; ads in one team</b></td>
                            <td>Separate departments, slower hand-offs</td>
                            <td>One skill each &mdash; you coordinate the rest</td>
                        </tr>
                        <tr>
                            <td><b>Build quality</b></td>
                            <td class="highlight-col">&#10003; <b>Custom-built and performance-focused</b></td>
                            <td>Work is often passed to junior staff</td>
                            <td>Often relies on templates and plugins</td>
                        </tr>
                        <tr>
                            <td><b>Delivery</b></td>
                            <td class="highlight-col">&#10003; <b>Short sprints, usually days to weeks</b></td>
                            <td>Long timelines with many meetings</td>
                            <td>Timelines can be hard to predict</td>
                        </tr>
                        <tr>
                            <td><b>Pricing</b></td>
                            <td class="highlight-col">&#10003; <b>Clear packages and fixed milestones</b></td>
                            <td>Often high monthly retainers</td>
                            <td>Scope changes can add surprise costs</td>
                        </tr>
                        <tr>
                            <td><b>Ownership</b></td>
                            <td class="highlight-col">&#10003; <b>Full source code and IP handed over</b></td>
                            <td>Often locked in to the agency</td>
                            <td>Hand-over quality varies</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     STAGE 6: TRANSPARENT SERVICE PACKAGES + INTERACTIVE SCOPE ESTIMATOR (#packages)
     ====================================================================== -->
<section id="packages" class="ag-packages-sec">
    <div class="wrap">
        <div class="om-sec-center">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Transparent pricing</div>
            <h2 class="om-sec-heading">Clear packages for every stage</h2>
            <p class="om-sec-sub">Pick a ready-to-start package, or build a custom bundle with the estimator below.</p>

            <!-- Package Track Switcher Tabs -->
            <div class="om-filter-bar ag-pkg-tabs" style="margin-top:26px;" role="group" aria-label="Service Package Tracks">
                <button type="button" class="om-filter-pill active" data-pkg-tab="dev" aria-pressed="true">Web, app &amp; SaaS</button>
                <button type="button" class="om-filter-pill" data-pkg-tab="design" aria-pressed="false">Brand &amp; redesign</button>
                <button type="button" class="om-filter-pill" data-pkg-tab="growth" aria-pressed="false">SEO &amp; paid ads</button>
            </div>
        </div>

        <?php
        $tracksData = $packagesByTrack ?? ['dev' => [], 'design' => [], 'growth' => []];
        $trackOrder = ['dev', 'design', 'growth'];
        foreach ($trackOrder as $tIdx => $trackKey):
            $trackPackages = $tracksData[$trackKey] ?? [];
            $isFirstTrack = ($tIdx === 0);
        ?>
        <div class="ag-pkg-grid <?= $isFirstTrack ? 'active' : '' ?>" data-pkg-panel="<?= e($trackKey) ?>" <?= !$isFirstTrack ? 'style="display:none;"' : '' ?>>
            <?php foreach ($trackPackages as $pkg):
                $isPop = !empty($pkg['is_popular']);
                $featList = \App\Models\ServicePackage::parseFeatures($pkg['features'] ?? '');
                $pkgSelectVal = $pkg['title'] . ' (' . $pkg['price_label'] . ')';
            ?>
            <article class="ag-pkg-card <?= $isPop ? 'popular' : '' ?>">
                <?php if ($isPop): ?>
                    <div class="ag-pkg-pop-ribbon"><?= e(strtoupper($pkg['tier_badge'] ?: 'MOST POPULAR')) ?></div>
                <?php endif; ?>
                <div class="ag-pkg-head">
                    <div class="ag-pkg-top-meta">
                        <span class="ag-pkg-tier-cat"><?= e(strtoupper($trackKey)) ?> SPRINT</span>
                        <?php if (!empty($pkg['delivery_time'])): ?>
                            <span class="ag-pkg-delivery-pill">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:4px;vertical-align:-1px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><?= e($pkg['delivery_time']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h3 class="ag-pkg-tier"><?= e($pkg['title']) ?></h3>
                    <div class="ag-pkg-price"><?= e($pkg['price_label']) ?><span><?= e($pkg['billing_cycle'] ? '/' . ltrim($pkg['billing_cycle'], '/') : '') ?></span></div>
                    <p class="ag-pkg-sub"><?= e($pkg['subtitle']) ?></p>
                </div>
                <ul class="ag-pkg-list">
                    <?php foreach ($featList as $fItem): ?>
                        <li>&#10003; <?= e($fItem) ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="#contact" data-select-package="<?= e($pkgSelectVal) ?>" class="<?= $isPop ? 'om-btn-gold' : 'om-btn-glass' ?>" style="width:100%;justify-content:center;margin-top:auto;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span><?= e($pkg['cta_text'] ?: 'Select Package') ?></span>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

        <div style="text-align:center;margin-top:28px;">
            <a href="<?= url('/packages') ?>" class="om-btn-glass">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                <span>Compare full package details</span>
            </a>
        </div>

        <!-- Interactive Custom Scope & Bundle Estimator -->
        <div class="ag-scope-estimator rv s" id="scopeEstimator">
            <div class="ag-se-left">
                <div class="om-sec-tag" style="margin-bottom:8px;"><span class="om-sec-dot"></span> Bundle builder</div>
                <h3>Need more than one service?</h3>
                <p>Choose what you need and get an instant estimate, with an automatic<b>15% bundle discount</b>.</p>
                <div class="ag-se-options">
                    <label class="ag-se-check">
                        <input type="checkbox" value="Custom Website / Redesign" data-price="350" data-days="7" checked>
                        <span class="ag-se-label"><span>Website build or redesign</span> <b>+$350</b></span>
                    </label>
                    <label class="ag-se-check">
                        <input type="checkbox" value="Flutter Mobile App (iOS & Android)" data-price="750" data-days="14">
                        <span class="ag-se-label"><span>Mobile app (Flutter)</span> <b>+$750</b></span>
                    </label>
                    <label class="ag-se-check">
                        <input type="checkbox" value="Logo & Complete Brand Identity" data-price="150" data-days="3" checked>
                        <span class="ag-se-label"><span>Logo &amp; brand identity</span> <b>+$150</b></span>
                    </label>
                    <label class="ag-se-check">
                        <input type="checkbox" value="Technical & On-Page SEO" data-price="220" data-days="5">
                        <span class="ag-se-label"><span>SEO setup</span> <b>+$220</b></span>
                    </label>
                    <label class="ag-se-check">
                        <input type="checkbox" value="Meta & Google Paid Ads Setup" data-price="250" data-days="4">
                        <span class="ag-se-label"><span>Social &amp; paid ads</span> <b>+$250</b></span>
                    </label>
                </div>
            </div>
            <div class="ag-se-right">
                <span class="ag-se-badge">15% bundle saving applied</span>
                <div class="ag-se-total-label">Estimated investment</div>
                <div class="ag-se-total-price" id="seTotalPrice">$425</div>
                <div class="ag-se-timeline" id="seTimeline">Est. Delivery: ~10 Days</div>
                <button type="button" class="om-btn-gold" id="seLockBtn" style="width:100%;justify-content:center;border:none;cursor:pointer;margin-top:14px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span>Request this bundle</span>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     STAGE 7: READY-TO-LAUNCH COMMERCIAL SOFTWARE PLATFORMS (#products)
     ====================================================================== -->
<section id="products" class="om-dark-showcase">
    <div class="wrap">
        <div class="om-showcase-header">
            <div class="om-sec-tag dark"><span class="om-sec-dot"></span> Ready-made platforms</div>
            <h2 class="om-sec-heading light">Launch in days with a ready-made platform</h2>
            <p class="om-sec-sub light">Alongside custom builds, we offer tested commercial software. <b>License a platform directly on Gumroad</b> with full source code, or <b>have our team customize it</b> for your business.</p>
        </div>

        <div class="om-prod-grid">
            <?php
            $prodList = !empty($readyMadeProducts) ? $readyMadeProducts : array_slice(array_filter($projects ?? [], fn($p) => ($p['status'] ?? '') === 'For sale'), 0, 5);
            foreach (array_slice($prodList, 0, 6) as $i => $pr):
                $shot = $imgFor($pr['slug']);
                $tech = array_filter(array_map('trim', explode(',', (string)($pr['tech_stack'] ?? ''))));
                $live = $pr['live_url'] ?? '';
                $hasLive = $live && $live !== '#';
                $priceStr = !empty($pr['price']) ? $pr['price'] : '';
            ?>
                <article class="om-prod-card rv" style="--d:<?= $i * 0.12 ?>s">
                    <div class="om-prod-media-frame">
                        <div class="om-prod-bar">
                            <div class="om-prod-dots">
                                <span class="dot d-red"></span>
                                <span class="dot d-yellow"></span>
                                <span class="dot d-green"></span>
                            </div>
                            <span class="om-prod-badge-price"><?= $priceStr !== '' ? 'Direct: ' . e($priceStr) . ' &bull; Or Customize' : 'Custom quote available' ?></span>
                        </div>
                        <?php if ($shot): ?>
                            <img src="<?= asset($shot) ?>" width="1376" height="768" loading="lazy" alt="<?= e($pr['title']) ?>" class="om-prod-img">
                        <?php else: ?>
                            <div class="om-prod-cover">
                                <div class="om-prod-cover-icon">
                                    <?php if ($pr['slug'] === 'cheetah-lite'): ?>
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                    <?php else: ?>
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                    <?php endif; ?>
                                </div>
                                <div class="om-prod-cover-meta">
                                    <span class="om-prod-cover-badge">Ready to deploy</span>
                                    <b><?= e($pr['title']) ?> Architecture</b>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="om-prod-body">
                        <div class="om-prod-status-row">
                            <span class="om-prod-pill"><?= e(!empty($pr['subcategory']) ? $pr['subcategory'] : 'Commercial Script') ?></span>
                            <span class="om-prod-rights">&#10003; Full source code</span>
                        </div>
                        <h3 class="om-prod-title"><?= e($pr['title']) ?></h3>
                        <p class="om-prod-desc"><?= e($pr['summary'] ?? '') ?></p>
                        <div class="om-prod-chips">
                            <?php foreach ($tech as $t): ?><span><?= e($t) ?></span><?php endforeach; ?>
                        </div>
                        <div class="om-prod-actions">
                            <?php if (!empty($pr['gumroad_url'])): ?>
                                <a class="om-btn-gold" href="<?= e($pr['gumroad_url']) ?>" target="_blank" rel="noopener noreferrer">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    <span><?= $priceStr !== '' ? 'Buy License (' . e($priceStr) . ')' : 'Buy License' ?></span>
                                </a>
                            <?php endif; ?>
                            <a class="om-btn-glass light" href="#contact" data-select-package="Customize <?= e($pr['title']) ?> For My Business">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                <span>Customize for me</span>
                            </a>
                            <?php if ($hasLive): ?>
                                <a class="om-btn-glass light" href="<?= e($live) ?>" target="_blank" rel="noopener noreferrer">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                    <span>Live Demo</span>
                                </a>
                            <?php else: ?>
                                <a class="om-btn-glass light" href="<?= url('/portfolio/' . $pr['slug']) ?>">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                    <span>View details</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center;margin-top:40px;">
            <a href="<?= url('/software') ?>" class="om-btn-glass light" style="display:inline-flex;padding:12px 28px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                <span>Browse all platforms</span>
            </a>
        </div>
    </div>
</section>

<!-- ======================================================================
     STAGE 8: 4-STEP AGENCY WORKFLOW (#how)
     ====================================================================== -->
<section id="how" class="om-how-sec">
    <div class="wrap">
        <div class="om-sec-center">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> How it works</div>
            <h2 class="om-sec-heading">From first call to launch in four steps</h2>
            <p class="om-sec-sub">Clear milestones, a live staging link and no guesswork from day one.</p>
        </div>
        <div class="ag-workflow-4grid">
            <div class="om-how-card rv" style="--d:0s">
                <span class="om-how-step-num">01</span>
                <h3>Discover &amp; scope</h3>
                <p>We learn your goals, define the scope and agree on fixed milestones and a timeline before any work starts.</p>
            </div>
            <div class="om-how-card rv" style="--d:0.08s">
                <span class="om-how-step-num">02</span>
                <h3>Design</h3>
                <p>Our creative team shapes your brand, design system and key screens in Figma, so you can approve the look and feel before development begins.</p>
            </div>
            <div class="om-how-card rv" style="--d:0.16s">
                <span class="om-how-step-num">03</span>
                <h3>Build</h3>
                <p>We develop on a private staging server so you can review real progress every week and give feedback as we go.</p>
            </div>
            <div class="om-how-card rv" style="--d:0.24s">
                <span class="om-how-step-num">04</span>
                <h3>Launch &amp; grow</h3>
                <p>We deploy to your production server, set up technical SEO and tracking, and &mdash; if you want &mdash; launch search and social campaigns.</p>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     STAGE 9: VERIFIED CLIENT REVIEWS (#reviews)
     ====================================================================== -->
<section id="reviews" class="ag-reviews-sec">
    <div class="wrap">
        <div class="om-sec-center">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Client feedback</div>
            <h2 class="om-sec-heading">What clients say about working with us</h2>
            <p class="om-sec-sub">Honest feedback from founders and teams we&rsquo;ve built, designed and grown with.</p>
            
            <div class="ag-rating-summary-pill" style="margin:20px auto 36px;display:inline-flex;">
                <div class="ag-rsp-score"><?= e($avgRating ?? "—") ?></div>
                <div class="ag-rsp-meta" style="text-align:left;">
                    <div class="ag-rsp-stars" aria-label="5 out of 5 stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    <span><?= $totalReviews > 0 ? 'Based on ' . (int)$totalReviews . ' Verified Reviews' : 'Client Reviews' ?></span>
                </div>
                <div class="ag-rsp-actions">
                    <a href="<?= url('/reviews#submit-review') ?>" class="om-btn-gold sm">
                        <span>Leave a Review &#9733;</span>
                    </a>
                    <a href="<?= url('/reviews') ?>" class="om-btn-glass sm">
                        <span>All Reviews</span>
                    </a>
                </div>
            </div>
        </div>

        <?php
        $allReviews = $reviews ?? [];
        if (empty($allReviews)) {
            $allReviews = \App\Models\ClientReview::allApproved();
        }
        $half = (int)ceil(count($allReviews) / 2);
        $row1 = array_slice($allReviews, 0, $half);
        $row2 = array_slice($allReviews, $half);
        if (empty($row2)) {
            $row2 = $row1;
        }

        $getRevAvatar = function($idx) {
            $num = ($idx % 12) + 1;
            return asset('assets/images/avatars/avatar-' . $num . '.jpg');
        };
        ?>

        <!-- Dual-Row Continuous Marquee Stream (Inspired by Reference Video) -->
        <div class="om-reviews-marquee-container" id="reviewsMarqueeContainer">
            <!-- Row 1: Leftward Infinite Stream -->
            <div class="om-marquee-row om-marquee-row-1" aria-label="Client reviews stream row 1" role="region" tabindex="0">
                <div class="om-marquee-track om-marquee-left">
                    <?php for ($rep = 0; $rep < 3; $rep++): ?>
                        <?php foreach ($row1 as $idx => $rev):
                            $stars = max(1, min(5, (int)($rev['rating'] ?? 5)));
                            $avatarUrl = $getRevAvatar($idx);
                        ?>
                            <article class="om-rev-card" <?= $rep > 0 ? 'aria-hidden="true"' : '' ?>>
                                <div class="om-rev-header-row">
                                    <div class="om-rev-quote-icon" aria-hidden="true">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.324 1.202-4.544 3.284-4.664 5.234.34-.143.76-.235 1.23-.235 1.95 0 3.511 1.562 3.511 3.511 0 1.95-1.562 3.511-3.511 3.511-.703 0-1.354-.213-1.906-.901zm11 0C14.553 16.227 14 15 14 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.324 1.202-4.544 3.284-4.664 5.234.34-.143.76-.235 1.23-.235 1.95 0 3.511 1.562 3.511 3.511 0 1.95-1.562 3.511-3.511 3.511-.703 0-1.354-.213-1.906-.901z"/></svg>
                                    </div>
                                    <div class="om-rev-top-meta">
                                        <div class="om-rev-stars" aria-label="<?= $stars ?> out of 5 stars"><?= str_repeat('&#9733;', $stars) ?></div>
                                        <?php if (!empty($rev['outcome_metric'])): ?>
                                            <span class="om-rev-metric"><?= e($rev['outcome_metric']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="om-rev-text">“<?= e($rev['review_text']) ?>”</p>
                                <div class="om-rev-author-row">
                                    <img src="<?= $avatarUrl ?>" alt="<?= e($rev['client_name']) ?>" class="om-rev-avatar" width="44" height="44" loading="lazy">
                                    <div class="om-rev-author-info">
                                        <strong class="om-rev-name"><?= e($rev['client_name']) ?></strong>
                                        <span class="om-rev-role"><?= e($rev['client_role'] ?: 'Verified Client') ?><?= !empty($rev['company_name']) ? ' &bull; ' . e($rev['company_name']) : '' ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Row 2: Rightward Infinite Stream -->
            <div class="om-marquee-row om-marquee-row-2" aria-label="Client reviews stream row 2" role="region" tabindex="0">
                <div class="om-marquee-track om-marquee-right">
                    <?php for ($rep = 0; $rep < 3; $rep++): ?>
                        <?php foreach ($row2 as $idx => $rev):
                            $stars = max(1, min(5, (int)($rev['rating'] ?? 5)));
                            $avatarUrl = $getRevAvatar($idx + $half);
                        ?>
                            <article class="om-rev-card" <?= $rep > 0 ? 'aria-hidden="true"' : '' ?>>
                                <div class="om-rev-header-row">
                                    <div class="om-rev-quote-icon" aria-hidden="true">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.324 1.202-4.544 3.284-4.664 5.234.34-.143.76-.235 1.23-.235 1.95 0 3.511 1.562 3.511 3.511 0 1.95-1.562 3.511-3.511 3.511-.703 0-1.354-.213-1.906-.901zm11 0C14.553 16.227 14 15 14 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.324 1.202-4.544 3.284-4.664 5.234.34-.143.76-.235 1.23-.235 1.95 0 3.511 1.562 3.511 3.511 0 1.95-1.562 3.511-3.511 3.511-.703 0-1.354-.213-1.906-.901z"/></svg>
                                    </div>
                                    <div class="om-rev-top-meta">
                                        <div class="om-rev-stars" aria-label="<?= $stars ?> out of 5 stars"><?= str_repeat('&#9733;', $stars) ?></div>
                                        <?php if (!empty($rev['outcome_metric'])): ?>
                                            <span class="om-rev-metric"><?= e($rev['outcome_metric']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="om-rev-text">“<?= e($rev['review_text']) ?>”</p>
                                <div class="om-rev-author-row">
                                    <img src="<?= $avatarUrl ?>" alt="<?= e($rev['client_name']) ?>" class="om-rev-avatar" width="44" height="44" loading="lazy">
                                    <div class="om-rev-author-info">
                                        <strong class="om-rev-name"><?= e($rev['client_name']) ?></strong>
                                        <span class="om-rev-role"><?= e($rev['client_role'] ?: 'Verified Client') ?><?= !empty($rev['company_name']) ? ' &bull; ' . e($rev['company_name']) : '' ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <div style="text-align:center;margin-top:36px;display:flex;justify-content:center;gap:14px;flex-wrap:wrap;">
            <a class="om-btn-gold" href="<?= url('/reviews') ?>">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span><?= $totalReviews > 0 ? 'Read All ' . (int)$totalReviews . ' Reviews' : 'Read Client Reviews' ?></span>
            </a>
            <a class="om-btn-glass" href="<?= url('/reviews#submit-review') ?>">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                <span>Worked with us? Share your feedback</span>
            </a>
        </div>
    </div>
</section>

<!-- ======================================================================
     STAGE 10: DIRECT CONSULTATION & WHATSAPP HUB (#contact)
     ====================================================================== -->
<section id="contact" class="om-contact-sec">
    <div class="wrap om-contact-wrap">
        <div class="om-contact-left rv l">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Get in touch</div>
            <h2 class="om-sec-heading">Tell us what you&rsquo;d like to build</h2>
            <p class="om-sec-sub">Choose a package, ask about customizing one of our ready-made platforms, or request a free website and SEO review. Prefer a quick chat? Message us on WhatsApp.</p>

            <div class="om-whatsapp-card">
                <div class="om-wa-header">
                    <span class="om-wa-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                    </span>
                    <div class="om-wa-header-info">
                        <b>Chat on WhatsApp</b>
                        <span>Talk directly with our senior team</span>
                    </div>
                    <span class="om-wa-active-pill"><i class="om-pulse-dot"></i> Quick replies</span>
                </div>
                <a href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener" class="om-wa-btn" id="homeWaDynamicLink">
                    <span class="om-wa-btn-lead">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                        <span>Message us on WhatsApp</span>
                    </span>
                </a>
            </div>

            <!-- Client FAQ Accordion -->
            <div class="ag-faq-mini">
                <details class="ag-faq-item" open>
                    <summary>Do I own the source code and brand files?</summary>
                    <p>Yes. Every custom website, app and design system comes with the full source code and commercial rights, so you&rsquo;re never locked in.</p>
                </details>
                <details class="ag-faq-item">
                    <summary>Can you customize your ready-made platforms for us?</summary>
                    <p>Yes. You can license a platform directly on Gumroad, or we can rebrand it, add features and connect your local payment and shipping services.</p>
                </details>
                <details class="ag-faq-item">
                    <summary>How do payments work for custom projects?</summary>
                    <p>We work in milestones. You review progress on a staging site at each stage and pay for a milestone once you&rsquo;re happy with it.</p>
                </details>
            </div>
        </div>

        <form id="form" class="om-contact-form rv r" action="<?= url('/contact') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="text" name="_hp_check" value="" style="display:none!important;" tabindex="-1" autocomplete="off" aria-hidden="true">

            <div class="ag-form-head">
                <strong>Request a proposal</strong>
                <span>We reply within 24 hours &bull; Your details stay private</span>
            </div>

            <label>Your name<input name="name" required autocomplete="name" placeholder="John Doe"></label>
            <label>Your email<input name="email" type="email" required autocomplete="email" placeholder="john@company.com"></label>
            <label>Phone or WhatsApp (optional)<input name="phone" type="tel" placeholder="+1 555 123 4567" autocomplete="tel"></label>
            <label>Service or Package Needed
                <select name="project_type" id="homeProjectTypeSelect">
                    <optgroup label="Agency Service Packages">
                        <option value="Custom Web Development">Custom web development</option>
                        <option value="Mobile App Development (Flutter & Dart)">Mobile app (iOS &amp; Android)</option>
                        <option value="Website Redesign & Modernization">Website redesign</option>
                        <option value="SaaS Product Engineering">SaaS product or web portal</option>
                        <option value="Search Engine Optimization (SEO)">SEO</option>
                        <option value="Social Media Marketing & Paid Ads">Social media &amp; paid ads</option>
                        <option value="Branding, Logo & Graphic Design">Branding &amp; logo design</option>
                        <option value="E-Commerce & Online Store Build">E-commerce store</option>
                    </optgroup>
                    <optgroup label="Package Tiers">
                        <?php foreach (($packagesByTrack ?? []) as $tPkgs): ?>
                            <?php foreach ($tPkgs as $pOpt):
                                $optVal = $pOpt['title'] . ' (' . $pOpt['price_label'] . ')';
                            ?>
                                <option value="<?= e($optVal) ?>"><?= e($optVal) ?></option>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </optgroup>
                    <optgroup label="Ready-Made Software (Buy or Customize)">
                        <option value="Customize Ready-Made Software For My Business">Customize a ready-made platform</option>
                        <option value="Cheetah Enterprise License ($99)">Cheetah Enterprise License ($99)</option>
                        <option value="Cheetah Lite License ($49)">Cheetah Lite License ($49)</option>
                        <option value="Cheezify Food & POS License ($59)">Cheezify Food &amp; POS License ($59)</option>
                    </optgroup>
                    <optgroup label="Free Audit">
                        <option value="Free Website, UX & SEO Teardown Audit">Free 15-minute website &amp; SEO review</option>
                    </optgroup>
                </select>
            </label>
            <label>Project details &amp; goals<textarea name="message" id="homeProjectMessage" rows="4" required placeholder="Tell us about your project, goals, target timeline, or any specific requirements..."></textarea></label>
            <button class="om-btn-gold" type="submit" style="width:100%;justify-content:center;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span>Send your brief</span>
            </button>
        </form>
    </div>
</section>

<script src="<?= asset_v('js/hero-omoway.js') ?>" defer></script>
