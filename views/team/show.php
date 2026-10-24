<?php
/** @var Core\View $this */
$this->layout('layouts/main', [
    'meta_title' => $meta_title,
    'meta_desc' => $meta_desc
]);
?>

<div class="wrap" style="padding-top: 130px; padding-bottom: 90px;">
    <!-- Navigation Back Link -->
    <div style="margin-bottom: 24px;">
        <a href="<?= url('/team') ?>" class="btn" style="padding: 6px 14px; font-size: 13px;">
            &larr; Back to Agency Team
        </a>
    </div>

    <!-- Member Profile Hero Card -->
    <div class="calc-box rv" style="padding: 40px; border-radius: 20px; border: 1px solid var(--line); background: var(--surface); margin-bottom: 60px; box-shadow: 0 15px 40px rgba(0,0,0,0.4);">
        <div style="display: flex; gap: 32px; align-items: flex-start; flex-wrap: wrap;">
            <!-- Avatar -->
            <img src="<?= asset($member['avatar'] ?: 'brand/creatsify-icon.png') ?>" alt="<?= e($member['name']) ?>" style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover; border: 3px solid var(--accent); box-shadow: 0 0 24px var(--accent-glow); flex-shrink: 0;">

            <!-- Info Block -->
            <div style="flex: 1; min-width: 280px;">
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 8px;">
                    <h1 style="font-family: 'Bricolage Grotesque', sans-serif; font-size: clamp(26px, 4vw, 36px); font-weight: 700; color: var(--text); margin: 0;">
                        <?= e($member['name']) ?>
                    </h1>
                    <span style="font-size: 12px; font-weight: 700; color: #10b981; background: rgba(16, 185, 129, 0.12); padding: 3px 10px; border-radius: 20px; border: 1px solid rgba(16, 185, 129, 0.25);">
                        ● Verified Creator
                    </span>
                </div>

                <div style="font-size: 16px; font-weight: 600; color: var(--accent); margin-bottom: 14px;">
                    <?= e($member['role_title']) ?> &bull; Creatsify Studio
                </div>

                <p style="font-size: 15px; color: var(--muted); line-height: 1.7; margin-bottom: 20px; max-width: 820px;">
                    <?= nl2br(e($member['bio'])) ?>
                </p>

                <!-- Skills & Capabilities -->
                <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 24px;">
                    <?php foreach (explode(',', $member['skills'] ?: '') as $skill): ?>
                        <?php if (trim($skill)): ?>
                            <span style="font-size: 12px; background: var(--bg); color: var(--text); padding: 4px 10px; border-radius: 6px; border: 1px solid var(--line); font-weight: 500;">
                                <?= e(trim($skill)) ?>
                            </span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Connect Links -->
                <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                    <?php if (!empty($member['email'])): ?>
                        <a href="mailto:<?= e($member['email']) ?>" class="btn p" style="padding: 8px 18px; font-size: 13px; display:inline-flex; align-items:center; gap:6px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <span>Contact <?= e(explode(' ', $member['name'])[0]) ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($member['github_url'])): ?>
                        <a href="<?= e($member['github_url']) ?>" target="_blank" rel="noopener" class="btn" style="padding: 8px 14px; font-size: 13px;">
                            GitHub &nearr;
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($member['linkedin_url'])): ?>
                        <a href="<?= e($member['linkedin_url']) ?>" target="_blank" rel="noopener" class="btn" style="padding: 8px 14px; font-size: 13px;">
                            LinkedIn &nearr;
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($member['portfolio_url'])): ?>
                        <a href="<?= e($member['portfolio_url']) ?>" target="_blank" rel="noopener" class="btn" style="padding: 8px 14px; font-size: 13px;">
                            Design Portfolio &nearr;
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Title: Showcase Projects -->
    <div style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 12px;">
        <div>
            <div class="idx rv">Personal Portfolio</div>
            <h2 class="rv" style="font-family: 'Bricolage Grotesque', sans-serif; font-size: 28px; font-weight: 700; color: var(--text);">
                Showcase Projects & Architectures
            </h2>
            <p class="sub rv" style="--d:.2s; font-size: 14px; color: var(--muted); margin-top: 4px;">
                Systems and products personally built, engineered, or designed by <?= e($member['name']) ?>.
            </p>
        </div>
        <span style="font-size: 13px; color: var(--muted); background: var(--surface); padding: 6px 14px; border-radius: 20px; border: 1px solid var(--line);">
            <?= count($portfolios) ?> Projects Cataloged
        </span>
    </div>

    <!-- Projects Grid -->
    <?php if (empty($portfolios)): ?>
        <div style="padding: 48px; text-align: center; color: var(--muted); background: var(--surface); border: 1px solid var(--line); border-radius: 14px;">
            <p>No individual projects showcased yet for this team member.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 32px;">
            <?php foreach ($portfolios as $p): ?>
                <div class="calc-platform-card rv" style="padding: 0; overflow: hidden; border-radius: 16px; border: 1px solid var(--line); background: var(--surface); display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                    <!-- Artwork Header with Hover Zoom -->
                    <div style="position: relative; overflow: hidden; height: 210px; background: var(--bg);">
                        <img src="<?= asset($p['image_url'] ?: 'images/hero-artwork.jpg') ?>" alt="<?= e($p['title']) ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;">
                        <span style="position: absolute; top: 14px; left: 14px; font-size: 11px; font-weight: 700; color: #fff; background: rgba(7, 8, 13, 0.85); backdrop-filter: blur(8px); padding: 4px 10px; border-radius: 20px; border: 1px solid var(--line);">
                            <?= e($p['category']) ?>
                        </span>
                        <?php if ($p['is_featured']): ?>
                            <span style="position: absolute; top: 14px; right: 14px; font-size: 11px; font-weight: 700; color: #f59e0b; background: rgba(245, 158, 11, 0.15); backdrop-filter: blur(8px); padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(245, 158, 11, 0.4);">
                                ⭐ Featured
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Content Body -->
                    <div style="padding: 24px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <!-- Role in Project Badge -->
                            <?php if (!empty($p['role_in_project'])): ?>
                                <span style="font-size: 11.5px; font-weight: 600; color: var(--accent); display: block; margin-bottom: 6px;">
                                    Role: <?= e($p['role_in_project']) ?>
                                </span>
                            <?php endif; ?>

                            <h3 style="font-size: 19px; font-weight: 700; color: var(--text); margin-bottom: 8px;">
                                <?= e($p['title']) ?>
                            </h3>

                            <p style="font-size: 13.5px; color: var(--muted); line-height: 1.6; margin-bottom: 16px;">
                                <?= e($p['summary'] ?: $p['description']) ?>
                            </p>

                            <!-- Tech Stack Chips -->
                            <div style="display: flex; gap: 5px; flex-wrap: wrap; margin-bottom: 20px;">
                                <?php foreach (explode(',', $p['tech_stack'] ?: '') as $tech): ?>
                                    <?php if (trim($tech)): ?>
                                        <span style="font-size: 11px; background: var(--bg); color: var(--text); padding: 2px 7px; border-radius: 4px; border: 1px solid var(--line); font-weight: 500;">
                                            <?= e(trim($tech)) ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Footer Links -->
                        <div style="border-top: 1px solid var(--line); padding-top: 16px; display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-size: 12px; color: var(--muted);">
                                <?= e($p['completion_date'] ?: 'Shipped') ?> &bull; <?= e($p['client'] ?: 'Client Project') ?>
                            </span>

                            <?php if (!empty($p['project_url']) && $p['project_url'] !== '#'): ?>
                                <a href="<?= e($p['project_url']) ?>" target="_blank" rel="noopener" class="btn p" style="padding: 6px 14px; font-size: 12px; font-weight: 600;">
                                    View Live &nearr;
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
