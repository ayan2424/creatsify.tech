<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="page-hero">
    <div class="wrap">
        <span class="section-tag">Services</span>
        <h1 class="page-title">Services</h1>
        <p class="page-lead">Besides our ready-made software, we build custom websites, admin panels and stores. Tell us what you need and we reply with a plan and a price.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="svc-grid">
            <?php foreach ($services as $i => $service): ?>
                <article class="svc-card">
                    <div class="svc-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    </div>
                    <h3><?= e($service['title']) ?></h3>
                    <p><?= e($service['full_description'] ?: $service['short_description']) ?></p>
                    <?php if (!empty($service['deliverables'])): ?>
                        <div class="svc-list">
                            <strong>What you get</strong>
                            <ul>
                                <?php foreach (explode(',', $service['deliverables']) as $deliv): ?>
                                    <li><?= e(trim($deliv)) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <a href="<?= url('/contact?t=' . urlencode($service['title'])) ?>" class="btn p svc-btn">Ask about this &rarr;</a>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="how-grid" style="margin-top:56px">
            <div class="how-step"><span class="how-n">1</span><h3>Tell us what you need</h3><p>A short message is enough. Share links, examples and any deadline you have.</p></div>
            <div class="how-step"><span class="how-n">2</span><h3>Get a plan and a price</h3><p>We reply with what we would build, how long it takes and what it costs.</p></div>
            <div class="how-step"><span class="how-n">3</span><h3>We build, you review</h3><p>You see progress as we go, then we launch it and help you get started.</p></div>
        </div>

        <div class="pg-cta">
            <div>
                <h3>Not sure what you need?</h3>
                <p>Describe the problem. We will suggest the quickest route.</p>
            </div>
            <div class="pg-cta-btns"><a class="btn p" href="<?= url('/contact') ?>">Start a project</a></div>
        </div>
    </div>
</section>
