<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="page-hero">
    <div class="wrap" style="max-width:920px;">
        <span class="section-tag">New Work &amp; Pre-Sales</span>
        <h1 class="page-title">Let’s plan the right next move.</h1>
        <p class="page-lead">Use this form to start a custom project, discuss an agency service, or ask a pre-purchase question about ready-made software. Existing customers should use the dedicated support desk so their request becomes a tracked ticket.</p>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="wrap" style="max-width:1120px;">
        <div class="contact-paths">
            <a class="contact-path" href="#inquiry-form">
                <span class="section-tag">01 · Build with us</span>
                <h2>Custom project or agency service</h2>
                <p>A website, admin panel, online store, a change to one of our products, or an ongoing delivery team.</p>
                <span>Start a project brief &rarr;</span>
            </a>
            <a class="contact-path" href="<?= url('/portfolio') ?>">
                <span class="section-tag">02 · Explore software</span>
                <h2>Ready-made products &amp; licensing</h2>
                <p>Browse production software first, then use the form below for demos, licensing, or a fit discussion.</p>
                <span>See the software &rarr;</span>
            </a>
            <a class="contact-path contact-path-support" href="<?= url('/support') ?>">
                <span class="section-tag">Already a customer?</span>
                <h2>Get tracked support</h2>
                <p>For delivery, maintenance, software installation, license, billing, or technical help, open a support ticket.</p>
                <span>Go to customer support &rarr;</span>
            </a>
        </div>

        <div class="contact-card" id="inquiry-form">
            <?php if ($msg = flash('success')): ?>
                <div class="alert alert-success"><?= e($msg) ?></div>
            <?php endif; ?>
            <?php if ($err = flash('error')): ?>
                <div class="alert alert-danger"><?= e($err) ?></div>
            <?php endif; ?>

            <div class="contact-card-heading">
                <span class="section-tag">Project brief</span>
                <h2>Tell us what you are trying to achieve.</h2>
                <p>Share only what you know. We will turn it into a practical next-step conversation.</p>
            </div>

            <form action="<?= url('/contact') ?>" method="POST" class="contact-form" id="pageContactForm">
                <?= csrf_field() ?>
                <input type="text" name="_hp_check" value="" style="display:none!important;" tabindex="-1" autocomplete="off" aria-hidden="true">

                <div class="form-row">
                    <div class="form-group">
                        <label for="p_name">Your name</label>
                        <input type="text" id="p_name" name="name" placeholder="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label for="p_email">Work email</label>
                        <input type="email" id="p_email" name="email" placeholder="john@company.com" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="p_company">Company / brand</label>
                        <input type="text" id="p_company" name="company" placeholder="Optional">
                    </div>
                    <div class="form-group">
                        <label for="p_phone">Phone / WhatsApp</label>
                        <input type="text" id="p_phone" name="phone" placeholder="Optional">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="p_kind">What do you need?</label>
                        <select id="p_kind" name="inquiry_kind" required>
                            <?php foreach ($inquiryKinds as $value => $label): ?>
                                <option value="<?= e($value) ?>" <?= $selectedKind === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="p_type">Service, product, or focus</label>
                        <select id="p_type" name="project_type" required>
                            <option value="" disabled <?= $selectedOffering === '' ? 'selected' : '' ?>>Choose the closest option</option>
                            <?php if (!empty($services)): ?>
                                <optgroup label="Agency services">
                                    <?php foreach ($services as $service): ?>
                                        <option value="<?= e($service['title']) ?>" <?= $selectedOffering === $service['title'] ? 'selected' : '' ?>><?= e($service['title']) ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            <?php if (!empty($projects)): ?>
                                <optgroup label="Ready-made software">
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?= e($project['title']) ?>" <?= $selectedOffering === $project['title'] ? 'selected' : '' ?>><?= e($project['title']) ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            <option value="Other / not listed" <?= $selectedOffering === 'Other / not listed' ? 'selected' : '' ?>>Other / not listed</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="p_timeline">Ideal timeline</label>
                        <select id="p_timeline" name="timeline">
                            <option value="">Not sure yet</option>
                            <option value="As soon as possible">As soon as possible</option>
                            <option value="Within 1 month">Within 1 month</option>
                            <option value="1–3 months">1–3 months</option>
                            <option value="3+ months / ongoing">3+ months / ongoing</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="p_budget">Indicative budget</label>
                        <select id="p_budget" name="budget">
                            <option value="">Prefer not to say</option>
                            <option value="Under $1,000">Under $1,000</option>
                            <option value="$1,000–$5,000">$1,000–$5,000</option>
                            <option value="$5,000–$15,000">$5,000–$15,000</option>
                            <option value="$15,000+">$15,000+</option>
                            <option value="Need a recommendation">Need a recommendation</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="p_message">Context, goals, and requirements</label>
                    <textarea id="p_message" name="message" rows="7" placeholder="What should this help your business achieve? Add useful links, target users, features, and anything you have already tried." required></textarea>
                </div>

                <p class="form-help">We use this information only to scope and reply to your request. For help with an active delivery or a purchased product, use <a href="<?= url('/support') ?>">customer support</a>.</p>
                <button type="submit" class="btn p" style="width:100%;">Send project inquiry &rarr;</button>
            </form>
        </div>
    </div>
</section>

<style>
.contact-paths{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin:0 0 28px}.contact-path{display:flex;min-height:224px;flex-direction:column;padding:26px;border:1px solid var(--line);border-radius:16px;background:var(--surface);color:var(--text);text-decoration:none;transition:transform .2s ease,border-color .2s ease}.contact-path:hover{transform:translateY(-3px);border-color:var(--accent)}.contact-path h2{margin:12px 0 9px;font-size:21px}.contact-path p{margin:0;color:var(--muted);line-height:1.6}.contact-path>span:last-child{margin-top:auto;color:var(--accent);font-weight:700;padding-top:18px}.contact-path-support{background:linear-gradient(145deg,var(--surface),rgba(99,102,241,.11))}.contact-card{max-width:920px;margin:0 auto;background:var(--surface);padding:44px;border-radius:16px;border:1px solid var(--line)}.contact-card-heading{border-bottom:1px solid var(--line);padding-bottom:22px;margin-bottom:28px}.contact-card-heading h2{margin:10px 0 7px}.contact-card-heading p,.form-help{color:var(--muted);line-height:1.6}.form-help{font-size:13px;margin:0 0 20px}.form-help a{color:var(--accent)}@media(max-width:820px){.contact-paths{grid-template-columns:1fr}.contact-card{padding:26px 20px}}
</style>
