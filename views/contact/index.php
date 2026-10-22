<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="page-hero">
    <div class="wrap">
        <span class="section-tag">Direct Line</span>
        <h1 class="page-title">Start Your Project</h1>
        <p class="page-lead">Tell us about your product idea, timeline, or licensing inquiry. We dispatch real-time alerts to founder WhatsApp and review every submission thoroughly.</p>
    </div>
</section>

<section class="section">
    <div class="wrap" style="max-width: 800px;">
        <div class="contact-card" style="background: var(--surface); padding: 50px; border-radius: 14px; border: 1px solid var(--line);">
            <?php if ($msg = flash('success')): ?>
                <div class="alert alert-success"><?= e($msg) ?></div>
            <?php endif; ?>
            <?php if ($err = flash('error')): ?>
                <div class="alert alert-danger"><?= e($err) ?></div>
            <?php endif; ?>

            <form action="<?= url('/contact') ?>" method="POST" class="contact-form" id="pageContactForm">
                <?= csrf_field() ?>
                <div class="form-row">
                    <div class="form-group">
                        <label for="p_name">Your Name</label>
                        <input type="text" id="p_name" name="name" placeholder="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label for="p_email">Your Email</label>
                        <input type="email" id="p_email" name="email" placeholder="john@example.com" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="p_phone">Phone / WhatsApp (Optional)</label>
                        <input type="text" id="p_phone" name="phone" placeholder="+92 300 1234567">
                    </div>
                    <div class="form-group">
                        <label for="p_type">Project Type</label>
                        <select id="p_type" name="project_type">
                            <option value="Courier or warehouse system (Cheetah)">Courier or warehouse system (Cheetah)</option>
                            <option value="Web app or SaaS platform">Web app or SaaS platform</option>
                            <option value="Mobile or desktop app (Flutter/Electron)">Mobile or desktop app (Flutter/Electron)</option>
                            <option value="Restaurant ordering system (RestroMaster)">Restaurant ordering system (RestroMaster)</option>
                            <option value="WordPress / WooCommerce Store">WordPress / WooCommerce Store</option>
                            <option value="AI tool or WhatsApp integration">AI tool or WhatsApp integration</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="p_message">Message & Requirements</label>
                    <textarea id="p_message" name="message" rows="6" placeholder="Describe the problem, timeline, target users, or specific features you need..." required></textarea>
                </div>

                <button type="submit" class="btn p" style="width: 100%;">Send Inquiry &rarr;</button>
            </form>
        </div>
    </div>
</section>
