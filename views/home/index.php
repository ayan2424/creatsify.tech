<?php
/** @var Core\View $this */
$this->layout('layouts/main');

$hero = $sections['hero'] ?? [];
$heroMeta = $hero['metadata'] ?? [];
$rotatingWords = $heroMeta['rotating_words'] ?? ['couriers', 'online stores', 'local brands', 'startups'];
$heroChips = $heroMeta['chips'] ?? ['7 role portals in Cheetah', 'Direct software licensing', '3 commercial products'];

$cheetah = $sections['cheetah_showcase'] ?? [];
$cheetahMeta = $cheetah['metadata'] ?? [];
$tracker = $cheetahMeta['tracker'] ?? [
    'code' => 'CH-000007',
    'from' => 'Origin hub',
    'to' => 'Customer',
    'steps' => ['Shipment created', 'Linehaul dispatched', 'Out for delivery', 'Delivered ePOD signed']
];

$about = $sections['about_founder'] ?? [];
$aboutMeta = $about['metadata'] ?? [];
$aboutHighlights = $aboutMeta['highlights'] ?? [
    'Full-stack web' => 'PHP, Laravel, Python, React, MySQL',
    'Mobile and desktop' => 'Flutter apps and an Electron browser',
    'WordPress and e-commerce' => 'Elementor and WooCommerce stores',
    'Education' => 'Distinction in the first semester at Aptech'
];

$assetDir = dirname(__DIR__, 2) . '/public/assets/images/';
// Screenshot lookup: add public/assets/images/<project-slug>-preview.jpg and it appears automatically
$imgFor = static function (string $slug) use ($assetDir) {
    $file = $slug === 'cheetah-enterprise' ? 'cheetah-showcase.jpg' : $slug . '-preview.jpg';
    return is_file($assetDir . $file) ? 'images/' . $file : null;
};

$projectsJson = array_map(function ($p) use ($imgFor) {
    $tech = array_map('trim', explode(',', $p['tech_stack'] ?: 'PHP, MySQL'));
    $links = [];
    if (!empty($p['live_url']) && $p['live_url'] !== '#') {
        $links[] = ['Live demo', $p['live_url']];
    }
    $links[] = ['Case study', url('/portfolio/' . $p['slug'])];
    if (!empty($p['gumroad_url'])) {
        $priceTag = !empty($p['price']) ? ' (' . $p['price'] . ')' : '';
        $links[] = ['Buy on Gumroad' . $priceTag, $p['gumroad_url']];
    }
    
    $img = $p['image_url'] ?? '';
    if (empty($img) || str_contains($img, 'creatsify-icon.png')) {
        $f = $imgFor($p['slug']);
        $img = asset($f ?: 'images/hero-artwork.jpg');
    } else {
        $img = asset($img);
    }

    return [
        $p['title'],
        $p['category'] ?: 'p',
        $p['status'] ?: 'Shipped',
        $p['summary'] ?: $p['description'],
        $tech,
        $img,
        $links
    ];
}, $projects ?? []);
?>

<script>
    window.CREATSIFY_PROJECTS = <?= json_encode($projectsJson) ?>;
</script>

