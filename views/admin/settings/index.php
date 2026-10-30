<?php
/** @var Core\View $this */
$this->layout('layouts/admin');

$g = $settings['general'] ?? [];
$app = $settings['appearance'] ?? [];
$f = $settings['founder'] ?? [];
$m = $settings['mail'] ?? [];
$w = $settings['whatsapp'] ?? [];
$s = $settings['sms'] ?? [];
$code = $settings['custom_code'] ?? [];
?>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Platform Settings & Customization Desk</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Configure themes, API gateways, SMTP servers, brand identities, and custom code.</p>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="crm-filter-pills" style="margin-bottom: 30px; overflow-x: auto; padding-bottom: 10px;">
        <a href="<?= url('/admin/settings?tab=general') ?>" class="crm-filter-pill <?= $tab === 'general' ? 'active' : '' ?>">General</a>
        <a href="<?= url('/admin/settings?tab=appearance') ?>" class="crm-filter-pill <?= $tab === 'appearance' ? 'active' : '' ?>">Theme & Appearance</a>
        <a href="<?= url('/admin/settings?tab=founder') ?>" class="crm-filter-pill <?= $tab === 'founder' ? 'active' : '' ?>">Founder Identity</a>
        <a href="<?= url('/admin/settings?tab=mail') ?>" class="crm-filter-pill <?= $tab === 'mail' ? 'active' : '' ?>">Email SMTP</a>
        <a href="<?= url('/admin/settings?tab=whatsapp') ?>" class="crm-filter-pill <?= $tab === 'whatsapp' ? 'active' : '' ?>">WhatsApp Meta API</a>
        <a href="<?= url('/admin/settings?tab=sms') ?>" class="crm-filter-pill <?= $tab === 'sms' ? 'active' : '' ?>">SMS Gateways</a>
        <a href="<?= url('/admin/settings?tab=custom_code') ?>" class="crm-filter-pill <?= $tab === 'custom_code' ? 'active' : '' ?>">Custom Code & CSS</a>
    </div>

    <!-- 1. General Settings -->
    <?php if ($tab === 'general'): ?>
        <form action="<?= url('/admin/settings') ?>" method="POST" style="max-width: 700px;">
            <?= csrf_field() ?>
            <input type="hidden" name="_group" value="general">

            <div class="form-group">
                <label for="site_title">Website Title</label>
                <input type="text" id="site_title" name="site_title" class="form-control" value="<?= e($g['site_title'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="site_tagline">Tagline</label>
                <input type="text" id="site_tagline" name="site_tagline" class="form-control" value="<?= e($g['site_tagline'] ?? '') ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="site_email">Primary Contact Email</label>
                    <input type="email" id="site_email" name="site_email" class="form-control" value="<?= e($g['site_email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="site_phone">Phone / WhatsApp</label>
                    <input type="text" id="site_phone" name="site_phone" class="form-control" value="<?= e($g['site_phone'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="site_location">Studio Physical Location</label>
                <input type="text" id="site_location" name="site_location" class="form-control" value="<?= e($g['site_location'] ?? '') ?>">
            </div>

            <button type="submit" class="btn btn-primary">Save General Settings &rarr;</button>
        </form>
    <?php endif; ?>

    <!-- 2. Theme & Appearance -->
    <?php if ($tab === 'appearance'): ?>
        <form action="<?= url('/admin/settings') ?>" method="POST" style="max-width: 700px;">
            <?= csrf_field() ?>
            <input type="hidden" name="_group" value="appearance">

            <div class="form-group">
                <label for="primary_accent">Primary Accent Color</label>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <input type="color" id="primary_accent" name="primary_accent" value="<?= e($app['primary_accent'] ?? '#8b7bff') ?>" style="width: 50px; height: 42px; border-radius: 8px; padding: 2px; border: 1px solid var(--line); cursor: pointer;">
                    <input type="text" id="primary_accent_hex" class="form-control" value="<?= e($app['primary_accent'] ?? '#8b7bff') ?>" style="font-family: monospace; max-width: 130px;" placeholder="#8b7bff" maxlength="7">
                    <span id="accentPreviewBadge" style="font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 6px; background: <?= e($app['primary_accent'] ?? '#8b7bff') ?>; color: #fff;">Accent Preview</span>
                </div>
                <small style="color:var(--muted); font-size:12px; margin-top:4px; display:block;">Dynamically updates all buttons, glowing effects, and badges across the entire platform.</small>
            </div>

            <div class="form-group">
                <label for="dark_mode_default">Default Color Mode</label>
                <select id="dark_mode_default" name="dark_mode_default" class="form-control">
                    <option value="1" <?= ($app['dark_mode_default'] ?? '1') === '1' ? 'selected' : '' ?>>Dark Luxury (Default)</option>
                    <option value="0" <?= ($app['dark_mode_default'] ?? '1') === '0' ? 'selected' : '' ?>>Light Clean</option>
                </select>
            </div>

            <div class="form-group">
                <label for="site_logo">Dark Theme Logo URL</label>
                <input type="text" id="site_logo" name="site_logo" class="form-control" value="<?= e($app['site_logo'] ?? '/assets/brand/creatsify-wordmark.png') ?>">
            </div>

            <div class="form-group">
                <label for="site_logo_light">Light Theme Logo URL</label>
                <input type="text" id="site_logo_light" name="site_logo_light" class="form-control" value="<?= e($app['site_logo_light'] ?? '/assets/brand/creatsify-wordmark-light.png') ?>">
            </div>

            <div class="form-group">
                <label for="site_icon">Emblem / Favicon URL</label>
                <input type="text" id="site_icon" name="site_icon" class="form-control" value="<?= e($app['site_icon'] ?? '/assets/brand/favicon.png') ?>">
            </div>

            <button type="submit" class="btn btn-primary">Save Appearance &rarr;</button>
        </form>
    <?php endif; ?>

    <!-- 3. Founder Identity -->
    <?php if ($tab === 'founder'): ?>
        <form action="<?= url('/admin/settings') ?>" method="POST" style="max-width: 700px;">
            <?= csrf_field() ?>
            <input type="hidden" name="_group" value="founder">

            <div class="form-row">
                <div class="form-group">
                    <label for="founder_name">Founder Full Name</label>
                    <input type="text" id="founder_name" name="founder_name" class="form-control" value="<?= e($f['founder_name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="founder_title">Title / Role</label>
                    <input type="text" id="founder_title" name="founder_title" class="form-control" value="<?= e($f['founder_title'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="founder_bio">Bio & Background</label>
                <textarea id="founder_bio" name="founder_bio" class="form-control" rows="4"><?= e($f['founder_bio'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="founder_github">GitHub Profile URL</label>
                    <input type="text" id="founder_github" name="founder_github" class="form-control" value="<?= e($f['founder_github'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="founder_linkedin">LinkedIn Profile URL</label>
                    <input type="text" id="founder_linkedin" name="founder_linkedin" class="form-control" value="<?= e($f['founder_linkedin'] ?? '') ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Founder Identity &rarr;</button>
        </form>
    <?php endif; ?>

    <!-- 4. Email SMTP -->
    <?php if ($tab === 'mail'): ?>
        <div style="max-width: 700px;">
            <form action="<?= url('/admin/settings') ?>" method="POST" style="margin-bottom: 30px;">
                <?= csrf_field() ?>
                <input type="hidden" name="_group" value="mail">

                <div class="form-row">
                    <div class="form-group">
                        <label for="smtp_host">SMTP Host</label>
                        <input type="text" id="smtp_host" name="smtp_host" class="form-control" value="<?= e($m['smtp_host'] ?? '') ?>" placeholder="smtp.mailtrap.io">
                    </div>
                    <div class="form-group">
                        <label for="smtp_port">Port</label>
                        <input type="number" id="smtp_port" name="smtp_port" class="form-control" value="<?= e($m['smtp_port'] ?? '587') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="smtp_user">Username</label>
                        <input type="text" id="smtp_user" name="smtp_user" class="form-control" value="<?= e($m['smtp_user'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="smtp_pass">Password</label>
                        <div class="password-input-wrap">
                            <input type="password" id="smtp_pass" name="smtp_pass" class="form-control" value="<?= e($m['smtp_pass'] ?? '') ?>" placeholder="••••••••">
                            <button type="button" class="password-toggle-trigger" data-target="smtp_pass" title="Toggle password visibility">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="2" r="3"></circle></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="smtp_encryption">Encryption</label>
                        <select id="smtp_encryption" name="smtp_encryption" class="form-control">
                            <option value="tls" <?= ($m['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS (Recommended)</option>
                            <option value="ssl" <?= ($m['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                            <option value="none" <?= ($m['smtp_encryption'] ?? '') === 'none' ? 'selected' : '' ?>>None (Local)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="smtp_from_email">From Email Address</label>
                        <input type="email" id="smtp_from_email" name="smtp_from_email" class="form-control" value="<?= e($m['smtp_from_email'] ?? '') ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save SMTP Credentials &rarr;</button>
            </form>

            <!-- 1-Click Diagnostic SMTP Verification Tool -->
            <div style="background: var(--bg); padding: 25px; border-radius: 10px; border: 1px solid var(--line);">
                <h4 style="margin-top: 0; color: var(--text);">Diagnostic Mailer Test</h4>
                <p style="font-size: 13px; color: var(--muted); margin: 6px 0 16px;">Send a live test verification email to ensure your SMTP socket handshake is functioning.</p>
                <form action="<?= url('/admin/settings/test-email') ?>" method="POST" style="display: flex; gap: 10px;">
                    <?= csrf_field() ?>
                    <input type="email" name="test_email" class="form-control" placeholder="Recipient (e.g. ayan@creatsify.tech)" value="<?= e($g['site_email'] ?? '') ?>" required>
                    <button type="submit" class="btn btn-secondary" style="white-space: nowrap;">Send Test Email</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- 5. WhatsApp Meta API -->
    <?php if ($tab === 'whatsapp'): ?>
        <form action="<?= url('/admin/settings') ?>" method="POST" style="max-width: 700px;">
            <?= csrf_field() ?>
            <input type="hidden" name="_group" value="whatsapp">

            <div class="form-group">
                <label for="wa_phone_number_id">Meta WhatsApp Phone Number ID</label>
                <input type="text" id="wa_phone_number_id" name="wa_phone_number_id" class="form-control" value="<?= e($w['wa_phone_number_id'] ?? '') ?>" placeholder="e.g. 109283746501928">
            </div>

            <div class="form-group">
                <label for="wa_access_token">Permanent System User Access Token</label>
                <textarea id="wa_access_token" name="wa_access_token" class="form-control" rows="3" placeholder="EAABw..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="wa_admin_notification_phone">Admin Notification Phone</label>
                    <input type="text" id="wa_admin_notification_phone" name="wa_admin_notification_phone" class="form-control" value="<?= e($w['wa_admin_notification_phone'] ?? '') ?>" placeholder="923000000000">
                    <small style="color:var(--muted); font-size:12px; margin-top:4px; display:block;">Receives instant alerts on new inquiries and live chat human handoffs.</small>
                </div>
                <div class="form-group">
                    <label for="wa_verify_token">Webhook Verify Token</label>
                    <input type="text" id="wa_verify_token" name="wa_verify_token" class="form-control" value="<?= e($w['wa_verify_token'] ?? 'creatsify_wa_webhook_token_secure') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="wa_auto_reply_enabled">Incoming Auto-Reply</label>
                <select id="wa_auto_reply_enabled" name="wa_auto_reply_enabled" class="form-control">
                    <option value="1" <?= ($w['wa_auto_reply_enabled'] ?? '1') === '1' ? 'selected' : '' ?>>Enabled</option>
                    <option value="0" <?= ($w['wa_auto_reply_enabled'] ?? '1') === '0' ? 'selected' : '' ?>>Disabled</option>
                </select>
            </div>

            <div style="background: var(--bg); padding: 15px; border-radius: 8px; border: 1px solid var(--line); margin-bottom: 20px; font-size: 13px; color: var(--muted);">
                <strong>Webhook Callback URL:</strong> <code style="color:var(--accent);"><?= url('/api/webhooks/whatsapp') ?></code>
            </div>

            <button type="submit" class="btn btn-primary">Save WhatsApp Config &rarr;</button>
        </form>
    <?php endif; ?>

    <!-- 6. SMS Gateways -->
    <?php if ($tab === 'sms'): ?>
        <form action="<?= url('/admin/settings') ?>" method="POST" style="max-width: 700px;">
            <?= csrf_field() ?>
            <input type="hidden" name="_group" value="sms">

            <div class="form-group">
                <label for="sms_gateway">Active Gateway</label>
                <select id="sms_gateway" name="sms_gateway" class="form-control">
                    <option value="twilio" <?= ($s['sms_gateway'] ?? 'twilio') === 'twilio' ? 'selected' : '' ?>>Twilio REST API</option>
                    <option value="pakistan" <?= ($s['sms_gateway'] ?? '') === 'pakistan' ? 'selected' : '' ?>>Local Pakistani Gateway</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="sms_twilio_sid">Twilio Account SID</label>
                    <input type="text" id="sms_twilio_sid" name="sms_twilio_sid" class="form-control" value="<?= e($s['sms_twilio_sid'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="sms_twilio_token">Twilio Auth Token</label>
                    <div class="password-input-wrap">
                        <input type="password" id="sms_twilio_token" name="sms_twilio_token" class="form-control" value="<?= e($s['sms_twilio_token'] ?? '') ?>" placeholder="••••••••">
                        <button type="button" class="password-toggle-trigger" data-target="sms_twilio_token" title="Toggle token visibility">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="2" r="3"></circle></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="sms_twilio_from">Twilio Outbound Phone Number</label>
                    <input type="text" id="sms_twilio_from" name="sms_twilio_from" class="form-control" value="<?= e($s['sms_twilio_from'] ?? '') ?>" placeholder="+1234567890">
                </div>
                <div class="form-group">
                    <label for="sms_admin_phone">Admin Notification Phone</label>
                    <input type="text" id="sms_admin_phone" name="sms_admin_phone" class="form-control" value="<?= e($s['sms_admin_phone'] ?? '') ?>" placeholder="+923000000000">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save SMS Settings &rarr;</button>
        </form>
    <?php endif; ?>

    <!-- 7. Custom Code & Injectors -->
    <?php if ($tab === 'custom_code'): ?>
        <form action="<?= url('/admin/settings') ?>" method="POST" style="max-width: 800px;">
            <?= csrf_field() ?>
            <input type="hidden" name="_group" value="custom_code">

            <div class="form-group">
                <label for="custom_header_code">Header Injected Code (&lt;head&gt; Scripts & Tags)</label>
                <textarea id="custom_header_code" name="custom_header_code" class="form-control" rows="5" style="font-family: monospace; font-size: 13px;" placeholder="<!-- Google Analytics, Meta Pixel, or Custom Fonts -->"><?= e($code['custom_header_code'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="custom_footer_code">Footer Injected Code (Before &lt;/body&gt;)</label>
                <textarea id="custom_footer_code" name="custom_footer_code" class="form-control" rows="5" style="font-family: monospace; font-size: 13px;" placeholder="<!-- Custom tracker scripts, chat triggers, or widgets -->"><?= e($code['custom_footer_code'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="custom_css">Custom CSS Stylesheet Overrides</label>
                <textarea id="custom_css" name="custom_css" class="form-control" rows="6" style="font-family: monospace; font-size: 13px;" placeholder="/* Custom CSS overrides */"><?= e($code['custom_css'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Custom Code & CSS &rarr;</button>
        </form>
    <?php endif; ?>
</div>

<script>
    // Color Picker 2-Way Synchronization
    const colorPicker = document.getElementById('primary_accent');
    const colorHex = document.getElementById('primary_accent_hex');
    const previewBadge = document.getElementById('accentPreviewBadge');

    if (colorPicker && colorHex) {
        colorPicker.addEventListener('input', (e) => {
            colorHex.value = e.target.value.toLowerCase();
            if (previewBadge) previewBadge.style.backgroundColor = e.target.value;
        });

        colorHex.addEventListener('input', (e) => {
            let val = e.target.value.trim();
            if (!val.startsWith('#') && val.length > 0) val = '#' + val;
            if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
                colorPicker.value = val;
                if (previewBadge) previewBadge.style.backgroundColor = val;
            }
        });
    }

    // Reusable Password / Token Visibility Toggles
    document.querySelectorAll('.password-toggle-trigger').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (!input) return;
            const isPwd = input.type === 'password';
            input.type = isPwd ? 'text' : 'password';
            this.style.color = isPwd ? 'var(--adm-accent, #8b7bff)' : '';
        });
    });
</script>
