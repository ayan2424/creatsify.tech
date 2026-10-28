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
        <span class="trust-label">Every license includes</span>
        <div class="trust-badges">
            <div class="trust-badge"><span class="trust-check"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></span><b>Full source code</b><span class="trust-sub">No locked files</span></div>
            <div class="trust-badge"><span class="trust-check"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></span><b>White-label rights</b><span class="trust-sub">Use your own brand</span></div>
            <div class="trust-badge"><span class="trust-check"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></span><b>Live demos</b><span class="trust-sub">Try before you buy</span></div>
            <div class="trust-badge"><span class="trust-check"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></span><b>Direct support</b><span class="trust-sub">From the people who built it</span></div>
        </div>
    </div>
</div>
<?php
// Products for sale = featured projects. Hero showcase = those that have a screenshot.
$products = array_values(array_filter($projects ?? [], static fn ($pr) => !empty($pr['featured'])));
$toasts = ['cheetah-enterprise' => 'Shipment PK-000007 is out for delivery', 'cheetah-lite' => 'Courier orders, tracked end to end', 'cheezify' => 'New order from the online store'];
$kinds = ['cheetah-enterprise' => 'Courier and warehouse software', 'cheetah-lite' => 'Courier software, lite edition', 'cheezify' => 'Online cheese store'];
$hostOf = static function (array $pr) {
    $live = $pr['live_url'] ?? '';
    return ($live && $live !== '#') ? (parse_url($live, PHP_URL_HOST) ?: 'creatsify.tech') : 'creatsify.tech';
};
$showcase = [];
foreach ($products as $pr) {
    $f = $imgFor($pr['slug']);
    if ($f) {
        $showcase[] = ['name' => $pr['title'], 'kind' => $kinds[$pr['slug']] ?? ($pr['status'] ?? ''), 'img' => $f, 'toast' => $toasts[$pr['slug']] ?? ($pr['status'] ?? ''), 'host' => $hostOf($pr), 'href' => url('/portfolio/' . $pr['slug'])];
    }
}
if (!$showcase) {
    $showcase[] = ['name' => 'Creatsify', 'kind' => 'Software studio', 'img' => 'images/hero-artwork.jpg', 'toast' => 'Karachi, Pakistan', 'host' => 'creatsify.tech', 'href' => url('/portfolio')];
}
?>
<div class="fv hf" style="--d:.45s">
    <div class="hv<?= count($showcase) < 2 ? ' solo' : '' ?>" id="hv">
        <div class="hv-win" data-tilt="4">
            <div class="hv-bar">
                <span class="hv-dots" aria-hidden="true"><i></i><i></i><i></i></span>
                <span class="hv-url" id="hvUrl"><?= e($showcase[0]['host']) ?></span>
                <span class="hv-live"><i></i>Live</span>
            </div>
            <a class="hv-screen" id="hvLink" href="<?= e($showcase[0]['href']) ?>" aria-label="Open the case study for the product on screen">
                <?php foreach ($showcase as $k => $sc): ?>
                    <img class="hv-shot<?= $k === 0 ? ' on' : '' ?>" src="<?= asset($sc['img']) ?>" width="1376" height="768" alt="<?= e($sc['name']) ?> screenshot"<?= $k === 0 ? ' fetchpriority="high"' : ' loading="lazy"' ?>>
                <?php endforeach; ?>
                <span class="hv-toast" id="hvToast"><i></i><span><?= e($showcase[0]['toast']) ?></span></span>
            </a>
            <div class="hv-prog" aria-hidden="true"><i id="hvProg"></i></div>
        </div>
        <div class="hv-tabs" role="tablist" aria-label="Our products">
            <?php foreach ($showcase as $k => $sc): ?>
                <button type="button" class="hv-tab<?= $k === 0 ? ' on' : '' ?>" role="tab" aria-selected="<?= $k === 0 ? 'true' : 'false' ?>" data-host="<?= e($sc['host']) ?>" data-href="<?= e($sc['href']) ?>" data-toast="<?= e($sc['toast']) ?>">
                    <b><?= e($sc['name']) ?></b><span><?= e($sc['kind']) ?></span>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</div><div class="sc" aria-hidden="true"></div></section>

