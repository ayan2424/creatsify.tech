<?php
/** @var Core\View $this */
$this->layout('layouts/main');

$img = $project['image_url'] ?? '';
if (empty($img) || str_contains($img, 'creatsify-icon.png')) {
    if (str_contains(strtolower($project['title']), 'cheetah')) {
        $img = asset('images/cheetah-showcase.jpg');
    } elseif (str_contains(strtolower($project['title']), 'restro') || str_contains(strtolower($project['title']), 'flavor')) {
        $img = asset('images/restromaster-preview.jpg');
    } elseif (str_contains(strtolower($project['title']), 'vidd') || str_contains(strtolower($project['title']), 'media') || str_contains(strtolower($project['title']), 'browse')) {
        $img = asset('images/viddmate-preview.jpg');
    } else {
        $img = asset('images/hero-artwork.jpg');
    }
} else {
    $img = asset($img);
}
?>
<section class="page-hero" style="padding-top:140px;padding-bottom:40px;">
    <div class="wrap">
        <div style="margin-bottom: 24px;">
            <a href="<?= url('/portfolio') ?>" class="more">&larr; Back to all projects</a>
        </div>
        <div class="pill">
            <i></i>
            <span><?= strtoupper(e($project['category'])) ?> &bull; <?= e($project['status']) ?></span>
        </div>
        <h1 class="page-title" style="margin-top: 18px; font-size: clamp(28px, 4vw, 44px);"><?= e($project['title']) ?></h1>
        <p class="lead" style="max-width: 720px;"><?= e($project['summary']) ?></p>

        <div style="display: flex; gap: 10px; margin-top: 25px; flex-wrap: wrap;">
            <?php foreach (explode(',', $project['tech_stack'] ?? '') as $stackItem): ?>
                <span class="fch a" style="position:static;"><?= e(trim($stackItem)) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section" style="padding-bottom: 90px;">
    <div class="wrap">
        <?php if ($img): ?>
            <div style="margin-bottom: 40px; border-radius: 18px; overflow: hidden; border: 1px solid var(--line); box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
                <img src="<?= e($img) ?>" alt="<?= e($project['title']) ?>" style="width: 100%; height: auto; display: block; object-fit: cover; max-height: 520px;">
            </div>
        <?php endif; ?>

        <div class="case-study-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 36px;">
            <div class="case-study-body" style="background: var(--surface); padding: 40px; border-radius: 16px; border: 1px solid var(--line); font-size: 16px; line-height: 1.8;">
                <h3 style="margin-top: 0; font-size: 22px; color: var(--text);">Overview & Technical Architecture</h3>
                <p style="color: var(--muted);"><?= nl2br(e($project['description'] ?: $project['summary'])) ?></p>

                <h3 style="margin-top: 40px; font-size: 20px; color: var(--text);">Key Deliverables & Highlights</h3>
                <ul style="padding-left: 20px; color: var(--muted); margin-top: 15px; display: flex; flex-direction: column; gap: 10px;">
                    <li>Engineered with pure, optimized architectures for instantaneous response times.</li>
                    <li>Integrated real-time dispatch, audit trails, and multi-portal role-based security.</li>
                    <li>Extensively tested under high concurrency and automated database migrations.</li>
                </ul>
            </div>

            <div class="case-study-sidebar">
                <div style="background: var(--surface); padding: 30px; border-radius: 16px; border: 1px solid var(--line);">
                    <h4 style="margin-top: 0; font-size: 18px; color: var(--text);">Project Metadata</h4>
                    <div style="margin-top: 20px; font-size: 14px; color: var(--muted); display: flex; flex-direction: column; gap: 16px;">
                        <div>
                            <strong style="color: var(--text); display: block; margin-bottom: 4px;">Client / Audience:</strong>
                            <?= e($project['client'] ?: 'Global Enterprise / Operators') ?>
                        </div>
                        <div>
                            <strong style="color: var(--text); display: block; margin-bottom: 4px;">Status:</strong>
                            <?= e($project['status']) ?>
                        </div>
                        <div>
                            <strong style="color: var(--text); display: block; margin-bottom: 4px;">Category:</strong>
                            <?= ucfirst(e($project['category'])) ?>
                        </div>
                        <?php if (!empty($project['live_url'])): ?>
                            <div style="margin-top: 12px;">
                                <a href="<?= e($project['live_url']) ?>" target="_blank" rel="noopener" class="btn p" style="width: 100%; text-align: center; justify-content: center;">Visit Live Product &rarr;</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="margin-top: 25px; background: linear-gradient(135deg, rgba(139,123,255,0.12), rgba(255,79,163,0.06)); padding: 30px; border-radius: 16px; border: 1px solid var(--line);">
                    <h4 style="margin-top: 0; color: var(--text);">Want a similar platform?</h4>
                    <p style="font-size: 14px; color: var(--muted); margin: 10px 0 20px; line-height: 1.6;">We can deploy or customize this software for your business.</p>
                    <a href="<?= url('/contact') ?>" class="btn" style="width: 100%; text-align: center; justify-content: center;">Inquire now</a>
                </div>
            </div>
        </div>
    </div>
</section>
