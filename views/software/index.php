<?php
/** @var Core\View $this */
$this->layout('layouts/main');
$assetDir = dirname(__DIR__, 2) . '/public/assets/images/';
$thumb = static function (array $p) use ($assetDir) {
    $slug = $p['slug'] ?? '';
    $map = [
        'cheetah-enterprise'         => 'images/cheetah-showcase.jpg',
        'cheetah-lite'               => 'images/cheetah-lite-preview.jpg',
        'cheezify'                   => 'images/cheezify-preview.jpg',
        'restromaster-kds-interface' => 'images/restromaster-preview.jpg',
        'viddmate-video-suite'       => 'images/viddmate-preview.jpg',
    ];
    if (isset($map[$slug]) && is_file($assetDir . basename($map[$slug]))) {
        return $map[$slug];
    }
    $file = $slug . '-preview.jpg';
    return is_file($assetDir . $file) ? 'images/' . $file : null;
};
?>

<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Ready-To-Launch &amp; Customizable Platforms</div>
        <h1 class="page-title">Ready-Made Software: Buy Direct Or Customize</h1>
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
                            <span class="om-prod-pill"><?= e(!empty($pr['subcategory']) ? $pr['subcategory'] : 'Commercial Script') ?></span>
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
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    <span>Buy License (<?= e($priceStr) ?>)</span>
                                </a>
                            <?php endif; ?>
                            <a class="om-btn-glass light" href="<?= url('/contact?kind=software_custom&offering=' . urlencode('Customize ' . $pr['title'])) ?>#inquiry-form">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                <span>Customize For Me</span>
                            </a>
                            <?php if ($hasLive): ?>
                                <a class="om-btn-glass light" href="<?= e($live) ?>" target="_blank" rel="noopener noreferrer">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                    <span>Live Demo</span>
                                </a>
                            <?php else: ?>
                                <a class="om-btn-glass light" href="<?= url('/portfolio/' . $pr['slug']) ?>">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                    <span>View Specs</span>
                                </a>
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
                    Our engineering team can rebrand any ready-made platform above, add custom modules or Flutter mobile apps, integrate your local payment/courier APIs, and deploy it directly on your cloud VPS or hosting infrastructure.
                </p>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <a class="om-btn-gold" href="<?= url('/contact') ?>#inquiry-form">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    <span>Request Custom Changes</span>
                </a>
                <a class="om-btn-glass" href="<?= url('/support') ?>">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    <span>Open Support Desk</span>
                </a>
            </div>
        </div>
    </div>
</section>
