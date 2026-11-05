<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>

<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Studio Identity</div>
        <h1 class="page-title">About Creatsify Studio<span class="om-dot-gold">.</span></h1>
        <p class="page-lead">We are an independent software studio founded and led by three co-founders. We architect, build, and sell commercial courier and store software, plus build custom high-velocity web platforms for businesses worldwide.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="om-case-grid">
            <!-- Left: Studio Core Philosophy -->
            <div class="om-bento-card">
                <div class="om-sec-tag"><span class="om-sec-dot"></span> Engineering Truth</div>
                <h2 style="font-family:Sora,sans-serif;font-size:24px;font-weight:800;margin:12px 0 16px;color:var(--text);">Built To Be Understood, Hosted &amp; Owned</h2>
                <p style="font-size:15px;line-height:1.75;color:var(--muted);margin-bottom:20px;">
                    <?= e(setting('site_name', 'Creatsify')) ?> was founded on a simple principle: software should be fast, transparent, and completely owned by the buyer. We build in pure PHP 8.4 and MySQL with a clean MVC architecture—meaning zero third-party bloated framework dependencies, ultra-low server RAM footprints, and effortless deployment on standard aaPanel or Linux VPS servers.
                </p>

                <h3 style="font-family:Sora,sans-serif;font-size:18px;font-weight:800;margin:28px 0 14px;color:var(--text);">How We Build Differently</h3>
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div style="padding:16px;border-radius:14px;background:color-mix(in srgb,var(--surface) 60%,var(--bg));border:1px solid var(--line);">
                        <strong style="color:var(--text);font-size:14px;display:block;margin-bottom:4px;">&#10003; Zero Framework Bloat</strong>
                        <span style="color:var(--muted);font-size:13px;line-height:1.5;">Clean, readable code with strict types instead of megabytes of unused vendor dependencies.</span>
                    </div>
                    <div style="padding:16px;border-radius:14px;background:color-mix(in srgb,var(--surface) 60%,var(--bg));border:1px solid var(--line);">
                        <strong style="color:var(--text);font-size:14px;display:block;margin-bottom:4px;">&#10003; 100% Unlocked Source Code</strong>
                        <span style="color:var(--muted);font-size:13px;line-height:1.5;">When you license software from us, you get every single file, database migration, and asset with full commercial white-label rights.</span>
                    </div>
                    <div style="padding:16px;border-radius:14px;background:color-mix(in srgb,var(--surface) 60%,var(--bg));border:1px solid var(--line);">
                        <strong style="color:var(--text);font-size:14px;display:block;margin-bottom:4px;">&#10003; Native Real-Time Integrations</strong>
                        <span style="color:var(--muted);font-size:13px;line-height:1.5;">Official Meta WhatsApp Cloud API webhooks, Leaflet GPS maps, and real-time live chat built directly into the core engine.</span>
                    </div>
                </div>
            </div>

            <!-- Right: Studio Leadership & Co-Founders Box -->
            <aside class="om-case-aside">
                <div class="om-bento-card">
                    <div class="om-sec-tag"><span class="om-sec-dot"></span> Studio Leadership</div>
                    <h3 style="font-family:Sora,sans-serif;font-size:18px;font-weight:800;margin:12px 0 6px;color:var(--text);">Direct Co-Founder Architecture</h3>
                    <p style="font-size:13px;color:var(--muted);line-height:1.5;margin-bottom:18px;">
                        No account managers, no junior delegators. You work directly with the three co-founders who engineer and design every platform.
                    </p>

                    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:24px;">
                        <div style="padding:14px;background:color-mix(in srgb,var(--surface) 70%,var(--bg));border-radius:12px;border:1px solid var(--line);display:flex;align-items:center;gap:12px;">
                            <span style="width:28px;height:28px;border-radius:50%;background:#0A1A33;color:#ffffff;display:grid;place-items:center;font-size:11px;font-weight:800;flex-shrink:0;">01</span>
                            <div>
                                <strong style="color:var(--text);font-size:13.5px;display:block;">Muhammad Ayan Shamim</strong>
                                <span style="color:var(--accent);font-size:12px;font-weight:600;">Co-Founder &bull; Lead Full-Stack Architect</span>
                            </div>
                        </div>

                        <div style="padding:14px;background:color-mix(in srgb,var(--surface) 70%,var(--bg));border-radius:12px;border:1px solid var(--line);display:flex;align-items:center;gap:12px;">
                            <span style="width:28px;height:28px;border-radius:50%;background:#0A1A33;color:#ffffff;display:grid;place-items:center;font-size:11px;font-weight:800;flex-shrink:0;">02</span>
                            <div>
                                <strong style="color:var(--text);font-size:13.5px;display:block;">Syeda Sukaina Batool</strong>
                                <span style="color:var(--accent);font-size:12px;font-weight:600;">Co-Founder &bull; UI/UX Product Design Lead</span>
                            </div>
                        </div>

                        <div style="padding:14px;background:color-mix(in srgb,var(--surface) 70%,var(--bg));border-radius:12px;border:1px solid var(--line);display:flex;align-items:center;gap:12px;">
                            <span style="width:28px;height:28px;border-radius:50%;background:#0A1A33;color:#ffffff;display:grid;place-items:center;font-size:11px;font-weight:800;flex-shrink:0;">03</span>
                            <div>
                                <strong style="color:var(--text);font-size:13.5px;display:block;">Abdul Moiz</strong>
                                <span style="color:var(--accent);font-size:12px;font-weight:600;">Co-Founder &bull; Technical Operations Lead</span>
                            </div>
                        </div>
                    </div>

                    <a href="<?= url('/team') ?>" class="om-btn-gold" style="width:100%;justify-content:center;">
                        <span>Meet All 3 Co-Founders</span>
                        <span class="om-btn-circle-arrow">&rarr;</span>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
