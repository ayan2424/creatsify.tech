<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="page-hero">
    <div class="wrap">
        <span class="section-tag">Engineering Notes</span>
        <h1 class="page-title">Blog & Product Deep Dives</h1>
        <p class="page-lead">Technical essays on high-concurrency systems, database normalization, PHP MVC architecture, and building production software.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 30px;">
            <?php foreach ($posts as $post): ?>
                <article class="post-card" style="background: var(--surface); border-radius: 14px; border: 1px solid var(--line); overflow: hidden; display: flex; flex-direction: column;">
                    <div style="padding: 30px; display: flex; flex-direction: column; flex-grow: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; font-size: 13px; color: var(--muted);">
                            <span class="stack-chip" style="color: var(--accent);"><?= e($post['category']) ?></span>
                            <span><?= date('M j, Y', strtotime($post['published_at'])) ?> &bull; <?= (int)$post['views_count'] ?> views</span>
                        </div>

                        <h3 style="font-size: 20px; color: var(--text); margin-top: 0; line-height: 1.4;">
                            <a href="<?= url('/blog/' . e($post['slug'])) ?>" style="color: inherit; text-decoration: none;"><?= e($post['title']) ?></a>
                        </h3>
                        <p style="color: var(--muted); font-size: 15px; line-height: 1.6; flex-grow: 1;"><?= e($post['excerpt']) ?></p>

                        <div style="margin-top: 25px; border-top: 1px solid var(--line); padding-top: 15px;">
                            <a href="<?= url('/blog/' . e($post['slug'])) ?>" style="color: var(--accent); text-decoration: none; font-weight: 600; font-size: 14px;">Read article &rarr;</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
