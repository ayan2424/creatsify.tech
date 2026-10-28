<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="page-hero">
    <div class="wrap">
        <span class="section-tag">Capabilities</span>
        <h1 class="page-title">Engineering Services</h1>
        <p class="page-lead">We partner with founders and enterprise operators to architect, develop, and scale production software platforms.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr)); gap: 30px;">
            <?php foreach ($services as $service): ?>
                <div class="service-card" style="background: var(--surface); padding: 40px; border-radius: 14px; border: 1px solid var(--line); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="width: 48px; height: 48px; border-radius: 10px; background: rgba(139,123,255,0.15); display: flex; align-items: center; justify-content: center; color: var(--accent); margin-bottom: 25px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
                        </div>

                        <h3 style="font-size: 22px; color: var(--text); margin-top: 0; margin-bottom: 12px;"><?= e($service['title']) ?></h3>
                        <p style="color: var(--muted); font-size: 15px; line-height: 1.6; margin-bottom: 25px;"><?= e($service['full_description'] ?: $service['short_description']) ?></p>

                        <?php if (!empty($service['deliverables'])): ?>
                            <div style="border-top: 1px solid var(--line); padding-top: 20px; margin-top: 20px;">
                                <strong style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text); display: block; margin-bottom: 12px;">Deliverables:</strong>
                                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                                    <?php foreach (explode(',', $service['deliverables']) as $deliv): ?>
                                        <li style="font-size: 14px; color: var(--muted); display: flex; align-items: center; gap: 8px;">
                                             <span style="color: var(--accent);">&check;</span>
                                             <span><?= e(trim($deliv)) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div style="margin-top: 30px;">
                        <a href="<?= url('/contact') ?>" class="btn p" style="width: 100%;">Inquire about <?= e($service['title']) ?> &rarr;</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
