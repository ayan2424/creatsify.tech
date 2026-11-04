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

<!-- Facts -->
<div class="wrap" style="margin-top:64px"><div class="stats rv s">
    <div><b data-n="3">3</b><span>Products for sale</span></div>
    <div><b data-n="7">7</b><span>Role portals in Cheetah Enterprise</span></div>
    <div><b data-n="78">78</b><span>Database tables in Cheetah</span></div>
    <div><b data-n="100">100%</b><span>Source code included</span></div>
</div></div>

<!-- Software for sale -->
<section id="products"><div class="wrap">
    <div class="top">
        <div class="idx rv">Software for sale</div>
        <h2 data-split class="sp">Ready-made software you can buy today</h2>
        <p class="sub rv" style="--d:.2s">Open the live demo, then ask for a quote. You get the full source code, white-label rights and help with setup.</p>
    </div>
    <div class="prod-grid">
        <?php foreach ($products as $i => $pr):
            $shot = $imgFor($pr['slug']);
            $tech = array_filter(array_map('trim', explode(',', (string)($pr['tech_stack'] ?? ''))));
            $live = $pr['live_url'] ?? '';
            $hasLive = $live && $live !== '#';
        ?>
            <article class="prod rv" style="--d:<?= $i * 0.12 ?>s">
                <div class="prod-media">
                    <?php if ($shot): ?>
                        <img src="<?= asset($shot) ?>" width="1376" height="768" loading="lazy" alt="<?= e($pr['title']) ?> screenshot">
                    <?php else: ?>
                        <div class="prod-cover"><b><?= e($pr['title']) ?></b><span><?= e($kinds[$pr['slug']] ?? ($pr['category'] ?? '')) ?></span></div>
                    <?php endif; ?>
                </div>
                <div class="prod-body">
                    <span class="prod-status"><?= e($pr['status'] ?? 'For sale') ?><?= !empty($pr['price']) ? ' &middot; ' . e($pr['price']) : '' ?></span>
                    <h3><?= e($pr['title']) ?></h3>
                    <p><?= e($pr['summary'] ?? '') ?></p>
                    <div class="chips"><?php foreach ($tech as $t): ?><span><?= e($t) ?></span><?php endforeach; ?></div>
                    <div class="prod-actions">
                        <?php if ($hasLive): ?><a class="btn p" href="<?= e($live) ?>" target="_blank" rel="noopener noreferrer">Open live demo</a><?php endif; ?>
                        <?php if (!empty($pr['gumroad_url'])): ?>
                            <a class="btn" href="<?= e($pr['gumroad_url']) ?>" target="_blank" rel="noopener noreferrer">Buy<?= !empty($pr['price']) ? ' (' . e($pr['price']) . ')' : '' ?></a>
                        <?php else: ?>
                            <a class="btn" href="#contact" data-quote="<?= e($pr['title']) ?>">Get a quote</a>
                        <?php endif; ?>
                        <a class="prod-link" href="<?= url('/portfolio/' . $pr['slug']) ?>">Details &rarr;</a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div></section>

