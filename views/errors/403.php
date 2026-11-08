<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="section" style="padding: 120px 0; text-align: center;">
    <div class="wrap" style="max-width: 640px;">
        <div class="om-sec-tag" style="margin:0 auto 16px;"><span class="om-sec-dot" style="background:#f59e0b;"></span> 403 Status</div>
        <h1 class="page-title">Access Denied</h1>
        <p class="page-lead" style="margin:0 auto 32px;">
            <?= e($message ?? 'You do not have the required permissions to access this protected area.') ?>
        </p>
        <div style="display:flex;justify-content:center;gap:14px;flex-wrap:wrap;">
            <a href="<?= url('/') ?>" class="om-btn-gold">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span>Return to Homepage</span>
            </a>
            <a href="<?= url('/admin/login') ?>" class="om-btn-glass">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                <span>Admin Login</span>
            </a>
        </div>
    </div>
</section>
