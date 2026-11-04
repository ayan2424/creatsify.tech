<?php
/** @var Core\View $this */
$this->layout('layouts/main');
$siteName = setting('site_name', 'Creatsify');
$email = setting('site_email', 'ayan@creatsify.tech');
?>
<section class="page-hero">
    <div class="wrap">
        <span class="section-tag">Privacy</span>
        <h1 class="page-title">Privacy notice</h1>
        <p class="page-lead">What this website collects, why, and the choices you have. We keep it short and plain.</p>
    </div>
</section>
<section class="section">
    <div class="wrap" style="max-width:860px">
        <div class="pg-card pg-prose">
            <h2>Who we are</h2>
            <p><?= e($siteName) ?> is a software studio. You can reach us at <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a>.</p>

            <h3>What we collect</h3>
            <ul>
                <li><strong>What you send us.</strong> When you use the contact form, the support form, the live chat, WhatsApp or SMS, we receive what you type, such as your name, email, phone number, company and message.</li>
                <li><strong>Basic visit statistics.</strong> For each page view we record the page, your IP address, the page you came from, and your browser and device type. We use it to see which pages are used.</li>
                <li><strong>Cookies and local storage.</strong> A security token protects our forms. Your browser also remembers your theme and sound choice on your own device.</li>
            </ul>

            <h3>Why we use it</h3>
            <p>To reply to you, to give support, to keep the site secure, and to understand what visitors read. We do not sell your information.</p>

            <h3>Third parties</h3>
            <p>Messages you send us may reach our team through email, WhatsApp or SMS notifications. Our fonts are hosted on this site, so no font provider receives your visit.</p>

            <h3>How long we keep it</h3>
            <p>We keep messages and support tickets for as long as we need them to help you and to keep our records.</p>

            <h3>Your choices</h3>
            <p>You can ask us to show, correct or delete the information we hold about you. Write to <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a>.</p>

            <p style="margin-top:28px;font-size:13px">Last updated: October 2026.</p>
        </div>
    </div>
</section>
