<?php
/** @var Core\View $this */
$this->layout('layouts/main');

$waPhone = clean_phone_number(setting('whatsapp_number', setting('site_phone', '923002521199')), false);
$waMsg = urlencode("Hello Creatsify! I would like to discuss a project or service package for my business.");
$preSelectedOffering = trim((string)($_GET['offering'] ?? ''));
?>

<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Start Your Project Or Package</div>
        <h1 class="page-title">Let’s Build &amp; Scale Your Brand</h1>
        <p class="page-lead">
            Start a custom web or Flutter mobile app build, request a website redesign, launch an SEO &amp; paid social ad campaign, or ask us to customize one of our ready-made software platforms for your business.
        </p>
    </div>
</section>

<section class="section" style="padding-top:20px;">
    <div class="wrap">
        <!-- 3-Path Bento Grid -->
        <div class="om-how-grid" style="margin-bottom:48px;">
            <div class="om-how-card rv" style="--d:0.08s">
                <span class="om-how-step-num">01</span>
                <h3>Custom Agency Services</h3>
                <p>Web &amp; Mobile App Development, Website Redesign, Branding &amp; Logo Design, Technical SEO, and Social Media Paid Ads.</p>
                <div style="margin-top:16px;">
                    <a href="<?= url('/packages') ?>" class="om-btn-glass sm"><span>Compare Packages</span></a>
                </div>
            </div>
            <div class="om-how-card rv" style="--d:0.16s">
                <span class="om-how-step-num">02</span>
                <h3>Ready-Made Software</h3>
                <p>Buy a direct white-label license ($49–$99) or ask our engineering team to customize any platform for your workflows.</p>
                <div style="margin-top:16px;">
                    <a href="<?= url('/software') ?>" class="om-btn-glass sm"><span>Browse Products</span></a>
                </div>
            </div>
            <div class="om-how-card rv" style="--d:0.24s">
                <span class="om-how-step-num">03</span>
                <h3>Customer Support Desk</h3>
                <p>For existing clients and software license holders needing deployment guidance, server setup, or ticket tracking.</p>
                <div style="margin-top:16px;">
                    <a href="<?= url('/support') ?>" class="om-btn-glass sm"><span>Open Support</span></a>
                </div>
            </div>
        </div>

        <!-- Contact Form & WhatsApp Strategy Desk Grid -->
        <div class="om-contact-wrap" id="inquiry-form">
            <div class="om-contact-left rv l">
                <div class="om-sec-tag"><span class="om-sec-dot"></span> Direct Consultation</div>
                <h2 class="om-sec-heading">Tell Us What You Want To Achieve</h2>
                <p class="om-sec-sub">Share your goals, target timeline, or desired package. For the fastest response, message our senior engineering &amp; growth team directly on WhatsApp.</p>

                <div class="om-whatsapp-card">
                    <div class="om-wa-header">
                        <span class="om-wa-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                        </span>
                        <div class="om-wa-header-info">
                            <b>Instant WhatsApp Strategy Desk</b>
                            <span>Chat directly with our Agency Team</span>
                        </div>
                        <span class="om-wa-active-pill"><i class="om-pulse-dot"></i> Live Now</span>
                    </div>
                    <a href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener" class="om-wa-btn">
                        <span class="om-wa-btn-lead">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                            <span>Message Our Agency On WhatsApp</span>
                        </span>
                    </a>
                </div>

                <div class="om-contact-info-list">
                    <div class="om-ci-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span><b>Email:</b> <a href="mailto:<?= e(setting('site_email', 'hello@creatsify.tech')) ?>" style="color:var(--accent);text-decoration:none;"><?= e(setting('site_email', 'hello@creatsify.tech')) ?></a></span>
                    </div>
                    <div class="om-ci-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span><b>Availability:</b> Worldwide Remote Delivery</span>
                    </div>
                </div>
            </div>

            <!-- Glassmorphic Project Brief Form -->
            <form action="<?= url('/contact') ?>" method="POST" class="om-contact-form rv r" id="pageContactForm">
                <?= csrf_field() ?>
                <input type="text" name="_hp_check" value="" style="display:none!important;" tabindex="-1" autocomplete="off" aria-hidden="true">

                <?php if ($msg = flash('success')): ?>
                    <div style="padding:14px;border-radius:12px;background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);color:#10b981;font-weight:700;margin-bottom:16px;">
                        <?= e($msg) ?>
                    </div>
                <?php endif; ?>
                <?php if ($err = flash('error')): ?>
                    <div style="padding:14px;border-radius:12px;background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#ef4444;font-weight:700;margin-bottom:16px;">
                        <?= e($err) ?>
                    </div>
                <?php endif; ?>

                <label>Your Name<input name="name" required autocomplete="name" placeholder="John Doe"></label>
                <label>Your Email<input name="email" type="email" required autocomplete="email" placeholder="john@company.com"></label>
                <label>Phone / WhatsApp (Optional)<input name="phone" type="tel" placeholder="+1 555 123 4567" autocomplete="tel"></label>
                <label>Service, Package or Product Needed
                    <select name="project_type">
                        <?php if ($preSelectedOffering !== ''): ?>
                            <option value="<?= e($preSelectedOffering) ?>" selected><?= e($preSelectedOffering) ?></option>
                        <?php endif; ?>
                        <optgroup label="Core Agency Services">
                            <option value="Custom Web Development">Custom Web Development (PHP / Node.js / Next.js / Tailwind)</option>
                            <option value="Mobile App Development (Flutter & Dart)">Mobile App Development (Flutter &amp; Dart — iOS &amp; Android)</option>
                            <option value="Website Redesign & Modernization">Website Redesign &amp; Speed/CRO Modernization</option>
                            <option value="SaaS Product Engineering">SaaS Product &amp; Multi-Role Portal Engineering</option>
                            <option value="Search Engine Optimization (SEO)">Search Engine Optimization (Technical &amp; On-Page SEO)</option>
                            <option value="Social Media Marketing & Paid Ads">Social Media Marketing &amp; Paid Ads (Meta / Google / TikTok)</option>
                            <option value="Branding, Logo & Graphic Design">Branding, Custom Logo &amp; Graphic Design</option>
                            <option value="E-Commerce & Online Store Build">E-Commerce &amp; Online Store Build</option>
                        </optgroup>
                        <optgroup label="Ready-Made Software (Buy or Customize)">
                            <option value="Customize Ready-Made Software For My Business">Customize a Ready-Made Software Product For My Business</option>
                            <option value="Cheetah Courier Enterprise ($99)">Cheetah Courier Enterprise ($99)</option>
                            <option value="Cheetah Lite Courier ($49)">Cheetah Lite Courier ($49)</option>
                            <option value="Cheezify Food & POS ($59)">Cheezify Food &amp; POS ($59)</option>
                        </optgroup>
                        <optgroup label="Free Audit">
                            <option value="Free Website, UX & SEO Teardown Audit">Free 15-Min Website, UX &amp; SEO Teardown Audit</option>
                        </optgroup>
                    </select>
                </label>
                <label>Project Details &amp; Requirements
                    <textarea name="message" rows="5" required placeholder="Tell us about your business goals, target audience, timeline, or any specific features needed..."></textarea>
                </label>

                <button type="submit" class="om-btn-gold" style="width:100%;justify-content:center;margin-top:12px;border:none;cursor:pointer;">
                    <span class="om-btn-circle-arrow">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </span>
                    <span>Send Project Inquiry</span>
                </button>
            </form>
        </div>
    </div>
</section>
