<?php
/** @var Core\View $this */
$this->layout('layouts/main');

$waPhone = clean_phone_number(setting('whatsapp_number', setting('site_phone', '923002521199')), false);
$waMsg = urlencode("Hello! I would like to discuss a project with Creatsify Studio.");
?>

<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> New Work &amp; Presales</div>
        <h1 class="page-title">Let’s Plan The Right Next Move<span class="om-dot-gold">.</span></h1>
        <p class="page-lead">Start a custom project, discuss an agency capability, or ask a pre-purchase question about our ready-made courier and store software. Existing customers can use the support desk for tracked tickets.</p>
    </div>
</section>

<section class="section" style="padding-top:20px;">
    <div class="wrap">
        <!-- Omoway 3-Path Bento Grid -->
        <div class="om-how-grid" style="margin-bottom:48px;">
            <div class="om-how-card rv" style="--d:0.08s">
                <span class="om-how-step-num">01</span>
                <h3>Custom Software Build</h3>
                <p>A web platform, custom admin panel, online store, or bespoke extensions to one of our software engines.</p>
                <div style="margin-top:16px;">
                    <a href="#inquiry-form" class="om-btn-glass sm"><span>Start Brief &darr;</span></a>
                </div>
            </div>
            <div class="om-how-card rv" style="--d:0.16s">
                <span class="om-how-step-num">02</span>
                <h3>Ready-Made Software</h3>
                <p>Browse our white-label courier backbones and store engines with 100% full source code and live demos.</p>
                <div style="margin-top:16px;">
                    <a href="<?= url('/software') ?>" class="om-btn-glass sm"><span>Browse Software &rarr;</span></a>
                </div>
            </div>
            <div class="om-how-card rv" style="--d:0.24s">
                <span class="om-how-step-num">03</span>
                <h3>Customer Support Desk</h3>
                <p>For existing license holders, installation guidance, deployment help, or technical ticket tracking.</p>
                <div style="margin-top:16px;">
                    <a href="<?= url('/support') ?>" class="om-btn-glass sm"><span>Open Support &rarr;</span></a>
                </div>
            </div>
        </div>

        <!-- Contact Form & WhatsApp Co-Founders Grid -->
        <div class="om-contact-wrap" id="inquiry-form">
            <div class="om-contact-left rv l">
                <div class="om-sec-tag"><span class="om-sec-dot"></span> Direct Consultation</div>
                <h2 class="om-sec-heading">Tell Us What You Are Trying To Achieve<span class="om-dot-gold">.</span></h2>
                <p class="om-sec-sub">Share what you know—features, timelines, references. The quickest way to get an instant answer is messaging our co-founders directly on WhatsApp.</p>

                <!-- Omoway Synced WhatsApp Card -->
                <div class="om-whatsapp-card">
                    <div class="om-wa-header">
                        <span class="om-wa-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                        </span>
                        <div class="om-wa-header-info">
                            <b>Chat on WhatsApp</b>
                            <span>Talk to our Co-Founders directly</span>
                        </div>
                        <span class="om-wa-active-pill"><i class="om-pulse-dot"></i> Live Now</span>
                    </div>
                    <a href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener" class="om-wa-btn">
                        <span class="om-wa-btn-lead">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                            <span>Message Co-Founders on WhatsApp</span>
                        </span>
                        <span class="om-wa-circle-arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </span>
                    </a>
                </div>

                <div class="om-contact-info-list">
                    <div class="om-ci-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span><b>Email:</b> <a href="mailto:<?= e(setting('site_email', 'ayan@creatsify.tech')) ?>" style="color:var(--accent);text-decoration:none;"><?= e(setting('site_email', 'ayan@creatsify.tech')) ?></a></span>
                    </div>
                    <div class="om-ci-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span><b>Location:</b> Remote, worldwide clients</span>
                    </div>
                </div>
            </div>

            <!-- Omoway Glassmorphic Project Brief Form -->
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
                <label>Your Email<input name="email" type="email" required autocomplete="email" placeholder="john@example.com"></label>
                <label>Phone / WhatsApp (Optional)<input name="phone" type="tel" placeholder="+1 555 123 4567" autocomplete="tel"></label>
                <label>What do you need?
                    <select name="project_type">
                        <option>Cheetah Courier Enterprise ($99)</option>
                        <option>Cheetah Lite Courier ($49)</option>
                        <option>Cheezify Food &amp; POS ($59)</option>
                        <option>Custom Web Application / Admin Panel</option>
                        <option>WhatsApp Cloud API Automation</option>
                        <option>WooCommerce Store Setup</option>
                        <option>aaPanel / Linux Server Deployment</option>
                        <option>Other / Custom Inquiry</option>
                    </select>
                </label>
                <label>Context &amp; Requirements
                    <textarea name="message" rows="5" required placeholder="Tell us about your business goals, target audience, timeline, or any specific software features needed..."></textarea>
                </label>

                <button type="submit" class="om-btn-gold" style="width:100%;justify-content:center;margin-top:12px;border:none;cursor:pointer;">
                    <span>Send Project Inquiry</span>
                    <span class="om-btn-circle-arrow">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                </button>
            </form>
        </div>
    </div>
</section>