<!-- Cheetah deep dive -->
<section id="cheetah" class="alt"><div class="wrap">
    <div class="split">
        <div>
            <div class="idx rv">Our flagship</div>
            <h2 data-split class="sp"><?= e($cheetah['title'] ?? 'Cheetah: courier and warehouse software') ?></h2>
            <p class="sub rv" style="--d:.2s"><?= e($cheetah['content'] ?? 'Cheetah comes in two editions, each with its own code. Enterprise covers couriers and warehouses, and Lite is for courier-only businesses.') ?></p>
            <ul class="list" id="feat"></ul>
        </div>
        <div class="rv r cf">
            <div class="frame" data-tilt="6">
                <img id="cheetahimg" src="<?= asset('images/cheetah-showcase.jpg') ?>" alt="Cheetah dashboards, tracking globe and driver app" loading="lazy">
                <p>Cheetah: admin dashboard, tracking globe and driver app</p>
            </div>
            <div class="trk" id="trk">
                <div class="th">
                    <span class="trk-status-pill"><i class="pulse-dot"></i> Sample shipment</span>
                    <b><?= e($tracker['code'] ?? 'CH-000007') ?></b>
                    <span><?= e($tracker['from'] ?? 'Origin hub') ?> to <?= e($tracker['to'] ?? 'Customer') ?></span>
                </div>
                <ol>
                    <?php foreach (($tracker['steps'] ?? ['Shipment created', 'Linehaul dispatched', 'Out for delivery', 'Delivered, ePOD signed']) as $step): ?>
                        <li><?= e($step) ?></li>
                    <?php endforeach; ?>
                </ol>
                <div class="demo-cred-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2l-2 2m-1.5 1.5L12 11l4 4 5.5-5.5a2.12 2.12 0 0 0 0-3L20 5"></path><path d="M15.5 15.5L19 19"></path><path d="M7 13a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"></path></svg>
                    <span>Demo login: <b>admin@demo.com</b> / <b>cheetah2026</b></span>
                </div>
            </div>
        </div>
    </div>
</div></section>

<!-- Portfolio (stacked cards are built from the projects list in main.js) -->
<section id="work"><div class="wrap">
    <div class="top">
        <div class="idx rv">More work</div>
        <h2 data-split class="sp">Other things we have built</h2>
        <p class="sub rv" style="--d:.2s">The software above is ready to buy. These are projects we built that are not for sale.</p>
    </div>
    <div class="pjs" id="pjs"></div>
</div></section>

<!-- What we build for clients -->
<section id="services" class="alt"><div class="wrap">
    <div class="top">
        <div class="idx rv">Services</div>
        <h2 data-split class="sp">Need something built for your business?</h2>
        <p class="sub rv" style="--d:.2s">Besides the products above, we build custom work. Tell us what you need and we will reply with a plan and a price.</p>
    </div>
    <div class="cap-grid rv" style="--d:.25s;">
        <div class="cap-card">
            <div class="cap-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg></div>
            <h3>Websites and admin panels</h3>
            <p>Business websites, dashboards and management systems in PHP and MySQL, built around how your team works.</p>
            <div class="cap-chips"><span>PHP</span><span>MySQL</span><span>Admin panel</span></div>
            <a href="<?= url('/services') ?>" class="cap-link">See services &rarr;</a>
        </div>
        <div class="cap-card">
            <div class="cap-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"></path></svg></div>
            <h3>WordPress and online stores</h3>
            <p>Elementor and WooCommerce stores with product pages, checkout and the basics of SEO.</p>
            <div class="cap-chips"><span>WordPress</span><span>Elementor</span><span>WooCommerce</span></div>
            <a href="<?= url('/portfolio') ?>" class="cap-link">See our stores &rarr;</a>
        </div>
        <div class="cap-card">
            <div class="cap-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"></path><path d="M14 9h4l4 4v4c0 .6-.4 1-1 1h-2"></path><circle cx="7" cy="18" r="2"></circle><path d="M15 18H9"></path><circle cx="17" cy="18" r="2"></circle></svg></div>
            <h3>Courier and warehouse systems</h3>
            <p>Start from Cheetah Enterprise or Lite and we adapt it to your branches, riders and merchants.</p>
            <div class="cap-chips"><span>Tracking</span><span>Cash on delivery</span><span>Proof of delivery</span></div>
            <a href="#products" class="cap-link">See the editions &rarr;</a>
        </div>
        <div class="cap-card">
            <div class="cap-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></div>
            <h3>WhatsApp and SMS alerts</h3>
            <p>Order and shipment updates sent to your customers through the WhatsApp Cloud API and SMS.</p>
            <div class="cap-chips"><span>WhatsApp</span><span>SMS</span><span>Webhooks</span></div>
            <a href="#contact" class="cap-link">Ask about it &rarr;</a>
        </div>
    </div>
</div></section>

