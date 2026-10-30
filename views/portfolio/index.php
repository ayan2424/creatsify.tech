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
    <div class="container">
        <span class="section-tag">Software and work</span>
        <h1 class="page-title">Portfolio</h1>
        <p class="page-lead">Courier software, an online store with its own admin panel, and a WordPress store. Open a live demo, or ask for a quote.</p>

        <div class="filter-tabs" style="margin-top: 30px;">
            <a href="<?= url('/portfolio?cat=all') ?>" class="tab-btn <?= $activeCategory === 'all' ? 'active' : '' ?>">All</a>
            <a href="<?= url('/portfolio?cat=saas') ?>" class="tab-btn <?= $activeCategory === 'saas' ? 'active' : '' ?>">Software</a>
            <a href="<?= url('/portfolio?cat=stores') ?>" class="tab-btn <?= $activeCategory === 'stores' ? 'active' : '' ?>">Online stores</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="projects-grid">
            <?php foreach ($projects as $project):
                $shot = $thumb($project);
                $live = $project['live_url'] ?? '';
                $hasLive = $live && $live !== '#';
            ?>
                <article class="project-card">
                    <a class="prod-media" href="<?= url('/portfolio/' . e($project['slug'])) ?>" aria-label="<?= e($project['title']) ?> case study">
                        <?php if ($shot): ?>
                            <img src="<?= asset($shot) ?>" width="1376" height="768" loading="lazy" alt="<?= e($project['title']) ?> screenshot">
                        <?php else: ?>
                            <div class="prod-cover"><b><?= e($project['title']) ?></b><span><?= e(ucfirst($project['category'])) ?></span></div>
                        <?php endif; ?>
                    </a>
                    <div class="project-card-header">
                        <div class="project-category-badge"><?= strtoupper(e($project['category'])) ?></div>
                        <div class="project-status-badge"><?= e($project['status']) ?></div>
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
                        <a href="<?= url('/portfolio/' . e($project['slug'])) ?>" class="project-btn-case">Details &rarr;</a>
                        <?php if ($hasLive): ?>
                            <a href="<?= e($live) ?>" target="_blank" rel="noopener" class="project-btn-case">Live demo &nearr;</a>
                        <?php endif; ?>
                        <?php if (!empty($project['gumroad_url'])): ?>
                            <a href="<?= e($project['gumroad_url']) ?>" target="_blank" rel="noopener" class="btn p pc-buy">Buy<?= !empty($project['price']) ? ' (' . e($project['price']) . ')' : '' ?></a>
                        <?php elseif (($project['status'] ?? '') === 'For sale'): ?>
                            <a href="<?= url('/contact?t=' . urlencode($project['title'])) ?>" class="btn p pc-buy">Get a quote</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="pg-cta">
            <div>
                <h3>Already bought one of our products?</h3>
                <p>Send an installation, server setup or customization request and we will help.</p>
            </div>
            <div class="pg-cta-btns"><a href="<?= url('/support') ?>" class="btn p">Open a support request</a></div>
        </div>
    </div>
</section>
