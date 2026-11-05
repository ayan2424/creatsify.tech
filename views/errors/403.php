<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="section" style="padding: 120px 0; text-align: center;">
    <div class="wrap" style="max-width: 640px;">
        <div class="om-sec-tag" style="margin:0 auto 16px;"><span class="om-sec-dot" style="background:#f59e0b;"></span> 403 Status</div>
        <h1 class="page-title">Access Denied<span class="om-dot-gold">.</span></h1>
        <p class="page-lead" style="margin:0 auto 32px;">
            <?= e($message ?? 'You do not have the required permissions to access this protected area.') ?>
        </p>
        <div style="display:flex;justify-content:center;gap:14px;flex-wrap:wrap;">
            <a href="<?= url('/') ?>" class="om-btn-gold">
                <span>Return to Homepage</span>
                <span class="om-btn-circle-arrow">&rarr;</span>
            </a>
            <a href="<?= url('/admin/login') ?>" class="om-btn-glass">
                <span>Admin Login</span>
                <span class="om-btn-circle-plus">+</span>
            </a>
        </div>
    </div>
</section>
