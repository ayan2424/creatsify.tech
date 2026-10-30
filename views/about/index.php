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
                    <?= e(setting('site_name', 'Creatsify')) ?> was founded by <strong><?= e(setting('founder_name', 'Muhammad Ayan Shamim')) ?></strong>.
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
                    <img src="<?= asset(setting('founder_avatar', 'brand/creatsify-icon.png')) ?>" alt="<?= e(setting('founder_name', 'Founder')) ?>" width="96" height="96">
                    <h3><?= e(setting('founder_name', 'Muhammad Ayan Shamim')) ?></h3>
                    <p class="pg-role"><?= e(setting('founder_title', 'Founder & Lead Architect')) ?></p>
                    <p class="pg-bio"><?= e(setting('founder_bio', 'Builds courier software, online stores and custom admin panels in PHP and MySQL.')) ?></p>
                    <div class="pg-links">
                        <a href="<?= e(setting('founder_github', 'https://github.com/ayan2424')) ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-block">GitHub profile &rarr;</a>
                        <a href="<?= e(setting('founder_linkedin', 'https://linkedin.com/in/muhammadayanshamim')) ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-block">LinkedIn &rarr;</a>
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
