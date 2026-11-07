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
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Ready-To-Launch &amp; Customizable Platforms</div>
        <h1 class="page-title">Ready-Made Software: Buy Direct Or Customize<span class="om-dot-gold">.</span></h1>
        <p class="page-lead">
            Need to launch in days instead of months? Purchase a direct white-label license with 100% unlocked source code, OR ask our engineering team to customize any platform below with your brand identity, custom workflows, and local payment or courier gateways.
        </p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2 class="sr-only">Production Software For Sale Or Customization</h2>
        <div class="om-prod-grid">
            <?php foreach ($products as $i => $pr):
                $shot = $thumb($pr);
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
                            <span class="om-prod-pill">Ready to Ship</span>
                            <span class="om-prod-rights">&#10003; 100% Unlocked Code + Custom Tailoring</span>
                        </div>
                        <h3 class="om-prod-title"><?= e($pr['title']) ?></h3>
                        <p class="om-prod-desc"><?= e($pr['summary'] ?? '') ?></p>
                        <div class="om-prod-chips">
                            <?php foreach ($tech as $t): ?><span><?= e($t) ?></span><?php endforeach; ?>
                        </div>
                        <div class="om-prod-actions">
                            <?php if (!empty($pr['gumroad_url'])): ?>
                                <a class="om-btn-gold" href="<?= e($pr['gumroad_url']) ?>" target="_blank" rel="noopener noreferrer">
                                    <span>Buy Direct (<?= e($priceStr) ?>)</span>
                                    <span class="om-btn-circle-arrow">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                    </span>
                                </a>
                            <?php endif; ?>
                            <a class="om-btn-glass light" href="<?= url('/contact?kind=software_custom&offering=' . urlencode('Customize ' . $pr['title'])) ?>#inquiry-form">
                                <span>Customize For Me &#9998;</span>
                            </a>
                            <?php if ($hasLive): ?>
                                <a class="om-prod-link" href="<?= e($live) ?>" target="_blank" rel="noopener noreferrer">Live Demo ↗</a>
                            <?php else: ?>
                                <a class="om-prod-link" href="<?= url('/portfolio/' . $pr['slug']) ?>">Details &rarr;</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Custom Modification & Server Deployment Bento Card -->
        <div class="om-bento-card" style="margin-top:48px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;">
            <div style="max-width:680px;">
                <div class="om-sec-tag" style="margin-bottom:8px;"><span class="om-sec-dot"></span> Custom Tailoring &amp; Deployment</div>
                <h3 style="font-family:Sora,sans-serif;font-size:20px;font-weight:800;color:var(--text);margin:0 0 6px;">Want Us To Customize &amp; Deploy A System For Your Brand?</h3>
                <p style="font-size:14px;color:var(--muted);line-height:1.6;margin:0;">
                    Our engineering team can rebrand any ready-made platform above, add custom modules or Flutter mobile apps, integrate your local payment/courier APIs, and deploy it directly on your aaPanel or Linux VPS server.
                </p>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <a class="om-btn-gold" href="<?= url('/contact') ?>#inquiry-form">
                    <span>Request Custom Changes</span>
                    <span class="om-btn-circle-arrow">&rarr;</span>
                </a>
                <a class="om-btn-glass" href="<?= url('/support') ?>">
                    <span>Open Support Desk</span>
                </a>
            </div>
        </div>
    </div>
</section>
