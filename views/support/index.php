<?php
/** @var Core\View $this */
$this->layout('layouts/main');

$waPhone = clean_phone_number(setting('whatsapp_number', setting('site_phone', '923002521199')), false);
$waMsg = urlencode("Hello! I need technical support for my Creatsify software/service.");
?>

<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Customer Support &amp; Delivery Desk</div>
        <h1 class="page-title">Technical Support &amp; Deployment Desk</h1>
        <p class="page-lead">One direct place for license assistance, server configuration, software deployment, bug reports, and maintenance tickets. Every request receives a referenceable ticket in our agency CRM.</p>
        <p style="margin-top:14px;font-size:13.5px;color:var(--muted);">Planning a new project or software purchase? <a href="<?= url('/contact') ?>" style="color:var(--accent);font-weight:700;text-decoration:none;">Start with the contact desk instead</a></p>
    </div>
</section>

<section class="section" style="padding-top:20px;">
    <div class="wrap">
        <?php if ($isSuccess): ?>
            <div style="padding:28px;border-radius:20px;background:rgba(16,185,129,0.12);border:1px solid rgba(16,185,129,0.3);margin-bottom:32px;">
                <div class="om-sec-tag" style="background:#10b981;color:#ffffff;"><span class="om-sec-dot" style="background:#ffffff;"></span> Ticket Created</div>
                <h2 style="font-family:Sora,sans-serif;font-size:24px;font-weight:800;color:var(--text);margin:12px 0 8px;">We Received Ticket #<?= e($ticketSuccess) ?>.</h2>
                <p style="color:var(--muted);margin:0 0 18px;font-size:14.5px;">Our engineering support team will review the context and reply by email or WhatsApp. Keep this ticket number for tracking.</p>
                <a class="om-btn-gold sm" href="<?= url('/software') ?>">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    <span>Explore Software</span>
                </a>
            </div>
        <?php endif; ?>

        <?php if ($msg = flash('success')): ?>
            <div style="padding:14px 20px;border-radius:14px;background:rgba(16,185,129,0.12);border:1px solid rgba(16,185,129,0.3);color:#10b981;font-weight:700;margin-bottom:24px;">
                <?= e($msg) ?>
            </div>
        <?php endif; ?>
        <?php if ($err = flash('error')): ?>
            <div style="padding:14px 20px;border-radius:14px;background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#ef4444;font-weight:700;margin-bottom:24px;">
                <?= e($err) ?>
            </div>
        <?php endif; ?>

        <div class="om-case-grid">
            <!-- Ticket Creation Form Card -->
            <div class="om-bento-card">
                <div class="om-sec-tag"><span class="om-sec-dot"></span> Open A Ticket</div>
                <h2 style="font-family:Sora,sans-serif;font-size:22px;font-weight:800;margin:12px 0 8px;color:var(--text);">Give Us The Technical Context</h2>
                <p style="font-size:13.5px;color:var(--muted);line-height:1.6;margin:0 0 24px;">
                    For software tickets, an order ID or license key lets us verify and assist immediately. For agency delivery, select the relevant project.
                </p>

                <form action="<?= url('/support') ?>" method="POST" id="supportForm" class="om-contact-form" style="background:transparent;border:none;padding:0;box-shadow:none;">
                    <?= csrf_field() ?>
                    <input type="text" name="_hp_check" value="" style="display:none!important;" tabindex="-1" autocomplete="off" aria-hidden="true">

                    <div class="form-row">
                        <label>Your Name<input type="text" id="sp_name" name="name" required placeholder="John Doe"></label>
                        <label>Email Used With Us<input type="email" id="sp_email" name="email" required placeholder="john@example.com"></label>
                    </div>

                    <div class="form-row">
                        <label>Company / Brand (Optional)<input type="text" id="sp_company" name="company" placeholder="Acme Logistics"></label>
                        <label>WhatsApp / Phone (Optional)<input type="text" id="sp_whatsapp" name="whatsapp" placeholder="+1 555 123 4567"></label>
                    </div>

                    <div class="form-row">
                        <label>How can we help?
                            <select id="sp_type" name="support_type" required>
                                <?php foreach ($supportTypes as $value => $type): ?>
                                    <option value="<?= e($value) ?>" data-reference-required="<?= $type['reference_required'] ? '1' : '0' ?>" <?= $selectedType === $value ? 'selected' : '' ?>><?= e($type['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label>This is about
                            <select id="sp_offering_type" name="offering_type" required>
                                <option value="software" selected>Ready-made software product</option>
                                <option value="service">An agency service or delivered project</option>
                                <option value="other">Something else</option>
                            </select>
                        </label>
                    </div>

                    <div class="form-row">
                        <label>Product, service, or project
                            <select id="sp_offering" name="offering_name" required>
                                <option value="" disabled <?= $selectedOffering === '' ? 'selected' : '' ?>>Choose what this concerns</option>
                                <?php if ($selectedOffering !== ''): ?>
                                    <option value="<?= e($selectedOffering) ?>" selected><?= e($selectedOffering) ?></option>
                                <?php endif; ?>
                                <?php if (!empty($projects)): ?>
                                    <optgroup label="Ready-made software">
                                        <?php foreach ($projects as $project): ?>
                                            <?php if ($selectedOffering !== $project['title']): ?>
                                                <option value="<?= e($project['title']) ?>"><?= e($project['title']) ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                                <?php if (!empty($services)): ?>
                                    <optgroup label="Agency services">
                                        <?php foreach ($services as $service): ?>
                                            <?php if ($selectedOffering !== $service['title']): ?>
                                                <option value="<?= e($service['title']) ?>"><?= e($service['title']) ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                                <option value="Other / not listed">Other / not listed</option>
                            </select>
                        </label>
                        <label id="sp_reference_label">Purchase receipt, order ID, or license key
                            <input type="text" id="sp_reference" name="purchase_reference" placeholder="Required for software requests">
                            <span style="font-size:11.5px;color:var(--muted);margin-top:4px;display:block;" id="sp_reference_hint">Required for software support and deployment tickets.</span>
                        </label>
                    </div>

                    <div class="form-row">
                        <label>Hosting / Environment
                            <select id="sp_environment" name="server_environment">
                                <option value="">Not applicable / not sure</option>
                                <option value="Linux VPS (Nginx / Apache)">Linux VPS (Nginx / Apache)</option>
                                <option value="cPanel + Apache">cPanel + Apache</option>
                                <option value="Linux VPS + Nginx/PHP-FPM">Linux VPS + Nginx/PHP-FPM</option>
                                <option value="Docker / cloud environment">Docker / cloud environment</option>
                                <option value="Local development">Local development</option>
                                <option value="Other hosting">Other hosting</option>
                            </select>
                        </label>
                        <label>Related URL or app domain
                            <input type="url" id="sp_domain" name="domain_url" placeholder="https://example.com">
                        </label>
                    </div>

                    <div class="form-row">
                        <label>Issue category
                            <select id="sp_category" name="issue_category">
                                <option value="Installation, deployment, or configuration">Installation, deployment, or configuration</option>
                                <option value="Bug or unexpected behaviour">Bug or unexpected behaviour</option>
                                <option value="Access, account, or license">Access, account, or license</option>
                                <option value="Billing or purchase receipt">Billing or purchase receipt</option>
                                <option value="Maintenance or change request">Maintenance or change request</option>
                                <option value="Other customer support">Other customer support</option>
                            </select>
                        </label>
                        <label>Priority
                            <select id="sp_priority" name="priority">
                                <option value="low">Low — question or non-blocking</option>
                                <option value="medium" selected>Medium — normal assistance</option>
                                <option value="high">High — important functionality blocked</option>
                                <option value="urgent">Urgent — production outage</option>
                            </select>
                        </label>
                    </div>

                    <label>Ticket Subject
                        <input type="text" id="sp_subject" name="subject" placeholder="A short summary of the issue" required>
                    </label>

                    <label>What happened and what have you tried?
                        <textarea id="sp_message" name="message" rows="5" placeholder="Include steps, errors, screenshots or logs, affected users, and the result you expected." required></textarea>
                    </label>

                    <button type="submit" class="om-btn-gold" style="width:100%;justify-content:center;margin-top:10px;border:none;cursor:pointer;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <span>Create Support Ticket</span>
                    </button>
                </form>
            </div>

            <!-- Aside Helpdesk & SLA Overview -->
            <aside class="om-case-aside">
                <div class="om-bento-card">
                    <div class="om-sec-tag"><span class="om-sec-dot"></span> Fast Triage</div>
                    <h3 style="font-family:Sora,sans-serif;font-size:17px;font-weight:800;margin:10px 0 8px;color:var(--text);">Start in the Right Place</h3>
                    <p style="font-size:13px;color:var(--muted);line-height:1.6;margin:0 0 16px;">
                        Need a new service, custom build, or commercial software license? The contact desk routes directly to our senior engineering &amp; strategy team.
                    </p>
                    <a href="<?= url('/contact') ?>" class="om-btn-glass sm" style="width:100%;justify-content:center;">
                        <span>Start A Project Inquiry</span>
                    </a>
                </div>

                <div class="om-bento-card" style="margin-top:20px;">
                    <div class="om-sec-tag"><span class="om-sec-dot"></span> Guaranteed SLA</div>
                    <h3 style="font-family:Sora,sans-serif;font-size:17px;font-weight:800;margin:10px 0 8px;color:var(--text);">Rapid Engineering Response</h3>
                    <p style="font-size:13px;color:var(--muted);line-height:1.5;margin:0 0 12px;">
                        Critical deployment and production issues are triaged within 2–4 business hours by our senior engineering leads.
                    </p>
                    <span style="font-size:11.5px;color:var(--muted);line-height:1.4;display:block;">&bull; Direct 1-on-1 Senior Engineering Support</span>
                </div>

                <!-- Instant WhatsApp Triage -->
                <div class="om-whatsapp-card" style="margin-top:20px;">
                    <div class="om-wa-header">
                        <span class="om-wa-icon">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                        </span>
                        <div class="om-wa-header-info">
                            <b>Need Live Context?</b>
                            <span>Send ticket ID via WhatsApp</span>
                        </div>
                    </div>
                    <a href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener" class="om-wa-btn">
                        <span>Message on WhatsApp</span>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const type = document.getElementById('sp_type');
    const reference = document.getElementById('sp_reference');
    const hint = document.getElementById('sp_reference_hint');
    const updateReference = function () {
        const needsReference = type && type.options[type.selectedIndex].dataset.referenceRequired === '1';
        if (reference) reference.required = needsReference;
        if (hint) hint.textContent = needsReference
            ? 'Required for software support, deployment, and billing tickets.'
            : 'Optional for service delivery and maintenance tickets.';
    };
    if (type) { type.addEventListener('change', updateReference); updateReference(); }
});
</script>
