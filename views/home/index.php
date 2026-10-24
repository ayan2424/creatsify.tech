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
    <div class="trust-wrap hf" style="--d:.8s">
        <span class="trust-label">Verified & Endorsed On Commercial Marketplaces</span>
        <div class="trust-badges">
            <div class="trust-badge">
                <span class="trust-star" style="display:inline-flex; align-items:center; gap:4px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> 5.0
                </span>
                <b>CodeGrape</b>
                <span class="trust-sub">Elite Author</span>
            </div>
            <div class="trust-badge">
                <span class="trust-star" style="display:inline-flex; align-items:center; gap:4px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> 5.0
                </span>
                <b>Coderobotics</b>
                <span class="trust-sub">Featured Studio</span>
            </div>
            <div class="trust-badge">
                <span class="trust-check"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></span>
                <b>Codester</b>
                <span class="trust-sub">Verified Vendor</span>
            </div>
            <div class="trust-badge highlight">
                <span class="trust-check"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></span>
                <b>Zero Bloat</b>
                <span class="trust-sub">100% Code Ownership</span>
            </div>
        </div>
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

<!-- Tech Stack Infinite Marquee with Edge Masking -->
<div class="mq-wrapper">
    <div class="mq" data-dir="1" data-speed="0.9" aria-hidden="true"><div class="mt">
        <span class="mq-chip"><i class="mq-dot"></i>PHP 8.3</span><span class="mq-chip"><i class="mq-dot"></i>Laravel 11</span><span class="mq-chip"><i class="mq-dot"></i>MySQL 8</span><span class="mq-chip"><i class="mq-dot"></i>Python</span><span class="mq-chip"><i class="mq-dot"></i>React 19</span><span class="mq-chip"><i class="mq-dot"></i>Next.js</span><span class="mq-chip"><i class="mq-dot"></i>Flutter</span><span class="mq-chip"><i class="mq-dot"></i>Redis</span><span class="mq-chip"><i class="mq-dot"></i>Docker</span><span class="mq-chip"><i class="mq-dot"></i>WooCommerce</span><span class="mq-chip"><i class="mq-dot"></i>Linux / Nginx</span>
        <span class="mq-chip"><i class="mq-dot"></i>PHP 8.3</span><span class="mq-chip"><i class="mq-dot"></i>Laravel 11</span><span class="mq-chip"><i class="mq-dot"></i>MySQL 8</span><span class="mq-chip"><i class="mq-dot"></i>Python</span><span class="mq-chip"><i class="mq-dot"></i>React 19</span><span class="mq-chip"><i class="mq-dot"></i>Next.js</span><span class="mq-chip"><i class="mq-dot"></i>Flutter</span><span class="mq-chip"><i class="mq-dot"></i>Redis</span><span class="mq-chip"><i class="mq-dot"></i>Docker</span><span class="mq-chip"><i class="mq-dot"></i>WooCommerce</span><span class="mq-chip"><i class="mq-dot"></i>Linux / Nginx</span>
    </div></div>
</div>

<!-- Studio Telemetry & Live Counter Stats -->
<div class="wrap" style="margin-top:64px"><div class="stats rv s">
    <div><b data-n="10">10+</b><span>Commercial Products Shipped</span></div>
    <div><b data-n="78">78</b><span>Normalized DB Tables (Cheetah Flagship)</span></div>
    <div><b data-n="5">5+</b><span>Global Software Marketplaces</span></div>
    <div><b data-n="99">99.9%</b><span>Production Reliability & Fast MVC</span></div>
</div></div>

