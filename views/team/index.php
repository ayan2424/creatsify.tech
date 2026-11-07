<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>

<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Studio Leadership &amp; Core Team</div>
        <h1 class="page-title">The Engineering &amp; Design Team<span class="om-dot-gold">.</span></h1>
        <p class="page-lead">Three dedicated co-founders leading software architecture, product design systems, and client deployment operations. No account managers or outsourced layers—you collaborate directly with the engineers who build your software.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2 class="sr-only">Team Leadership</h2>
        <div class="tm-grid">
            <?php foreach ($members as $i => $m):
                $skills = array_slice(array_filter(array_map('trim', explode(',', (string)($m['skills'] ?? '')))), 0, 5);
                $stepNum = str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT);
            ?>
                <article class="om-bento-card rv" style="--d:<?= $i * 0.1 ?>s;display:flex;flex-direction:column;position:relative;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:12px;background:#0A1A33;color:#00D2EE;font-family:Sora,sans-serif;font-size:13px;font-weight:800;border:1px solid rgba(212,160,23,0.3);"><?= $stepNum ?></span>
                        <span class="om-sec-tag" style="margin:0;"><span class="om-sec-dot"></span> Co-Founder</span>
                    </div>

                    <div style="display:flex;align-items:center;gap:16px;margin-bottom:18px;">
                        <div class="om-profile-avatar-wrap" style="width:72px;height:72px;flex-shrink:0;">
                            <img class="om-profile-avatar" src="<?= asset($m['avatar'] ?: 'brand/creatsify-icon.png') ?>" width="72" height="72" loading="lazy" alt="<?= e($m['name']) ?>">
                        </div>
                        <div>
                            <h3 style="font-family:Sora,sans-serif;font-size:18px;font-weight:800;color:var(--text);margin:0 0 4px;"><?= e($m['name']) ?></h3>
                            <p style="color:var(--accent);font-size:13px;font-weight:600;margin:0;"><?= e($m['role_title']) ?></p>
                        </div>
                    </div>

                    <?php if (!empty($m['bio'])): ?>
                        <p style="font-size:14px;color:var(--muted);line-height:1.65;margin:0 0 20px;flex-grow:1;"><?= e($m['bio']) ?></p>
                    <?php endif; ?>

                    <?php if ($skills): ?>
                        <div class="om-prod-chips" style="margin-bottom:24px;">
                            <?php foreach ($skills as $sk): ?>
                                <span><?= e($sk) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div style="margin-top:auto;padding-top:16px;border-top:1px solid var(--line);">
                        <a class="om-tc-btn" href="<?= url('/team/' . e($m['slug'])) ?>" style="width:100%;justify-content:space-between;">
                            <span>View Co-Founder Profile<?= !empty($m['portfolio_count']) ? ' (' . (int)$m['portfolio_count'] . ' projects)' : '' ?></span>
                            <span class="om-tc-arrow">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                            </span>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