<section class="om-hero" id="hero">
    <div class="wrap om-hero-wrap">
        <!-- Left Side: Confident, Clean Headline & Pill CTAs (Omoway Layout) -->
        <div class="om-hero-content hf">
            <div class="om-hero-tag">
                <span class="om-tag-beacon"></span>
                <span>On-demand software &amp; enterprise systems</span>
            </div>
            
            <h1 class="om-hero-headline">
                <span class="om-head-top">Awesome Software</span>
                <span class="om-head-bot">For Your Business<span class="om-head-dot">.</span></span>
            </h1>
            
            <p class="om-hero-sub">
                We engineer and license battle-tested courier logistics backbones, multi-vendor fleet systems, and pure PHP 8.4 MVC store engines with 100% full source code, white-label rights, and direct co-founder architecture.
            </p>
            
            <div class="om-cta-group">
                <a class="om-btn-gold" href="#products">
                    <span>Get Started Now</span>
                    <span class="om-btn-circle-arrow">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                </a>
                <a class="om-btn-glass" href="#about">
                    <span>Talk to Co-Founders</span>
                    <span class="om-btn-circle-plus">+</span>
                </a>
            </div>
            
            <div class="om-proof-bar">
                <span class="om-proof-item"><i class="om-proof-check">&#10003;</i> 100% Unlocked Code</span>
                <span class="om-proof-item"><i class="om-proof-check">&#10003;</i> Pure PHP 8.4 MVC</span>
                <span class="om-proof-item"><i class="om-proof-check">&#10003;</i> 3 Live Demos Online</span>
                <span class="om-proof-item"><i class="om-proof-check">&#10003;</i> Direct Co-Founder Setup</span>
            </div>
        </div>

        <!-- Right Side: The Omoway Signature Floating Layered Stage -->
        <div class="om-hero-visual-stage hf" style="--d:.3s" id="omVisualStage">
            <!-- Ambient Stage Glow -->
            <div class="om-stage-glow" aria-hidden="true"></div>

            <!-- Centerpiece Card: Cheetah Enterprise Platform -->
            <div class="om-centerpiece-card">
                <div class="om-card-browser-bar">
                    <div class="om-browser-dots">
                        <span class="dot d-red"></span>
                        <span class="dot d-yellow"></span>
                        <span class="dot d-green"></span>
                    </div>
                    <div class="om-browser-url">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <span>cheetah.ayan24.me</span>
                    </div>
                    <span class="om-browser-badge"><i class="om-live-dot"></i> Live v2.4</span>
                </div>
                <div class="om-card-media">
                    <img src="<?= asset('images/cheetah-showcase.jpg') ?>" alt="Cheetah Enterprise Logistics Platform" class="om-showcase-img" loading="eager">
                    <div class="om-media-overlay">
                        <div class="om-media-tag">
                            <span class="om-tag-brand">Cheetah Enterprise</span>
                            <span class="om-tag-desc">7 Role Portals &bull; Live GPS Tracking</span>
                        </div>
                        <div class="om-media-price">$99</div>
                    </div>
                </div>
            </div>

            <!-- Floating Card 1: Top Left - Obsidian Metric & Animated Sparkline Bar Chart -->
            <div class="om-float-card om-float-stats">
                <div class="om-stat-header">
                    <div class="om-stat-num">99<span class="om-stat-pct">%</span></div>
                    <span class="om-stat-pill">Velocity</span>
                </div>
                <div class="om-stat-desc">Peak Execution Velocity</div>
                <div class="om-bar-chart" aria-label="Performance velocity chart">
                    <div class="om-chart-bar" style="--bh: 42%; --delay: 0.1s;"></div>
                    <div class="om-chart-bar" style="--bh: 72%; --delay: 0.2s;"></div>
                    <div class="om-chart-bar" style="--bh: 98%; --delay: 0.3s;"></div>
                    <div class="om-chart-bar" style="--bh: 64%; --delay: 0.4s;"></div>
                    <div class="om-chart-bar" style="--bh: 88%; --delay: 0.5s;"></div>
                    <div class="om-chart-bar" style="--bh: 100%; --delay: 0.6s;"></div>
                </div>
            </div>

            <!-- Floating Card 2: Bottom Left - Decided Quality & Full Source Code -->
            <div class="om-float-card om-float-quality">
                <div class="om-quality-top">
                    <span class="om-quality-step">01</span>
                    <div class="om-quality-check">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </div>
                <strong class="om-quality-heading">100% Source Code</strong>
                <span class="om-quality-sub">Full White-Label Rights</span>
            </div>

            <!-- Floating Card 3: Bottom Right - Live Demo Pill -->
            <a href="https://cheetah.ayan24.me" target="_blank" rel="noopener noreferrer" class="om-float-card om-float-demo" title="Launch Cheetah Enterprise Demo">
                <span class="om-play-btn">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                </span>
                <span class="om-demo-label">Live Demo ↗</span>
            </a>

            <!-- Floating Card 4: Top Right - 3 Co-Founders Team Pill -->
            <div class="om-float-card om-float-founders">
                <div class="om-founders-avatars">
                    <span class="om-avatar-chip chip-1" title="Muhammad Ayan Shamim">A</span>
                    <span class="om-avatar-chip chip-2" title="Syeda Sukaina Batool">S</span>
                    <span class="om-avatar-chip chip-3" title="Abdul Moiz">M</span>
                </div>
                <div class="om-founders-meta">
                    <strong>3 Co-Founders</strong>
                    <span>Direct Setup &bull; No Middlemen</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Omoway-style Monochromatic Partners & Tech Strip -->
