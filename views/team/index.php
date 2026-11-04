<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="page-hero">
    <div class="wrap">
        <span class="section-tag">Team</span>
        <h1 class="page-title">The team</h1>
        <p class="page-lead">The people who build and support Creatsify software. Open a profile to see what each person has worked on.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2 class="sr-only">Team members</h2>
        <div class="tm-grid">
            <?php foreach ($members as $i => $m):
                $skills = array_slice(array_filter(array_map('trim', explode(',', (string)($m['skills'] ?? '')))), 0, 5);
            ?>
                <article class="tm-card rv" style="--d:<?= $i * 0.08 ?>s">
                    <img class="tm-avatar" src="<?= asset($m['avatar'] ?: 'brand/creatsify-icon.png') ?>" width="72" height="72" loading="lazy" alt="<?= e($m['name']) ?>">
                    <h3><?= e($m['name']) ?></h3>
                    <p class="tm-role"><?= e($m['role_title']) ?></p>
                    <?php if (!empty($m['bio'])): ?><p class="tm-bio"><?= e($m['bio']) ?></p><?php endif; ?>
                    <?php if ($skills): ?><div class="chips"><?php foreach ($skills as $sk): ?><span><?= e($sk) ?></span><?php endforeach; ?></div><?php endif; ?>
                    <a class="tm-link" href="<?= url('/team/' . e($m['slug'])) ?>" style="display:inline-flex;align-items:center;gap:5px;">
                        <span>View profile<?= !empty($m['portfolio_count']) ? ' (' . (int)$m['portfolio_count'] . ' projects)' : '' ?></span>
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="pg-cta">
            <div><h3>Want to work with us?</h3><p>Tell us what you need and we reply with a plan and a price.</p></div>
            <div class="pg-cta-btns"><a class="btn p" href="<?= url('/contact') ?>">Start a project</a><a class="btn" href="<?= url('/software') ?>">Browse the software</a></div>
        </div>
    </div>
</section>
