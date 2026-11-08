<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="section" style="padding: 120px 0; text-align: center;">
    <div class="wrap" style="max-width: 640px;">
        <div class="om-sec-tag" style="margin:0 auto 16px;"><span class="om-sec-dot" style="background:#ef4444;"></span> 404 Status</div>
        <h1 class="page-title">Page Not Found</h1>
        <p class="page-lead" style="margin:0 auto 32px;">
            The page or route you are looking for has been relocated, updated, or does not exist on Creatsify Studio.
        </p>
        <div style="display:flex;justify-content:center;gap:14px;flex-wrap:wrap;">
            <a href="<?= url('/') ?>" class="om-btn-gold">
                <span class="om-btn-circle-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
                <span>Return to Homepage</span>
            </a>
            <a href="<?= url('/software') ?>" class="om-btn-glass">
                <span>Browse Software</span>
                <span class="om-btn-circle-plus">+</span>
            </a>
        </div>
    </div>
</section>