<div class="om-partners-bar">
    <div class="wrap om-partners-inner">
        <div class="om-partner-item">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            <span>PHP 8.4 MVC</span>
        </div>
        <div class="om-partner-item">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
            <span>MySQL 8.0</span>
        </div>
        <div class="om-partner-item">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            <span>aaPanel Linux</span>
        </div>
        <div class="om-partner-item">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
            <span>Leaflet GPS</span>
        </div>
        <div class="om-partner-item">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
            <span>WhatsApp Cloud API</span>
        </div>
        <div class="om-partner-item">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            <span>Vanilla ES6</span>
        </div>
        <div class="om-partner-item">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>
            <span>Gumroad Store</span>
        </div>
    </div>
</div>

<!-- Facts Bar (Omoway Pill Geometry) -->
<div class="wrap om-facts-wrap">
    <div class="om-facts-bar rv s">
        <div class="om-fact-item">
            <b data-n="3">3</b>
            <span>Products For Sale</span>
        </div>
        <div class="om-fact-item">
            <b data-n="7">7</b>
            <span>Role Portals in Cheetah</span>
        </div>
        <div class="om-fact-item">
            <b data-n="78">78</b>
            <span>Database Tables</span>
        </div>
        <div class="om-fact-item">
            <b data-n="100">100%</b>
            <span>Source Code Included</span>
        </div>
    </div>
</div>

