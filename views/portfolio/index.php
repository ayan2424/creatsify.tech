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

?>

<!-- Agency Portfolio Hero (Inspired by Logoxoom / Modern Creative Agency) -->
<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Agency Portfolio &bull; Verified Client Case Studies</div>
        <h1 class="page-title">Our Work &amp; Measurable Client Impact</h1>
        <p class="page-lead">A showcase of bespoke web applications, cross-platform Flutter mobile suites, conversion-engineered redesigns, scalable software architectures, and high-ROAS marketing campaigns delivered for ambitious brands worldwide.</p>

        <!-- 7 Official Service Category Filter Tabs (Ready-Made Software is maintained as an attribute tag, not a category) -->
        <div class="om-filter-bar" style="margin-top:28px;" role="group" aria-label="Filter case studies by discipline">
            <a href="<?= url('/portfolio?cat=web-dev') ?>" class="om-filter-pill <?= ($activeCategory === 'web-dev' || empty($activeCategory) || $activeCategory === 'all') ? 'active' : '' ?>">Web Design &amp; Development</a>
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
            ?>
                <article class="ag-case-card rv on" data-work-cat="<?= e($cat) ?>" style="--d:<?= ($i * 0.08) ?>s;">
                    <!-- 1. The Clean, Crisp Screenshot Image -->
                    <?php if ($shot): ?>
                        <img src="<?= asset($shot) ?>" width="1376" height="768" loading="lazy" alt="<?= e($project['title']) ?> showcase" class="ag-card-img">
                    <?php else: ?>
                        <div class="ag-case-img-fallback">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            <span><?= e($project['title']) ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- 2. macOS 3 Traffic Light Dots (Always Visible at Top-Left) -->
                    <div class="ag-card-dots" aria-hidden="true">
                        <span class="ag-dot d-red"></span>
                        <span class="ag-dot d-yellow"></span>
                        <span class="ag-dot d-green"></span>
                    </div>

                    <!-- 3. Details & Action Buttons (Hidden by Default, Revealed on Hover & Focus with Progressive Blur) -->
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
                            <button type="button" 
                                    class="ag-btn-preview js-open-lightbox" 
                                    data-img="<?= asset($shot) ?>" 
                                    data-title="<?= e($project['title']) ?>" 
                                    data-slug="<?= e($slug) ?>" 
                                    data-badge="<?= e($catLabel) ?>" 
                                    data-live="<?= e($live) ?>" 
                                    data-software="<?= $isSoftware ? '1' : '0' ?>"
                                    data-price="<?= e($priceStr) ?>"
                                    data-gumroad="<?= e($project['gumroad_url'] ?? '') ?>"
                                    data-desc="<?= e($desc) ?>"
                                    data-tech="<?= e($project['tech_stack'] ?? '') ?>"
                                    data-client="<?= e($project['client'] ?? '') ?>"
                                    title="Quick Preview &amp; Specs">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                                <span>Preview</span>
                            </button>

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
