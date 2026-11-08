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
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Studio Portfolio</div>
        <h1 class="page-title">Work &amp; Commercial Software</h1>
        <p class="page-lead">Courier platforms, multi-branch online stores, and custom client web applications. Test the live demos, inspect the architecture, or purchase instant white-label licenses.</p>

        <!-- Omoway Floating Pill Filter Bar -->
        <div class="om-filter-bar" style="margin-top:28px;">
            <a href="<?= url('/portfolio?cat=all') ?>" class="om-filter-pill <?= $activeCategory === 'all' ? 'active' : '' ?>">All Systems</a>
            <a href="<?= url('/portfolio?cat=saas') ?>" class="om-filter-pill <?= $activeCategory === 'saas' ? 'active' : '' ?>">Courier Software</a>
            <a href="<?= url('/portfolio?cat=stores') ?>" class="om-filter-pill <?= $activeCategory === 'stores' ? 'active' : '' ?>">Online Stores &amp; POS</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2 class="sr-only">Portfolio Projects</h2>
        <div class="projects-grid">
            <?php foreach ($projects as $i => $project):
                $shot = $thumb($project);
                $live = $project['live_url'] ?? '';
                $hasLive = $live && $live !== '#';
                $isForSale = ($project['status'] ?? '') === 'For sale';
            ?>
                <article class="project-card rv" style="--d:<?= $i * 0.08 ?>s">
                    <a class="prod-media" href="<?= url('/portfolio/' . e($project['slug'])) ?>">
                        <div class="om-prod-bar">
                            <div class="om-prod-dots">
                                <span class="dot d-red"></span>
                                <span class="dot d-yellow"></span>
                                <span class="dot d-green"></span>
                            </div>
                            <span class="om-prod-badge-price"><?= $isForSale && !empty($project['price']) ? e($project['price']) : e($project['status']) ?></span>
                        </div>
                        <?php if ($shot): ?>
                            <img src="<?= asset($shot) ?>" width="1376" height="768" loading="lazy" alt="<?= e($project['title']) ?> screenshot">
                        <?php else: ?>
                            <div class="prod-cover">
                                <b style="font-family:Sora,sans-serif;font-size:18px;font-weight:800;"><?= e($project['title']) ?></b>
                                <span style="font-size:12px;opacity:0.7;margin-top:4px;"><?= e(ucfirst($project['category'])) ?></span>
                            </div>
                        <?php endif; ?>
                    </a>
                    <div class="project-card-header">
                        <div class="project-category-badge"><?= strtoupper(e($project['category'])) ?></div>
                        <div class="project-status-badge" style="background:<?= $isForSale ? '#10b981' : '#2563eb' ?>;"><?= e($project['status']) ?></div>
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
                        <a href="<?= url('/portfolio/' . e($project['slug'])) ?>" class="project-btn-case">Details</a>
                        <?php if ($hasLive): ?>
                            <a href="<?= e($live) ?>" target="_blank" rel="noopener" class="project-btn-case">Live Demo</a>
                        <?php endif; ?>
                        <?php if (!empty($project['gumroad_url'])): ?>
                            <a href="<?= e($project['gumroad_url']) ?>" target="_blank" rel="noopener" class="om-btn-gold sm pc-buy">
                                <span class="om-btn-circle-arrow">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                </span>
                                <span>Buy License<?= !empty($project['price']) ? ' (' . e($project['price']) . ')' : '' ?></span>
                            </a>
                        <?php elseif ($isForSale): ?>
                            <a href="<?= url('/contact?kind=software_presale&offering=' . urlencode($project['title'])) . '#inquiry-form' ?>" class="om-btn-gold sm pc-buy">
                                <span class="om-btn-circle-arrow">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                </span>
                                <span>Get a Quote</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Client Support Note Bento Card -->
        <div class="om-bento-card" style="margin-top:48px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;">
            <div style="max-width:680px;">
                <div class="om-sec-tag" style="margin-bottom:8px;"><span class="om-sec-dot"></span> Client Support Desk</div>
                <h3 style="font-family:Sora,sans-serif;font-size:20px;font-weight:800;color:var(--text);margin:0 0 6px;">Already Licensed Our Software?</h3>
                <p style="font-size:14px;color:var(--muted);line-height:1.6;margin:0;">
                    We help with deployment, server rewrites, custom extension requests, and technical guidance through tracked support tickets.
                </p>
            </div>
            <div>
                <a href="<?= url('/support') ?>" class="om-btn-glass">
                    <span class="om-btn-circle-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg></span>
                    <span>Open Customer Ticket</span>
                </a>
            </div>
        </div>
    </div>
</section>