<!-- Section 2: Agency & Leadership Overview (Omoway "Powerful Agency" Pattern) -->
<section id="about" class="om-agency-sec">
    <div class="wrap om-agency-wrap">
        <!-- Left: Overlapping Media Cards with Green Metric Badge -->
        <div class="om-agency-visual rv l">
            <div class="om-agency-main-card">
                <div class="om-agency-card-bar">
                    <span class="om-badge-studio">CREATSIFY STUDIO</span>
                    <span class="om-badge-status"><i class="om-pulse-dot"></i> Active 2026</span>
                </div>
                <div class="om-agency-card-inner">
                    <div class="om-agency-brand-block">
                        <img src="<?= asset('brand/creatsify-icon.png') ?>" alt="Creatsify" class="om-agency-logo">
                        <div>
                            <strong>Core Software Architecture</strong>
                            <span>Strict PHP 8.4 MVC &bull; MySQL 8 &bull; aaPanel</span>
                        </div>
                    </div>
                    <div class="om-agency-code-snippet">
                        <code>namespace App\Core; class StudioEngine { const OWNERSHIP = '100% Full Source'; }</code>
                    </div>
                </div>
            </div>

            <!-- Floating Green Metric Badge (Omoway 28+ Years style -> 3 Co-Founders) -->
            <div class="om-agency-float-badge">
                <div class="om-afb-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="om-afb-text">
                    <b>3 Co-Founders</b>
                    <span>100% Direct Engineering</span>
                </div>
            </div>

            <!-- Floating Metric Card Bottom -->
            <div class="om-agency-stat-card">
                <span class="om-stat-title">ZERO BLOAT OVERHEAD</span>
                <div class="om-stat-flex">
                    <div class="om-sf-item"><b>18ms</b><span>Query Latency</span></div>
                    <div class="om-sf-sep"></div>
                    <div class="om-sf-item"><b>78</b><span>Tables in Cheetah</span></div>
                </div>
            </div>
        </div>

        <!-- Right: Editorial Typography & Co-Founders Breakdown -->
        <div class="om-agency-text rv r">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Studio Leadership</div>
            <h2 class="om-sec-heading">Powerful Software Studio For Growing Businesses<span class="om-dot-gold">.</span></h2>
            <p class="om-sec-lead">
                <?= e(setting('founder_bio', 'Creatsify is an independent software studio led directly by three co-founders: Muhammad Ayan Shamim, Syeda Sukaina Batool, and Abdul Moiz. Together, we architect, design, and ship production-grade courier platforms, online store systems, and custom web applications for clients worldwide with zero middle-men.')) ?>
            </p>

            <div class="om-founders-list">
                <div class="om-founder-row">
                    <div class="om-founder-num">01</div>
                    <div class="om-founder-details">
                        <strong>Muhammad Ayan Shamim</strong>
                        <span>Co-Founder &amp; Lead Full-Stack Architect (PHP 8 MVC, MySQL 8, Core Systems)</span>
                    </div>
                </div>
                <div class="om-founder-row">
                    <div class="om-founder-num">02</div>
                    <div class="om-founder-details">
                        <strong>Syeda Sukaina Batool</strong>
                        <span>Co-Founder &amp; UI/UX Product Design Lead (Design Systems, High-Conversion UX, Figma)</span>
                    </div>
                </div>
                <div class="om-founder-row">
                    <div class="om-founder-num">03</div>
                    <div class="om-founder-details">
                        <strong>Abdul Moiz</strong>
                        <span>Co-Founder &amp; Technical Operations Lead (Production Linux, aaPanel, Server Ops)</span>
                    </div>
                </div>
            </div>

            <div class="om-agency-actions">
                <a class="om-btn-gold" href="<?= url('/team') ?>">
                    <span>Meet the 3 Co-Founders</span>
                    <span class="om-btn-circle-arrow">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                </a>
                <a class="om-btn-glass" href="<?= url('/contact') ?>">
                    <span>Work With Us</span>
                    <span class="om-btn-circle-plus">+</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Section 3: Software for Sale (Omoway "Take A Look At Our Recent Work" Dark Showcase) -->
