<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>
<section class="page-hero">
    <div class="wrap">
        <span class="section-tag">About</span>
        <h1 class="page-title">About Creatsify</h1>
        <p class="page-lead">Creatsify is a small software studio. We build and sell ready-made courier and online store software, and we build custom websites and admin panels for businesses.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="about-page-grid">
            <div class="pg-card pg-prose">
                <h2>Built to be understood, hosted and owned</h2>
                <p>
                    <?= e(setting('site_name', 'Creatsify')) ?> is an independent software studio founded by three co-founders: <strong>Muhammad Ayan Shamim</strong> (Lead Full-Stack Architect), <strong>Syeda Sukaina Batool</strong> (UI/UX Product Design Lead), and <strong>Abdul Moiz</strong> (Technical Operations Lead).
                    We write software in plain PHP and MySQL with a clean MVC structure, so it stays fast, easy to read and easy to host almost anywhere.
                </p>

                <h3>How we work</h3>
                <ul>
                    <li><strong>No framework bloat.</strong> A small, tidy codebase instead of megabytes of code you never use.</li>
                    <li><strong>Proven by use.</strong> Cheetah and Cheezify both have live demos you can click through before you decide.</li>
                    <li><strong>Messaging built in.</strong> Live chat, WhatsApp Cloud API and SMS alerts are part of the software, not add-ons.</li>
                </ul>

                <h3>Who we work with</h3>
                <p>
                    Our software is licensed directly, with full source code and white-label rights. We also work one-on-one with businesses across North America, the UK and the Middle East on custom projects.
                </p>
            </div>

            <div>
                <div class="pg-card pg-founder">
                    <img src="<?= asset('brand/creatsify-icon.png') ?>" alt="Creatsify Co-Founders" width="80" height="80" style="border-radius:12px;margin:0 auto 16px;display:block;">
                    <h3 style="text-align:center;">Co-Founders & Leadership</h3>
                    <p class="pg-role" style="text-align:center;">The Engineering & Design Team</p>
                    
                    <div style="display:flex;flex-direction:column;gap:12px;margin:18px 0;text-align:left;">
                        <div style="padding:10px 14px;background:var(--bg);border-radius:8px;border:1px solid var(--line);">
                            <strong style="color:var(--text);font-size:13.5px;display:block;">Muhammad Ayan Shamim</strong>
                            <span style="color:var(--accent);font-size:12px;font-weight:600;">Co-Founder & Lead Full-Stack Architect</span>
                        </div>
                        <div style="padding:10px 14px;background:var(--bg);border-radius:8px;border:1px solid var(--line);">
                            <strong style="color:var(--text);font-size:13.5px;display:block;">Syeda Sukaina Batool</strong>
                            <span style="color:var(--accent);font-size:12px;font-weight:600;">Co-Founder & UI/UX Product Design Lead</span>
                        </div>
                        <div style="padding:10px 14px;background:var(--bg);border-radius:8px;border:1px solid var(--line);">
                            <strong style="color:var(--text);font-size:13.5px;display:block;">Abdul Moiz</strong>
                            <span style="color:var(--accent);font-size:12px;font-weight:600;">Co-Founder & Technical Operations Lead</span>
                        </div>
                    </div>

                    <p class="pg-bio" style="font-size:13px;line-height:1.5;margin-bottom:18px;">
                        Together, we design, build, and support all commercial software and client platforms shipped by Creatsify.
                    </p>
                    <div class="pg-links">
                        <a href="<?= url('/team') ?>" class="btn btn-secondary btn-block" style="display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                            <span>Meet the 3 Co-Founders</span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="pg-cta">
            <div>
                <h3>See it in action, or tell us what you need</h3>
                <p>Open a live demo, or send a short message. We reply with a plan and a price.</p>
            </div>
            <div class="pg-cta-btns">
                <a class="btn p" href="<?= url('/portfolio') ?>">Browse the software</a>
                <a class="btn" href="<?= url('/contact') ?>">Start a project</a>
            </div>
        </div>
    </div>
</section>
