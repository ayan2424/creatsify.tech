<?php
/** @var Core\View $this */
$this->layout('layouts/main');
$assetDir = dirname(__DIR__, 2) . '/public/assets/images/';
$img = $project['image_url'] ?? '';
if (empty($img) || str_contains($img, 'creatsify-icon.png')) {
    $file = $project['slug'] === 'cheetah-enterprise' ? 'cheetah-showcase.jpg' : $project['slug'] . '-preview.jpg';
    $img = is_file($assetDir . $file) ? asset('images/' . $file) : '';
} else {
    $img = asset($img);
}
$live = $project['live_url'] ?? '';
$hasLive = $live && $live !== '#';
$forSale = ($project['status'] ?? '') === 'For sale';
$host = $hasLive ? (parse_url($live, PHP_URL_HOST) ?: 'creatsify.tech') : 'creatsify.tech';
?>
<section class="page-hero case-hero">
    <div class="wrap">
        <a href="<?= url('/portfolio') ?>" class="more">&larr; Back to the portfolio</a>
        <div class="pill" style="margin-top:22px"><i></i><span><?= e(ucfirst($project['category'])) ?> &bull; <?= e($project['status']) ?></span></div>
        <h1 class="page-title"><?= e($project['title']) ?></h1>
        <p class="lead case-lead"><?= e($project['summary']) ?></p>
        <div class="case-chips">
            <?php foreach (array_filter(array_map('trim', explode(',', $project['tech_stack'] ?? ''))) as $stackItem): ?>
                <span class="stack-chip"><?= e($stackItem) ?></span>
            <?php endforeach; ?>
        </div>
        <div class="case-actions">
            <?php if ($hasLive): ?><a class="btn p" href="<?= e($live) ?>" target="_blank" rel="noopener">Open live demo &nearr;</a><?php endif; ?>
            <?php if (!empty($project['gumroad_url'])): ?>
                <a class="btn p" href="<?= e($project['gumroad_url']) ?>" target="_blank" rel="noopener">Buy<?= !empty($project['price']) ? ' (' . e($project['price']) . ')' : '' ?> &rarr;</a>
            <?php elseif ($forSale): ?>
                <a class="btn p" href="<?= url('/contact?t=' . urlencode($project['title'])) ?>">Get a quote</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section case-section">
    <div class="wrap">
        <div class="case-shot">
            <div class="case-bar"><span class="hv-dots" aria-hidden="true"><i></i><i></i><i></i></span><span class="case-url"><?= e($host) ?></span></div>
            <?php if ($img): ?>
                <img src="<?= e($img) ?>" alt="<?= e($project['title']) ?> screenshot">
            <?php else: ?>
                <div class="prod-cover case-cover"><b><?= e($project['title']) ?></b><span><?= e(ucfirst($project['category'])) ?></span></div>
            <?php endif; ?>
        </div>

        <div class="case-grid">
            <div class="pg-card pg-prose">
                <h2>Overview</h2>
                <p><?= nl2br(e($project['description'] ?: $project['summary'])) ?></p>
                <?php if ($forSale): ?>
                    <h3>What you get</h3>
                    <ul>
                        <li>The full source code, with nothing locked.</li>
                        <li>White-label rights, so you can use your own brand.</li>
                        <li>A live demo to try before you decide.</li>
                        <li>Help with setup and going live.</li>
                    </ul>
                <?php endif; ?>
            </div>

            <aside>
                <div class="pg-card case-meta">
                    <h4>Details</h4>
                    <dl>
                        <div><dt>Status</dt><dd><?= e($project['status']) ?></dd></div>
                        <div><dt>Type</dt><dd><?= e(ucfirst($project['category'])) ?></dd></div>
                        <?php if (!empty($project['client'])): ?><div><dt>Built for</dt><dd><?= e($project['client']) ?></dd></div><?php endif; ?>
                        <?php if (!empty($project['price'])): ?><div><dt>License price</dt><dd class="case-price"><?= e($project['price']) ?></dd></div><?php endif; ?>
                    </dl>
                </div>
                <div class="pg-card case-support">
                    <h4>Already bought this?</h4>
                    <p>We help with installation, server setup and changes.</p>
                    <a href="<?= url('/support?script=' . urlencode($project['title'])) ?>" class="btn btn-block">Open a support request &rarr;</a>
                </div>
            </aside>
        </div>
    </div>
</section>