<section id="products" class="om-dark-showcase">
    <div class="wrap">
        <div class="om-showcase-header">
            <div class="om-sec-tag dark"><span class="om-sec-dot"></span> Ready-Made Products</div>
            <h2 class="om-sec-heading light">Take A Look At Our Production Software<span class="om-dot-gold">.</span></h2>
            <p class="om-sec-sub light">Open any live demo to test the real system, then purchase an instant white-label license on Gumroad. You receive the complete source code, commercial rights, and direct setup support.</p>
        </div>

        <div class="om-prod-grid">
            <?php 
            $prodList = $projects ?? [];
            foreach ($prodList as $i => $pr):
                $shot = $imgFor($pr['slug']);
                $tech = array_filter(array_map('trim', explode(',', (string)($pr['tech_stack'] ?? ''))));
                $live = $pr['live_url'] ?? '';
                $hasLive = $live && $live !== '#';
            ?>
                <article class="om-prod-card rv" style="--d:<?= $i * 0.12 ?>s">
                    <div class="om-prod-media-frame">
                        <div class="om-prod-bar">
                            <div class="om-prod-dots">
                                <span class="dot d-red"></span>
                                <span class="dot d-yellow"></span>
                                <span class="dot d-green"></span>
                            </div>
                            <span class="om-prod-badge-price"><?= !empty($pr['price']) ? e($pr['price']) : '$99' ?></span>
                        </div>
                        <?php if ($shot): ?>
                            <img src="<?= asset($shot) ?>" width="1376" height="768" loading="lazy" alt="<?= e($pr['title']) ?>" class="om-prod-img">
                        <?php else: ?>
                            <div class="om-prod-cover">
                                <div class="om-prod-cover-icon">
                                    <?php if ($pr['slug'] === 'cheetah-lite'): ?>
                                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                    <?php else: ?>
                                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                    <?php endif; ?>
                                </div>
                                <b><?= e($pr['title']) ?></b>
                                <span><?= e($pr['slug'] === 'cheetah-lite' ? 'Courier-Only Dispatch Platform' : 'Multi-Branch Restaurant & Store POS') ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="om-prod-body">
                        <div class="om-prod-status-row">
                            <span class="om-prod-pill"><?= e($pr['status'] ?? 'Ready to License') ?></span>
                            <span class="om-prod-rights">&#10003; 100% Unlocked Code</span>
                        </div>
                        <h3 class="om-prod-title"><?= e($pr['title']) ?></h3>
                        <p class="om-prod-desc"><?= e($pr['summary'] ?? '') ?></p>
                        <div class="om-prod-chips">
                            <?php foreach ($tech as $t): ?><span><?= e($t) ?></span><?php endforeach; ?>
                        </div>
                        <div class="om-prod-actions">
                            <?php if (!empty($pr['gumroad_url'])): ?>
                                <a class="om-btn-gold" href="<?= e($pr['gumroad_url']) ?>" target="_blank" rel="noopener noreferrer">
                                    <span>Buy License<?= !empty($pr['price']) ? ' (' . e($pr['price']) . ')' : '' ?></span>
                                    <span class="om-btn-circle-arrow">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                    </span>
                                </a>
                            <?php else: ?>
                                <a class="om-btn-gold" href="#contact" data-quote="<?= e($pr['title']) ?>">
                                    <span>Get a Quote</span>
                                    <span class="om-btn-circle-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></span>
                                </a>
                            <?php endif; ?>
                            <?php if ($hasLive): ?>
                                <a class="om-btn-glass light" href="<?= e($live) ?>" target="_blank" rel="noopener noreferrer">
                                    <span>Live Demo ↗</span>
                                </a>
                            <?php endif; ?>
                            <a class="om-prod-link" href="<?= url('/portfolio/' . $pr['slug']) ?>">Details &rarr;</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Section 4: Services / Capabilities (Omoway "How We Take Your Business" Accordion / Stack) -->
