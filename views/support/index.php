<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="page-hero">
    <div class="wrap">
        <div class="pill hf"><i></i>Official First-Party Customer Portal</div>
        <h1 class="page-title" style="margin-top:14px;"><span class="rot grad">In-House Script Support</span> & Deployment Desk</h1>
        <p class="page-lead">Direct technical support for buyers of our white-label software scripts on Gumroad. Whether you need aaPanel Nginx rewrites, database configuration, or custom modules, our engineering team assists you directly.</p>
    </div>
</section>

<section class="section" style="padding-top:20px;">
    <div class="wrap">
        <?php if (!empty($isSuccess) && !empty($ticketSuccess)): ?>
            <div style="background:rgba(16,185,129,0.12); border:1px solid rgba(16,185,129,0.4); border-radius:14px; padding:28px 32px; margin-bottom:40px; box-shadow:0 12px 30px rgba(16,185,129,0.15);">
                <div style="display:flex; align-items:center; gap:16px; margin-bottom:12px;">
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:50%; background:#10b981; color:#fff;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </span>
                    <div>
                        <h3 style="margin:0; font-size:22px; color:var(--text); font-weight:800;">Support Ticket Opened: <?= e($ticketSuccess) ?></h3>
                        <p style="margin:4px 0 0; color:var(--muted); font-size:14px;">Your request has been routed directly to our lead engineering team in Karachi.</p>
                    </div>
                </div>
                <p style="color:var(--text); font-size:15px; line-height:1.6; margin:0 0 16px;">
                    We have logged your server environment and error diagnostics. A senior software architect will follow up with you via <strong>email</strong> or <strong>WhatsApp</strong> within <strong>2 to 6 hours</strong>.
                </p>
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', setting('founder_whatsapp', '923000000000')) ?>?text=<?= urlencode('Hello Creatsify! I just submitted support ticket #' . $ticketSuccess . ' for my script.') ?>" target="_blank" rel="noopener" class="btn" style="background:#25D366; color:#fff; border-color:#25D366; font-weight:700;">
                        Open on WhatsApp &rarr;
                    </a>
                    <a href="<?= url('/portfolio') ?>" class="btn">Explore Other Scripts</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($msg = flash('success')): ?>
            <div class="alert alert-success" style="margin-bottom:24px; padding:16px 20px; border-radius:10px; background:rgba(16,185,129,0.15); border:1px solid #10b981; color:var(--text);"><?= e($msg) ?></div>
        <?php endif; ?>

        <?php if ($err = flash('error')): ?>
            <div class="alert alert-danger" style="margin-bottom:24px; padding:16px 20px; border-radius:10px; background:rgba(239,68,68,0.15); border:1px solid #ef4444; color:#ef4444;"><?= e($err) ?></div>
        <?php endif; ?>

        <div style="display:grid; grid-template-columns:1fr; gap:40px; align-items:start;" class="support-layout-grid">
            <!-- Left Form Column -->
            <div style="background:var(--surface); border:1px solid var(--line); border-radius:16px; padding:36px; box-shadow:0 12px 35px rgba(0,0,0,0.15);">
                <div style="border-bottom:1px solid var(--line); padding-bottom:18px; margin-bottom:24px;">
                    <span style="display:inline-block; font-size:11px; text-transform:uppercase; letter-spacing:1px; font-weight:800; color:var(--accent);">Dedicated Customer Desk</span>
                    <h2 style="margin:6px 0 0; font-size:22px; color:var(--text); font-weight:800;">Submit Script Support Ticket</h2>
                    <p style="margin:4px 0 0; color:var(--muted); font-size:14px;">Fill out your license and server details for rapid diagnostics and patch delivery.</p>
                </div>

                <form action="<?= url('/support') ?>" method="POST" id="scriptSupportForm">
                    <?= csrf_field() ?>
                    <input type="text" name="_hp_check" value="" style="display:none!important;" tabindex="-1" autocomplete="off" aria-hidden="true">

                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:18px; margin-bottom:18px;">
                        <div class="form-group">
                            <label for="sp_name" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:6px;">Your Full Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" id="sp_name" name="name" class="form-control" placeholder="Marcus Keller" required style="width:100%; padding:11px 14px; border-radius:8px; border:1px solid var(--line); background:var(--bg); color:var(--text); font-size:14px;">
                        </div>

                        <div class="form-group">
                            <label for="sp_email" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:6px;">Your Gumroad Account Email <span style="color:#ef4444;">*</span></label>
                            <input type="email" id="sp_email" name="email" class="form-control" placeholder="marcus@example.com" required style="width:100%; padding:11px 14px; border-radius:8px; border:1px solid var(--line); background:var(--bg); color:var(--text); font-size:14px;">
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:18px; margin-bottom:18px;">
                        <div class="form-group">
                            <label for="sp_whatsapp" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:6px;">WhatsApp / Phone Number</label>
                            <input type="text" id="sp_whatsapp" name="whatsapp" class="form-control" placeholder="+1 (555) 123-4567 or +92 300 0000000" style="width:100%; padding:11px 14px; border-radius:8px; border:1px solid var(--line); background:var(--bg); color:var(--text); font-size:14px;">
                            <span style="font-size:11px; color:var(--muted); display:block; margin-top:3px;">Used for instant WhatsApp technical updates if needed.</span>
                        </div>

                        <div class="form-group">
                            <label for="sp_order" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:6px;">Gumroad Order ID / License Key <span style="color:#ef4444;">*</span></label>
                            <input type="text" id="sp_order" name="order_id" class="form-control" placeholder="e.g. Q98Z-7KL2 or Receipt #12345" required style="width:100%; padding:11px 14px; border-radius:8px; border:1px solid var(--line); background:var(--bg); color:var(--text); font-size:14px;">
                            <span style="font-size:11px; color:var(--muted); display:block; margin-top:3px;">Found in your Gumroad purchase confirmation receipt.</span>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:18px; margin-bottom:18px;">
                        <div class="form-group">
                            <label for="sp_script" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:6px;">Select Purchased Script <span style="color:#ef4444;">*</span></label>
                            <select id="sp_script" name="script_name" class="form-control" required style="width:100%; padding:11px 14px; border-radius:8px; border:1px solid var(--line); background:var(--bg); color:var(--text); font-size:14px;">
                                <option value="Cheetah Enterprise Logistics & Courier Suite" <?= stripos($selectedScript, 'cheetah') !== false ? 'selected' : '' ?>>Cheetah Enterprise Logistics & Courier Suite (PHP 8.2/8.4)</option>
                                <option value="Cheetah Driver & Picker App (Flutter)" <?= stripos($selectedScript, 'driver') !== false ? 'selected' : '' ?>>Cheetah Driver & Picker Mobile App (Flutter / Dart)</option>
                                <option value="RestroMaster POS & Kitchen Display" <?= stripos($selectedScript, 'restro') !== false ? 'selected' : '' ?>>RestroMaster POS, Kitchen Display & QR Ordering</option>
                                <option value="ViddMate Converter & Desktop Media Suite" <?= stripos($selectedScript, 'vidd') !== false ? 'selected' : '' ?>>ViddMate Converter & Desktop Media Suite</option>
                                <option value="SaaSForge Multi-Tenant SaaS Starter" <?= stripos($selectedScript, 'forge') !== false ? 'selected' : '' ?>>SaaSForge Multi-Tenant Starter Kit</option>
                                <?php foreach ($projects as $prj): ?>
                                    <?php if (!in_array($prj['title'], ['Cheetah Enterprise Logistics & Courier Suite', 'RestroMaster POS & Kitchen Display'])): ?>
                                        <option value="<?= e($prj['title']) ?>" <?= $selectedScript === $prj['title'] ? 'selected' : '' ?>><?= e($prj['title']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <option value="Other White-Label Script">Other White-Label Script</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sp_server" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:6px;">Hosting / Server Environment <span style="color:#ef4444;">*</span></label>
                            <select id="sp_server" name="server_environment" class="form-control" required style="width:100%; padding:11px 14px; border-radius:8px; border:1px solid var(--line); background:var(--bg); color:var(--text); font-size:14px;">
                                <option value="aaPanel + Nginx (PHP 8.2 / 8.4)">aaPanel + Nginx (PHP 8.2 / 8.4)</option>
                                <option value="cPanel + Apache (PHP 8.2 / 8.4)">cPanel + Apache (PHP 8.2 / 8.4)</option>
                                <option value="Ubuntu / Debian VPS (Nginx + PHP-FPM)">Ubuntu / Debian VPS (Nginx + PHP-FPM)</option>
                                <option value="Cloudflare Pages / Docker Engine">Cloudflare / Docker Engine</option>
                                <option value="Local Development (XAMPP / Laragon)">Local Development (XAMPP / Laragon)</option>
                                <option value="Shared Web Hosting (Other)">Shared Web Hosting (Other)</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:18px; margin-bottom:18px;">
                        <div class="form-group">
                            <label for="sp_domain" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:6px;">Installation Domain / URL</label>
                            <input type="text" id="sp_domain" name="domain_url" class="form-control" placeholder="https://app.yourdomain.com" style="width:100%; padding:11px 14px; border-radius:8px; border:1px solid var(--line); background:var(--bg); color:var(--text); font-size:14px;">
                        </div>

                        <div class="form-group">
                            <label for="sp_category" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:6px;">Issue Category <span style="color:#ef4444;">*</span></label>
                            <select id="sp_category" name="issue_category" class="form-control" required style="width:100%; padding:11px 14px; border-radius:8px; border:1px solid var(--line); background:var(--bg); color:var(--text); font-size:14px;">
                                <option value="aaPanel Nginx Rewrite & 404 Routing">aaPanel Nginx Rewrite & 404 Routing</option>
                                <option value="Initial Database & Migration Setup">Initial Database & Migration Setup</option>
                                <option value="PHP Extension (PDO, GD, cURL, Fileinfo)">PHP Extension (PDO, GD, cURL, Fileinfo)</option>
                                <option value="Flutter Mobile App Build & API Connection">Flutter Mobile App Build & API Connection</option>
                                <option value="Email SMTP / WhatsApp Cloud API Setup">Email SMTP / WhatsApp Cloud API Setup</option>
                                <option value="Custom Module or Branding Request">Custom Module or Branding Request</option>
                                <option value="Other Technical Bug">Other Technical Bug</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:18px; margin-bottom:18px;">
                        <div class="form-group">
                            <label for="sp_priority" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:6px;">Priority Level <span style="color:#ef4444;">*</span></label>
                            <select id="sp_priority" name="priority" class="form-control" required style="width:100%; padding:11px 14px; border-radius:8px; border:1px solid var(--line); background:var(--bg); color:var(--text); font-size:14px;">
                                <option value="low">Low &mdash; General Inquiry or Code Clarification</option>
                                <option value="medium" selected>Medium &mdash; Routine Installation Assistance</option>
                                <option value="high">High &mdash; Live Functionality or Routing Blocked</option>
                                <option value="urgent">Urgent &mdash; Production Outage / Critical Failure</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sp_subject" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:6px;">Ticket Subject <span style="color:#ef4444;">*</span></label>
                            <input type="text" id="sp_subject" name="subject" class="form-control" placeholder="e.g. 404 on Sub-routes in aaPanel Nginx" required style="width:100%; padding:11px 14px; border-radius:8px; border:1px solid var(--line); background:var(--bg); color:var(--text); font-size:14px;">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:24px;">
                        <label for="sp_msg" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:6px;">Error Logs & Problem Description <span style="color:#ef4444;">*</span></label>
                        <textarea id="sp_msg" name="message" rows="6" class="form-control" placeholder="Please paste your exact server error message, Nginx/PHP error log excerpt, or describe the steps that reproduce the issue..." required style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--line); background:var(--bg); color:var(--text); font-size:14px; font-family:monospace; line-height:1.6;"></textarea>
                    </div>

                    <button type="submit" class="btn p btn-block" style="width:100%; padding:14px 24px; font-size:15px; font-weight:800; cursor:pointer;">
                        Submit Support Ticket &rarr;
                    </button>
                </form>
            </div>

            <!-- Right Column: Quick Self-Service & Direct Hotlines -->
            <div style="display:flex; flex-direction:column; gap:24px;">
                <!-- Card 1: aaPanel Quick Fix Cheat Sheet -->
                <div style="background:var(--surface); border:1px solid var(--line); border-radius:16px; padding:28px; box-shadow:0 8px 24px rgba(0,0,0,0.12);">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                        <span style="display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:8px; background:rgba(99,102,241,0.15); color:var(--accent);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        </span>
                        <h3 style="margin:0; font-size:17px; color:var(--text); font-weight:800;">aaPanel Nginx Quick Fix</h3>
                    </div>
                    <p style="font-size:13px; color:var(--muted); line-height:1.6; margin-bottom:14px;">
                        Getting a 404 error when navigating internal routes on aaPanel? You only need the front controller rewrite rule in your site's <strong>URL rewrite</strong> box:
                    </p>
                    <div style="background:#0d1117; color:#58a6ff; border-radius:8px; padding:14px; font-family:monospace; font-size:12px; line-height:1.5; overflow-x:auto; margin-bottom:14px; border:1px solid #30363d;">
