<?php
/** @var Core\View $this */
$this->layout('layouts/main');

$hero = $sections['hero'] ?? [];
$heroMeta = $hero['metadata'] ?? [];
$rotatingWords = $heroMeta['rotating_words'] ?? ['courier companies', 'online stores', 'learning platforms', 'media brands', 'startups', 'restaurant chains'];
$heroChips = $heroMeta['chips'] ?? ['7 role portals in Cheetah', 'Direct software licensing', 'Agency delivery team'];

$projectsJson = array_map(function (array $project): array {
    $tech = array_slice(array_filter(array_map('trim', explode(',', (string)($project['tech_stack'] ?? 'PHP, MySQL')))), 0, 4);
    $links = [];
    if (!empty($project['live_url']) && $project['live_url'] !== '#') {
        $links[] = ['Live demo', $project['live_url']];
    }
    $links[] = ['Case study', url('/portfolio/' . $project['slug'])];
    if (!empty($project['gumroad_url'])) {
        $price = !empty($project['price']) ? ' (' . $project['price'] . ')' : '';
        $links[] = ['Buy on Gumroad' . $price, $project['gumroad_url']];
    }

    $image = $project['image_url'] ?? '';
    if ($image === '' || str_contains($image, 'creatsify-icon.png')) {
        $title = strtolower((string)$project['title']);
        $image = str_contains($title, 'cheetah') ? asset('images/cheetah-showcase.jpg')
            : ((str_contains($title, 'restro') || str_contains($title, 'flavor')) ? asset('images/restromaster-preview.jpg')
            : ((str_contains($title, 'vidd') || str_contains($title, 'media') || str_contains($title, 'browse')) ? asset('images/viddmate-preview.jpg') : asset('images/hero-artwork.jpg')));
    } else {
        $image = asset($image);
    }

    return [
        $project['title'],
        $project['category'] ?: 'p',
        $project['status'] ?: 'Shipped',
        $project['summary'] ?: $project['description'],
        $tech,
        $image,
        $links,
    ];
}, $projects ?? []);
?>

<script>window.CREATSIFY_PROJECTS = <?= json_encode($projectsJson) ?>;</script>

<section class="hero hero-agency" id="hero"><div class="hero-glow" aria-hidden="true"></div><div class="wrap">
    <div class="hero-copy">
        <div class="pill hero-location hf"><i></i>Full-stack studio · Karachi, Pakistan</div>
        <div class="hero-kicker hf" style="--d:.16s"><span>Creatsify / Operating studio</span><span>Products · systems · creative delivery</span></div>
        <h1><span class="ln"><span class="ch" style="--i:0"><?= e($hero['title'] ?? 'We build software') ?></span></span><span class="ln"><span class="ch" style="--i:1">for <span class="rot grad" id="rot"><?= e($rotatingWords[0] ?? 'courier companies') ?></span></span></span></h1>
        <p class="lead hf" style="--d:.5s"><?= e($hero['content'] ?? 'From idea to a live product. We build courier platforms, online stores, learning tools and apps that real businesses can run on.') ?></p>
        <div class="row hf" style="--d:.65s;flex-wrap:wrap">
            <a class="btn p" href="<?= url('/contact') ?>">Start a project <span aria-hidden="true">→</span></a>
            <a class="btn" href="<?= url('/portfolio') ?>">Explore ready software</a>
            <a class="hero-support-link" href="<?= url('/support') ?>">Already a customer? Get help &rarr;</a>
        </div>
        <div class="trust-wrap hf" style="--d:.8s">
            <span class="trust-label">One team from first brief to post-launch support.</span>
            <div class="trust-badges">
                <div class="trust-badge"><span class="trust-number">01</span><span><b>Custom delivery</b><span class="trust-sub">Web, app &amp; creative work</span></span></div>
                <div class="trust-badge"><span class="trust-number">02</span><span><b>Ready software</b><span class="trust-sub">Direct licensed systems</span></span></div>
                <div class="trust-badge"><span class="trust-number">03</span><span><b>Real support</b><span class="trust-sub">One first-party ticket desk</span></span></div>
            </div>
        </div>
    </div>
    <div class="fv hf" style="--d:.45s"><div class="fw" id="hst">
        <div class="ly" data-d="10"><div class="frame" id="hframe" data-tilt="8"><div class="frame-status"><i></i><span>Currently shipping</span><b>Studio delivery</b></div><img id="heroimg" src="<?= asset('images/hero-artwork.jpg') ?>" alt="Creatsify digital product artwork"><div class="frame-caption"><p>From a clear brief to a business-ready system.</p><span>Explore our work <b aria-hidden="true">↗</b></span></div></div></div>
        <div class="ly l2" data-d="38"><div class="mini"><img id="hm1" src="<?= asset('images/cheetah-showcase.jpg') ?>" alt="Cheetah dashboard"><span>Cheetah</span></div></div>
        <div class="ly l3" data-d="56"><div class="mini"><img id="hm2" src="<?= asset('images/viddmate-preview.jpg') ?>" alt="ViddMate app"><span>ViddMate</span></div></div>
        <div class="ly c1" data-d="26"><span class="chipi"><i></i><?= e($heroChips[0] ?? '7 role portals in Cheetah') ?></span></div>
        <div class="ly c2" data-d="44"><span class="chipi chipi-delivery" style="animation-delay:1.4s"><i></i><?= e($heroChips[2] ?? 'Agency delivery team') ?></span></div>
    </div></div>
