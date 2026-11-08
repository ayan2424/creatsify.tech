<?php
/** @var Core\View $this */
$this->layout('layouts/main');
$assetDir = dirname(__DIR__, 2) . '/public/assets/images/';

$thumb = static function (array $p) use ($assetDir) {
    $slug = $p['slug'] ?? '';
    $map = [
        'apex-retail-redesign'       => 'images/apex-preview.jpg',
        'lumina-fleet-app'           => 'images/lumina-preview.jpg',
        'kinetix-brand-system'       => 'images/kinetix-preview.jpg',
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

$outcomeMap = [
    'apex-retail-redesign'       => '+184% Conversion Lift',
    'lumina-fleet-app'           => '4.9★ App Store Rating',
    'kinetix-brand-system'       => '3.8x Meta & Google ROAS',
    'cheetah-enterprise'         => '120k+ Parcels/Mo Handled',
    'cheetah-lite'               => '12-Day Custom Launch',
    'cheezify'                   => '+92% Repeat Order Rate',
    'restromaster-kds-interface' => '0.8s Kitchen Order Sync',
    'viddmate-video-suite'       => '4.2x Faster Encoding',
];
?>

<!-- Agency Portfolio Hero (Inspired by Logoxoom / Modern Creative Agency) -->
<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Agency Portfolio &bull; Verified Client Case Studies</div>
        <h1 class="page-title">Our Work &amp; Measurable Client Impact</h1>
        <p class="page-lead">A showcase of bespoke web applications, cross-platform Flutter mobile suites, conversion-engineered redesigns, and scalable ad acquisition engines delivered for ambitious brands worldwide.</p>

        <!-- Logoxoom Style Filter Tabs -->
        <div class="om-filter-bar" style="margin-top:28px;" role="tablist" aria-label="Filter case studies by discipline">
            <a href="<?= url('/portfolio?cat=all') ?>" class="om-filter-pill <?= $activeCategory === 'all' ? 'active' : '' ?>">All Work</a>
            <a href="<?= url('/portfolio?cat=web-saas') ?>" class="om-filter-pill <?= $activeCategory === 'web-saas' ? 'active' : '' ?>">Web &amp; SaaS</a>
            <a href="<?= url('/portfolio?cat=mobile') ?>" class="om-filter-pill <?= $activeCategory === 'mobile' ? 'active' : '' ?>">Flutter Mobile Apps</a>
            <a href="<?= url('/portfolio?cat=redesign') ?>" class="om-filter-pill <?= $activeCategory === 'redesign' ? 'active' : '' ?>">Website Redesign &amp; CRO</a>
            <a href="<?= url('/portfolio?cat=growth') ?>" class="om-filter-pill <?= $activeCategory === 'growth' ? 'active' : '' ?>">Brand Identity &amp; Ads</a>
        </div>
    </div>
</section>

<!-- Portfolio Case Study Grid -->
<section class="section" style="padding-top:20px;">
    <div class="wrap">
        <div class="ag-case-grid">
            <?php foreach ($projects as $i => $project):
                $slug = $project['slug'];
                $shot = $thumb($project);
                $live = $project['live_url'] ?? '';
                $hasLive = $live && $live !== '#';
                $tech = array_filter(array_map('trim', explode(',', (string)($project['tech_stack'] ?? ''))));
                $client = !empty($project['client']) ? $project['client'] : 'Creatsify Enterprise';
                $outcome = $outcomeMap[$slug] ?? '+150% Growth Lift';
            ?>
                <article class="ag-case-card rv" style="--d:<?= $i * 0.08 ?>s">
                    <div class="ag-case-media-frame">
                        <div class="ag-case-browser-bar">
                            <div class="om-browser-dots">
                                <span class="dot d-red"></span>
                                <span class="dot d-yellow"></span>
                                <span class="dot d-green"></span>
                            </div>
                            <span class="ag-case-browser-url">creatsify.tech / showcase / <?= e($slug) ?></span>
                            <span class="ag-case-badge-pill"><?= e($outcome) ?></span>
                        </div>
                        <a href="<?= url('/portfolio/' . e($slug)) ?>" class="ag-case-img-link" aria-label="View case study for <?= e($project['title']) ?>">
                            <?php if ($shot): ?>
                                <img src="<?= asset($shot) ?>" width="1376" height="768" loading="lazy" alt="<?= e($project['title']) ?> showcase" class="ag-case-img">
                            <?php else: ?>
                                <div class="ag-case-img-fallback">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                    <span><?= e($project['title']) ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="ag-case-overlay">
                                <span class="ag-case-overlay-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    <span>Explore Case Study</span>
                                </span>
                            </div>
                        </a>
                    </div>

                    <div class="ag-case-body">
                        <div class="ag-case-meta-top">
                            <span class="ag-case-client-tag">&#10003; <?= e($client) ?></span>
                            <span class="ag-case-status-tag">Delivered &amp; Verified</span>
                        </div>
                        <h3 class="ag-case-title"><a href="<?= url('/portfolio/' . e($slug)) ?>"><?= e($project['title']) ?></a></h3>
                        <p class="ag-case-desc"><?= e($project['summary'] ?: $project['description']) ?></p>

                        <div class="om-prod-chips">
                            <?php foreach ($tech as $t): ?><span><?= e($t) ?></span><?php endforeach; ?>
                        </div>

                        <div class="ag-case-actions">
                            <a href="<?= url('/portfolio/' . e($slug)) ?>" class="ag-case-btn-primary">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <span>Explore Case Study</span>
                            </a>
                            <?php if ($hasLive): ?>
                                <a href="<?= e($live) ?>" target="_blank" rel="noopener noreferrer" class="ag-case-btn-secondary">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span>Live Preview</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Agency Consultation Hub Callout Banner -->
        <div class="om-bento-card" style="margin-top:56px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:24px;padding:36px 32px;">
            <div style="max-width:680px;">
                <div class="om-sec-tag" style="margin-bottom:10px;"><span class="om-sec-dot"></span> Partner With Creatsify</div>
                <h3 style="font-family:Sora,sans-serif;font-size:24px;font-weight:800;color:var(--text);margin:0 0 8px;">Have A Project You Want Engineered Or Redesigned?</h3>
                <p style="font-size:14.5px;color:var(--muted);line-height:1.6;margin:0;">
                    From full-stack web platforms and Flutter mobile apps to conversion-engineered website redesigns and high-ROAS marketing funnels—our senior divisions deliver end-to-end.
                </p>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <a href="<?= url('/contact') ?>" class="om-btn-gold">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span>Start Your Project</span>
                </a>
                <a href="<?= url('/software') ?>" class="om-btn-glass">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    <span>Browse Ready-Made Software</span>
                </a>
            </div>
        </div>
    </div>
</section>
