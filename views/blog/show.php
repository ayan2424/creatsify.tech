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
            <h1 class="page-title" style="margin-top:12px;"><?= e($post['title']) ?></h1>
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
                <span class="om-btn-circle-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg></span>
                <span>More Insights</span>
            </a>

            <a href="<?= url('/contact') ?>" class="om-btn-gold sm">
                <span class="om-btn-circle-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></span>
                <span>Start A Project Brief</span>
            </a>
        </div>
    </div>
</section>
