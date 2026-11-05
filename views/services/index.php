<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>

<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Agency Capabilities</div>
        <h1 class="page-title">Engineering &amp; Agency Services<span class="om-dot-gold">.</span></h1>
        <p class="page-lead">Besides our commercial white-label software, our co-founders engineer bespoke platforms, high-speed custom admin panels, online stores, and real-time APIs tailored to your team's exact workflows.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2 class="sr-only">Our Services</h2>
        <div class="svc-grid">
            <?php foreach ($services as $i => $service): ?>
                <article class="svc-card rv" style="--d:<?= $i * 0.1 ?>s">
                    <div class="svc-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    </div>
                    <h3><?= e($service['title']) ?></h3>
                    <p><?= e($service['full_description'] ?: $service['short_description']) ?></p>

                    <?php if (!empty($service['deliverables'])): ?>
                        <div class="svc-list">
                            <strong>Deliverables Included</strong>
                            <ul>
                                <?php foreach (explode(',', $service['deliverables']) as $deliv): ?>
                                    <li><?= e(trim($deliv)) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <a href="<?= url('/contact?kind=custom_service&offering=' . urlencode($service['title'])) . '#inquiry-form' ?>" class="om-btn-gold sm" style="justify-content:center;margin-top:auto;">
                        <span>Inquire About This</span>
                        <span class="om-btn-circle-arrow">&rarr;</span>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- 3-Step Process (Omoway Rhythm) -->
        <div style="margin-top:72px;">
            <div class="om-sec-center">
                <div class="om-sec-tag"><span class="om-sec-dot"></span> How We Work</div>
                <h2 class="om-sec-heading">From Initial Scope To Production Launch<span class="om-dot-gold">.</span></h2>
                <p class="om-sec-sub">Direct engagement with our three co-founders with zero agency bureaucracy.</p>
            </div>

            <div class="om-how-grid" style="margin-top:40px;">
                <div class="om-how-card rv" style="--d:0.08s">
                    <span class="om-how-step-num">01</span>
                    <h3>Tell Us What You Need</h3>
                    <p>A short message is enough. Share feature priorities, existing workflows, tech requirements, and target launch timelines.</p>
                </div>
                <div class="om-how-card rv" style="--d:0.16s">
                    <span class="om-how-step-num">02</span>
                    <h3>Architecture &amp; Fixed Quote</h3>
                    <p>We review the requirements and provide an exact technical scope, fixed quote, and realistic delivery schedule with no surprises.</p>
                </div>
                <div class="om-how-card rv" style="--d:0.24s">
                    <span class="om-how-step-num">03</span>
                    <h3>We Build, You Review</h3>
                    <p>We develop on live staging environments with weekly demos. Once approved, we deploy to your aaPanel / Linux server and provide hands-on onboarding.</p>
                </div>
            </div>
        </div>
    </div>
</section>