<!-- Core Engineering Capabilities Section -->
<section id="capabilities" class="section-cap"><div class="wrap">
    <div class="top">
        <div class="idx rv">01 · Capabilities</div>
        <h2 data-split class="sp">What we engineer & scale</h2>
        <p class="sub rv" style="--d:.2s">From high-throughput logistics backbones to real-time mobile apps, we design custom systems engineered for direct execution speed.</p>
    </div>
    <div class="cap-grid rv" style="--d:.25s;">
        <!-- 1. Logistics -->
        <div class="cap-card">
            <div class="cap-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"></path><path d="M14 9h4l4 4v4c0 .6-.4 1-1 1h-2"></path><circle cx="7" cy="18" r="2"></circle><path d="M15 18H9"></path><circle cx="17" cy="18" r="2"></circle></svg>
            </div>
            <h3>Logistics & 3PL Warehousing</h3>
            <p>Multi-branch hubs, linehaul dispatch, merchant client portals, barcode scanning, and electronic proof of delivery (ePOD).</p>
            <div class="cap-chips">
                <span>Cheetah Core</span><span>7 Role Portals</span><span>Real-Time GPS</span>
            </div>
            <a href="<?= url('/portfolio/cheetah-logistics') ?>" class="cap-link">Explore platform &rarr;</a>
        </div>
        <!-- 2. Web & SaaS -->
        <div class="cap-card">
            <div class="cap-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
            </div>
            <h3>High-Performance Web & SaaS</h3>
            <p>Custom zero-bloat PHP MVC cores, Laravel platforms, multi-tenant database isolation, Stripe/PayPal billing, and robust REST APIs.</p>
            <div class="cap-chips">
                <span>PHP 8.3</span><span>MySQL</span><span>Multi-Tenant</span>
            </div>
            <a href="<?= url('/services') ?>" class="cap-link">View architecture &rarr;</a>
        </div>
        <!-- 3. Mobile & Cross-Platform -->
        <div class="cap-card">
            <div class="cap-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
            </div>
            <h3>Mobile & Desktop Apps</h3>
            <p>Native Flutter iOS & Android apps with offline caching, Hive local storage, and Electron desktop creator tools with hardware acceleration.</p>
            <div class="cap-chips">
                <span>Flutter</span><span>Electron</span><span>Offline First</span>
            </div>
            <a href="<?= url('/services') ?>" class="cap-link">Inspect apps &rarr;</a>
        </div>
        <!-- 4. Omnichannel AI & Automation -->
        <div class="cap-card">
            <div class="cap-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
            </div>
            <h3>AI & Omnichannel Automation</h3>
            <p>Meta WhatsApp Cloud API v21.0 integrations, automated transactional SMS/OTP dispatch, and custom AI chat/LLM agent workflows.</p>
            <div class="cap-chips">
                <span>WhatsApp Cloud</span><span>Webhooks</span><span>AI Agents</span>
            </div>
            <a href="<?= url('/services') ?>" class="cap-link">Explore automations &rarr;</a>
        </div>
    </div>
</div></section>

<!-- Featured Work Section with Card Parallax & Smooth Stacking -->
<section id="work"><div class="wrap">
    <div class="top">
        <div>
            <div class="idx rv">02 · Featured Work</div>
            <h2 data-split class="sp">Featured flagship builds</h2>
            <p class="sub rv" style="--d:.2s">Real production platforms we engineered, shipped, and commercialize globally.</p>
        </div>
    </div>
    <div class="pjs" id="pjs"></div>
</div></section>

<!-- Cheetah Flagship Section with 3D Frame, Live Shipment Tracker and Edition Cards -->
<section id="cheetah" class="alt"><div class="wrap">
    <div class="split">
        <div>
            <div class="idx rv">03 · Flagship Architecture</div>
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
                    <span class="trk-status-pill"><i class="pulse-dot"></i> Live Shipment In-Transit · Real-Time Telemetry</span>
                    <b><?= e($tracker['code'] ?? 'PK-000007') ?></b>
                    <span>Sample shipment · <?= e($tracker['from'] ?? 'Karachi') ?> to <?= e($tracker['to'] ?? 'Lahore') ?></span>
                </div>
                <ol>
                    <?php foreach (($tracker['steps'] ?? ['Shipment created', 'Linehaul dispatched', 'Out for delivery', 'Delivered, ePOD signed']) as $step): ?>
                        <li><?= e($step) ?></li>
                    <?php endforeach; ?>
                </ol>
                <div class="demo-cred-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2l-2 2m-1.5 1.5L12 11l4 4 5.5-5.5a2.12 2.12 0 0 0 0-3L20 5"></path><path d="M15.5 15.5L19 19"></path><path d="M7 13a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"></path></svg>
                    <span>Public Demo Access: <b>admin@demo.com</b> / <b>cheetah2026</b></span>
                </div>
            </div>
        </div>
    </div>
    <div class="grid eds" id="eds"></div>
</div></section>

