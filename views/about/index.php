<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="page-hero">
    <div class="wrap">
        <span class="section-tag">Studio & Founder</span>
        <h1 class="page-title">About Creatsify</h1>
        <p class="page-lead">We are an independent software development studio in Karachi, Pakistan, dedicated to engineering robust, high-performance web systems, SaaS platforms, and mobile apps.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="about-page-grid">
            <div style="background: var(--surface); padding: 45px; border-radius: 14px; border: 1px solid var(--line); font-size: 16px; line-height: 1.8; color: var(--muted);">
                <h2 style="color: var(--text); font-size: 26px; margin-top: 0;">Built by Engineers, Designed for Scale</h2>
                <p>
                    <?= e(setting('site_name', 'Creatsify')) ?> was founded by <strong><?= e(setting('founder_name', 'Muhammad Ayan Shamim')) ?></strong>, a software engineer specializing in scalable application architectures. Frustrated by the sluggish performance and vulnerability overhead of heavy off-the-shelf platforms, we build software grounded in clean architecture, normalized relational schemas, and direct execution speed.
                </p>

                <h3 style="color: var(--text); font-size: 20px; margin-top: 35px;">Our Philosophy</h3>
                <ul style="padding-left: 20px; display: flex; flex-direction: column; gap: 12px;">
                    <li><strong style="color: var(--text);">Zero Bloat:</strong> We favor tailored, highly optimized native MVC cores over megabytes of unused framework boilerplate.</li>
                    <li><strong style="color: var(--text);">Production Proven:</strong> From our 78-table Cheetah courier suite to white-label restaurant ordering engines, everything we ship runs in real commercial operations.</li>
                    <li><strong style="color: var(--text);">Omnichannel Communication:</strong> We integrate first-party live chat engines, WhatsApp Cloud API v21.0, and SMS gateways directly into software systems.</li>
                </ul>

                <h3 style="color: var(--text); font-size: 20px; margin-top: 35px;">Global Reach</h3>
                <p>
                    Our white-label software products are licensed directly through our official Gumroad store with 100% source code ownership and direct in-house technical support. We also partner one-on-one with enterprise clients across North America, the UK, the Middle East, and Pakistan.
                </p>
            </div>

            <div>
                <div style="background: var(--surface); padding: 35px; border-radius: 14px; border: 1px solid var(--line); text-align: center;">
                    <img src="<?= asset(setting('founder_avatar', 'brand/creatsify-icon.png')) ?>" alt="<?= e(setting('founder_name', 'Founder')) ?>" width="96" height="96" style="border-radius: 50%; border: 2px solid var(--accent); margin-bottom: 20px;">
                    <h3 style="margin: 0; color: var(--text); font-size: 20px;"><?= e(setting('founder_name', 'Muhammad Ayan Shamim')) ?></h3>
                    <p style="color: var(--accent); font-size: 14px; margin: 5px 0 20px;"><?= e(setting('founder_title', 'Founder & Lead Architect')) ?></p>
                    <p style="color: var(--muted); font-size: 14px; line-height: 1.6; text-align: left;">
                        <?= e(setting('founder_bio', 'Specializes in custom PHP MVC cores, high-performance web systems, Flutter mobile applications, and relational database architecture.')) ?>
                    </p>

                    <div style="margin-top: 25px; display: flex; flex-direction: column; gap: 10px;">
                        <a href="<?= e(setting('founder_github', 'https://github.com/ayan2424')) ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-block">GitHub Profile &rarr;</a>
                        <a href="<?= e(setting('founder_linkedin', 'https://linkedin.com/in/muhammadayanshamim')) ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-block">LinkedIn &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
