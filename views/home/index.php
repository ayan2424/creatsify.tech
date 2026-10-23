<?php
/** @var Core\View $this */
$this->layout('layouts/main');

$hero = $sections['hero'] ?? [];
$heroMeta = $hero['metadata'] ?? [];
$rotatingWords = $heroMeta['rotating_words'] ?? ['courier companies', 'online stores', 'learning platforms', 'media brands', 'startups', 'restaurant chains'];
$heroChips = $heroMeta['chips'] ?? ['7 role portals in Cheetah', 'Sold on 4 marketplaces', '10 products shipped'];

$cheetah = $sections['cheetah_showcase'] ?? [];
$cheetahMeta = $cheetah['metadata'] ?? [];
$tracker = $cheetahMeta['tracker'] ?? [
    'code' => 'PK-000007',
    'from' => 'Karachi',
    'to' => 'Lahore',
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

$projectsJson = array_map(function($p) {
    $tech = array_map('trim', explode(',', $p['tech_stack'] ?: 'PHP, MySQL'));
    $links = [];
    if (!empty($p['live_url']) && $p['live_url'] !== '#') {
        $links[] = ['Live demo', $p['live_url']];
    }
    $links[] = ['Case study', url('/portfolio/' . $p['slug'])];
    
    $img = $p['image_url'] ?? '';
    if (empty($img) || str_contains($img, 'creatsify-icon.png')) {
        $t = strtolower($p['title']);
        if (str_contains($t, 'cheetah')) {
            $img = asset('images/cheetah-showcase.jpg');
        } elseif (str_contains($t, 'restro') || str_contains($t, 'flavor')) {
            $img = asset('images/restromaster-preview.jpg');
        } elseif (str_contains($t, 'vidd') || str_contains($t, 'media') || str_contains($t, 'browse')) {
            $img = asset('images/viddmate-preview.jpg');
        } else {
            $img = asset('images/hero-artwork.jpg');
        }
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

<section class="hero" id="hero"><div class="wrap">
<div>
    <div class="pill hf"><i></i>Full-stack studio · Karachi, Pakistan</div>
    <h1><span class="ln"><span class="ch" style="--i:0"><?= e($hero['title'] ?? 'We build software') ?></span></span><span class="ln"><span class="ch" style="--i:1">for <span class="rot grad" id="rot"><?= e($rotatingWords[0] ?? 'courier companies') ?></span></span></span></h1>
    <p class="lead hf" style="--d:.5s"><?= e($hero['content'] ?? 'From idea to a live product. We build courier platforms, online stores, learning tools and apps that real businesses can run on.') ?></p>
    <div class="row hf" style="--d:.65s">
        <a class="btn p" href="#work">See our work <span aria-hidden="true">→</span></a>
        <a class="btn" href="#contact">Get a quote</a>
    </div>
    <div class="trust hf" style="--d:.8s">
        <span>Verified on</span>
        <b>Coderobotics</b>
        <b>Codester</b>
        <b>CodeGrape</b>
    </div>
</div>
<div class="fv hf" style="--d:.45s"><div class="fw" id="hst">
    <div class="ly" data-d="10">
        <div class="frame" id="hframe" data-tilt="8">
            <img id="heroimg" src="<?= asset('images/hero-artwork.jpg') ?>" alt="Creatsify 3D digital craft artwork">
            <p>Creatsify Studio · Karachi, Pakistan</p>
        </div>
    </div>
    <div class="ly l2" data-d="38"><div class="mini"><img id="hm1" src="<?= asset('images/cheetah-showcase.jpg') ?>" alt="Cheetah dashboard"><span>Cheetah</span></div></div>
    <div class="ly l3" data-d="56"><div class="mini"><img id="hm2" src="<?= asset('images/viddmate-preview.jpg') ?>" alt="ViddMate app"><span>ViddMate</span></div></div>
    <div class="ly c1" data-d="26"><span class="chipi"><i></i><?= e($heroChips[0] ?? '7 role portals in Cheetah') ?></span></div>
    <div class="ly c2" data-d="44"><span class="chipi" style="animation-delay:1.4s"><i></i><?= e($heroChips[2] ?? '10 products shipped') ?></span></div>
</div></div>
</div><div class="sc" aria-hidden="true"></div></section>

<!-- Tech Stack Infinite Marquee -->
<div class="mq" data-dir="1" data-speed="0.9" aria-hidden="true"><div class="mt">
    <span>PHP</span><span>Laravel</span><span>MySQL</span><span>Python</span><span>React</span><span>Next.js</span><span>Flutter</span><span>Electron</span><span>WordPress</span><span>WooCommerce</span><span>Docker</span><span>Redis</span>
    <span>PHP</span><span>Laravel</span><span>MySQL</span><span>Python</span><span>React</span><span>Next.js</span><span>Flutter</span><span>Electron</span><span>WordPress</span><span>WooCommerce</span><span>Docker</span><span>Redis</span>
</div></div>

<!-- Live Counter Stats Section -->
<div class="wrap" style="margin-top:64px"><div class="stats rv s">
    <div><b data-n="10">10</b><span>Products shipped</span></div>
    <div><b data-n="3">3</b><span>Cheetah editions</span></div>
    <div><b data-n="7">7</b><span>Role portals in Cheetah</span></div>
    <div><b data-n="78">78</b><span>Database tables in Cheetah</span></div>
</div></div>

<!-- Featured Work Section with Card Parallax & Smooth Stacking -->
<section id="work"><div class="wrap">
    <div class="top">
        <div>
            <div class="idx rv">01 · Our work</div>
            <h2 data-split class="sp">Our work</h2>
            <p class="sub rv" style="--d:.2s">Real products we built, shipped and sell. Keep scrolling to explore.</p>
        </div>
    </div>
    <div class="pjs" id="pjs"></div>
</div></section>

<!-- Cheetah Flagship Section with 3D Frame, Live Shipment Tracker and Edition Cards -->
<section id="cheetah" class="alt"><div class="wrap">
    <div class="split">
        <div>
            <div class="idx rv">02 · Flagship</div>
            <h2 data-split class="sp"><?= e($cheetah['title'] ?? 'Cheetah: courier and warehouse software') ?></h2>
            <p class="sub rv" style="--d:.2s"><?= e($cheetah['content'] ?? 'Our flagship product line comes in three separate editions, each with its own code, so you can pick the one that fits your business.') ?></p>
            <ul class="list" id="feat"></ul>
        </div>
        <div class="rv r cf">
            <div class="frame" data-tilt="6">
                <img id="cheetahimg" src="<?= asset('images/cheetah-showcase.jpg') ?>" alt="Cheetah dashboards, globe and mobile app">
                <p>Cheetah: admin dashboard, tracking globe and driver app</p>
            </div>
            <div class="trk" id="trk">
                <div class="th">
                    <b><?= e($tracker['code'] ?? 'PK-000007') ?></b>
                    <span>Sample shipment · <?= e($tracker['from'] ?? 'Karachi') ?> to <?= e($tracker['to'] ?? 'Lahore') ?></span>
                </div>
                <ol>
                    <?php foreach (($tracker['steps'] ?? ['Shipment created', 'Linehaul dispatched', 'Out for delivery', 'Delivered, ePOD signed']) as $step): ?>
                        <li><?= e($step) ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
    <div class="grid eds" id="eds"></div>
</div></section>

<!-- About Section with Founder Bio, Social Links and Rotating Badge SVG -->
<section id="about"><div class="wrap split">
    <div>
        <div class="idx rv">03 · About</div>
        <h2 data-split class="sp"><?= e($about['title'] ?? 'Built by Muhammad Ayan Shamim') ?></h2>
        <p class="sub rv" style="--d:.2s"><?= e(setting('founder_bio', 'Ayan is the founder of Creatsify. He studies AI in the ACCP program at Aptech, Karachi, and builds and sells real software: web platforms, mobile apps, desktop tools and WordPress stores.')) ?></p>
        <div class="row rv" style="margin-top:26px;--d:.3s">
            <a class="btn" href="<?= e(setting('founder_github', 'https://github.com/ayan2424')) ?>" target="_blank" rel="noopener noreferrer">GitHub</a>
            <a class="btn" href="<?= e(setting('founder_linkedin', 'https://linkedin.com/in/muhammadayanshamim')) ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a>
        </div>
        <div class="bdg rv s" style="--d:.4s">
            <svg viewBox="0 0 200 200" aria-hidden="true">
                <defs><path id="cp" d="M100,100 m-80,0 a80,80 0 1,1 160,0 a80,80 0 1,1 -160,0"/></defs>
                <text><textPath href="#cp" textLength="495" lengthAdjust="spacing"><?= e($aboutMeta['badge'] ?? 'CREATSIFY • KARACHI • BUILD • SHIP • GROW • ') ?></textPath></text>
            </svg>
            <img src="<?= asset('brand/creatsify-icon.png') ?>" alt="Creatsify emblem">
        </div>
    </div>
    <ul class="facts">
        <li class="rv r"><b>Full-stack web</b><span>PHP, Laravel, Python, React, MySQL</span></li>
        <li class="rv r" style="--d:.1s"><b>Mobile and desktop</b><span>Flutter apps and an Electron browser</span></li>
        <li class="rv r" style="--d:.2s"><b>WordPress and e-commerce</b><span>Elementor and WooCommerce stores</span></li>
        <li class="rv r" style="--d:.3s"><b>Education</b><span>Distinction in the first semester at Aptech</span></li>
    </ul>
</div>
<!-- Live Studio CLI Terminal -->
<div class="wrap rv" style="margin-top:48px;--d:.35s;">
    <div class="terminal-wrapper">
        <div class="terminal-header">
            <div class="terminal-dots">
                <span class="d-red"></span>
                <span class="d-yellow"></span>
                <span class="d-green"></span>
            </div>
            <div class="terminal-title">creatsify-cli · v2.4 (zsh)</div>
            <div class="terminal-status">LIVE NODE</div>
        </div>
        <div class="terminal-body" id="termOutput">
            <div class="term-line accent">Creatsify Interactive Studio CLI [Version 2.4.0-production]</div>
            <div class="term-line system">Type <b>help</b> or click any quick command below to inspect architecture, projects, or founder profile.</div>
        </div>
        <div class="term-input-row">
            <span class="term-prompt">ayan@creatsify:~$</span>
            <input type="text" id="termInput" placeholder="type 'help', 'cheetah', 'projects', 'stack'..." autocomplete="off" spellcheck="false">
        </div>
        <div class="term-quick-bar">
            <span style="font-size:11px;color:var(--muted);margin-right:4px;">Quick Actions:</span>
            <button type="button" class="term-quick-btn" data-cmd="help">help</button>
            <button type="button" class="term-quick-btn" data-cmd="cheetah">cheetah</button>
            <button type="button" class="term-quick-btn" data-cmd="projects">projects</button>
            <button type="button" class="term-quick-btn" data-cmd="stack">stack</button>
            <button type="button" class="term-quick-btn" data-cmd="whoami">whoami</button>
            <button type="button" class="term-quick-btn" data-cmd="quote">quote</button>
            <button type="button" class="term-quick-btn" data-cmd="clear">clear</button>
        </div>
    </div>
</div>
</section>

<!-- Interactive Scope & Velocity Calculator -->
<section id="calculator" class="calc-section">
    <div class="wrap">
        <div class="idx rv">Architectural Estimator</div>
        <h2 data-split class="sp">Configure your software scope & velocity</h2>
        <p class="sub rv" style="--d:.2s;max-width:680px;">Select your core platform type and required enterprise modules to calculate estimated velocity, database architecture requirements, and delivery milestones.</p>
        
        <div class="calc-box rv" id="projectCalculator" style="--d:.3s;">
            <div class="calc-grid">
                <div>
                    <div class="calc-features-title">1. Select Target Platform Architecture</div>
                    <div class="calc-platforms">
                        <div class="calc-platform-card selected" data-platform="courier">
                            <b>🚀 Cheetah Logistics Core</b>
                            <span>Couriers, linehaul tracking, merchant hubs & ePOD</span>
                        </div>
                        <div class="calc-platform-card" data-platform="restaurant">
                            <b>🍽️ RestroMaster Core</b>
                            <span>QR dine-in POS, live KDS & multi-branch kitchen</span>
                        </div>
                        <div class="calc-platform-card" data-platform="saas">
                            <b>⚡ Multi-Tenant Cloud SaaS</b>
                            <span>Custom SaaS, Stripe billing, API & tenant subdomains</span>
                        </div>
                        <div class="calc-platform-card" data-platform="mobile">
                            <b>📱 Flutter Cross-Platform</b>
                            <span>Native iOS & Android mobile apps with offline cache</span>
                        </div>
                    </div>

                    <div class="calc-features-title">2. Select Modules & Integrations</div>
                    <div class="calc-features-list">
                        <div class="calc-feature-item checked" data-weeks="1" data-modules="2" data-tables="8">
                            <input type="checkbox" checked>
                            <span>Meta WhatsApp Cloud API (Automated notifications & OTP alerts)</span>
                        </div>
                        <div class="calc-feature-item checked" data-weeks="1.5" data-modules="3" data-tables="12">
                            <input type="checkbox" checked>
                            <span>Multi-Role RBAC Portals (Admin, Operations, Finance, Client)</span>
                        </div>
                        <div class="calc-feature-item" data-weeks="1" data-modules="2" data-tables="6">
                            <input type="checkbox">
                            <span>Payment Gateways (Stripe, JazzCash, EasyPaisa, Cash-on-Delivery)</span>
                        </div>
                        <div class="calc-feature-item" data-weeks="1.5" data-modules="2" data-tables="8">
                            <input type="checkbox">
                            <span>Live Dispatch Map & Real-time GPS Fleet Telemetry</span>
                        </div>
                        <div class="calc-feature-item" data-weeks="1" data-modules="1" data-tables="4">
                            <input type="checkbox">
                            <span>Automated PDF Invoices, Waybills & Thermal Barcode Labels</span>
                        </div>
                    </div>
                </div>

                <div class="calc-summary-panel">
                    <div>
                        <div class="calc-summary-title">Scope Blueprint</div>
                        <p style="font-size:13.5px;color:var(--muted);line-height:1.6;margin-bottom:12px;">Engineered with clean MVC patterns, fully normalized schemas, and zero third-party platform lock-in.</p>
                        
                        <div class="calc-timeline-badge" id="calcTimeline">
                            ⏱ Estimated Velocity: 4 – 6 Weeks
                        </div>

                        <div id="calcModules" style="font-weight:600;font-size:14px;color:var(--text);margin-bottom:18px;">
                            8 Core Modules • 68 Normalized DB Tables
                        </div>

                        <ul class="calc-blueprint-items">
                            <li>100% Source code ownership with white-label rights</li>
                            <li>Full REST API documentation & DB migrations included</li>
                            <li>Automated error logging & database backups</li>
                            <li>Production server deployment & SSL setup</li>
                        </ul>
                    </div>

                    <button type="button" class="btn p calc-prefill-btn" id="calcPrefillBtn">
                        Prefill Inquiry Form &rarr;
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Big Dynamic Text Marquee with Scroll Velocity Skew -->
<div class="mq big" data-dir="-1" data-speed="1.2" aria-hidden="true"><div class="mt">
    <span>Let’s build</span><span>Let’s launch</span><span>Let’s grow</span>
    <span>Let’s build</span><span>Let’s launch</span><span>Let’s grow</span>
    <span>Let’s build</span><span>Let’s launch</span><span>Let’s grow</span>
    <span>Let’s build</span><span>Let’s launch</span><span>Let’s grow</span>
</div></div>

<!-- Contact Section with Direct Form -->
<section id="contact" class="alt"><div class="wrap split">
    <div>
        <div class="idx rv">04 · Contact</div>
        <h2 data-split class="sp"><?= e($sections['contact']['title'] ?? 'Tell us what you want to build') ?></h2>
        <p class="sub rv" style="--d:.2s">Send a short message and we will reply by email. You can also write directly to <a href="mailto:<?= e(setting('site_email', 'ayan@creatsify.tech')) ?>"><b><?= e(setting('site_email', 'ayan@creatsify.tech')) ?></b></a>.</p>
    </div>
    <form id="form" class="rv r" action="<?= url('/contact') ?>" method="POST">
        <?= csrf_field() ?>
        <label>Your name<input name="name" required autocomplete="name"></label>
        <label>Your email<input name="email" type="email" required autocomplete="email"></label>
        <label>Project type
            <select name="project_type">
                <option>Website or WordPress store</option>
                <option>Web app or SaaS</option>
                <option>Mobile or desktop app</option>
                <option>Courier or warehouse system</option>
                <option>Restaurant ordering system</option>
                <option>AI tool</option>
            </select>
        </label>
        <label>Message<textarea name="message" required></textarea></label>
        <button class="btn p" type="submit">Send inquiry</button>
    </form>
</div></section>
