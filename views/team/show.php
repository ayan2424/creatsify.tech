<?php
/** @var Core\View $this */
$this->layout('layouts/main');
$skills = array_filter(array_map('trim', explode(',', (string)($member['skills'] ?? ''))));
$firstName = explode(' ', trim($member['name']))[0];
?>

<section class="page-hero om-team-hero">
    <div class="wrap">
        <a href="<?= url('/team') ?>" class="om-back-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            <span>Back to The Team</span>
        </a>

        <div class="om-profile-header-card">
            <div class="om-profile-avatar-wrap">
                <img class="om-profile-avatar" src="<?= asset($member['avatar'] ?: 'brand/creatsify-icon.png') ?>" width="110" height="110" alt="<?= e($member['name']) ?>">
            </div>
            <div class="om-profile-info">
                <div class="om-sec-tag"><span class="om-sec-dot"></span> Studio Leadership</div>
                <h1 class="page-title" style="margin:8px 0 6px;"><?= e($member['name']) ?></h1>
                <p class="om-profile-role-title"><?= e($member['role_title']) ?></p>

                <div class="om-profile-socials">
                    <?php if (!empty($member['github_url'])): ?>
                        <a href="<?= e($member['github_url']) ?>" target="_blank" rel="noopener" class="om-social-pill">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>
                            <span>GitHub</span>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($member['linkedin_url'])): ?>
                        <a href="<?= e($member['linkedin_url']) ?>" target="_blank" rel="noopener" class="om-social-pill">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                            <span>LinkedIn</span>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($member['portfolio_url'])): ?>
                        <a href="<?= e($member['portfolio_url']) ?>" target="_blank" rel="noopener" class="om-social-pill">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            <span>Website</span>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($member['email'])): ?>
                        <a href="mailto:<?= e($member['email']) ?>" class="om-social-pill">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <span>Email</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="om-case-grid">
            <div class="om-bento-card">
                <div class="om-sec-tag"><span class="om-sec-dot"></span> Executive Profile</div>
                <h2 style="font-family:Sora,sans-serif;font-size:22px;font-weight:800;margin:12px 0 16px;color:var(--text);">About <?= e($member['name']) ?></h2>
                <div style="font-size:15px;line-height:1.75;color:var(--muted);margin-bottom:28px;">
                    <?= nl2br(e($member['bio'] ?? '')) ?>
                </div>

                <?php if ($skills): ?>
                    <h3 style="font-family:Sora,sans-serif;font-size:16.5px;font-weight:800;margin:0 0 14px;color:var(--text);">Core Capabilities &amp; Tech Stack</h3>
                    <div class="om-prod-chips">
                        <?php foreach ($skills as $sk): ?>
                            <span><?= e($sk) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="om-case-aside">
                <div class="om-bento-card">
                    <div class="om-sec-tag"><span class="om-sec-dot"></span> Collaboration</div>
                    <h3 style="font-family:Sora,sans-serif;font-size:18px;font-weight:800;margin:10px 0 10px;color:var(--text);">Work Directly with <?= e($firstName) ?></h3>
                    <p style="font-size:13.5px;color:var(--muted);line-height:1.6;margin:0 0 20px;">
                        Have a new platform idea, courier logistics project, or need custom web architecture? Partner directly with our co-founders without agency overhead.
                    </p>
                    <a href="<?= url('/contact') ?>" class="om-btn-gold" style="width:100%;justify-content:center;">
                        <span>Start A Project Brief</span>
                        <span class="om-btn-circle-arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </span>
                    </a>
                </div>
            </aside>
        </div>

        <?php if (!empty($portfolios)): ?>
            <div style="margin-top:64px;">
                <div class="om-sec-tag"><span class="om-sec-dot"></span> Track Record</div>
                <h2 class="om-sec-heading">Selected Work &amp; Shipments</h2>
                <div class="projects-grid" style="margin-top:24px;">
                    <?php foreach ($portfolios as $w): ?>
                        <article class="project-card">
                            <div class="project-card-header">
                                <span class="project-category-badge"><?= strtoupper(e($w['category'] ?? 'WORK')) ?></span>
                                <?php if (!empty($w['role_in_project'])): ?>
                                    <span class="project-status-badge" style="background:#0A1A33;color:#ffffff;"><?= e($w['role_in_project']) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="project-card-body">
                                <h3 class="project-title"><?= e($w['title']) ?></h3>
                                <p class="project-summary"><?= e($w['summary'] ?: $w['description']) ?></p>
                                <div class="project-stack">
                                    <?php foreach (array_filter(array_map('trim', explode(',', (string)($w['tech_stack'] ?? '')))) as $t): ?>
                                        <span class="stack-chip"><?= e($t) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php if (!empty($w['project_url']) && $w['project_url'] !== '#'): ?>
                                <div class="project-card-footer">
                                    <a class="om-btn-glass sm" href="<?= e($w['project_url']) ?>" target="_blank" rel="noopener">
                                        <span>Open Project</span>
                                        <span class="om-btn-circle-arrow">&nearr;</span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