<!-- Technology we actually use -->
<div class="mq-wrapper">
    <div class="mq" data-dir="1" data-speed="0.9" aria-hidden="true"><div class="mt">
        <span class="mq-chip"><i class="mq-dot"></i>PHP 8</span><span class="mq-chip"><i class="mq-dot"></i>MySQL</span><span class="mq-chip"><i class="mq-dot"></i>JavaScript</span><span class="mq-chip"><i class="mq-dot"></i>WordPress</span><span class="mq-chip"><i class="mq-dot"></i>Elementor</span><span class="mq-chip"><i class="mq-dot"></i>WooCommerce</span><span class="mq-chip"><i class="mq-dot"></i>Flutter</span><span class="mq-chip"><i class="mq-dot"></i>REST APIs</span><span class="mq-chip"><i class="mq-dot"></i>WhatsApp Cloud API</span><span class="mq-chip"><i class="mq-dot"></i>Linux / Nginx</span>
    </div></div>
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
                    <span class="prod-status"><?= e($pr['status'] ?? 'For sale') ?></span>
                    <h3><?= e($pr['title']) ?></h3>
                    <p><?= e($pr['summary'] ?? '') ?></p>
                    <div class="chips"><?php foreach ($tech as $t): ?><span><?= e($t) ?></span><?php endforeach; ?></div>
                    <div class="prod-actions">
                        <?php if ($hasLive): ?><a class="btn p" href="<?= e($live) ?>" target="_blank" rel="noopener noreferrer">Open live demo</a><?php endif; ?>
                        <a class="btn" href="#contact" data-quote="<?= e($pr['title']) ?>">Get a quote</a>
                        <a class="btn" href="<?= url('/portfolio/' . $pr['slug']) ?>">Details</a>
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
                    <b><?= e($tracker['code'] ?? 'PK-000007') ?></b>
                    <span><?= e($tracker['from'] ?? 'Karachi') ?> to <?= e($tracker['to'] ?? 'Lahore') ?></span>
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
        <div class="idx rv">Portfolio</div>
        <h2 data-split class="sp">Projects we have built</h2>
        <p class="sub rv" style="--d:.2s">Courier software, an online store with its own admin panel, and a WordPress store.</p>
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

<!-- About -->
<section id="about"><div class="wrap split">
    <div>
        <div class="idx rv">About</div>
        <h2 data-split class="sp"><?= e($about['title'] ?? ('Built by ' . setting('founder_name', 'Creatsify Team'))) ?></h2>
        <p class="sub rv" style="--d:.2s"><?= e(setting('founder_bio', 'Creatsify is a software studio in Karachi. We sell ready-made courier and store software, and build custom websites and admin panels.')) ?></p>
        <div class="row rv" style="margin-top:26px;--d:.3s">
            <a class="btn" href="<?= e(setting('founder_github', 'https://github.com/ayan2424')) ?>" target="_blank" rel="noopener noreferrer">GitHub</a>
            <a class="btn" href="<?= e(setting('founder_linkedin', 'https://linkedin.com/in/muhammadayanshamim')) ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a>
        </div>
        <div class="bdg rv s" style="--d:.4s">
            <svg viewBox="0 0 200 200" aria-hidden="true">
                <defs><path id="cp" d="M100,100 m-80,0 a80,80 0 1,1 160,0 a80,80 0 1,1 -160,0"/></defs>
                <text><textPath href="#cp" textLength="495" lengthAdjust="spacing"><?= e($aboutMeta['badge'] ?? (strtoupper(setting('site_name', 'CREATSIFY')) . ' • BUILD • SHIP • GROW • ')) ?></textPath></text>
            </svg>
            <img src="<?= asset('brand/creatsify-icon.png') ?>" alt="Creatsify emblem">
        </div>
    </div>
    <ul class="facts">
        <li class="rv r"><b>Web development</b><span>PHP, MySQL and JavaScript, without a heavy framework</span></li>
        <li class="rv r" style="--d:.1s"><b>WordPress and e-commerce</b><span>Elementor and WooCommerce stores</span></li>
        <li class="rv r" style="--d:.2s"><b>Software products</b><span>Cheetah and Cheezify, sold with full source code</span></li>
        <li class="rv r" style="--d:.3s"><b>Based in</b><span>Karachi, Pakistan</span></li>
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
                        <span>Talk to <?= e(setting('founder_name', 'the founder')) ?> directly</span>
                    </div>
                </div>
                <?php
                $waPhone = clean_phone_number(setting('whatsapp_number', setting('site_phone', '923002521199')), false);
                $waMsg = urlencode("Hello, I would like to discuss a project with " . setting('site_name', 'Creatsify Studio'));
                $displayPhone = format_phone_display(setting('site_phone', '+92 300 2521199'));
                ?>
                <a href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener" class="wa-chat-btn"><span>Message <?= e($displayPhone) ?></span> &rarr;</a>
            </div>
            <div class="contact-perks-list">
                <div class="contact-perk-item">
                    <span style="color:var(--accent);display:flex;align-items:center;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg></span>
                    <div><b>Email:</b> <a href="mailto:<?= e(setting('site_email', 'ayan@creatsify.tech')) ?>" style="color:var(--accent);"><?= e(setting('site_email', 'ayan@creatsify.tech')) ?></a></div>
                </div>
                <div class="contact-perk-item">
                    <span style="color:var(--accent);display:flex;align-items:center;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></span>
                    <div><b>Studio:</b> Karachi, Pakistan (GMT+5)</div>
                </div>
            </div>
        </div>
    </div>
    <form id="form" class="rv r" action="<?= url('/contact') ?>" method="POST">
        <?= csrf_field() ?>
        <label>Your name<input name="name" required autocomplete="name" placeholder="John Doe"></label>
        <label>Your email<input name="email" type="email" required autocomplete="email" placeholder="john@example.com"></label>
        <label>Phone / WhatsApp (optional)<input name="phone" type="tel" placeholder="+92 300 1234567" autocomplete="tel"></label>
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
        <button class="btn p" type="submit" style="width:100%;font-weight:700;">Send message &rarr;</button>
    </form>
</div></section>