<!-- About Section with Founder Bio, Social Links and Rotating Badge SVG -->
<section id="about"><div class="wrap split">
    <div>
        <div class="idx rv">04 · Studio & Founder</div>
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
            <div class="terminal-title">creatsify-cli · v2.5 (zsh)</div>
            <div class="terminal-status">LIVE NODE (Karachi)</div>
        </div>
        <div class="terminal-body" id="termOutput">
            <div class="term-line accent">Creatsify Interactive Studio CLI [Version 2.5.0-production]</div>
            <div class="term-line system">Type <b>help</b> or press <b>Tab</b> to autocomplete commands. Click quick action buttons below.</div>
        </div>
        <div class="terminal-input-row" style="padding: 10px 18px; background: rgba(0,0,0,0.3); border-top: 1px solid var(--line);">
            <span class="term-prompt">ayan@creatsify:~$</span>
            <input type="text" id="termInput" class="terminal-input" placeholder="type 'help', 'cheetah', 'team', 'matrix'..." autocomplete="off" spellcheck="false">
        </div>
        <div class="term-quick-cmds">
            <span>Quick Actions:</span>
            <button type="button" class="term-quick-btn" data-cmd="help">help</button>
            <button type="button" class="term-quick-btn" data-cmd="cheetah">cheetah</button>
            <button type="button" class="term-quick-btn" data-cmd="team">team</button>
            <button type="button" class="term-quick-btn" data-cmd="services">services</button>
            <button type="button" class="term-quick-btn" data-cmd="projects">projects</button>
            <button type="button" class="term-quick-btn" data-cmd="stack">stack</button>
            <button type="button" class="term-quick-btn" data-cmd="uptime">uptime</button>
            <button type="button" class="term-quick-btn" data-cmd="ping">ping</button>
            <button type="button" class="term-quick-btn" data-cmd="matrix">matrix</button>
            <button type="button" class="term-quick-btn" data-cmd="whatsapp">whatsapp</button>
            <button type="button" class="term-quick-btn" data-cmd="clear">clear</button>
        </div>
    </div>
</div>
</section>

<!-- Agency Creators & Engineering Team Section -->
<section id="team" class="alt"><div class="wrap">
    <div class="top" style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:28px;flex-wrap:wrap;gap:20px;">
        <div>
            <div class="idx rv">05 · Engineering Specialists</div>
            <h2 data-split class="sp">Agency Creators & Specialists</h2>
            <p class="sub rv" style="--d:.2s">Dedicated craftsmen behind our architecture. Browse individual worker portfolios, verified skillsets, and production contributions.</p>
        </div>
        <a class="btn rv" href="<?= url('/team') ?>" style="--d:.25s;">View all specialists →</a>
    </div>

    <!-- Team Quality & Availability Badges Bar -->
    <div class="team-badges-bar rv" style="--d:.25s;">
        <span class="team-badge-chip"><i></i> 100% In-House Engineers</span>
        <span class="team-badge-chip"><i></i> Verified Commercial Portfolio</span>
        <span class="team-badge-chip"><i></i> Open for Contract Architecture</span>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
        <?php foreach (($teamMembers ?? []) as $tm): ?>
            <div class="team-specialist-card rv">
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div class="avatar-wrap">
                                <img src="<?= asset($tm['avatar'] ?: 'brand/creatsify-icon.png') ?>" alt="<?= e($tm['name']) ?>" style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent); box-shadow: 0 0 14px var(--accent-glow);">
                                <span class="avatar-available-dot" title="Available for immediate assignment"></span>
                            </div>
                            <div>
                                <b style="display:block; font-size: 16.5px; color: var(--text);"><?= e($tm['name']) ?></b>
                                <span style="font-size: 12.5px; color: var(--accent); font-weight:600;"><?= e($tm['role_title']) ?></span>
                            </div>
                        </div>

                        <!-- Social Shortcuts -->
                        <div class="team-member-socials">
                            <?php if (!empty($tm['github_url'])): ?>
                                <a href="<?= e($tm['github_url']) ?>" target="_blank" rel="noopener" class="team-social-btn" title="GitHub">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($tm['linkedin_url'])): ?>
                                <a href="<?= e($tm['linkedin_url']) ?>" target="_blank" rel="noopener" class="team-social-btn" title="LinkedIn">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($tm['email'])): ?>
                                <a href="mailto:<?= e($tm['email']) ?>" class="team-social-btn" title="Direct Email">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <p style="font-size: 13.5px; color: var(--muted); line-height: 1.6; margin-bottom: 18px;">
                        <?= e(mb_strimwidth($tm['bio'] ?? '', 0, 130, '...')) ?>
                    </p>

                    <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 22px;">
                        <?php 
                        $tmSkills = array_filter(array_map('trim', explode(',', $tm['skills'] ?? '')));
                        $tmSkills = array_slice($tmSkills, 0, 4);
                        foreach ($tmSkills as $sk): ?>
                            <span style="font-size: 11px; background: var(--bg); color: var(--text); padding: 3px 8px; border-radius: 5px; border: 1px solid var(--line); font-weight: 500;">
                                <?= e($sk) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="border-top: 1px solid var(--line); padding-top: 18px; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                    <a href="<?= url('/team/' . $tm['slug']) ?>" class="btn p" style="flex:1; text-align:center; padding: 9px 16px; font-size: 13px; font-weight: 700;">
                        View Portfolio (<?= (int)($tm['portfolio_count'] ?? 0) ?>) &rarr;
                    </a>
                    <a href="<?= url('/contact?specialist=' . urlencode($tm['name'])) ?>" class="btn" style="padding: 9px 14px; font-size: 12.5px; font-weight:600;" title="Request assignment">
                        Hire &rarr;
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div></section>

