<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="page-hero">
    <div class="container">
        <span class="section-tag">Case Studies & Software</span>
        <h1 class="page-title">Portfolio & Shipped Products</h1>
        <p class="page-lead">Explore our production platforms, SaaS engines, mobile apps, and flagship software built for clients and global software marketplaces.</p>

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

                    <div class="project-card-footer">
                        <a href="<?= url('/portfolio/' . e($project['slug'])) ?>" class="project-btn-case">Full case study &rarr;</a>
                        <?php if (!empty($project['live_url'])): ?>
                            <a href="<?= e($project['live_url']) ?>" target="_blank" rel="noopener" class="project-btn-link" aria-label="Visit external link">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