<section id="services" class="om-services-sec">
    <div class="wrap om-services-wrap">
        <div class="om-services-left rv l">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Agency Capabilities</div>
            <h2 class="om-sec-heading">How We Take Your Business To The Next Level<span class="om-dot-gold">.</span></h2>
            <p class="om-sec-sub">Besides our commercial white-label software, our co-founders build bespoke platforms, high-conversion stores, and real-time APIs tailored to your team's operational workflows.</p>
            <div style="margin-top:28px;">
                <a class="om-btn-gold" href="<?= url('/services') ?>">
                    <span>Explore All Services</span>
                    <span class="om-btn-circle-arrow">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                </a>
            </div>
        </div>

        <div class="om-services-right rv r">
            <div class="om-service-card">
                <div class="om-sc-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                </div>
                <div class="om-sc-content">
                    <h3>Websites &amp; Custom Admin Panels</h3>
                    <p>High-velocity web platforms, operational dashboards, and database engines in pure PHP 8.4 and MySQL with zero framework bloat.</p>
                    <div class="om-sc-chips"><span>PHP 8.4 MVC</span><span>MySQL 8</span><span>aaPanel Linux</span></div>
                </div>
                <a href="<?= url('/services') ?>" class="om-sc-arrow" aria-label="Learn more about Websites & Admin Panels">&rarr;</a>
            </div>

            <div class="om-service-card">
                <div class="om-sc-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"></path><path d="M14 9h4l4 4v4c0 .6-.4 1-1 1h-2"></path><circle cx="7" cy="18" r="2"></circle><path d="M15 18H9"></path><circle cx="17" cy="18" r="2"></circle></svg>
                </div>
                <div class="om-sc-content">
                    <h3>Courier &amp; Warehouse Platforms</h3>
                    <p>Customizations of Cheetah Enterprise or Lite adapted to your exact hubs, multi-vendor merchants, riders, and Cash on Delivery cycles.</p>
                    <div class="om-sc-chips"><span>Live GPS</span><span>ePOD Signatures</span><span>COD Reconcile</span></div>
                </div>
                <a href="#products" class="om-sc-arrow" aria-label="Learn more about Courier Platforms">&rarr;</a>
            </div>

            <div class="om-service-card">
                <div class="om-sc-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"></path></svg>
                </div>
                <div class="om-sc-content">
                    <h3>WordPress &amp; WooCommerce Stores</h3>
                    <p>High-converting Elementor and WooCommerce stores with streamlined product catalogs, fast carts, checkout optimization, and technical SEO.</p>
                    <div class="om-sc-chips"><span>WordPress</span><span>WooCommerce</span><span>Elementor</span></div>
                </div>
                <a href="<?= url('/portfolio') ?>" class="om-sc-arrow" aria-label="Learn more about WooCommerce Stores">&rarr;</a>
            </div>

            <div class="om-service-card">
                <div class="om-sc-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </div>
                <div class="om-sc-content">
                    <h3>WhatsApp Cloud API &amp; SMS Automation</h3>
                    <p>Automated order pings, shipment delivery milestones, and instant buyer alerts via official Meta WhatsApp Cloud API webhooks.</p>
                    <div class="om-sc-chips"><span>WhatsApp API</span><span>Webhooks</span><span>Real-Time Pings</span></div>
                </div>
                <a href="#contact" class="om-sc-arrow" aria-label="Learn more about WhatsApp Automation">&rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- Section 5: Flagship Cheetah Deep Dive (Omoway "Understand The Business Process") -->
<section id="cheetah" class="om-flagship-sec">
    <div class="wrap om-flagship-wrap">
        <div class="om-flagship-left rv l">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Flagship Architecture</div>
            <h2 class="om-sec-heading"><?= e($cheetah['title'] ?? 'Cheetah: Courier & Warehouse Platform') ?><span class="om-dot-gold">.</span></h2>
            <p class="om-sec-sub"><?= e($cheetah['content'] ?? 'Cheetah comes in two editions, each with its own clean codebase. Enterprise covers multi-role couriers and warehouses with 7 portals, and Lite is for single-branch express courier operations.') ?></p>
            <ul class="om-flagship-checklist">
                <li><i class="om-check">&#10003;</i> 7 Distinct Role Portals: Admin, Merchant, Hub Manager, Driver, Picker, Finance &amp; Customer</li>
                <li><i class="om-check">&#10003;</i> Interactive Leaflet GPS map tracking with real-time waypoint routing</li>
                <li><i class="om-check">&#10003;</i> Digital ePOD touch signature capture on the mobile driver interface</li>
                <li><i class="om-check">&#10003;</i> Automated 4x6" PDF thermal shipping label generator</li>
            </ul>
            <div style="margin-top:28px;display:flex;gap:12px;flex-wrap:wrap;">
                <a class="om-btn-gold" href="https://cheetah.ayan24.me" target="_blank" rel="noopener noreferrer">
                    <span>Open Live Demo</span>
                    <span class="om-btn-circle-arrow"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></span>
                </a>
                <a class="om-btn-glass" href="<?= url('/portfolio/cheetah-enterprise') ?>">
                    <span>View Case Study</span>
                    <span class="om-btn-circle-plus">+</span>
                </a>
            </div>
        </div>

        <div class="om-flagship-right rv r">
            <div class="om-flagship-frame">
                <img src="<?= asset('images/cheetah-showcase.jpg') ?>" alt="Cheetah Dashboard and Live GPS Tracking" loading="lazy" class="om-flagship-img">
            </div>
            <div class="om-flagship-trk">
                <div class="om-trk-head">
                    <span class="om-trk-pill"><i class="om-pulse-dot"></i> Live Simulation</span>
                    <b><?= e($tracker['code'] ?? 'CH-000007') ?></b>
                    <span><?= e($tracker['from'] ?? 'Origin Hub 01') ?> &rarr; <?= e($tracker['to'] ?? 'Customer Destination') ?></span>
                </div>
                <ol class="om-trk-steps">
                    <?php foreach (($tracker['steps'] ?? ['Shipment created & waybill assigned', 'Linehaul dispatched to sorting hub', 'Out for delivery with assigned rider', 'Delivered & ePOD signed by customer']) as $step): ?>
                        <li><?= e($step) ?></li>
                    <?php endforeach; ?>
                </ol>
                <div class="om-demo-cred-badge">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2l-2 2m-1.5 1.5L12 11l4 4 5.5-5.5a2.12 2.12 0 0 0 0-3L20 5"></path><path d="M15.5 15.5L19 19"></path><path d="M7 13a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"></path></svg>
                    <span>Demo login: <b>admin@demo.com</b> / <b>cheetah2026</b></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 6: How It Works (Omoway 3-Step Process) -->