<!-- Interactive Scope & Velocity Calculator -->
<section id="calculator" class="calc-section"><div class="wrap">
    <div class="idx rv">06 · Architectural Estimator</div>
    <h2 data-split class="sp">Configure your software scope & velocity</h2>
    <p class="sub rv" style="--d:.2s;max-width:680px;">Select your core platform type and required enterprise modules to calculate estimated velocity, database architecture requirements, and delivery milestones.</p>
    
    <div class="calc-box rv" id="projectCalculator" style="--d:.3s;">
        <div class="calc-grid">
            <div>
                <div class="calc-features-title">1. Select Target Platform Architecture</div>
                <div class="calc-platforms">
                    <div class="calc-platform-card selected" data-platform="courier">
                        <b><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:6px;"><path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"></path><path d="M14 9h4l4 4v4c0 .6-.4 1-1 1h-2"></path><circle cx="7" cy="18" r="2"></circle><path d="M15 18H9"></path><circle cx="17" cy="18" r="2"></circle></svg>Cheetah Logistics Core</b>
                        <span>Couriers, linehaul tracking, merchant hubs & ePOD</span>
                    </div>
                    <div class="calc-platform-card" data-platform="restaurant">
                        <b><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:6px;"><path d="M6 2v20M18 2v20M6 12h12"></path></svg>RestroMaster Core</b>
                        <span>QR dine-in POS, live KDS & multi-branch kitchen</span>
                    </div>
                    <div class="calc-platform-card" data-platform="saas">
                        <b><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:6px;"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>Multi-Tenant Cloud SaaS</b>
                        <span>Custom SaaS, Stripe billing, API & tenant subdomains</span>
                    </div>
                    <div class="calc-platform-card" data-platform="mobile">
                        <b><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:6px;"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>Flutter Cross-Platform</b>
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
</div></section>

