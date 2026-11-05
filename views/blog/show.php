<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>

<section class="page-hero">
    <div class="wrap" style="max-width:880px;">
        <a href="<?= url('/blog') ?>" class="om-back-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            <span>Back to All Articles</span>
        </a>

        <div style="margin-top:20px;">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> <?= e($post['category']) ?> &bull; <?= date('F j, Y', strtotime($post['published_at'])) ?></div>
            <h1 class="page-title" style="margin-top:12px;"><?= e($post['title']) ?><span class="om-dot-gold">.</span></h1>
            <p class="page-lead"><?= e($post['excerpt']) ?></p>
        </div>
    </div>
</section>

<section class="section" style="padding-top:30px;padding-bottom:80px;">
    <div class="wrap" style="max-width:880px;">
        <article class="om-bento-card" style="font-size:16px;line-height:1.8;color:var(--muted);padding:44px;">
            <?= sanitize_html($post['content']) ?>
        </article>

        <div style="margin-top:40px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
            <a href="<?= url('/blog') ?>" class="om-btn-glass sm">
                <span class="om-back-arrow">&larr;</span>
                <span>More Insights</span>
            </a>

            <a href="<?= url('/contact') ?>" class="om-btn-gold sm">
                <span>Start A Project Brief</span>
                <span class="om-btn-circle-arrow">&rarr;</span>
            </a>
        </div>
    </div>
</section>