<span style="color:#ff7b72;">location</span> / {<br>
&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#79c0ff;">try_files</span> <span style="color:#a5d6ff;">$uri $uri/</span> /index.php?<span style="color:#a5d6ff;">$query_string</span>;<br>
}
                    </div>
                    <p style="font-size:12px; color:var(--muted); line-height:1.5; margin:0;">
                        <em>Tip:</em> Do NOT paste outer <code style="color:var(--accent);">server { ... }</code> or <code style="color:var(--accent);">gzip</code> directives inside aaPanel's URL rewrite box, as aaPanel already includes them globally.
                    </p>
                </div>

                <!-- Card 2: WhatsApp Direct Line -->
                <div style="background:var(--surface); border:1px solid var(--line); border-radius:16px; padding:28px; box-shadow:0 8px 24px rgba(0,0,0,0.12);">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                        <span style="display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:8px; background:rgba(37,211,102,0.15); color:#25D366;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </span>
                        <h3 style="margin:0; font-size:17px; color:var(--text); font-weight:800;">Direct WhatsApp Hotline</h3>
                    </div>
                    <p style="font-size:13px; color:var(--muted); line-height:1.6; margin-bottom:16px;">
                        Need urgent advice during a live deployment? Send our founder a direct WhatsApp message with your Gumroad order receipt.
                    </p>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', setting('founder_whatsapp', '923000000000')) ?>?text=<?= urlencode('Hello Creatsify! I need technical support with my script purchase.') ?>" target="_blank" rel="noopener" class="btn btn-block" style="background:#25D366; color:#fff; border-color:#25D366; display:flex; align-items:center; justify-content:center; gap:8px; font-weight:700;">
                        <span>Message on WhatsApp</span> &rarr;
                    </a>
                </div>

                <!-- Card 3: Our In-House Engineering Guarantee -->
                <div style="background:var(--surface); border:1px solid var(--line); border-radius:16px; padding:28px; box-shadow:0 8px 24px rgba(0,0,0,0.12);">
                    <h3 style="margin:0 0 12px; font-size:17px; color:var(--text); font-weight:800;">Our Support Guarantee</h3>
                    <ul style="padding-left:18px; margin:0; display:flex; flex-direction:column; gap:10px; font-size:13px; color:var(--muted); line-height:1.6;">
                        <li><strong style="color:var(--text);">Direct Authors:</strong> You deal directly with the engineers who created the codebase.</li>
                        <li><strong style="color:var(--text);">Full Source Transparency:</strong> Clean PHP 8.2+ MVC, zero obfuscation, zero hidden backdoors.</li>
                        <li><strong style="color:var(--text);">Free Bug Patches:</strong> Core architectural fixes delivered free of charge.</li>
                        <li><strong style="color:var(--text);">Custom Modules:</strong> Need specialized payment gateways or rider workflows? We can build them on custom milestones.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@media (min-width: 900px) {
    .support-layout-grid {
        grid-template-columns: 1.6fr 1.1fr !important;
    }
}
</style>
