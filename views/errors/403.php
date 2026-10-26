<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="section" style="padding: 120px 0; text-align: center;">
    <div class="container" style="max-width: 600px; margin: 0 auto;">
        <span class="section-tag" style="color: #ff9f43; border-color: rgba(255,159,67,0.3);">403 Forbidden</span>
        <h1 style="font-size: 48px; color: var(--text); margin: 20px 0 10px;">Access Denied</h1>
        <p style="color: var(--muted); font-size: 16px; line-height: 1.6; margin-bottom: 30px;">
            <?= e($message ?? 'You do not have the required permissions to access this protected area.') ?>
        </p>
        <div style="display:flex; justify-content:center; gap:12px;">
            <a href="<?= url('/') ?>" class="btn p">Return to Homepage &rarr;</a>
            <a href="<?= url('/admin/login') ?>" class="btn">Admin Login &rarr;</a>
        </div>
    </div>
</section>