</div><div class="sc" aria-hidden="true"></div></section>

<div class="homepage-v2">
    <section class="home-routes"><div class="wrap">
        <div class="home-route-grid rv">
            <a class="home-route" href="<?= url('/contact?kind=custom_service') ?>"><span class="section-tag">01 · New work</span><h2>Build something custom.</h2><p>Bring us a product, service, workflow, campaign, or ongoing delivery need.</p><b>Start a project brief &rarr;</b></a>
            <a class="home-route" href="<?= url('/portfolio') ?>"><span class="section-tag">02 · Ready software</span><h2>Start from a proven system.</h2><p>Explore live demos, case studies, direct licensing, and implementation options.</p><b>Browse the catalog &rarr;</b></a>
            <a class="home-route home-route-support" href="<?= url('/support') ?>"><span class="section-tag">03 · Existing customers</span><h2>Get help with a ticket.</h2><p>For delivery, maintenance, licensing, deployment, billing, or technical support.</p><b>Open customer support &rarr;</b></a>
        </div>
    </div></section>

    <div class="wrap home-proof-wrap"><div class="stats rv s home-proof">
        <div><b data-n="10">10+</b><span>Commercial products shipped</span></div>
        <div><b data-n="78">78</b><span>Tables in our flagship architecture</span></div>
        <div><b data-n="100">100%</b><span>White-label source ownership</span></div>
    </div></div>

    <?php if (!empty($services)): ?>
        <section class="home-section service-bridge" id="capabilities"><div class="wrap">
            <div class="home-section-heading">
                <div class="idx rv">01 · Agency services</div>
                <h2 data-split class="sp">A delivery team, not a directory.</h2>
                <p class="sub rv" style="--d:.2s">Focused services with clear ownership, practical milestones, and a team that stays accountable after launch.</p>
            </div>
            <div class="home-service-grid rv" style="--d:.25s">
                <?php foreach (array_slice($services, 0, 4) as $service): ?>
                    <article class="home-service-card"><span class="section-tag"><?= e($service['category'] ?? 'Agency service') ?></span><h3><?= e($service['title']) ?></h3><p><?= e($service['short_description'] ?? $service['description'] ?? '') ?></p><a href="<?= url('/contact?kind=custom_service&offering=' . urlencode($service['title'])) ?>">Discuss this service &rarr;</a></article>
                <?php endforeach; ?>
            </div>
            <a class="btn" href="<?= url('/services') ?>">View all services &rarr;</a>
        </div></section>
    <?php endif; ?>

    <section class="home-section home-work" id="work"><div class="wrap">
        <div class="home-section-heading">
            <div class="idx rv">02 · Selected software</div>
            <h2 data-split class="sp">Proven systems, not concept screens.</h2>
            <p class="sub rv" style="--d:.2s">A focused selection of products with live demos, case studies, direct licensing, and implementation support.</p>
        </div>
        <div class="pjs" id="pjs"></div>
    </div></section>

    <section class="home-section home-delivery alt"><div class="wrap">
        <div class="home-section-heading">
            <div class="idx rv">03 · How we work</div>
            <h2 data-split class="sp">Clear from first brief to support.</h2>
            <p class="sub rv" style="--d:.2s">Your work is documented, owned by named people, and carried into a support process after delivery.</p>
        </div>
        <div class="delivery-grid rv" style="--d:.25s">
            <article class="delivery-card"><span>01</span><h3>Scope together</h3><p>We turn the request into an outcome, priorities, realistic milestones, and the right delivery team.</p></article>
            <article class="delivery-card"><span>02</span><h3>Build with visibility</h3><p>Projects are managed with assigned team members, practical handoffs, and a single accountable workflow.</p></article>
            <article class="delivery-card"><span>03</span><h3>Launch and support</h3><p>After release, existing clients can open a tracked ticket for service, software, maintenance, or deployment help.</p></article>
        </div>
    </div></section>

    <?php if (!empty($teamMembers)): ?>
        <section class="home-section home-team" id="team"><div class="wrap">
            <div class="home-section-heading home-heading-with-action">
                <div><div class="idx rv">04 · The people doing the work</div><h2 data-split class="sp">Meet the delivery team.</h2><p class="sub rv" style="--d:.2s">Specialists can be assigned to the project, and their portfolios stay connected to the agency work they deliver.</p></div>
                <a class="btn rv" href="<?= url('/team') ?>" style="--d:.25s">View the full team &rarr;</a>
            </div>
            <div class="home-team-grid rv" style="--d:.25s">
                <?php foreach (array_slice($teamMembers, 0, 3) as $member): ?>
                    <?php $skills = array_slice(array_filter(array_map('trim', explode(',', (string)($member['skills'] ?? '')))), 0, 3); ?>
                    <article class="home-team-card">
                        <img src="<?= asset($member['avatar'] ?: 'brand/creatsify-icon.png') ?>" alt="<?= e($member['name']) ?>">
                        <div><span><?= e($member['role_title']) ?></span><h3><?= e($member['name']) ?></h3><p><?= e(mb_strimwidth((string)($member['bio'] ?? ''), 0, 110, '...')) ?></p><div class="home-team-skills"><?php foreach ($skills as $skill): ?><em><?= e($skill) ?></em><?php endforeach; ?></div><a href="<?= url('/team/' . $member['slug']) ?>">View portfolio &rarr;</a></div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div></section>
    <?php endif; ?>

    <section id="calculator" class="home-section calc-section"><div class="wrap">
        <div class="home-section-heading"><div class="idx rv">05 · Scope estimator</div><h2 data-split class="sp">Turn an idea into a practical starting point.</h2><p class="sub rv" style="--d:.2s">Pick a platform and the capabilities you need. We will prefill your inquiry with the resulting project brief.</p></div>
        <div class="calc-box rv" id="projectCalculator" style="--d:.3s;">
            <div class="calc-grid">
                <div>
                    <div class="calc-features-title">1. Choose a starting point</div>
                    <div class="calc-platforms">
                        <div class="calc-platform-card selected" data-platform="courier"><b>Courier &amp; warehouse</b><span>Hubs, dispatch, tracking, merchant portals, and ePOD.</span></div>
                        <div class="calc-platform-card" data-platform="restaurant"><b>Restaurant operations</b><span>QR ordering, POS, kitchen display, and delivery.</span></div>
                        <div class="calc-platform-card" data-platform="saas"><b>Custom web platform</b><span>Web apps, client portals, APIs, and subscription workflows.</span></div>
                        <div class="calc-platform-card" data-platform="mobile"><b>Mobile application</b><span>Cross-platform Android and iOS with offline-ready flows.</span></div>
                    </div>
                    <div class="calc-features-title">2. Add what the project needs</div>
                    <div class="calc-features-list">
                        <div class="calc-feature-item checked" data-weeks="1" data-modules="2" data-tables="8"><input type="checkbox" checked><span>WhatsApp, SMS, or customer notifications</span></div>
                        <div class="calc-feature-item checked" data-weeks="1.5" data-modules="3" data-tables="12"><input type="checkbox" checked><span>Role-based access for your team and customers</span></div>
                        <div class="calc-feature-item" data-weeks="1" data-modules="2" data-tables="6"><input type="checkbox"><span>Payments, billing, or checkout</span></div>
                        <div class="calc-feature-item" data-weeks="1.5" data-modules="2" data-tables="8"><input type="checkbox"><span>Live location, dispatch, or operational telemetry</span></div>
                        <div class="calc-feature-item" data-weeks="1" data-modules="1" data-tables="4"><input type="checkbox"><span>PDFs, invoices, labels, or operational documents</span></div>
                    </div>
                </div>
                <aside class="calc-summary-panel"><div><div class="calc-summary-title">Scope snapshot</div><p>We will validate the brief with you before promising dates, price, or a technical solution.</p><div class="calc-timeline-badge" id="calcTimeline">⏱ Estimated velocity: 4 – 6 weeks</div><div id="calcModules">8 core modules • 68 normalized DB tables</div><ul class="calc-blueprint-items"><li>Clear milestone-based delivery</li><li>Source code and deployment handoff</li><li>Named team ownership</li><li>First-party support after launch</li></ul></div><button type="button" class="btn p calc-prefill-btn" id="calcPrefillBtn">Use this brief &rarr;</button></aside>
            </div>
        </div>
    </div></section>

    <section id="contact" class="home-section home-final"><div class="wrap home-final-grid">
        <div class="home-final-copy"><div class="idx rv">06 · Start a conversation</div><h2 data-split class="sp">Tell us what needs to move.</h2><p class="sub rv" style="--d:.2s">Whether you are starting a project or checking fit for a product, send a short brief. Existing customers should use the <a href="<?= url('/support') ?>">customer support desk</a> for a tracked request.</p><div class="home-final-links"><a href="<?= url('/contact') ?>">Use the full project brief &rarr;</a><a href="<?= url('/support') ?>">Open customer support &rarr;</a></div></div>
        <form id="form" class="rv r home-inquiry-form" action="<?= url('/contact') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="inquiry_kind" value="custom_service"><input type="text" name="_hp_check" value="" class="visually-hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
            <label>Your name<input name="name" required autocomplete="name" placeholder="John Doe"></label>
            <label>Your email<input name="email" type="email" required autocomplete="email" placeholder="john@company.com"></label>
            <label>Project type<select name="project_type"><option>Website or WordPress store</option><option>Web app or SaaS</option><option>Mobile or desktop app</option><option>Courier or warehouse system</option><option>Restaurant ordering system</option><option>AI tool</option></select></label>
            <label>Timeline<select name="timeline"><option value="">Not sure yet</option><option>As soon as possible</option><option>Within 1 month</option><option>1–3 months</option><option>3+ months / ongoing</option></select></label>
            <label>What are you trying to achieve?<textarea name="message" rows="5" required placeholder="Describe the outcome, audience, and anything that is important to the project..."></textarea></label>
            <button class="btn p" type="submit">Send project inquiry &rarr;</button>
        </form>
    </div></section>
</div>
