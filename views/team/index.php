<?php
/** @var Core\View $this */
$this->layout('layouts/main', [
    'meta_title' => $meta_title ?? 'Agency Creators & Engineering Team | Creatsify Studio',
    'meta_desc' => $meta_desc ?? 'Meet the engineers, architects, and designers behind Creatsify Studio.'
]);
?>

<div class="wrap" style="padding-top: 140px; padding-bottom: 80px;">
    <!-- Section Heading -->
    <div style="text-align: center; max-width: 720px; margin: 0 auto 60px;">
        <div class="pill rv" style="display: inline-flex; margin-bottom: 16px;">
            <i></i>Agency Creators & Specialists
        </div>
        <h1 class="rv" style="font-family: 'Bricolage Grotesque', sans-serif; font-size: clamp(32px, 5vw, 48px); font-weight: 700; letter-spacing: -0.02em; line-height: 1.15; margin-bottom: 16px;">
            The engineers and designers behind <span class="grad">Creatsify</span>
        </h1>
        <p class="sub rv" style="--d:.2s; font-size: 16px; color: var(--muted); line-height: 1.6;">
            Every product at Creatsify is engineered and designed by dedicated craftsmen. Explore our team members' individual portfolios, personal projects, and specialized skillsets.
        </p>
    </div>

    <!-- Team Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 28px;">
        <?php foreach ($members as $m): ?>
            <div class="calc-platform-card rv" style="padding: 32px; display: flex; flex-direction: column; justify-content: space-between; height: 100%; border-radius: 18px; border: 1px solid var(--line); background: var(--surface); transition: transform 0.25s ease, border-color 0.25s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                <div>
                    <!-- Member Header -->
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                        <img src="<?= asset($m['avatar'] ?: 'brand/creatsify-icon.png') ?>" alt="<?= e($m['name']) ?>" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent); box-shadow: 0 0 16px var(--accent-glow);">
                        <div>
                            <h3 style="font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 4px;"><?= e($m['name']) ?></h3>
                            <span style="font-size: 12px; font-weight: 600; color: var(--accent); background: var(--surface-hover); padding: 3px 10px; border-radius: 20px; border: 1px solid var(--line); display: inline-block;">
                                <?= e($m['role_title']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Bio -->
                    <p style="font-size: 14px; color: var(--muted); line-height: 1.6; margin-bottom: 20px;">
                        <?= e($m['bio']) ?>
                    </p>

                    <!-- Tech Stack Pills -->
                    <div style="margin-bottom: 24px;">
                        <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); font-weight: 600; display: block; margin-bottom: 8px;">Key Specializations:</span>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                            <?php foreach (explode(',', $m['skills'] ?: '') as $skill): ?>
                                <?php if (trim($skill)): ?>
                                    <span style="font-size: 11.5px; background: var(--bg); color: var(--text); padding: 3px 8px; border-radius: 6px; border: 1px solid var(--line); font-weight: 500;">
                                        <?= e(trim($skill)) ?>
                                    </span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Footer CTA & Socials -->
                <div style="border-top: 1px solid var(--line); padding-top: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                    <a href="<?= url('/team/' . $m['slug']) ?>" class="btn p" style="padding: 8px 18px; font-size: 13px;">
                        Explore Portfolio (<?= (int)($m['portfolio_count'] ?? 0) ?>) &rarr;
                    </a>

                    <!-- Social Links -->
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <?php if (!empty($m['github_url'])): ?>
                            <a href="<?= e($m['github_url']) ?>" target="_blank" rel="noopener" style="color: var(--muted); text-decoration: none;" title="GitHub">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($m['linkedin_url'])): ?>
                            <a href="<?= e($m['linkedin_url']) ?>" target="_blank" rel="noopener" style="color: var(--muted); text-decoration: none;" title="LinkedIn">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($m['email'])): ?>
                            <a href="mailto:<?= e($m['email']) ?>" style="color: var(--muted); text-decoration: none;" title="Email">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
