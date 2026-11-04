<?php
/** @var Core\View $this */
$this->layout('layouts/main');
$assetDir = dirname(__DIR__, 2) . '/public/assets/images/';
$thumb = static function (array $p) use ($assetDir) {
    $file = $p['slug'] === 'cheetah-enterprise' ? 'cheetah-showcase.jpg' : $p['slug'] . '-preview.jpg';
    return is_file($assetDir . $file) ? 'images/' . $file : null;
};
$slugs = array_column($products, 'slug');
?>
<section class="page-hero">
    <div class="wrap">
        <span class="section-tag">White-label software</span>
        <h1 class="page-title">Software you can buy</h1>
        <p class="page-lead">Ready-made courier and online store software. You get the full source code, white-label rights and help with setup. Open the live demo first, then ask for a quote.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2 class="sr-only">Software for sale</h2>
        <div class="projects-grid">
            <?php foreach ($products as $project):
                $shot = $thumb($project);
                $live = $project['live_url'] ?? '';
                $hasLive = $live && $live !== '#';
            ?>
                <article class="project-card">
                    <a class="prod-media" href="<?= url('/portfolio/' . e($project['slug'])) ?>">
                        <?php if ($shot): ?>
                            <img src="<?= asset($shot) ?>" width="1376" height="768" loading="lazy" alt="<?= e($project['title']) ?> screenshot">
                        <?php else: ?>
                            <div class="prod-cover"><b><?= e($project['title']) ?></b><span><?= e(ucfirst($project['category'])) ?></span></div>
                        <?php endif; ?>
                    </a>
                    <div class="project-card-header">
                        <div class="project-category-badge"><?= strtoupper(e($project['category'])) ?></div>
                        <div class="project-status-badge"><?= !empty($project['price']) ? e($project['price']) : 'For sale' ?></div>
                    </div>
                    <div class="project-card-body">
                        <h3 class="project-title"><a href="<?= url('/portfolio/' . e($project['slug'])) ?>"><?= e($project['title']) ?></a></h3>
                        <p class="project-summary"><?= e($project['summary']) ?></p>
                        <div class="project-stack">
                            <?php foreach (array_filter(array_map('trim', explode(',', $project['tech_stack'] ?? ''))) as $stackItem): ?>
                                <span class="stack-chip"><?= e($stackItem) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="project-card-footer pc-actions">
                        <a href="<?= url('/portfolio/' . e($project['slug'])) ?>" class="project-btn-case">Details &rarr;</a>
                        <?php if ($hasLive): ?><a href="<?= e($live) ?>" target="_blank" rel="noopener" class="project-btn-case">Live demo &nearr;</a><?php endif; ?>
                        <?php if (!empty($project['gumroad_url'])): ?>
                            <a href="<?= e($project['gumroad_url']) ?>" target="_blank" rel="noopener" class="btn p pc-buy">Buy<?= !empty($project['price']) ? ' (' . e($project['price']) . ')' : '' ?></a>
                        <?php else: ?>
                            <a href="<?= url('/contact?kind=software_presale&offering=' . urlencode($project['title'])) ?>#inquiry-form" class="btn p pc-buy">Get a quote</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($products)): ?>
            <div class="pg-card"><p>Our software list is being updated. <a href="<?= url('/contact') ?>">Contact us</a> for what is available.</p></div>
        <?php endif; ?>

        <div class="top" style="margin-top:72px"><div class="idx">Every license includes</div><h2 class="sp">What you get with the software</h2></div>
        <div class="inc-grid">
            <div class="pg-card inc"><span class="inc-ico">&lt;/&gt;</span><h3>Full source code</h3><p>Nothing is locked. You own the files and can change them.</p></div>
            <div class="pg-card inc"><span class="inc-ico">&#9733;</span><h3>White-label rights</h3><p>Run it under your own name and brand.</p></div>
            <div class="pg-card inc"><span class="inc-ico">&#9654;</span><h3>A live demo first</h3><p>Click through the real admin panel before you decide.</p></div>
            <div class="pg-card inc"><span class="inc-ico">&#10003;</span><h3>Help with setup</h3><p>We help you install it and go live.</p></div>
        </div>

        <?php if (in_array('cheetah-enterprise', $slugs, true) && in_array('cheetah-lite', $slugs, true)): ?>
            <div class="top" style="margin-top:72px"><div class="idx">Cheetah</div><h2 class="sp">Which Cheetah do you need?</h2></div>
            <div class="pick-grid">
                <div class="pg-card pick"><h3>Cheetah Enterprise</h3><p>Choose this if you run couriers <strong>and</strong> warehouses, or work across several branches. It includes the warehouse module, the picker role, role portals and the Flutter driver and picker app.</p><a class="btn p" href="<?= url('/portfolio/cheetah-enterprise') ?>">See Enterprise &rarr;</a></div>
                <div class="pg-card pick"><h3>Cheetah Lite</h3><p>Choose this if you only run courier deliveries. It is the lighter edition, without the warehouse module and without the picker role.</p><a class="btn" href="<?= url('/portfolio/cheetah-lite') ?>">See Lite &rarr;</a></div>
            </div>
        <?php endif; ?>

        <div class="top" style="margin-top:72px"><div class="idx">Questions</div><h2 class="sp">Before you buy</h2></div>
        <div class="faq">
            <details><summary>What do I get when I buy?</summary><p>The full source code, white-label rights so you can use your own brand, and help with setup.</p></details>
            <details><summary>Can I try it first?</summary><p>Yes. Every product has a live demo you can open and click through.</p></details>
            <details><summary>Can you change it for my business?</summary><p>Yes. We can adapt the software to your branches, riders, products or brand. Ask for a quote and tell us what you need.</p></details>
            <details><summary>Where can I host it?</summary><p>It is plain PHP and MySQL, so it runs on most shared hosts, VPS servers and control panels such as aaPanel.</p></details>
            <details><summary>What if I need help later?</summary><p>Open a request on the <a href="<?= url('/support') ?>">support page</a>. Every request gets a ticket number.</p></details>
        </div>

        <div class="pg-cta">
            <div><h3>Ready to buy, or not sure yet?</h3><p>Tell us which product you are looking at and we reply with the price and the next steps.</p></div>
            <div class="pg-cta-btns"><a class="btn p" href="<?= url('/contact?kind=software_presale') ?>#inquiry-form">Get a quote</a><a class="btn" href="<?= url('/portfolio') ?>">See our work</a></div>
        </div>
    </div>
</section>
