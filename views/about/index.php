<?php
/** @var Core\View $this */
$this->layout('layouts/main');
$avgRating    = number_format((float)($reviewStats['avg_rating'] ?? 4.9), 1);
$totalReviews = (int)($reviewStats['total'] ?? 48);
?>

<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Full-Spectrum Digital Agency</div>
        <h1 class="page-title">About Creatsify Digital Agency</h1>
        <p class="page-lead">
            Creatsify is a full-service software engineering, brand design, and digital growth agency. We help ambitious businesses build high-speed web platforms, cross-platform Flutter mobile apps, distinctive brand identities, and high-ROAS search &amp; social acquisition engines under one roof.
        </p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="om-case-grid">
            <!-- Left: Agency Philosophy & Engineering Standards -->
            <div class="om-bento-card">
                <div class="om-sec-tag"><span class="om-sec-dot"></span> Our Agency DNA</div>
                <h2 style="font-family:Sora,sans-serif;font-size:24px;font-weight:800;margin:12px 0 16px;color:var(--text);">Engineering + Brand Craft + Growth Marketing In One Unified Powerhouse</h2>
                <p style="font-size:15px;line-height:1.75;color:var(--muted);margin-bottom:20px;">
                    Most businesses struggle because their web developer, graphic designer, and marketing agency work in isolated silos. At <?= e(setting('site_name', 'Creatsify')) ?>, our software engineers, UI/UX &amp; brand designers, and SEO/paid-ads strategists collaborate on your project from day one—ensuring every line of code, every logo mark, and every ad campaign is engineered to convert visitors into revenue.
                </p>

                <h3 style="font-family:Sora,sans-serif;font-size:18px;font-weight:800;margin:28px 0 14px;color:var(--text);">Our Non-Negotiable Standards</h3>
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div style="padding:16px;border-radius:14px;background:color-mix(in srgb,var(--surface) 60%,var(--bg));border:1px solid var(--line);">
                        <strong style="color:var(--text);font-size:14px;display:block;margin-bottom:4px;">&#10003; Modern Multi-Stack Engineering</strong>
                        <span style="color:var(--muted);font-size:13px;line-height:1.5;">From PHP 8.4, Node.js, and Next.js web platforms with MySQL databases to 60fps cross-platform Flutter &amp; Dart mobile apps for iOS and Android.</span>
                    </div>
                    <div style="padding:16px;border-radius:14px;background:color-mix(in srgb,var(--surface) 60%,var(--bg));border:1px solid var(--line);">
                        <strong style="color:var(--text);font-size:14px;display:block;margin-bottom:4px;">&#10003; Distinctive Brand Identity &amp; High-Craft UI/UX</strong>
                        <span style="color:var(--muted);font-size:13px;line-height:1.5;">Custom 3D &amp; vector logos, dark/light mode design systems, website redesigns, and scroll-stopping social media ad creatives that set your brand apart.</span>
                    </div>
                    <div style="padding:16px;border-radius:14px;background:color-mix(in srgb,var(--surface) 60%,var(--bg));border:1px solid var(--line);">
                        <strong style="color:var(--text);font-size:14px;display:block;margin-bottom:4px;">&#10003; Measurable SEO &amp; Paid Social ROAS</strong>
                        <span style="color:var(--muted);font-size:13px;line-height:1.5;">Technical SEO schema engineering paired with data-backed Meta (Facebook/Instagram), Google PPC, and TikTok ad campaigns tracked to real leads.</span>
                    </div>
                    <div style="padding:16px;border-radius:14px;background:color-mix(in srgb,var(--surface) 60%,var(--bg));border:1px solid var(--line);">
                        <strong style="color:var(--text);font-size:14px;display:block;margin-bottom:4px;">&#10003; 100% Ownership &amp; Ready-Made Acceleration</strong>
                        <span style="color:var(--muted);font-size:13px;line-height:1.5;">Whether we build your platform from scratch or customize one of our ready-made software systems, you receive 100% unlocked source code and full IP rights.</span>
                    </div>
                </div>
            </div>

            <!-- Right: 4 Specialist Agency Divisions -->
            <aside class="om-case-aside">
                <div class="om-bento-card">
                    <div class="om-sec-tag"><span class="om-sec-dot"></span> 4 Integrated Divisions</div>
                    <h3 style="font-family:Sora,sans-serif;font-size:18px;font-weight:800;margin:12px 0 6px;color:var(--text);">Our Specialist Practice Areas</h3>
                    <p style="font-size:13px;color:var(--muted);line-height:1.5;margin-bottom:18px;">
                        Senior-led execution across code, mobile apps, visual identity, and performance marketing with zero agency bloat.
                    </p>

                    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:24px;">
                        <div style="padding:14px;background:color-mix(in srgb,var(--surface) 70%,var(--bg));border-radius:12px;border:1px solid var(--line);display:flex;align-items:center;gap:12px;">
                            <span style="width:28px;height:28px;border-radius:50%;background:var(--accent);color:var(--on-accent,#ffffff);display:grid;place-items:center;font-size:11px;font-weight:800;flex-shrink:0;">01</span>
                            <div>
                                <strong style="color:var(--text);font-size:13.5px;display:block;">Web &amp; SaaS Engineering Division</strong>
                                <span style="color:var(--accent);font-size:12px;font-weight:600;">PHP &bull; Node.js &bull; Next.js &bull; Tailwind &bull; MySQL</span>
                            </div>
                        </div>

                        <div style="padding:14px;background:color-mix(in srgb,var(--surface) 70%,var(--bg));border-radius:12px;border:1px solid var(--line);display:flex;align-items:center;gap:12px;">
                            <span style="width:28px;height:28px;border-radius:50%;background:var(--accent);color:var(--on-accent,#ffffff);display:grid;place-items:center;font-size:11px;font-weight:800;flex-shrink:0;">02</span>
                            <div>
                                <strong style="color:var(--text);font-size:13.5px;display:block;">Mobile App Engineering Division</strong>
                                <span style="color:var(--accent);font-size:12px;font-weight:600;">Flutter &amp; Dart &bull; iOS &amp; Android Native Speed</span>
                            </div>
                        </div>

                        <div style="padding:14px;background:color-mix(in srgb,var(--surface) 70%,var(--bg));border-radius:12px;border:1px solid var(--line);display:flex;align-items:center;gap:12px;">
                            <span style="width:28px;height:28px;border-radius:50%;background:var(--accent);color:var(--on-accent,#ffffff);display:grid;place-items:center;font-size:11px;font-weight:800;flex-shrink:0;">03</span>
                            <div>
                                <strong style="color:var(--text);font-size:13.5px;display:block;">Brand, Logo &amp; UI/UX Redesign Studio</strong>
                                <span style="color:var(--accent);font-size:12px;font-weight:600;">Logo Design &bull; Brand Kits &bull; CRO Redesigns</span>
                            </div>
                        </div>

                        <div style="padding:14px;background:color-mix(in srgb,var(--surface) 70%,var(--bg));border-radius:12px;border:1px solid var(--line);display:flex;align-items:center;gap:12px;">
                            <span style="width:28px;height:28px;border-radius:50%;background:var(--accent);color:var(--on-accent,#ffffff);display:grid;place-items:center;font-size:11px;font-weight:800;flex-shrink:0;">04</span>
                            <div>
                                <strong style="color:var(--text);font-size:13.5px;display:block;">SEO &amp; Paid Social Growth Desk</strong>
                                <span style="color:var(--accent);font-size:12px;font-weight:600;">Technical SEO &bull; Meta/IG Ads &bull; Google PPC</span>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <a href="<?= url('/packages') ?>" class="om-btn-gold" style="width:100%;justify-content:center;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                            <span>Explore Service Packages</span>
                        </a>
                        <a href="<?= url('/reviews') ?>" class="om-btn-glass" style="width:100%;justify-content:center;">
                            <span>&#9733; <?= e($avgRating) ?>/5 Client Reviews (<?= $totalReviews ?>+)</span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