<!-- Team -->
<?php if (!empty($teamMembers)): ?>
<section id="team"><div class="wrap">
    <div class="top">
        <div class="idx rv">Team</div>
        <h2 data-split class="sp">The people behind the software</h2>
        <p class="sub rv" style="--d:.2s">The team that builds, ships and supports everything we sell.</p>
    </div>
    <div class="tm-grid">
        <?php foreach ($teamMembers as $i => $m):
            $skills = array_slice(array_filter(array_map('trim', explode(',', (string)($m['skills'] ?? '')))), 0, 4);
        ?>
            <article class="tm-card rv" style="--d:<?= $i * 0.1 ?>s">
                <img class="tm-avatar" src="<?= asset($m['avatar'] ?: 'brand/creatsify-icon.png') ?>" width="72" height="72" loading="lazy" alt="<?= e($m['name']) ?>">
                <h3><?= e($m['name']) ?></h3>
                <p class="tm-role"><?= e($m['role_title']) ?></p>
                <?php if ($skills): ?><div class="chips"><?php foreach ($skills as $sk): ?><span><?= e($sk) ?></span><?php endforeach; ?></div><?php endif; ?>
                <a class="tm-link" href="<?= url('/team/' . $m['slug']) ?>">View profile &rarr;</a>
            </article>
        <?php endforeach; ?>
    </div>
    <div class="row rv" style="margin-top:30px;justify-content:center"><a class="btn" href="<?= url('/team') ?>">Meet the whole team</a></div>
</div></section>
<?php endif; ?>

<!-- How buying works -->
<section id="how" class="alt"><div class="wrap">
    <div class="top">
        <div class="idx rv">How it works</div>
        <h2 data-split class="sp">From demo to live in three steps</h2>
        <p class="sub rv" style="--d:.2s">Every product starts with a live demo, so you know exactly what you are getting before you ask for anything.</p>
    </div>
    <div class="how-grid">
        <div class="how-step rv"><span class="how-n">1</span><h3>Try the live demo</h3><p>Open the Cheetah or Cheezify demo and click through the real admin panel, not a slide deck.</p></div>
        <div class="how-step rv" style="--d:.12s"><span class="how-n">2</span><h3>Tell us what you need</h3><p>Send a quote request. If you want changes, we scope them and reply with a plan and a price.</p></div>
        <div class="how-step rv" style="--d:.24s"><span class="how-n">3</span><h3>Get the code and go live</h3><p>You receive the full source code, white-label rights and help with setup.</p></div>
    </div>
    <div class="row rv" style="margin-top:34px;justify-content:center;--d:.3s"><a class="btn p" href="#products">Browse the software</a><a class="btn" href="#contact">Ask a question</a></div>
</div></section>

<!-- About -->
<section id="about"><div class="wrap split">
    <div>
        <div class="idx rv">Leadership</div>
        <h2 data-split class="sp"><?= e($about['title'] ?? 'Built by Creatsify Co-Founders') ?></h2>
        <p class="sub rv" style="--d:.2s"><?= e(setting('founder_bio', 'Creatsify is an independent software studio led by three co-founders: Muhammad Ayan Shamim, Syeda Sukaina Batool, and Abdul Moiz. Together, we architect, design, and ship production-grade courier platforms, online store systems, and custom web applications for clients worldwide.')) ?></p>
        <div class="row rv" style="margin-top:26px;--d:.3s">
            <a class="btn p" href="<?= url('/team') ?>" style="display:inline-flex;align-items:center;gap:6px;">
                <span>Meet the 3 Co-Founders</span>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            <a class="btn" href="<?= url('/contact') ?>">Work with us</a>
        </div>
        <div class="bdg rv s" style="--d:.4s">
            <svg viewBox="0 0 200 200" aria-hidden="true">
                <defs><path id="cp" d="M100,100 m-80,0 a80,80 0 1,1 160,0 a80,80 0 1,1 -160,0"/></defs>
                <text><textPath href="#cp" textLength="495" lengthAdjust="spacing"><?= e($aboutMeta['badge'] ?? (strtoupper(setting('site_name', 'CREATSIFY')) . ' • STUDIO LEADERSHIP • BUILD • SHIP • ')) ?></textPath></text>
            </svg>
            <img src="<?= asset('brand/creatsify-icon.png') ?>" alt="Creatsify emblem">
        </div>
    </div>
    <ul class="facts">
        <li class="rv r"><b>Muhammad Ayan Shamim</b><span>Co-Founder & Lead Full-Stack Architect (PHP 8 MVC, MySQL 8, Core Systems)</span></li>
        <li class="rv r" style="--d:.1s"><b>Syeda Sukaina Batool</b><span>Co-Founder & UI/UX Product Design Lead (Design Systems, High-Conversion UX, Figma)</span></li>
        <li class="rv r" style="--d:.2s"><b>Abdul Moiz</b><span>Co-Founder & Technical Operations Lead (Production Linux, aaPanel, Server Ops)</span></li>
        <li class="rv r" style="--d:.3s"><b>Direct Ownership</b><span>Every product and client engagement is planned, built, and supported directly by our three co-founders</span></li>
    </ul>
