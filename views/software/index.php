<?php
/** @var Core\View $this */
$this->layout('layouts/main');
$assetDir = dirname(__DIR__, 2) . '/public/assets/images/';
$thumb = static function (array $p) use ($assetDir) {
    $file = $p['slug'] === 'cheetah-enterprise' ? 'cheetah-showcase.jpg' : $p['slug'] . '-preview.jpg';
    return is_file($assetDir . $file) ? 'images/' . $file : null;
};
?>

<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Commercial Systems</div>
        <h1 class="page-title">White-Label Software You Can Buy<span class="om-dot-gold">.</span></h1>
        <p class="page-lead">Production-ready courier logistics backbones, online store engines, and restaurant POS platforms. You receive the complete 100% unlocked source code, commercial rights, and direct setup support from our three co-founders.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2 class="sr-only">Production Software For Sale</h2>
        <div class="om-prod-grid">
            <?php foreach ($products as $i => $pr):
                $shot = $thumb($pr);
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
                                <a class="om-btn-gold" href="<?= url('/contact?kind=software_presale&offering=' . urlencode($pr['title'])) ?>#inquiry-form">
                                    <span>Get a Quote<?= !empty($pr['price']) ? ' (' . e($pr['price']) . ')' : '' ?></span>
                                    <span class="om-btn-circle-arrow">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                    </span>
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

        <!-- aaPanel Deployment Note Bento Card -->
        <div class="om-bento-card" style="margin-top:48px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;">
            <div style="max-width:680px;">
                <div class="om-sec-tag" style="margin-bottom:8px;"><span class="om-sec-dot"></span> aaPanel Deployment</div>
                <h3 style="font-family:Sora,sans-serif;font-size:20px;font-weight:800;color:var(--text);margin:0 0 6px;">Need Help Deploying On Your Server?</h3>
                <p style="font-size:14px;color:var(--muted);line-height:1.6;margin:0;">
                    We provide direct co-founder assistance setting up your Nginx URL rewrites, PHP 8.4 runtime, and MySQL database tables on aaPanel or any standard Linux VPS.
                </p>
            </div>
            <div>
                <a class="om-btn-glass" href="<?= url('/support') ?>">
                    <span>Open Support Desk</span>
                    <span class="om-btn-circle-arrow">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</section>
