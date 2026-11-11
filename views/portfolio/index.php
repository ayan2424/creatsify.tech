<?php
/** @var Core\View $this */
$this->layout('layouts/main');
$assetDir = dirname(__DIR__, 2) . '/public/assets/images/';

$thumb = static function (array $p) use ($assetDir) {
    $slug = $p['slug'] ?? '';
    $map = [
        'growth-engine-campaigns'    => 'images/novagrowth-preview.jpg',
        'apex-retail-redesign'       => 'images/apex-preview.jpg',
        'lumina-fleet-app'           => 'images/lumina-preview.jpg',
        'kinetix-brand-system'       => 'images/kinetix-preview.jpg',
        'cheetah-enterprise'         => 'images/cheetah-showcase.jpg',
        'cheetah-lite'               => 'images/cheetah-lite-preview.jpg',
        'cheezify'                   => 'images/cheezify-preview.jpg',
        'restromaster-kds-interface' => 'images/restromaster-preview.jpg',
        'viddmate-video-suite'       => 'images/viddmate-preview.jpg',
        'pulserank-geo-seo'          => 'images/pulserank-preview.jpg',
        'novagrowth-performance-ads' => 'images/novagrowth-preview.jpg',
    ];
    if (isset($map[$slug]) && is_file($assetDir . basename($map[$slug]))) {
        return $map[$slug];
    }
    $file = $slug . '-preview.jpg';
    return is_file($assetDir . $file) ? 'images/' . $file : null;
};

$monogram = static function (string $slug, string $title): string {
    $custom = [
        'growth-engine-campaigns'    => 'GE',
        'apex-retail-redesign'       => 'AR',
        'cheetah-enterprise'         => 'CE',
        'lumina-fleet-app'           => 'LF',
        'cheetah-lite'               => 'CL',
        'kinetix-brand-system'       => 'KB',
        'cheezify'                   => 'CZ',
        'restromaster-kds-interface' => 'RM',
        'viddmate-video-suite'       => 'VM',
        'pulserank-geo-seo'          => 'PR',
        'novagrowth-performance-ads' => 'NG',
    ];
    if (isset($custom[$slug])) {
        return $custom[$slug];
    }
    $words = preg_split('/\s+/', trim($title));
    if (count($words) >= 2) {
        return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
    }
    return strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $title), 0, 2));
};

$auraColor = static function (string $slug, int $idx): string {
    $custom = [
        'cheetah-enterprise'         => 'rgba(16, 185, 129, 0.28)',
        'cheetah-lite'               => 'rgba(14, 165, 233, 0.28)',
        'cheezify'                   => 'rgba(245, 158, 11, 0.28)',
        'restromaster-kds-interface' => 'rgba(239, 68, 68, 0.28)',
        'viddmate-video-suite'       => 'rgba(139, 92, 246, 0.30)',
        'apex-retail-redesign'       => 'rgba(0, 210, 238, 0.28)',
        'lumina-fleet-app'           => 'rgba(20, 184, 166, 0.28)',
        'kinetix-brand-system'       => 'rgba(236, 72, 153, 0.28)',
        'pulserank-geo-seo'          => 'rgba(45, 212, 191, 0.28)',
        'novagrowth-performance-ads' => 'rgba(168, 85, 247, 0.30)',
    ];
    if (isset($custom[$slug])) {
        return $custom[$slug];
    }
    $palette = [
        'rgba(0, 210, 238, 0.28)',
        'rgba(16, 185, 129, 0.28)',
        'rgba(139, 92, 246, 0.30)',
        'rgba(245, 158, 11, 0.28)',
        'rgba(236, 72, 153, 0.28)',
    ];
    return $palette[$idx % count($palette)];
};
?>