</div></section>

<!-- Contact -->
<section id="contact" class="alt"><div class="wrap split">
    <div>
        <div class="idx rv">Contact</div>
        <h2 data-split class="sp"><?= e($sections['contact']['title'] ?? 'Tell us what you want to build') ?></h2>
        <p class="sub rv" style="--d:.2s">Send a message or ask for a quote on one of our products. The quickest way to reach us is WhatsApp.</p>
        <div class="contact-direct-hub rv" style="--d:.3s;">
            <div class="whatsapp-direct-card">
                <div class="wa-header">
                    <span class="wa-icon" style="color:#25d366;display:flex;align-items:center;">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                    </span>
                    <div>
                        <b>Chat on WhatsApp</b>
                        <span>Talk to our Co-Founders directly</span>
                    </div>
                </div>
                <?php
                $waPhone = clean_phone_number(setting('whatsapp_number', setting('site_phone', '923002521199')), false);
                $waMsg = urlencode("Hello, I would like to discuss a project with " . setting('site_name', 'Creatsify Studio'));
                ?>
                <a href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener" class="wa-chat-btn" style="display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                    <span>Message us on WhatsApp</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
            <div class="contact-perks-list">
                <div class="contact-perk-item">
                    <span style="color:var(--accent);display:flex;align-items:center;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg></span>
                    <div><b>Email:</b> <a href="mailto:<?= e(setting('site_email', 'ayan@creatsify.tech')) ?>" style="color:var(--accent);"><?= e(setting('site_email', 'ayan@creatsify.tech')) ?></a></div>
                </div>
                <div class="contact-perk-item">
                    <span style="color:var(--accent);display:flex;align-items:center;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></span>
                    <div><b>Working:</b> Remote, with clients worldwide</div>
                </div>
            </div>
        </div>
    </div>
    <form id="form" class="rv r" action="<?= url('/contact') ?>" method="POST">
        <?= csrf_field() ?>
        <label>Your name<input name="name" required autocomplete="name" placeholder="John Doe"></label>
        <label>Your email<input name="email" type="email" required autocomplete="email" placeholder="john@example.com"></label>
        <label>Phone / WhatsApp (optional)<input name="phone" type="tel" placeholder="+1 555 123 4567" autocomplete="tel"></label>
        <label>What do you need?
            <select name="project_type">
                <option>Cheetah Enterprise</option>
                <option>Cheetah Lite</option>
                <option>Cheezify</option>
                <option>Custom website or admin panel</option>
                <option>WordPress or WooCommerce store</option>
                <option>Something else</option>
            </select>
        </label>
        <label>Message<textarea name="message" rows="5" required placeholder="Tell us about your business and what you want to build..."></textarea></label>
        <button class="btn p" type="submit" style="width:100%;font-weight:700;display:inline-flex;align-items:center;justify-content:center;gap:8px;">
            <span>Send message</span>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </button>
    </form>
</div></section>

<script src="<?= asset_v('js/hero-omoway.js') ?>" defer></script>