<section id="how" class="om-how-sec">
    <div class="wrap">
        <div class="om-sec-center">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Direct Process</div>
            <h2 class="om-sec-heading">From Demo To Live In Three Steps<span class="om-dot-gold">.</span></h2>
            <p class="om-sec-sub">Every product starts with an active live demo, so you evaluate the exact software before asking for licensing or customization.</p>
        </div>
        <div class="om-how-grid">
            <div class="om-how-card rv" style="--d:0s">
                <span class="om-how-step-num">01</span>
                <h3>Try the Live Demo</h3>
                <p>Open the real Cheetah or Cheezify demo and click through all role portals and admin tables, not a marketing slide deck.</p>
            </div>
            <div class="om-how-card rv" style="--d:0.12s">
                <span class="om-how-step-num">02</span>
                <h3>Tell Us What You Need</h3>
                <p>Purchase direct white-label rights on Gumroad, or send a quote request for bespoke extensions with direct co-founder scoping.</p>
            </div>
            <div class="om-how-card rv" style="--d:0.24s">
                <span class="om-how-step-num">03</span>
                <h3>Get Code &amp; Go Live</h3>
                <p>Receive the 100% unlocked source code, database migrations, and direct assistance deploying onto your aaPanel / Linux server.</p>
            </div>
        </div>
        <div class="om-how-actions rv" style="--d:0.3s">
            <a class="om-btn-gold" href="#products"><span>Browse Software</span><span class="om-btn-circle-arrow">&rarr;</span></a>
            <a class="om-btn-glass" href="#contact"><span>Ask a Question</span><span class="om-btn-circle-plus">+</span></a>
        </div>
    </div>
</section>

<!-- Section 7: Team / Co-Founders (Omoway "People Behind The Software") -->
<?php if (!empty($teamMembers)): ?>
<section id="team" class="om-team-sec">
    <div class="wrap">
        <div class="om-sec-center">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Studio Leadership</div>
            <h2 class="om-sec-heading">The People Behind The Software<span class="om-dot-gold">.</span></h2>
            <p class="om-sec-sub">The three co-founders who architect, design, ship, and support everything we sell.</p>
        </div>
        <div class="om-team-grid">
            <?php foreach ($teamMembers as $i => $m):
                $skills = array_slice(array_filter(array_map('trim', explode(',', (string)($m['skills'] ?? '')))), 0, 4);
            ?>
                <article class="om-team-card rv" style="--d:<?= $i * 0.1 ?>s">
                    <div class="om-tc-avatar-wrap">
                        <img class="om-tc-avatar" src="<?= asset($m['avatar'] ?: 'brand/creatsify-icon.png') ?>" width="84" height="84" loading="lazy" alt="<?= e($m['name']) ?>">
                    </div>
                    <h3 class="om-tc-name"><?= e($m['name']) ?></h3>
                    <p class="om-tc-role"><?= e($m['role_title']) ?></p>
                    <?php if ($skills): ?>
                        <div class="om-tc-chips">
                            <?php foreach ($skills as $sk): ?><span><?= e($sk) ?></span><?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <a class="om-tc-btn" href="<?= url('/team/' . $m['slug']) ?>">
                        <span>View Profile</span>
                        <span class="om-tc-arrow">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </span>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="om-team-actions rv" style="--d:0.3s">
            <a class="om-btn-glass" href="<?= url('/team') ?>">
                <span>Meet the Whole Team</span>
                <span class="om-btn-circle-arrow">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </span>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Section 8: Contact & Consultation Hub (Omoway Bottom Dark Card Pattern) -->
