<?php
/** @var Core\View $this */
$this->layout('layouts/main');

$assetDir = dirname(__DIR__, 2) . '/public/assets/images/';
$imgFor = static function (string $slug) use ($assetDir) {
    $file = $slug === 'cheetah-enterprise' ? 'cheetah-showcase.jpg' : $slug . '-preview.jpg';
    return is_file($assetDir . $file) ? 'images/' . $file : null;
};

$waPhone = clean_phone_number(setting('whatsapp_number', setting('site_phone', '923002521199')), false);
$waMsg = urlencode("Hello! I would like to discuss a project or service package with Creatsify Digital Agency.");

$avgRating = number_format((float)($reviewStats['avg_rating'] ?? $reviewStats['average'] ?? 4.9), 1);
$totalReviews = max(12, (int)($reviewStats['total'] ?? 12));
?>

<!-- JSON-LD Rich Snippet Schema: ProfessionalService + AggregateRating -->
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ProfessionalService',
    'name' => setting('site_name', 'Creatsify') . ' Digital Agency',
    'url' => url('/'),
    'logo' => asset('brand/creatsify-logo-dark.png'),
    'description' => 'Full-service Web & Mobile App Development, Website Redesign, SaaS Engineering, Branding & Logo Design, Technical SEO, and Performance Marketing Agency.',
    'priceRange' => '$$',
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => (string)$avgRating,
        'bestRating' => '5',
        'worstRating' => '1',
        'reviewCount' => (string)$totalReviews,
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
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
                <span>Full-Spectrum Digital Agency &bull; Worldwide Desk</span>
            </div>

            <h1 class="om-hero-headline">
                <span class="om-head-top">High-Velocity Engineering,</span>
                <span class="om-head-bot">Distinctive Brands &amp; Scaled Growth</span>
            </h1>

            <p class="om-hero-sub">
                We craft high-performance web platforms, cross-platform Flutter mobile apps, conversion-engineered website redesigns, and profitable SEO &amp; paid acquisition engines for ambitious businesses worldwide.
            </p>

            <div class="om-cta-group">
                <a class="om-btn-gold" href="#contact">
                    <span class="om-btn-circle-arrow">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </span>
                    <span>Start Your Project</span>
                </a>
                <a class="om-btn-glass" href="#work">
                    <span class="om-btn-circle-arrow">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </span>
                    <span>Explore Case Studies</span>
                </a>
                <a class="om-hero-wa-link" href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener">
                    <span class="om-wa-icon-sm">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                    </span>
                    <span>Chat On WhatsApp (Instant)</span>
                </a>
            </div>

            <div class="om-proof-bar">
                <span class="om-proof-item"><i class="om-proof-check">&#9733;</i> <?= e($avgRating) ?>/5.0 Client Rating</span>
                <span class="om-proof-item"><i class="om-proof-check">&#10003;</i> 100% Source Code &amp; IP Ownership</span>
                <span class="om-proof-item"><i class="om-proof-check">&#10003;</i> Fixed Milestones &amp; Rapid Sprints</span>
                <span class="om-proof-item"><i class="om-proof-check">&#10003;</i> Direct Senior Technical Access</span>
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
                    <span class="om-browser-badge"><i class="om-live-dot"></i> Unified Studio</span>
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
                                <strong>Web &amp; Mobile App Engineering</strong>
                                <span class="ag-metric-pill emerald">99/100 Speed</span>
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
                                <strong>Brand Identity &amp; UI/UX Redesign</strong>
                                <span class="ag-metric-pill cyan">+184% Lift</span>
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
                                <strong>Technical SEO &amp; Paid Social Ads</strong>
                                <span class="ag-metric-pill gold">3.8x Avg ROAS</span>
                            </div>
                            <p>Google Top-3 SEO &bull; Meta FB/IG Ads &bull; Google PPC &bull; Funnels</p>
                        </div>
                    </div>

                    <!-- Live Agency KPI Strip inside Command Card -->
                    <div class="ag-command-kpis">
                        <div class="ag-kpi-box">
                            <span class="ag-kpi-val">3.8x</span>
                            <span class="ag-kpi-lbl">Avg. Client ROAS</span>
                        </div>
                        <div class="ag-kpi-box">
                            <span class="ag-kpi-val">99/100</span>
                            <span class="ag-kpi-lbl">Core Web Vitals</span>
                        </div>
                        <div class="ag-kpi-box">
                            <span class="ag-kpi-val"><?= e($avgRating) ?>&#9733;</span>
                            <span class="ag-kpi-lbl">Verified Rating</span>
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
                    <strong>4 Senior Divisions</strong>
                    <span>Code &bull; Mobile &bull; Brand &bull; Ads</span>
                </div>
            </div>

            <!-- Corner Floating Badge 2: Bottom Left - Verified Star Rating -->
            <a href="#reviews" class="om-float-card om-float-quality" style="text-decoration:none;">
                <div class="om-quality-top">
                    <span class="om-quality-step" style="color:#f59e0b;font-size:13px;letter-spacing:1px;">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                    <div class="om-quality-check">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </div>
                <strong class="om-quality-heading"><?= e($avgRating) ?> / 5.0 Rating</strong>
                <span class="om-quality-sub">Verified Reviews</span>
            </a>
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
                <span>Logo &amp; Brand Identity</span>
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
                <span>Logo &amp; Brand Identity</span>
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
            <span>Projects &amp; Campaigns Shipped</span>
        </div>
        <div class="om-fact-item">
            <b><?= e($avgRating) ?>&#9733;</b>
            <span>Verified Client Rating</span>
        </div>
        <div class="om-fact-item">
            <b data-n="15">15+</b>
            <span>Core Tech &amp; Ad Stacks</span>
        </div>
        <div class="om-fact-item">
            <b data-n="100">100%</b>
            <span>Full Code &amp; IP Ownership</span>
        </div>
    </div>
