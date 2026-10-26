<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="page-hero">
    <div class="container">
        <span class="section-tag">Direct Licensing & Case Studies</span>
        <h1 class="page-title">Portfolio & Shipped Products</h1>
        <p class="page-lead">Explore our production platforms, SaaS engines, mobile apps, and flagship software available for direct licensing via our official Gumroad store with 100% white-label code ownership and in-house technical support.</p>

        <div class="filter-tabs" style="margin-top: 30px;">
            <a href="<?= url('/portfolio?cat=all') ?>" class="tab-btn <?= $activeCategory === 'all' ? 'active' : '' ?>">All</a>
            <a href="<?= url('/portfolio?cat=saas') ?>" class="tab-btn <?= $activeCategory === 'saas' ? 'active' : '' ?>">Platforms & SaaS</a>
            <a href="<?= url('/portfolio?cat=stores') ?>" class="tab-btn <?= $activeCategory === 'stores' ? 'active' : '' ?>">Stores & Ordering</a>
            <a href="<?= url('/portfolio?cat=apps') ?>" class="tab-btn <?= $activeCategory === 'apps' ? 'active' : '' ?>">Apps & Tools</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="projects-grid">
            <?php foreach ($projects as $project): ?>
                <div class="project-card">
                    <div class="project-card-header">
                        <div class="project-category-badge"><?= strtoupper(e($project['category'])) ?></div>
                        <div class="project-status-badge"><?= e($project['status']) ?></div>
                    </div>

                    <div class="project-card-body">
                        <h3 class="project-title">
                            <a href="<?= url('/portfolio/' . e($project['slug'])) ?>"><?= e($project['title']) ?></a>
                        </h3>
                        <p class="project-summary"><?= e($project['summary']) ?></p>

                        <div class="project-stack">
                            <?php foreach (explode(',', $project['tech_stack'] ?? '') as $stackItem): ?>
                                <span class="stack-chip"><?= e(trim($stackItem)) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="project-card-footer" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <a href="<?= url('/portfolio/' . e($project['slug'])) ?>" class="project-btn-case">Case study &rarr;</a>
                            <?php if (!empty($project['live_url'])): ?>
                                <a href="<?= e($project['live_url']) ?>" target="_blank" rel="noopener" class="project-btn-link" aria-label="Visit external live demo">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($project['gumroad_url'])): ?>
                            <a href="<?= e($project['gumroad_url']) ?>" target="_blank" rel="noopener" class="project-btn-gumroad" style="display:inline-flex; align-items:center; gap:5px; padding:6px 12px; background:linear-gradient(135deg,#ff3366,#ff6b8b); color:#fff; border-radius:6px; font-weight:700; font-size:12px; text-decoration:none; box-shadow:0 2px 8px rgba(255,51,102,0.3); transition:transform 0.2s ease;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                <span>Buy License<?= !empty($project['price']) ? ' (' . e($project['price']) . ')' : '' ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- In-House Support Banner -->
        <div style="margin-top:50px; background:var(--surface); border:1px solid var(--line); border-radius:14px; padding:32px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:20px;">
            <div>
                <span class="pill" style="margin-bottom:8px;"><i></i>In-House Technical Support</span>
                <h3 style="margin:6px 0 0; font-size:20px; color:var(--text); font-weight:800;">Already purchased a script from our Gumroad store?</h3>
                <p style="margin:4px 0 0; color:var(--muted); font-size:14px;">Submit an installation, aaPanel Nginx rewrite, or customization ticket directly on our website.</p>
            </div>
            <a href="<?= url('/support') ?>" class="btn p" style="white-space:nowrap;">Open Support Ticket &rarr;</a>
        </div>
    </div>
</section>
