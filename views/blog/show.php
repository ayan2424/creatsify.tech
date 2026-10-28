<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="page-hero" style="padding-top:140px;padding-bottom:40px;">
    <div class="wrap" style="max-width: 820px;">
        <div style="margin-bottom: 24px;">
            <a href="<?= url('/blog') ?>" class="more">&larr; Back to all articles</a>
        </div>
        <div class="pill">
            <i></i>
            <span><?= e($post['category']) ?> &bull; <?= date('F j, Y', strtotime($post['published_at'])) ?> &bull; <?= (int)$post['views_count'] ?> views</span>
        </div>
        <h1 class="page-title" style="margin-top: 18px; font-size: clamp(28px, 4vw, 42px);"><?= e($post['title']) ?></h1>
        <p class="lead"><?= e($post['excerpt']) ?></p>
    </div>
</section>

<section class="section" style="padding-bottom: 90px;">
    <div class="wrap" style="max-width: 820px;">
        <article class="article-content">
            <?= sanitize_html($post['content']) ?>
        </article>
        <div style="margin-top: 36px; text-align: center;">
            <a href="<?= url('/blog') ?>" class="btn">&larr; Read more insights</a>
        </div>
    </div>
</section>
