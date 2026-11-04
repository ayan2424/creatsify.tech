<?php
/** @var Core\View $this */
$this->layout('layouts/main');
$skills = array_filter(array_map('trim', explode(',', (string)($member['skills'] ?? ''))));
?>
<section class="page-hero">
    <div class="wrap">
        <a href="<?= url('/team') ?>" class="more" style="display:inline-flex;align-items:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            <span>Back to the team</span>
        </a>
        <div class="tm-hero">
            <img class="tm-avatar tm-avatar-lg" src="<?= asset($member['avatar'] ?: 'brand/creatsify-icon.png') ?>" width="104" height="104" alt="<?= e($member['name']) ?>">
            <div>
                <h1 class="page-title"><?= e($member['name']) ?></h1>
                <p class="tm-role"><?= e($member['role_title']) ?></p>
                <div class="tm-socials">
                    <?php if (!empty($member['github_url'])): ?><a href="<?= e($member['github_url']) ?>" target="_blank" rel="noopener">GitHub</a><?php endif; ?>
                    <?php if (!empty($member['linkedin_url'])): ?><a href="<?= e($member['linkedin_url']) ?>" target="_blank" rel="noopener">LinkedIn</a><?php endif; ?>
                    <?php if (!empty($member['portfolio_url'])): ?><a href="<?= e($member['portfolio_url']) ?>" target="_blank" rel="noopener">Website</a><?php endif; ?>
                    <?php if (!empty($member['email'])): ?><a href="mailto:<?= e($member['email']) ?>">Email</a><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="case-grid">
            <div class="pg-card pg-prose">
                <h2>About</h2>
                <p><?= nl2br(e($member['bio'] ?? '')) ?></p>
                <?php if ($skills): ?>
                    <h3>Skills</h3>
                    <div class="case-chips" style="margin-top:0"><?php foreach ($skills as $sk): ?><span class="stack-chip"><?= e($sk) ?></span><?php endforeach; ?></div>
                <?php endif; ?>
            </div>
            <aside>
                <div class="pg-card case-support">
                    <h4>Work with <?= e(explode(' ', trim($member['name']))[0]) ?></h4>
                    <p>Tell us what you need and we reply with a plan and a price.</p>
                    <a href="<?= url('/contact') ?>" class="btn p btn-block" style="display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                        <span>Start a project</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </div>
            </aside>
        </div>

        <?php if (!empty($portfolios)): ?>
            <h2 class="work-title">Selected work</h2>
            <div class="work-list">
                <?php foreach ($portfolios as $w): ?>
                    <article class="pg-card work-item">
                        <div class="work-head">
                            <h3><?= e($w['title']) ?></h3>
                            <?php if (!empty($w['category'])): ?><span class="project-category-badge"><?= strtoupper(e($w['category'])) ?></span><?php endif; ?>
                        </div>
                        <?php if (!empty($w['role_in_project']) || !empty($w['client'])): ?>
                            <p class="work-meta"><?= e(trim(($w['role_in_project'] ?? '') . (!empty($w['client']) ? ' &middot; ' . $w['client'] : ''), ' &middot;')) ?></p>
                        <?php endif; ?>
                        <p><?= e($w['summary'] ?: $w['description']) ?></p>
                        <div class="project-stack">
                            <?php foreach (array_filter(array_map('trim', explode(',', (string)($w['tech_stack'] ?? '')))) as $t): ?><span class="stack-chip"><?= e($t) ?></span><?php endforeach; ?>
                        </div>
                        <?php if (!empty($w['project_url']) && $w['project_url'] !== '#'): ?>
                            <a class="project-btn-case" href="<?= e($w['project_url']) ?>" target="_blank" rel="noopener">Open project &nearr;</a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