<section id="contact" class="om-contact-sec">
    <div class="wrap om-contact-wrap">
        <div class="om-contact-left rv l">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Direct Consultation</div>
            <h2 class="om-sec-heading"><?= e($sections['contact']['title'] ?? 'Tell Us What You Want To Build') ?><span class="om-dot-gold">.</span></h2>
            <p class="om-sec-sub">Send a message or ask for licensing guidance on one of our systems. The quickest way to reach our co-founders is direct WhatsApp chat.</p>

            <div class="om-whatsapp-card">
                <div class="om-wa-header">
                    <span class="om-wa-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                    </span>
                    <div class="om-wa-header-info">
                        <b>Chat on WhatsApp</b>
                        <span>Talk to our Co-Founders directly</span>
                    </div>
                    <span class="om-wa-active-pill"><i class="om-pulse-dot"></i> Live Now</span>
                </div>
                <?php
                $waPhone = clean_phone_number(setting('whatsapp_number', setting('site_phone', '923002521199')), false);
                $waMsg = urlencode("Hello, I would like to discuss a project with " . setting('site_name', 'Creatsify Studio'));
                ?>
                <a href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener" class="om-wa-btn">
                    <span class="om-wa-btn-lead">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                        <span>Message Co-Founders on WhatsApp</span>
                    </span>
                    <span class="om-wa-circle-arrow">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                </a>
            </div>

            <div class="om-contact-info-list">
                <div class="om-ci-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span><b>Email:</b> <a href="mailto:<?= e(setting('site_email', 'ayan@creatsify.tech')) ?>"><?= e(setting('site_email', 'ayan@creatsify.tech')) ?></a></span>
                </div>
                <div class="om-ci-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span><b>Working:</b> Remote, worldwide clients</span>
                </div>
            </div>
        </div>

        <form id="form" class="om-contact-form rv r" action="<?= url('/contact') ?>" method="POST">
            <?= csrf_field() ?>
            <label>Your Name<input name="name" required autocomplete="name" placeholder="John Doe"></label>
            <label>Your Email<input name="email" type="email" required autocomplete="email" placeholder="john@example.com"></label>
            <label>Phone / WhatsApp (Optional)<input name="phone" type="tel" placeholder="+1 555 123 4567" autocomplete="tel"></label>
            <label>What do you need?
                <select name="project_type">
                    <option>Cheetah Enterprise ($99)</option>
                    <option>Cheetah Lite ($59)</option>
                    <option>Cheezify ($49)</option>
                    <option>Custom Website or Admin Panel</option>
                    <option>WordPress or WooCommerce Store</option>
                    <option>Something Else</option>
                </select>
            </label>
            <label>Message<textarea name="message" rows="5" required placeholder="Tell us about your business and requirements..."></textarea></label>
            <button class="om-btn-gold" type="submit" style="width:100%;justify-content:center;">
                <span>Send Message</span>
                <span class="om-btn-circle-arrow"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></span>
            </button>
        </form>
    </div>
</section>

<script src="<?= asset_v('js/hero-omoway.js') ?>" defer></script>
