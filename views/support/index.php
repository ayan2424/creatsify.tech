<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="page-hero">
    <div class="wrap" style="max-width:950px;">
        <span class="section-tag">Existing Customers</span>
        <h1 class="page-title">Customer support &amp; delivery desk.</h1>
        <p class="page-lead">One direct place for active agency work, maintenance, licensed software, deployment, billing, and technical issues. Every request receives a referenceable ticket in our internal CRM.</p>
        <p class="support-intro-link">Planning a new project or considering a software purchase? <a href="<?= url('/contact') ?>">Start with the contact desk instead.</a></p>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="wrap" style="max-width:1180px;">
        <?php if ($isSuccess): ?>
            <div class="support-success">
                <span class="section-tag">Ticket created</span>
                <h2>We received ticket #<?= e($ticketSuccess) ?>.</h2>
                <p>Our team will review the context and reply by email or WhatsApp. Keep this ticket number for follow-up.</p>
                <a class="btn p" href="<?= url('/portfolio') ?>">Explore software &rarr;</a>
            </div>
        <?php endif; ?>
        <?php if ($msg = flash('success')): ?>
            <div class="alert alert-success"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php if ($err = flash('error')): ?>
            <div class="alert alert-danger"><?= e($err) ?></div>
        <?php endif; ?>

        <div class="support-layout-grid">
            <div class="support-form-card">
                <div class="support-form-heading">
                    <span class="section-tag">Open a ticket</span>
                    <h2>Give us the context we need.</h2>
                    <p>For software tickets, a receipt, order ID, or license key lets us verify and help faster. For agency delivery, choose the relevant service or project.</p>
                </div>

                <form action="<?= url('/support') ?>" method="POST" id="supportForm">
                    <?= csrf_field() ?>
                    <input type="text" name="_hp_check" value="" style="display:none!important;" tabindex="-1" autocomplete="off" aria-hidden="true">

                    <div class="form-row">
                        <div class="form-group"><label for="sp_name">Your name</label><input type="text" id="sp_name" name="name" required></div>
                        <div class="form-group"><label for="sp_email">Email used with us</label><input type="email" id="sp_email" name="email" required></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label for="sp_company">Company / brand</label><input type="text" id="sp_company" name="company" placeholder="Optional"></div>
                        <div class="form-group"><label for="sp_whatsapp">WhatsApp / phone</label><input type="text" id="sp_whatsapp" name="whatsapp" placeholder="Optional"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sp_type">How can we help?</label>
                            <select id="sp_type" name="support_type" required>
                                <?php foreach ($supportTypes as $value => $type): ?>
                                    <option value="<?= e($value) ?>" data-reference-required="<?= $type['reference_required'] ? '1' : '0' ?>" <?= $selectedType === $value ? 'selected' : '' ?>><?= e($type['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sp_offering_type">This is about</label>
                            <select id="sp_offering_type" name="offering_type" required>
                                <option value="service">An agency service or delivered project</option>
                                <option value="software" selected>Ready-made software product</option>
                                <option value="other">Something else</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sp_offering">Product, service, or project</label>
                            <select id="sp_offering" name="offering_name" required>
                                <option value="" disabled <?= $selectedOffering === '' ? 'selected' : '' ?>>Choose what this concerns</option>
                                <?php if ($selectedOffering !== ''): ?>
                                    <option value="<?= e($selectedOffering) ?>" selected><?= e($selectedOffering) ?></option>
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
                                <?php if (!empty($projects)): ?>
                                    <optgroup label="Ready-made software">
                                        <?php foreach ($projects as $project): ?>
                                            <?php if ($selectedOffering !== $project['title']): ?>
                                                <option value="<?= e($project['title']) ?>"><?= e($project['title']) ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                                <option value="Other / not listed">Other / not listed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sp_reference" id="sp_reference_label">Purchase receipt, order ID, or license key</label>
                            <input type="text" id="sp_reference" name="purchase_reference" placeholder="Required for software requests">
                            <span class="field-hint" id="sp_reference_hint">This is required for software support, deployment, and billing tickets.</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sp_environment">Hosting / environment</label>
                            <select id="sp_environment" name="server_environment">
                                <option value="">Not applicable / not sure</option>
                                <option value="aaPanel + Nginx">aaPanel + Nginx</option>
                                <option value="cPanel + Apache">cPanel + Apache</option>
                                <option value="Linux VPS + Nginx/PHP-FPM">Linux VPS + Nginx/PHP-FPM</option>
                                <option value="Docker / cloud environment">Docker / cloud environment</option>
                                <option value="Local development">Local development</option>
                                <option value="Other hosting">Other hosting</option>
                            </select>
                        </div>
                        <div class="form-group"><label for="sp_domain">Related URL or app domain</label><input type="url" id="sp_domain" name="domain_url" placeholder="https://example.com"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sp_category">Issue category</label>
                            <select id="sp_category" name="issue_category">
                                <option value="Installation, deployment, or configuration">Installation, deployment, or configuration</option>
                                <option value="Bug or unexpected behaviour">Bug or unexpected behaviour</option>
                                <option value="Access, account, or license">Access, account, or license</option>
                                <option value="Billing or purchase receipt">Billing or purchase receipt</option>
                                <option value="Maintenance or change request">Maintenance or change request</option>
                                <option value="Other customer support">Other customer support</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sp_priority">Priority</label>
                            <select id="sp_priority" name="priority">
                                <option value="low">Low — question or non-blocking</option>
                                <option value="medium" selected>Medium — normal assistance</option>
                                <option value="high">High — important functionality blocked</option>
                                <option value="urgent">Urgent — production outage</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group"><label for="sp_subject">Ticket subject</label><input type="text" id="sp_subject" name="subject" placeholder="A short summary of the issue" required></div>
                    <div class="form-group"><label for="sp_message">What happened and what have you tried?</label><textarea id="sp_message" name="message" rows="7" placeholder="Include steps, errors, screenshots or logs, affected users, and the result you expected." required></textarea></div>
                    <button type="submit" class="btn p" style="width:100%;">Create support ticket &rarr;</button>
                </form>
            </div>

            <aside class="support-aside">
                <div class="support-aside-card">
                    <span class="section-tag">Start in the right place</span>
                    <h3>New work is not a support ticket.</h3>
                    <p>Need a new service, a custom build, or a licensing demo? The contact desk routes that to scoping and sales—not the support queue.</p>
                    <a href="<?= url('/contact') ?>" class="btn">Start a project inquiry &rarr;</a>
                </div>
                <div class="support-aside-card">
                    <h3>aaPanel Nginx quick check</h3>
                    <p>For internal-route 404s, use only the front-controller rule in aaPanel’s URL rewrite box:</p>
                    <pre><code>location / {
    try_files $uri $uri/ /index.php?$query_string;
}</code></pre>
                    <p class="field-hint">Do not paste a complete <code>server { ... }</code> block or duplicate PHP-FPM rules in the aaPanel rewrite box.</p>
                </div>
                <div class="support-aside-card">
                    <h3>Need immediate context?</h3>
                    <p>Send a WhatsApp message with your ticket number or purchase reference. The ticket form remains the best place for logs and a complete technical history.</p>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', setting('founder_whatsapp', '923000000000')) ?>?text=<?= urlencode('Hello Creatsify! I need help with an existing service or software product.') ?>" target="_blank" rel="noopener" class="btn support-whatsapp">Message on WhatsApp &rarr;</a>
                </div>
            </aside>
        </div>
    </div>
</section>

<style>
.support-intro-link{margin:18px 0 0;color:var(--muted)}.support-intro-link a,.field-hint a{color:var(--accent)}.support-success{margin:0 0 26px;padding:28px;border-radius:16px;background:rgba(16,185,129,.11);border:1px solid rgba(16,185,129,.45)}.support-success h2{margin:10px 0 8px}.support-success p{color:var(--muted);margin:0 0 20px}.support-layout-grid{display:grid;grid-template-columns:minmax(0,1.6fr) minmax(280px,.9fr);gap:24px;align-items:start}.support-form-card,.support-aside-card{background:var(--surface);border:1px solid var(--line);border-radius:16px}.support-form-card{padding:36px}.support-form-heading{border-bottom:1px solid var(--line);padding-bottom:20px;margin-bottom:24px}.support-form-heading h2{margin:10px 0 8px}.support-form-heading p,.support-aside-card p{color:var(--muted);line-height:1.6}.support-aside{display:flex;flex-direction:column;gap:16px}.support-aside-card{padding:25px}.support-aside-card h3{margin:10px 0}.support-aside-card pre{margin:15px 0;padding:14px;overflow:auto;border-radius:9px;background:#0d1117;color:#a5d6ff;font-size:12px;line-height:1.55}.field-hint{display:block;margin-top:6px;font-size:12px;color:var(--muted);line-height:1.45}.support-whatsapp{background:#25D366!important;border-color:#25D366!important;color:#fff!important}@media(max-width:900px){.support-layout-grid{grid-template-columns:1fr}.support-form-card{padding:26px 20px}}
</style>
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
