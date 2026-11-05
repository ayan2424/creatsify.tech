<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>

<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Engineering Notes</div>
        <h1 class="page-title">Blog &amp; Architecture Insights<span class="om-dot-gold">.</span></h1>
        <p class="page-lead">Technical essays on high-velocity systems, MySQL database schema normalization, pure PHP 8.4 MVC architecture, and real-world software production.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2 class="sr-only">Articles</h2>
        <div class="projects-grid">
            <?php foreach ($posts as $i => $post): ?>
                <article class="project-card rv" style="--d:<?= $i * 0.1 ?>s">
                    <div class="project-card-header">
                        <span class="project-category-badge"><?= e($post['category']) ?></span>
                        <span style="font-size:12px;color:var(--muted);"><?= date('M j, Y', strtotime($post['published_at'])) ?></span>
                    </div>
                    <div class="project-card-body">
                        <h3 class="project-title">
                            <a href="<?= url('/blog/' . e($post['slug'])) ?>"><?= e($post['title']) ?></a>
                        </h3>
                        <p class="project-summary"><?= e($post['excerpt']) ?></p>
                    </div>
                    <div class="project-card-footer">
                        <a href="<?= url('/blog/' . e($post['slug'])) ?>" class="om-btn-glass sm" style="margin-left:auto;">
                            <span>Read Article</span>
                            <span class="om-btn-circle-arrow">&rarr;</span>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