<!-- Client Reviews & Marketplace Endorsements -->
<section id="reviews" class="section-reviews alt"><div class="wrap">
    <div class="top">
        <div class="idx rv">07 · Client Endorsements</div>
        <h2 data-split class="sp">Verified buyer reviews & client proof</h2>
        <p class="sub rv" style="--d:.2s">What logistics operators, marketplace licensees, and product founders say about our architecture and code quality.</p>
    </div>

    <div class="reviews-grid">
        <!-- Review 1: Logistics Client -->
        <div class="review-card rv" style="--d:.1s;">
            <div>
                <div class="review-stars" style="display:flex; gap:3px; color:#f59e0b; margin-bottom:12px;">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <?php endfor; ?>
                </div>
                <p class="review-quote">
                    "Cheetah's 7-role portal architecture completely streamlined our multi-branch logistics dispatch and Cash-on-Delivery reconciliations. The system handles thousands of shipments without a hitch."
                </p>
            </div>
            <div class="review-author">
                <div class="review-avatar-fallback">TR</div>
                <div class="review-meta">
                    <b>Tariq Rahim</b>
                    <span>Director of Operations, Express Linehaul</span>
                    <span class="review-verified-badge" style="display:inline-flex; align-items:center; gap:5px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Verified Logistics Operator</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Review 2: Marketplace Commercial Buyer -->
        <div class="review-card rv" style="--d:.2s;">
            <div>
                <div class="review-stars" style="display:flex; gap:3px; color:#f59e0b; margin-bottom:12px;">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <?php endfor; ?>
                </div>
                <p class="review-quote">
                    "Cleanest native PHP MVC code I've purchased in years. Database tables are properly indexed, the documentation is crystal clear, and zero unnecessary framework bloat. High-performance software."
                </p>
            </div>
            <div class="review-author">
                <div class="review-avatar-fallback">MK</div>
                <div class="review-meta">
                    <b>Marcus Keller</b>
                    <span>Software Director, CodeGrape Licensee</span>
                    <span class="review-verified-badge" style="display:inline-flex; align-items:center; gap:5px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Verified Commercial Licensee</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Review 3: SaaS Founder -->
        <div class="review-card rv" style="--d:.3s;">
            <div>
                <div class="review-stars" style="display:flex; gap:3px; color:#f59e0b; margin-bottom:12px;">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <?php endfor; ?>
                </div>
                <p class="review-quote">
                    "Muhammad Ayan Shamim delivered our multi-tenant platform on time with custom Stripe subscription routing, full webhook reconciliation, and responsive Flutter dashboards. Top-tier engineering craft."
                </p>
            </div>
            <div class="review-author">
                <div class="review-avatar-fallback">SH</div>
                <div class="review-meta">
                    <b>Samir H.</b>
                    <span>Founder, CloudKitchen Ventures (UAE)</span>
                    <span class="review-verified-badge" style="display:inline-flex; align-items:center; gap:5px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Enterprise Project Client</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div></section>

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
        <div class="idx rv">08 · Direct Line & Quotation</div>
        <h2 data-split class="sp"><?= e($sections['contact']['title'] ?? 'Tell us what you want to build') ?></h2>
        <p class="sub rv" style="--d:.2s">Send a direct message or quotation request. We dispatch real-time notifications to founder mobile and review every architectural requirement thoroughly.</p>

        <!-- Direct Communication Hub -->
        <div class="contact-direct-hub rv" style="--d:.3s;">
            <div class="whatsapp-direct-card">
                <div class="wa-header">
                    <span class="wa-icon" style="color:#25d366;display:flex;align-items:center;">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2z"/></svg>
                    </span>
                    <div>
                        <b>Fast-Track WhatsApp Consultation</b>
                        <span>Connect directly with Founder Muhammad Ayan Shamim</span>
                    </div>
                </div>
                <a href="https://wa.me/923002521199?text=Hello%20Ayan,%20I%20would%20like%20to%20discuss%20a%20software%20project%20with%20Creatsify" target="_blank" rel="noopener" class="wa-chat-btn">
                    <span>Chat on WhatsApp (+92 300 2521199)</span> &rarr;
                </a>
            </div>

            <div class="contact-perks-list">
                <div class="contact-perk-item">
                    <span style="color:var(--accent);display:flex;align-items:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </span>
                    <div><b>Guaranteed Response SLA:</b> <span>Within 2 hours on business days</span></div>
                </div>
                <div class="contact-perk-item">
                    <span style="color:var(--accent);display:flex;align-items:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </span>
                    <div><b>Studio Timezone:</b> <span>Karachi, Pakistan (GMT+5) · Global Client Coverage</span></div>
                </div>
                <div class="contact-perk-item">
                    <span style="color:var(--accent);display:flex;align-items:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </span>
                    <div><b>Direct Founder Email:</b> <span><a href="mailto:<?= e(setting('site_email', 'ayan@creatsify.tech')) ?>" style="color:var(--accent);"><b><?= e(setting('site_email', 'ayan@creatsify.tech')) ?></b></a></span></div>
                </div>
            </div>
        </div>
    </div>
    <form id="form" class="rv r" action="<?= url('/contact') ?>" method="POST">
        <?= csrf_field() ?>
        <label>Your name<input name="name" required autocomplete="name" placeholder="John Doe"></label>
        <label>Your email<input name="email" type="email" required autocomplete="email" placeholder="john@example.com"></label>
        <label>Phone / WhatsApp (Optional)<input name="phone" type="tel" placeholder="+92 300 1234567" autocomplete="tel"></label>
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
        <label>Message & Specifications<textarea name="message" rows="5" required placeholder="Describe your product idea, target users, or timeline..."></textarea></label>
        <button class="btn p" type="submit" style="width:100%;font-weight:700;">Send inquiry &rarr;</button>
    </form>
</div></section>
