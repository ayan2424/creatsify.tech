<?php
/** @var Core\View $this */
$this->layout('layouts/main');
$siteName = setting('site_name', 'Creatsify');
$email = setting('site_email', 'ayan@creatsify.tech');
?>

<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Legal Notice</div>
        <h1 class="page-title">Privacy Policy &amp; Terms<span class="om-dot-gold">.</span></h1>
        <p class="page-lead">What information this website collects, why, and the choices you have. We respect user privacy and keep terms concise and transparent.</p>
    </div>
</section>

<section class="section" style="padding-top:20px;">
    <div class="wrap" style="max-width:880px;">
        <div class="om-bento-card" style="padding:44px;">
            <h2 style="font-family:Sora,sans-serif;font-size:22px;font-weight:800;color:var(--text);margin-top:0;">Who We Are</h2>
            <p style="font-size:15px;line-height:1.7;color:var(--muted);"><?= e($siteName) ?> is a full-service software engineering, brand design, and digital marketing agency. You can contact our agency team anytime at <a href="mailto:<?= e($email) ?>" style="color:var(--accent);text-decoration:none;font-weight:700;"><?= e($email) ?></a>.</p>

            <h3 style="font-family:Sora,sans-serif;font-size:18px;font-weight:800;color:var(--text);margin-top:28px;">What We Collect</h3>
            <ul style="font-size:14.5px;line-height:1.75;color:var(--muted);padding-left:20px;">
                <li><strong style="color:var(--text);">Direct Communications:</strong> When you use the contact desk, customer support portal, or live chat, we receive the details you submit, including your name, email, optional phone number, company, and message context.</li>
                <li><strong style="color:var(--text);">Telemetry &amp; Usage:</strong> For security and performance monitoring, we log request paths, response times, and general browser user-agents.</li>
                <li><strong style="color:var(--text);">Cookies &amp; Security:</strong> Secure CSRF tokens protect our public forms against unauthorized forged requests. Local storage retains your theme preference.</li>
            </ul>

            <h3 style="font-family:Sora,sans-serif;font-size:18px;font-weight:800;color:var(--text);margin-top:28px;">Why We Use It</h3>
            <p style="font-size:15px;line-height:1.7;color:var(--muted);">To deliver direct technical support, provide software licensing agreements, respond to client inquiries, and maintain high-security operations. We never sell, lease, or rent customer data to third-party advertisers.</p>

            <h3 style="font-family:Sora,sans-serif;font-size:18px;font-weight:800;color:var(--text);margin-top:28px;">Third-Party Services</h3>
            <p style="font-size:15px;line-height:1.7;color:var(--muted);">Commercial license payments are processed directly through Gumroad. Notifications reach our engineering and support desk through Meta WhatsApp Cloud API webhooks or encrypted SMTP email alerts. Fonts and assets are self-hosted on our server infrastructure.</p>

            <h3 style="font-family:Sora,sans-serif;font-size:18px;font-weight:800;color:var(--text);margin-top:28px;">Data Rights &amp; Retention</h3>
            <p style="font-size:15px;line-height:1.7;color:var(--muted);">You have the right to request a copy of, correct, or delete any personal information held in our CRM. Contact us directly at <a href="mailto:<?= e($email) ?>" style="color:var(--accent);text-decoration:none;font-weight:700;"><?= e($email) ?></a>.</p>

            <div style="margin-top:32px;padding-top:20px;border-top:1px solid var(--line);font-size:13px;color:var(--muted);">
                Last Updated: 2026 &bull; Creatsify Studio &bull; Worldwide, remote.
            </div>
        </div>
    </div>
</section>
