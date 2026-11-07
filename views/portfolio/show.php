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

$waPhone = clean_phone_number(setting('whatsapp_number', setting('site_phone', '923002521199')), false);
$waMsg = urlencode("Hello! I am interested in licensing or customizing " . ($project['title'] ?? 'software') . " from Creatsify.");
?>

<!-- Omoway Case Study Hero -->
<section class="page-hero om-case-hero">
    <div class="wrap">
        <a href="<?= url('/portfolio') ?>" class="om-back-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            <span>Back to Portfolio</span>
        </a>

        <div style="margin-top:20px;">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> <?= e(ucfirst($project['category'])) ?> &bull; <?= e($project['status']) ?></div>
            <h1 class="page-title"><?= e($project['title']) ?><span class="om-dot-gold">.</span></h1>
            <p class="page-lead"><?= e($project['summary']) ?></p>

            <div class="om-prod-chips" style="margin:20px 0 28px;">
                <?php foreach (array_filter(array_map('trim', explode(',', $project['tech_stack'] ?? ''))) as $stackItem): ?>
                    <span><?= e($stackItem) ?></span>
                <?php endforeach; ?>
            </div>

            <div class="om-case-actions">
                <?php if ($hasLive): ?>
                    <a class="om-btn-gold" href="<?= e($live) ?>" target="_blank" rel="noopener">
                        <span>Open Live Demo</span>
                        <span class="om-btn-circle-arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </span>
                    </a>
                <?php endif; ?>

                <?php if (!empty($project['gumroad_url'])): ?>
                    <a class="om-btn-gold" href="<?= e($project['gumroad_url']) ?>" target="_blank" rel="noopener">
                        <span>Buy White-Label License<?= !empty($project['price']) ? ' (' . e($project['price']) . ')' : '' ?></span>
                        <span class="om-btn-circle-arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </span>
                    </a>
                <?php elseif ($forSale): ?>
                    <a class="om-btn-gold" href="<?= url('/contact?kind=software_presale&offering=' . urlencode($project['title'])) . '#inquiry-form' ?>">
                        <span>Request License Quote<?= !empty($project['price']) ? ' (' . e($project['price']) . ')' : '' ?></span>
                        <span class="om-btn-circle-arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </span>
                    </a>
                <?php endif; ?>

                <a class="om-btn-glass" href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener">
                    <span>Chat on WhatsApp</span>
                    <span class="om-btn-circle-plus">+</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Omoway Case Study Showcase & Body -->
<section class="section" style="padding-top:40px;">
    <div class="wrap">
        <!-- Interactive Preview Frame with macOS Dots -->
        <div class="om-prod-card" style="margin-bottom:48px; border-radius:28px; overflow:hidden;">
            <div class="om-prod-media-frame" style="aspect-ratio: 21/9; min-height: 380px;">
                <div class="om-prod-bar">
                    <div class="om-prod-dots">
                        <span class="dot d-red"></span>
                        <span class="dot d-yellow"></span>
                        <span class="dot d-green"></span>
                    </div>
                    <span class="om-prod-badge-price"><?= !empty($project['price']) ? e($project['price']) : 'Direct License' ?></span>
                </div>
                <?php if ($img): ?>
                    <img src="<?= e($img) ?>" alt="<?= e($project['title']) ?> screenshot" class="om-prod-img" style="object-fit:cover;">
                <?php else: ?>
                    <div class="om-prod-cover">
                        <div class="om-prod-cover-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        </div>
                        <b><?= e($project['title']) ?></b>
                        <span><?= e(ucfirst($project['category'])) ?> &bull; Production Platform</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bento Grid: Main Specs & Aside Metadata -->
        <div class="om-case-grid">
            <div class="om-bento-card">
                <div class="om-sec-tag"><span class="om-sec-dot"></span> Architecture Overview</div>
                <h2 style="font-family:Sora,sans-serif;font-size:24px;font-weight:800;margin:12px 0 16px;color:var(--text);"><?= e($project['title']) ?> System Specs</h2>
                <div style="font-size:15px;line-height:1.75;color:var(--muted);margin-bottom:28px;">
                    <?= nl2br(e($project['description'] ?: $project['summary'])) ?>
                </div>

                <?php if ($forSale): ?>
                    <div style="padding:24px;border-radius:18px;background:color-mix(in srgb,var(--surface) 60%,var(--bg));border:1px solid var(--line);">
                        <h3 style="font-family:Sora,sans-serif;font-size:18px;font-weight:800;margin:0 0 14px;color:var(--text);">Commercial License Inclusions</h3>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:var(--text);"><i style="color:#10b981;font-weight:800;font-style:normal;">&#10003;</i> 100% Unlocked Source Code</div>
                            <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:var(--text);"><i style="color:#10b981;font-weight:800;font-style:normal;">&#10003;</i> Full White-Label Rights</div>
                            <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:var(--text);"><i style="color:#10b981;font-weight:800;font-style:normal;">&#10003;</i> Active Live Interactive Demo</div>
                            <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:var(--text);"><i style="color:#10b981;font-weight:800;font-style:normal;">&#10003;</i> Direct Setup &amp; Deployment Help</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="om-case-aside">
                <div class="om-bento-card">
                    <h3 style="font-family:Sora,sans-serif;font-size:17px;font-weight:800;margin:0 0 16px;color:var(--text);">System Metadata</h3>
                    <div style="display:flex;flex-direction:column;gap:14px;">
                        <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--line);font-size:13.5px;">
                            <span style="color:var(--muted);">Category</span>
                            <span style="font-weight:700;color:var(--text);"><?= e(ucfirst($project['category'])) ?></span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--line);font-size:13.5px;">
                            <span style="color:var(--muted);">Status</span>
                            <span style="font-weight:700;color:var(--accent);"><?= e($project['status']) ?></span>
                        </div>
                        <?php if (!empty($project['price'])): ?>
                            <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--line);font-size:13.5px;">
                                <span style="color:var(--muted);">License Price</span>
                                <span style="font-weight:800;color:#D4A017;font-size:16px;"><?= e($project['price']) ?></span>
                            </div>
                        <?php endif; ?>
                        <div style="display:flex;justify-content:space-between;font-size:13.5px;">
                            <span style="color:var(--muted);">Architecture</span>
                            <span style="font-weight:700;color:var(--text);">PHP 8.4 MVC &bull; MySQL 8</span>
                        </div>
                    </div>
                </div>

                <div class="om-bento-card" style="margin-top:20px;background:color-mix(in srgb,var(--surface) 80%,transparent);">
                    <h3 style="font-family:Sora,sans-serif;font-size:17px;font-weight:800;margin:0 0 10px;color:var(--text);">Need Custom Tailoring or Setup?</h3>
                    <p style="font-size:13.5px;color:var(--muted);line-height:1.6;margin:0 0 16px;">
                        Buyers receive direct deployment support on aaPanel or standard Linux VPS servers, or can hire our agency team to customize features and branding.
                    </p>
                    <a href="<?= url('/support?script=' . urlencode($project['title'])) ?>" class="om-btn-glass" style="width:100%;justify-content:center;">
                        <span>Open Support Request</span>
                        <span class="om-btn-circle-arrow">&rarr;</span>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