</div>

<!-- ======================================================================
     STAGE 3: THE 6 CORE AGENCY SERVICES BENTO GRID (#services)
     ====================================================================== -->
<section id="services" class="om-services-sec">
    <div class="wrap">
        <div class="om-sec-center">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> What We Build &amp; Scale</div>
            <h2 class="om-sec-heading">End-To-End Digital Engineering, Design &amp; Growth</h2>
            <p class="om-sec-sub">Stop juggling disconnected contractors. Our specialized divisions handle your entire digital ecosystem under one roof with zero handoff friction.</p>

            <!-- Interactive Service Category Filter Pills -->
            <div class="om-filter-bar ag-svc-filter" style="margin-top:24px;" role="tablist" aria-label="Filter agency services">
                <button type="button" class="om-filter-pill active" data-svc-filter="all">All Capabilities</button>
                <button type="button" class="om-filter-pill" data-svc-filter="engineering">Web &amp; Mobile Apps</button>
                <button type="button" class="om-filter-pill" data-svc-filter="creative">Branding &amp; Redesign</button>
                <button type="button" class="om-filter-pill" data-svc-filter="growth">SEO &amp; Paid Social Ads</button>
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
                <h3>Custom Web &amp; SaaS Development</h3>
                <p>High-velocity business platforms, custom admin panels, and multi-tenant web applications engineered for sub-second response times and complete security.</p>
                <div class="om-sc-chips">
                    <span>PHP 8.4</span><span>Node.js</span><span>Next.js</span><span>Tailwind</span><span>MySQL 8</span><span>REST APIs</span>
                </div>
                <a href="#contact" data-select-package="Custom Web Development" class="ag-svc-cta">
                    <span>Scope Web Project</span>
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
                <h3>Cross-Platform Mobile Apps</h3>
                <p>Silky-smooth 60fps native-speed iOS and Android mobile apps engineered in Flutter &amp; Dart with real-time GPS tracking, push alerts, and offline-first sync.</p>
                <div class="om-sc-chips">
                    <span>Flutter</span><span>Dart</span><span>iOS + Android</span><span>REST APIs</span><span>Push Alerts</span>
                </div>
                <a href="#contact" data-select-package="Mobile App Development (Flutter & Dart)" class="ag-svc-cta">
                    <span>Build Your App</span>
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
                <h3>Website Redesign &amp; CRO Overhaul</h3>
                <p>Transform slow, outdated, or low-converting websites into mobile-first conversion machines with 95+ Core Web Vitals and frictionless conversion flows.</p>
                <div class="om-sc-chips">
                    <span>UI/UX Overhaul</span><span>95+ Speed</span><span>Mobile-First</span><span>Figma Systems</span>
                </div>
                <a href="#contact" data-select-package="Website Redesign & Modernization" class="ag-svc-cta">
                    <span>Redesign My Site</span>
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
                <h3>Brand Identity &amp; Logo Design</h3>
                <p>Memorable 3D &amp; vector logos, comprehensive brand guideline books, design tokens, presentation pitch decks, and high-CTR marketing creative assets.</p>
                <div class="om-sc-chips">
                    <span>Master Logo</span><span>Brand Guidelines</span><span>3D Marks</span><span>Figma Tokens</span>
                </div>
                <a href="#contact" data-select-package="Branding, Logo & Graphic Design" class="ag-svc-cta">
                    <span>Craft Your Brand</span>
                    <span class="ag-svc-arrow"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></span>
                </a>
            </article>

            <!-- 5. Search Engine Optimization (SEO) -->
            <article class="ag-svc-bento-card rv" data-svc-cat="growth" style="--d:0.25s">
                <div class="ag-svc-top">
                    <div class="om-sc-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    <span class="ag-svc-badge">Organic Ranking</span>
                </div>
                <h3>Technical SEO &amp; Authority Silos</h3>
                <p>Technical schema architecture, site speed optimization, keyword content silos, and authority link strategies engineered to put your brand in Google Top 3.</p>
                <div class="om-sc-chips">
                    <span>Technical Schema</span><span>Keyword Silos</span><span>Speed 99</span><span>Local &amp; Global SEO</span>
                </div>
                <a href="#contact" data-select-package="Search Engine Optimization (SEO)" class="ag-svc-cta">
                    <span>Boost Search Rankings</span>
                    <span class="ag-svc-arrow"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
                </a>
            </article>

            <!-- 6. Social Media Marketing & Paid Ads -->
            <article class="ag-svc-bento-card rv" data-svc-cat="growth" style="--d:0.3s">
                <div class="ag-svc-top">
                    <div class="om-sc-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    </div>
                    <span class="ag-svc-badge">High-ROAS Ads</span>
                </div>
                <h3>Paid Social Ads &amp; Performance Funnels</h3>
                <p>Data-driven paid customer acquisition across Meta (Facebook &amp; Instagram), Google Ads, and TikTok with high-CTR creative testing and conversion tracking.</p>
                <div class="om-sc-chips">
                    <span>Meta FB/IG Ads</span><span>Google PPC</span><span>TikTok Ads</span><span>ROAS Scaling</span>
                </div>
                <a href="#contact" data-select-package="Social Media Marketing & Paid Ads" class="ag-svc-cta">
                    <span>Scale Ad Campaigns</span>
                    <span class="ag-svc-arrow"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></span>
                </a>
            </article>
        </div>

        <!-- Highlight Banner: Ready-Made Software Integration -->
        <div class="ag-services-footer-banner rv s" style="margin-top:36px;">
            <div class="ag-sfb-content">
                <div class="om-sec-tag dark" style="margin-bottom:6px;"><span class="om-sec-dot"></span> Ready-To-Ship Solutions</div>
                <strong>Need A Proven Platform Ready In Days?</strong>
                <p>Explore our battle-tested courier, logistics, and POS platforms with 100% unlocked source code, or let our team customize them for you.</p>
            </div>
            <a href="#products" class="om-btn-gold">
                <span class="om-btn-circle-arrow">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                </span>
                <span>View Ready-Made Platforms</span>
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
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Proven Execution</div>
            <h2 class="om-sec-heading">Selected Work &amp; Measurable Client Impact</h2>
            <p class="om-sec-sub">Every project we ship is engineered for speed, visual authority, and measurable revenue growth across web, mobile, brand, and paid acquisition.</p>

            <!-- Interactive Work Filter Pills -->
            <div class="om-filter-bar ag-work-filter" style="margin-top:24px;" role="tablist" aria-label="Filter case studies">
                <button type="button" class="om-filter-pill active" data-work-filter="all">All Case Studies</button>
                <button type="button" class="om-filter-pill" data-work-filter="web-saas">Web &amp; SaaS</button>
                <button type="button" class="om-filter-pill" data-work-filter="mobile">Flutter Apps</button>
                <button type="button" class="om-filter-pill" data-work-filter="branding">Redesign &amp; Brand</button>
                <button type="button" class="om-filter-pill" data-work-filter="growth">SEO &amp; Ads</button>
            </div>
        </div>

        <div class="ag-case-grid" style="margin-top:40px;">
            <!-- Case Study 1: Cheetah Courier & Logistics Ecosystem -->
            <article class="ag-case-card rv" data-work-cat="web-saas mobile" style="--d:0.05s">
                <div class="ag-case-visual grad-cyan">
                    <div class="ag-case-top-badges">
                        <span class="ag-case-cat">SaaS &amp; Flutter Mobile Apps</span>
                        <span class="ag-case-receipt">300+ Couriers Powered</span>
                    </div>
                    <div class="ag-case-preview-box">
                        <div class="ag-cp-metric">99.98%<span>Uptime SLA</span></div>
                        <div class="ag-cp-metric">45k+<span>Daily Parcels</span></div>
                        <div class="ag-cp-metric">60fps<span>Flutter Speed</span></div>
                    </div>
                </div>
                <div class="ag-case-body">
                    <h3>Cheetah Courier &amp; Multi-Branch Logistics Ecosystem</h3>
                    <p>Enterprise logistics backbone with 7 role portals, driver &amp; picker Flutter mobile apps, real-time rider GPS tracking, and automated thermal barcode printing.</p>
                    <div class="om-prod-chips">
                        <span>PHP 8.4</span><span>Flutter</span><span>MySQL 8</span><span>REST API</span>
                    </div>
                    <div class="ag-case-actions">
                        <a href="<?= url('/portfolio/cheetah-enterprise') ?>" class="ag-case-btn-primary">
                            <span class="ag-case-btn-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
                            <span>Explore Case Study</span>
                        </a>
                        <a href="https://cheetah.ayan24.me" target="_blank" rel="noopener noreferrer" class="ag-case-btn-secondary">
                            <span>Live Demo</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        </a>
                    </div>
                </div>
            </article>

            <!-- Case Study 2: Apex Retail Storefront Redesign -->
            <article class="ag-case-card rv" data-work-cat="branding web-saas" style="--d:0.1s">
                <div class="ag-case-visual grad-cobalt">
                    <div class="ag-case-top-badges">
                        <span class="ag-case-cat">Website Redesign &amp; CRO</span>
                        <span class="ag-case-receipt">+184% Conversion Lift</span>
                    </div>
                    <div class="ag-case-preview-box">
                        <div class="ag-cp-metric">0.8s<span>Page Load</span></div>
                        <div class="ag-cp-metric">99<span>Lighthouse Score</span></div>
                        <div class="ag-cp-metric">2.8x<span>Mobile Revenue</span></div>
                    </div>
                </div>
                <div class="ag-case-body">
                    <h3>Apex Retail — High-Speed Storefront Redesign</h3>
                    <p>Re-engineered a slow legacy e-commerce site into a mobile-first, conversion-optimized experience with streamlined 1-step checkout and sub-second load times.</p>
                    <div class="om-prod-chips">
                        <span>Next.js</span><span>Tailwind CSS</span><span>UI/UX Redesign</span><span>CRO Silos</span>
                    </div>
                    <div class="ag-case-actions">
                        <a href="<?= url('/contact?package=Website+Redesign#inquiry-form') ?>" class="ag-case-btn-primary">
                            <span class="ag-case-btn-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg></span>
                            <span>Request Similar Redesign</span>
                        </a>
                    </div>
                </div>
            </article>

            <!-- Case Study 3: Cross-Platform Flutter Mobile Apps -->
            <article class="ag-case-card rv" data-work-cat="mobile web-saas" style="--d:0.15s">
                <div class="ag-case-visual grad-emerald">
                    <div class="ag-case-top-badges">
                        <span class="ag-case-cat">Flutter Mobile App</span>
                        <span class="ag-case-receipt">4.9&#9733; App Store Rating</span>
                    </div>
                    <div class="ag-case-preview-box">
                        <div class="ag-cp-metric">60fps<span>Fluid Performance</span></div>
                        <div class="ag-cp-metric">iOS+Android<span>Single Codebase</span></div>
                        <div class="ag-cp-metric">Offline-First<span>State Sync</span></div>
                    </div>
                </div>
                <div class="ag-case-body">
                    <h3>Lumina Fleet Companion — Cross-Platform Mobile Suite</h3>
                    <p>Engineered native-speed iOS and Android mobile applications featuring offline-first state synchronization, digital signature capture, and cloud API integration.</p>
                    <div class="om-prod-chips">
                        <span>Flutter</span><span>Dart</span><span>Node.js API</span><span>Cloud Sync</span>
                    </div>
                    <div class="ag-case-actions">
                        <a href="<?= url('/contact?package=Mobile+App+Development#inquiry-form') ?>" class="ag-case-btn-primary">
                            <span class="ag-case-btn-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg></span>
                            <span>Build An App Like This</span>
                        </a>
                    </div>
                </div>
            </article>

            <!-- Case Study 4: 3D Brand System & Omnichannel Paid Ads -->
            <article class="ag-case-card rv" data-work-cat="branding growth" style="--d:0.2s">
                <div class="ag-case-visual grad-purple">
                    <div class="ag-case-top-badges">
                        <span class="ag-case-cat">Brand Identity &amp; Paid Ads</span>
                        <span class="ag-case-receipt">3.8x Meta &amp; Google ROAS</span>
                    </div>
                    <div class="ag-case-preview-box">
                        <div class="ag-cp-metric">3D + Vector<span>Brand Suite</span></div>
                        <div class="ag-cp-metric">+215%<span>Organic Search</span></div>
                        <div class="ag-cp-metric">-42%<span>Cost Per Lead</span></div>
                    </div>
                </div>
                <div class="ag-case-body">
                    <h3>Kinetix Cloud — Complete Brand Identity &amp; Performance Ad Funnel</h3>
                    <p>Designed a distinctive 3D &amp; vector logo system, dark/light UI tokens, and executed high-CTR Meta and Google PPC ad campaigns to scale qualified inbound leads.</p>
                    <div class="om-prod-chips">
                        <span>Logo Design</span><span>Brand Book</span><span>Meta Ads</span><span>Google PPC</span>
                    </div>
                    <div class="ag-case-actions">
                        <a href="<?= url('/contact?package=Branding+And+Growth#inquiry-form') ?>" class="ag-case-btn-primary">
                            <span class="ag-case-btn-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></span>
                            <span>Build Brand &amp; Ad Engine</span>
                        </a>
                    </div>
                </div>
            </article>
        </div>

        <div style="text-align:center;margin-top:36px;">
            <a class="om-btn-glass" href="<?= url('/portfolio') ?>">
                <span class="om-btn-circle-arrow">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </span>
                <span>View All Case Studies &amp; Software Systems</span>
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
            <div class="om-sec-tag"><span class="om-sec-dot"></span> The Creatsify Difference</div>
            <h2 class="om-sec-heading">The Unfair Advantage Of One Unified Powerhouse</h2>
            <p class="om-sec-sub">Why waste months juggling disconnected freelancers when your developers, designers, and growth marketers can engineer your success together.</p>
        </div>

        <!-- 4 Core Advantages Cards -->
        <div class="ag-strength-grid" style="margin-top:40px;">
            <div class="ag-strength-card rv" style="--d:0.05s">
                <span class="ag-str-num">01</span>
                <h3>Code + Design + Ads Under One Roof</h3>
                <p>Your PHP/Next.js engineers, Flutter developers, brand designers, and SEO/ad strategists work as one unified team—so your code, design, and marketing amplify each other.</p>
            </div>
            <div class="ag-strength-card rv" style="--d:0.1s">
                <span class="ag-str-num">02</span>
                <h3>Pure Handcrafted Speed &bull; Zero Bloat</h3>
                <p>No sluggish WordPress templates or bloated themes. Every web platform, mobile app, and portal is engineered for sub-second speed, clean architecture, and 95+ Core Web Vitals.</p>
            </div>
            <div class="ag-strength-card rv" style="--d:0.15s">
                <span class="ag-str-num">03</span>
                <h3>Direct Senior Technical Access</h3>
                <p>No non-technical account managers playing telephone. You communicate directly with senior developers and creative strategists who actually build your product.</p>
            </div>
            <div class="ag-strength-card rv" style="--d:0.2s">
                <span class="ag-str-num">04</span>
                <h3>100% IP &amp; Codebase Ownership</h3>
                <p>Zero vendor lock-in. You receive 100% unlocked source code, complete commercial IP rights, transparent milestone pricing, and live weekly staging previews.</p>
            </div>
        </div>

        <!-- Comparison Table: Creatsify vs Traditional Agencies vs Freelancers -->
        <div class="ag-compare-wrap rv s" style="margin-top:44px;">
            <div class="ag-compare-table-scroll">
                <table class="ag-compare-table">
                    <thead>
                        <tr>
                            <th>Comparison Factor</th>
                            <th class="highlight-col">
                                <span class="ag-cmp-badge">UNIFIED POWERHOUSE</span>
                                Creatsify Digital Agency
                            </th>
                            <th>Traditional Big Agencies</th>
                            <th>Random Freelancers</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><b>Scope of Execution</b></td>
                            <td class="highlight-col">&#10003; <b>Web + Mobile + Brand + SEO &amp; Ads</b></td>
                            <td>Isolated departments, slow handoffs</td>
                            <td>&#10007; Single skill only (you manage 4 people)</td>
                        </tr>
                        <tr>
                            <td><b>Engineering Craft</b></td>
                            <td class="highlight-col">&#10003; <b>100% Custom, 95+ Speed &amp; CRO Focused</b></td>
                            <td>Frequently outsourced to juniors</td>
                            <td>&#10007; Bloated templates &amp; builder plugins</td>
                        </tr>
                        <tr>
                            <td><b>Sprint Delivery</b></td>
                            <td class="highlight-col">&#10003; <b>Rapid Agile Sprints (Days to Weeks)</b></td>
                            <td>&#10007; 3 to 6 months of endless meetings</td>
                            <td>&#10007; Unpredictable delays &amp; ghosting risk</td>
                        </tr>
                        <tr>
                            <td><b>Pricing Transparency</b></td>
                            <td class="highlight-col">&#10003; <b>Transparent Packages &amp; Fixed Milestones</b></td>
                            <td>&#10007; $15k–$50k bloated monthly retainers</td>
                            <td>Hidden fees and scope creep charges</td>
                        </tr>
                        <tr>
                            <td><b>Code &amp; IP Rights</b></td>
                            <td class="highlight-col">&#10003; <b>100% Full Unlocked Source Code Ownership</b></td>
                            <td>Proprietary agency lock-in</td>
                            <td>Inconsistent documentation &amp; handoff</td>
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
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Radical Pricing Transparency</div>
            <h2 class="om-sec-heading">Tailored Agency Packages For Every Stage</h2>
            <p class="om-sec-sub">Choose a proven sprint package below to kickoff immediately, or use our interactive Scope Estimator to calculate an instant custom bundle.</p>

            <!-- Package Track Switcher Tabs -->
            <div class="om-filter-bar ag-pkg-tabs" style="margin-top:26px;" role="tablist" aria-label="Service Package Tracks">
                <button type="button" class="om-filter-pill active" data-pkg-tab="dev">01. Web, App &amp; SaaS Dev</button>
                <button type="button" class="om-filter-pill" data-pkg-tab="design">02. Branding, Logo &amp; Redesign</button>
                <button type="button" class="om-filter-pill" data-pkg-tab="growth">03. SEO &amp; Paid Social Ads</button>
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
                    <span><?= e($pkg['cta_text'] ?: 'Select Package') ?></span>
                    <span class="om-btn-circle-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></span>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

        <div style="text-align:center;margin-top:28px;">
            <a href="<?= url('/packages') ?>" class="om-btn-glass">
                <span class="om-btn-circle-arrow"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg></span>
                <span>View Complete Package Specs &amp; Deliverables</span>
            </a>
        </div>

        <!-- Interactive Custom Scope & Bundle Estimator -->
        <div class="ag-scope-estimator rv s" id="scopeEstimator">
            <div class="ag-se-left">
                <div class="om-sec-tag" style="margin-bottom:8px;"><span class="om-sec-dot"></span> Interactive Custom Bundle Builder</div>
                <h3>Need A Custom Multi-Service Bundle?</h3>
                <p>Select the services you need below to calculate an instant project estimate with an automatic <b>15% Multi-Service Agency Bundle Discount</b>.</p>
                <div class="ag-se-options">
                    <label class="ag-se-check">
                        <input type="checkbox" value="Custom Website / Redesign" data-price="350" data-days="7" checked>
                        <span class="ag-se-label"><span>Custom Web Dev / Redesign</span> <b>+$350</b></span>
                    </label>
                    <label class="ag-se-check">
                        <input type="checkbox" value="Flutter Mobile App (iOS & Android)" data-price="750" data-days="14">
                        <span class="ag-se-label"><span>Flutter Mobile App</span> <b>+$750</b></span>
                    </label>
                    <label class="ag-se-check">
                        <input type="checkbox" value="Logo & Complete Brand Identity" data-price="150" data-days="3" checked>
                        <span class="ag-se-label"><span>Logo &amp; Brand Identity</span> <b>+$150</b></span>
                    </label>
                    <label class="ag-se-check">
                        <input type="checkbox" value="Technical & On-Page SEO" data-price="220" data-days="5">
                        <span class="ag-se-label"><span>Technical SEO Setup</span> <b>+$220</b></span>
                    </label>
                    <label class="ag-se-check">
                        <input type="checkbox" value="Meta & Google Paid Ads Setup" data-price="250" data-days="4">
                        <span class="ag-se-label"><span>Social Media &amp; Paid Ads</span> <b>+$250</b></span>
                    </label>
                </div>
            </div>
            <div class="ag-se-right">
                <span class="ag-se-badge">15% BUNDLE SAVING APPLIED</span>
                <div class="ag-se-total-label">Estimated Investment</div>
                <div class="ag-se-total-price" id="seTotalPrice">$425</div>
                <div class="ag-se-timeline" id="seTimeline">Est. Delivery: ~10 Days</div>
                <button type="button" class="om-btn-gold" id="seLockBtn" style="width:100%;justify-content:center;border:none;cursor:pointer;margin-top:14px;">
                    <span class="om-btn-circle-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></span>
                    <span>Lock In This Custom Bundle</span>
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
            <div class="om-sec-tag dark"><span class="om-sec-dot"></span> Ready-To-Ship Platforms</div>
            <h2 class="om-sec-heading light">Want To Launch In Days? Pick A Ready-Made Platform</h2>
            <p class="om-sec-sub light">
                In addition to custom agency builds, we engineer battle-tested commercial software. <b>Buy a direct white-label license on Gumroad</b> with 100% unlocked source code, OR <b>hire our team to customize it</b> for your business.
            </p>
        </div>

        <div class="om-prod-grid">
            <?php
            $prodList = array_slice($projects ?? [], 0, 3);
            foreach ($prodList as $i => $pr):
                $shot = $imgFor($pr['slug']);
                $tech = array_filter(array_map('trim', explode(',', (string)($pr['tech_stack'] ?? ''))));
                $live = $pr['live_url'] ?? '';
                $hasLive = $live && $live !== '#';
                $priceStr = !empty($pr['price']) ? $pr['price'] : '$99';
            ?>
                <article class="om-prod-card rv" style="--d:<?= $i * 0.12 ?>s">
                    <div class="om-prod-media-frame">
                        <div class="om-prod-bar">
                            <div class="om-prod-dots">
                                <span class="dot d-red"></span>
                                <span class="dot d-yellow"></span>
                                <span class="dot d-green"></span>
                            </div>
                            <span class="om-prod-badge-price">Direct: <?= e($priceStr) ?> &bull; Or Customize</span>
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
                                    <span class="om-prod-cover-badge">Ready-to-Deploy Codebase</span>
                                    <b><?= e($pr['title']) ?> Architecture</b>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="om-prod-body">
                        <div class="om-prod-status-row">
                            <span class="om-prod-pill">Ready to Ship</span>
                            <span class="om-prod-rights">&#10003; 100% Unlocked Source Code</span>
                        </div>
                        <h3 class="om-prod-title"><?= e($pr['title']) ?></h3>
                        <p class="om-prod-desc"><?= e($pr['summary'] ?? '') ?></p>
                        <div class="om-prod-chips">
                            <?php foreach ($tech as $t): ?><span><?= e($t) ?></span><?php endforeach; ?>
                        </div>
                        <div class="om-prod-actions">
                            <?php if (!empty($pr['gumroad_url'])): ?>
                                <a class="om-btn-gold" href="<?= e($pr['gumroad_url']) ?>" target="_blank" rel="noopener noreferrer">
                                    <span class="om-btn-circle-arrow">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    </span>
                                    <span>Buy On Gumroad (<?= e($priceStr) ?>)</span>
                                </a>
                            <?php endif; ?>
                            <a class="om-btn-glass light" href="#contact" data-select-package="Customize <?= e($pr['title']) ?> For My Business">
                                <span>Customize For Me &#9998;</span>
                            </a>
                            <?php if ($hasLive): ?>
                                <a class="om-prod-link" href="<?= e($live) ?>" target="_blank" rel="noopener noreferrer">
                                    <span>Live Demo</span>
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                </a>
                            <?php else: ?>
                                <a class="om-prod-link" href="<?= url('/portfolio/' . $pr['slug']) ?>">
                                    <span>Specs</span>
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center;margin-top:40px;">
            <a href="<?= url('/software') ?>" class="om-btn-glass light" style="display:inline-flex;padding:12px 28px;">
                <span class="om-btn-circle-arrow"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg></span>
                <span>Browse All Ready-Made Products &amp; Platforms</span>
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
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Frictionless Execution</div>
            <h2 class="om-sec-heading">From Concept To Market Dominance In 4 Steps</h2>
            <p class="om-sec-sub">Clear sprint milestones, transparent staging links, and zero guesswork from day one.</p>
        </div>
        <div class="ag-workflow-4grid">
            <div class="om-how-card rv" style="--d:0s">
                <span class="om-how-step-num">01</span>
                <h3>Strategy &amp; Fixed Scope</h3>
                <p>We audit your project requirements, establish concrete technical and CRO targets, and lock in a fixed milestone sprint timeline.</p>
            </div>
            <div class="om-how-card rv" style="--d:0.08s">
                <span class="om-how-step-num">02</span>
                <h3>Brand &amp; UI/UX Craft</h3>
                <p>Our creative division engineers your logo, design tokens, and interactive conversion layouts in Figma before heavy coding starts.</p>
            </div>
            <div class="om-how-card rv" style="--d:0.16s">
                <span class="om-how-step-num">03</span>
                <h3>High-Velocity Engineering</h3>
                <p>We build your web app, Flutter mobile app, or portal on a private staging server so you can test live progress each week.</p>
            </div>
            <div class="om-how-card rv" style="--d:0.24s">
                <span class="om-how-step-num">04</span>
                <h3>Launch, SEO &amp; Ad Scale</h3>
                <p>We deploy to your production server, submit technical schema to Google, and launch profitable search and social ad campaigns.</p>
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
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Verified Client Feedback</div>
            <h2 class="om-sec-heading">Trusted By Ambitious Brands Worldwide</h2>
            <p class="om-sec-sub">Real feedback and verified outcomes from founders and teams who partnered with Creatsify for engineering, branding, SEO, and paid social ads.</p>
            
            <div class="ag-rating-summary-pill" style="margin:20px auto 36px;display:inline-flex;">
                <div class="ag-rsp-score"><?= e($avgRating) ?></div>
                <div class="ag-rsp-meta" style="text-align:left;">
                    <div class="ag-rsp-stars" aria-label="5 out of 5 stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    <span>Based on <?= (int)$totalReviews ?>+ Verified Reviews</span>
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

        <div class="ag-reviews-grid">
            <?php foreach (($reviews ?? []) as $idx => $rev):
                $stars = max(1, min(5, (int)($rev['rating'] ?? 5)));
                $initials = strtoupper(substr(trim((string)($rev['client_name'] ?? 'C')), 0, 1));
            ?>
                <article class="ag-review-card rv" style="--d:<?= ($idx % 3) * 0.08 ?>s">
                    <div class="ag-rc-top">
                        <div class="ag-rc-stars" aria-label="<?= $stars ?> out of 5 stars">
                            <?= str_repeat('&#9733;', $stars) ?><?= str_repeat('&#9734;', 5 - $stars) ?>
                        </div>
                        <?php if (!empty($rev['outcome_metric'])): ?>
                            <span class="ag-rc-outcome"><?= e($rev['outcome_metric']) ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 class="ag-rc-title">“<?= e($rev['review_title']) ?>”</h3>
                    <p class="ag-rc-text"><?= e($rev['review_text']) ?></p>
                    <div class="ag-rc-footer">
                        <div class="ag-rc-avatar"><?= e($initials) ?></div>
                        <div class="ag-rc-author">
                            <strong><?= e($rev['client_name']) ?></strong>
                            <span><?= e($rev['client_role'] ?: 'Client') ?><?= !empty($rev['company_name']) ? ' &bull; ' . e($rev['company_name']) : '' ?></span>
                        </div>
                        <span class="ag-rc-service-tag"><?= e($rev['service_category']) ?></span>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center;margin-top:36px;display:flex;justify-content:center;gap:14px;flex-wrap:wrap;">
            <a class="om-btn-gold" href="<?= url('/reviews') ?>">
                <span class="om-btn-circle-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></span>
                <span>Read All <?= (int)$totalReviews ?>+ Verified Reviews</span>
            </a>
            <a class="om-btn-glass" href="<?= url('/reviews#submit-review') ?>">
                <span class="om-btn-circle-plus">&#9733;</span>
                <span>Worked With Us? Submit Your Review</span>
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
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Start Your Project Or Package</div>
            <h2 class="om-sec-heading">Tell Us What You Want To Build Or Scale</h2>
            <p class="om-sec-sub">Select an agency sprint package, request custom tailoring for our ready-made platforms, or claim a free website &amp; SEO teardown audit. For instant communication, reach our senior team directly on WhatsApp.</p>

            <div class="om-whatsapp-card">
                <div class="om-wa-header">
                    <span class="om-wa-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                    </span>
                    <div class="om-wa-header-info">
                        <b>Direct WhatsApp Strategy Desk</b>
                        <span>Instant response from our Senior Engineering &amp; Growth Leads</span>
                    </div>
                    <span class="om-wa-active-pill"><i class="om-pulse-dot"></i> Online Now</span>
                </div>
                <a href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener" class="om-wa-btn" id="homeWaDynamicLink">
                    <span class="om-wa-btn-lead">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                        <span>Message Our Agency Desk On WhatsApp</span>
                    </span>
                </a>
            </div>

            <!-- Client FAQ Accordion -->
            <div class="ag-faq-mini">
                <details class="ag-faq-item" open>
                    <summary>Do I get 100% ownership of the source code and brand files?</summary>
                    <p>Yes. Every custom website, Flutter mobile app, design system, and white-label license comes with 100% unlocked source code and full commercial IP rights.</p>
                </details>
                <details class="ag-faq-item">
                    <summary>Can you customize your ready-made software for our business?</summary>
                    <p>Absolutely. You can buy a direct Gumroad license ($49–$99), or our engineering team can rebrand, add custom features, and integrate your local payment &amp; shipping APIs.</p>
                </details>
                <details class="ag-faq-item">
                    <summary>How do payments work for custom agency builds?</summary>
                    <p>We operate on milestone-based payments. You review and approve live progress on our staging server at each sprint before releasing the milestone payment.</p>
                </details>
            </div>
        </div>

        <form id="form" class="om-contact-form rv r" action="<?= url('/contact') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="text" name="_hp_check" value="" style="display:none!important;" tabindex="-1" autocomplete="off" aria-hidden="true">

            <div class="ag-form-head">
                <strong>Request A Proposal Or Lock In A Package</strong>
                <span>Response time: Under 2 hours &bull; 100% Confidential</span>
            </div>

            <label>Your Name<input name="name" required autocomplete="name" placeholder="John Doe"></label>
            <label>Your Email<input name="email" type="email" required autocomplete="email" placeholder="john@company.com"></label>
            <label>Phone / WhatsApp (Optional)<input name="phone" type="tel" placeholder="+1 555 123 4567" autocomplete="tel"></label>
            <label>Service or Package Needed
                <select name="project_type" id="homeProjectTypeSelect">
                    <optgroup label="Agency Service Packages">
                        <option value="Custom Web Development">Custom Web Development (PHP / Node.js / Next.js / Tailwind)</option>
                        <option value="Mobile App Development (Flutter & Dart)">Mobile App Development (Flutter &amp; Dart — iOS &amp; Android)</option>
                        <option value="Website Redesign & Modernization">Website Redesign &amp; Speed/CRO Modernization</option>
                        <option value="SaaS Product Engineering">SaaS Product &amp; Multi-Role Portal Engineering</option>
                        <option value="Search Engine Optimization (SEO)">Search Engine Optimization (Technical &amp; On-Page SEO)</option>
                        <option value="Social Media Marketing & Paid Ads">Social Media Marketing &amp; Paid Ads (Meta / Google / TikTok)</option>
                        <option value="Branding, Logo & Graphic Design">Branding, Custom Logo &amp; Graphic Design</option>
                        <option value="E-Commerce & Online Store Build">E-Commerce &amp; Online Store Build (Custom / WooCommerce)</option>
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
                        <option value="Customize Ready-Made Software For My Business">Customize a Ready-Made Software Product For My Business</option>
                        <option value="Cheetah Enterprise License ($99)">Cheetah Enterprise License ($99)</option>
                        <option value="Cheetah Lite License ($49)">Cheetah Lite License ($49)</option>
                        <option value="Cheezify Food & POS License ($59)">Cheezify Food &amp; POS License ($59)</option>
                    </optgroup>
                    <optgroup label="Free Audit">
                        <option value="Free Website, UX & SEO Teardown Audit">Free 15-Min Website, UX &amp; SEO Teardown Audit</option>
                    </optgroup>
                </select>
            </label>
            <label>Project Details &amp; Goals<textarea name="message" id="homeProjectMessage" rows="4" required placeholder="Tell us about your project, goals, target timeline, or any specific requirements..."></textarea></label>
            <button class="om-btn-gold" type="submit" style="width:100%;justify-content:center;">
                <span class="om-btn-circle-arrow"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></span>
                <span>Send Project Brief</span>
            </button>
        </form>
    </div>
</section>

<script src="<?= asset_v('js/hero-omoway.js') ?>" defer></script>
