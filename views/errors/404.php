<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="section" style="padding: 120px 0; text-align: center;">
    <div class="container" style="max-width: 600px;">
        <span class="section-tag" style="color: #ff4fa3; border-color: rgba(255,79,163,0.3);">404 Error</span>
        <h1 style="font-size: 48px; color: var(--text); margin: 20px 0 10px;">Page Not Found</h1>
        <p style="color: var(--muted); font-size: 16px; line-height: 1.6; margin-bottom: 30px;">
            The page or resource you are looking for has been relocated, renamed, or does not exist.
        </p>
        <a href="<?= url('/') ?>" class="btn p">Return to Homepage &rarr;</a>
    </div>
</section>
