<?php
/** @var Core\View $this */
$this->layout('layouts/main');
?>

<section class="page-hero">
    <div class="wrap">
        <div class="om-sec-tag"><span class="om-sec-dot"></span> Full-Spectrum Agency Capabilities</div>
        <h1 class="page-title">Web, Mobile Apps, Branding, SEO &amp; Paid Ads<span class="om-dot-gold">.</span></h1>
        <p class="page-lead">
            From custom full-stack web applications (`PHP`, `Node.js`, `Next.js`, `Tailwind`, `MySQL`) and cross-platform `Flutter & Dart` mobile apps to conversion-focused website redesigns, brand identity systems, technical SEO, and high-ROAS social media ad campaigns.
        </p>
        <div style="margin-top:24px;display:flex;gap:12px;flex-wrap:wrap;">
            <a href="<?= url('/packages') ?>" class="om-btn-gold">
                <span>View Transparent Service Packages</span>
                <span class="om-btn-circle-arrow">&rarr;</span>
            </a>
            <a href="<?= url('/reviews') ?>" class="om-btn-glass">
                <span>Read Verified Client Reviews &#9733;</span>
            </a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2 class="sr-only">Our Services</h2>
        <div class="svc-grid">
            <?php foreach ($services as $i => $service): ?>
                <article class="svc-card rv" style="--d:<?= $i * 0.08 ?>s">
                    <div class="svc-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    </div>
                    <h3><?= e($service['title']) ?></h3>
                    <p><?= e($service['full_description'] ?: $service['short_description']) ?></p>

                    <?php if (!empty($service['deliverables'])): ?>
                        <div class="svc-list">
                            <strong>Deliverables &amp; Stack Included</strong>
                            <ul>
                                <?php foreach (explode(',', $service['deliverables']) as $deliv): ?>
                                    <li><?= e(trim($deliv)) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div style="display:flex;gap:10px;margin-top:auto;flex-wrap:wrap;">
                        <a href="<?= url('/contact?kind=custom_service&offering=' . urlencode($service['title'])) . '#inquiry-form' ?>" class="om-btn-gold sm" style="flex:1;justify-content:center;">
                            <span>Start Project</span>
                            <span class="om-btn-circle-arrow">&rarr;</span>
                        </a>
                        <a href="<?= url('/packages') ?>" class="om-btn-glass sm">
                            <span>Packages</span>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- 4-Step Agency Execution Process -->
        <div style="margin-top:72px;">
            <div class="om-sec-center">
                <div class="om-sec-tag"><span class="om-sec-dot"></span> How We Execute</div>
                <h2 class="om-sec-heading">From Initial Scope To Market Dominance<span class="om-dot-gold">.</span></h2>
                <p class="om-sec-sub">Senior engineering, creative design, and growth marketing aligned on fixed milestones with zero bureaucracy.</p>
            </div>

            <div class="ag-workflow-4grid" style="margin-top:40px;">
                <div class="om-how-card rv" style="--d:0.05s">
                    <span class="om-how-step-num">01</span>
                    <h3>Discovery &amp; Fixed Scope</h3>
                    <p>Share your goals, target audience, or existing website. We define clear technical and marketing KPIs with a fixed milestone roadmap.</p>
                </div>
                <div class="om-how-card rv" style="--d:0.12s">
                    <span class="om-how-step-num">02</span>
                    <h3>Brand &amp; High-Craft UI/UX</h3>
                    <p>Our creative studio crafts your logo, brand tokens, and conversion-engineered layouts in Light &amp; Dark mode.</p>
                </div>
                <div class="om-how-card rv" style="--d:0.18s">
                    <span class="om-how-step-num">03</span>
                    <h3>Full-Stack Web &amp; App Build</h3>
                    <p>We develop your custom web platform, Flutter mobile app, or online store on a live staging server with weekly interactive previews.</p>
                </div>
                <div class="om-how-card rv" style="--d:0.24s">
                    <span class="om-how-step-num">04</span>
                    <h3>Launch, SEO &amp; Paid Ad Scale</h3>
                    <p>We deploy to production, configure technical SEO schemas, and launch high-ROAS Meta, Instagram, and Google ad campaigns.</p>
                </div>
            </div>
        </div>
    </div>
</section>