<!-- Agency Portfolio Hero (Inspired by Logoxoom / Modern Creative Agency) -->
<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Agency Portfolio &bull; Verified Client Case Studies</div>
        <h1 class="page-title">Our Work &amp; Measurable Client Impact</h1>
        <p class="page-lead">A showcase of bespoke web applications, cross-platform Flutter mobile suites, conversion-engineered redesigns, scalable software architectures, and high-ROAS marketing campaigns delivered for ambitious brands worldwide.</p>

        <!-- 7 Official Service Category Filter Tabs (Ready-Made Software is maintained as an attribute tag, not a category) -->
        <div class="om-filter-bar" style="margin-top:28px;" role="tablist" aria-label="Filter case studies by discipline">
            <a href="<?= url('/portfolio?cat=all') ?>" class="om-filter-pill <?= ($activeCategory === 'all' || empty($activeCategory)) ? 'active' : '' ?>">All Work</a>
            <a href="<?= url('/portfolio?cat=web-dev') ?>" class="om-filter-pill <?= $activeCategory === 'web-dev' ? 'active' : '' ?>">Web Design &amp; Development</a>
            <a href="<?= url('/portfolio?cat=mobile-app') ?>" class="om-filter-pill <?= $activeCategory === 'mobile-app' ? 'active' : '' ?>">Mobile App Development</a>
            <a href="<?= url('/portfolio?cat=saas-crm') ?>" class="om-filter-pill <?= $activeCategory === 'saas-crm' ? 'active' : '' ?>">SaaS &amp; CRM</a>
            <a href="<?= url('/portfolio?cat=software-eng') ?>" class="om-filter-pill <?= $activeCategory === 'software-eng' ? 'active' : '' ?>">Software &amp; Product Engineering</a>
            <a href="<?= url('/portfolio?cat=seo-geo') ?>" class="om-filter-pill <?= $activeCategory === 'seo-geo' ? 'active' : '' ?>">SEO &amp; GEO</a>
            <a href="<?= url('/portfolio?cat=digital-marketing') ?>" class="om-filter-pill <?= $activeCategory === 'digital-marketing' ? 'active' : '' ?>">Digital Marketing</a>
            <a href="<?= url('/portfolio?cat=ui-ux-branding') ?>" class="om-filter-pill <?= $activeCategory === 'ui-ux-branding' ? 'active' : '' ?>">UI/UX Design &amp; Branding</a>
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
                $hasLive = !empty($live) && $live !== '#';
                $cat = $project['category'] ?? 'web-dev';
                $catName = \App\Models\Project::categoryLabel($cat);
                $catLabel = !empty($project['subcategory']) ? $project['subcategory'] : $catName;
                $isSoftware = !empty($project['is_software']) || ($project['status'] ?? '') === 'For sale';
                $priceStr = !empty($project['price']) ? $project['price'] : '$99';
                $desc = !empty($project['summary']) ? $project['summary'] : (!empty($project['description']) ? $project['description'] : '');
                $mono = $monogram($slug, (string)$project['title']);
                $aura = $auraColor($slug, $i);
            ?>
                <article class="ag-case-card rv on" data-work-cat="<?= e($cat) ?>" style="--d:<?= ($i * 0.08) ?>s; --card-aura:<?= $aura ?>;">
                    <!-- Top-Left macOS Traffic Light Dots -->
                    <div class="ag-card-dots" aria-hidden="true">
                        <span class="ag-dot d-red"></span>
                        <span class="ag-dot d-yellow"></span>
                        <span class="ag-dot d-green"></span>
                    </div>

                    <!-- Ambient Glow Aura -->
                    <div class="ag-card-aura" aria-hidden="true"></div>

                    <!-- Matrix Dot Grid Texture -->
                    <div class="ag-card-matrix" aria-hidden="true"></div>

                    <!-- Center 2-Letter Monogram Watermark -->
                    <div class="ag-card-watermark" aria-hidden="true"><?= e($mono) ?></div>

                    <!-- Atmospheric Screenshot Backdrop -->
                    <?php if ($shot): ?>
                        <div class="ag-card-media-wrap" aria-hidden="true">
                            <img src="<?= asset($shot) ?>" width="1376" height="768" loading="lazy" alt="" class="ag-card-img">
                        </div>
                    <?php endif; ?>

                    <!-- Bottom-Aligned Card Body -->
                    <div class="ag-card-body">
                        <h3 class="ag-card-title">
                            <a href="<?= url('/portfolio/' . e($slug)) ?>"><?= e($project['title']) ?></a>
                        </h3>

                        <div class="ag-card-tags">
                            <span class="ag-card-pill"><?= e($catLabel) ?></span>
                            <?php if ($isSoftware): ?>
                                <span class="ag-case-tag-sw" title="Ready-Made Commercial Software Available For Direct Purchase Or Customization">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                                    <span>Ready-Made Software &bull; <?= e($priceStr) ?></span>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if ($desc): ?>
                            <p class="ag-card-desc"><?= e($desc) ?></p>
                        <?php endif; ?>

                        <div class="ag-card-actions">
                            <?php if ($hasLive): ?>
                                <a href="<?= e($live) ?>" target="_blank" rel="noopener noreferrer" class="ag-btn-preview" title="Open Live Website">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                    <span>Live Preview</span>
                                </a>
                            <?php else: ?>
                                <a href="<?= url('/portfolio/' . e($slug)) ?>" class="ag-btn-preview" title="View Case Study">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                    <span>Live Preview</span>
                                </a>
                            <?php endif; ?>

                            <a href="<?= url('/portfolio/' . e($slug)) ?>" class="ag-btn-details" title="View Full Case Study">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <span>Case Details</span>
                            </a>
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
