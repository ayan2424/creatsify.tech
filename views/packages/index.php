<?php
/** @var Core\View $this */
$this->layout('layouts/main');

$waPhone = preg_replace('/[^0-9]/', '', setting('whatsapp_number', '923252369959')) ?: '923252369959';
$waMsg   = rawurlencode('Hello Creatsify! I am reviewing your Service Packages and would like to discuss a package or custom bundle for my business.');
$avgRating    = number_format((float)($reviewStats['avg_rating'] ?? 4.9), 1);
$totalReviews = (int)($reviewStats['total'] ?? 48);
$tracksData   = $packagesByTrack ?? ['dev' => [], 'design' => [], 'growth' => []];
$trackMeta    = $packageTracks ?? [
    'dev'    => ['label' => '01. Web, App & SaaS Dev', 'short' => 'Web, App & SaaS'],
    'design' => ['label' => '02. Branding, Logo & Redesign', 'short' => 'Branding & Redesign'],
    'growth' => ['label' => '03. SEO & Social Media Ads', 'short' => 'SEO & Paid Ads'],
];
?>

<!-- ======================================================================
     PACKAGES HERO — RADICAL PRICING TRANSPARENCY
     ====================================================================== -->
<section class="ag-subhero">
    <div class="wrap">
        <div class="om-sec-center">
            <div class="om-badge-pill" style="margin-bottom:16px;">
                <span class="om-badge-dot"></span>
                <span>Transparent Agency Packages &bull; Fixed Milestones &bull; 100% Ownership</span>
            </div>
            <h1 class="om-sec-heading" style="font-size:clamp(34px,5vw,56px);max-width:18ch;margin-inline:auto;">
                Clear Packages Built To Scale Your Brand
            </h1>
            <p class="om-sec-sub" style="max-width:66ch;margin-inline:auto;">
                Whether you need a custom PHP/Node.js/Next.js web platform, a cross-platform Flutter mobile app, a high-converting website redesign, a master brand identity, or an ROI-driven SEO &amp; paid social ad engine—pick a transparent package below or build your own custom bundle.
            </p>

            <!-- Trust & Guarantee Strip -->
            <div class="ag-pkg-trust-row">
                <span>&#9733; <b><?= e($avgRating) ?>/5.0</b> Client Rating (<?= $totalReviews ?>+ Reviews)</span>
                <span>&#10003; <b>100%</b> Source Code &amp; Brand IP Ownership</span>
                <span>&#10003; <b>Weekly</b> Live Staging Previews</span>
                <span>&#10003; <b>Zero</b> Hidden Retainer Traps</span>
            </div>

            <!-- Interactive Package Track Switcher Tabs -->
            <div class="om-filter-bar ag-pkg-tabs" style="margin-top:30px;" role="tablist" aria-label="Service Package Tracks">
                <button type="button" class="om-filter-pill active" data-pkg-tab="all">All Packages (9)</button>
                <button type="button" class="om-filter-pill" data-pkg-tab="dev">01. Web, App &amp; SaaS Dev</button>
                <button type="button" class="om-filter-pill" data-pkg-tab="design">02. Branding, Logo &amp; Redesign</button>
                <button type="button" class="om-filter-pill" data-pkg-tab="growth">03. SEO &amp; Social Media Ads</button>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     DYNAMIC PACKAGES BY TRACK
     ====================================================================== -->
<section class="ag-packages-sec" style="padding-top:20px;">
    <div class="wrap">
        <?php
        $trackDescriptions = [
            'dev' => [
                'tag'   => 'Track 01 — Software & App Engineering',
                'title' => 'Web, Mobile App & SaaS Development Packages',
                'desc'  => 'Custom full-stack web builds (PHP, Node.js, Next.js, Tailwind, MySQL), cross-platform Flutter & Dart mobile apps, and scalable SaaS platforms.',
            ],
            'design' => [
                'tag'   => 'Track 02 — Creative & Brand Transformation',
                'title' => 'Branding, Logo Design & Website Redesign Packages',
                'desc'  => 'Distinctive 3D & vector logo systems, complete brand guidelines, social media creative kits, and conversion-focused website UI/UX redesigns.',
            ],
            'growth' => [
                'tag'   => 'Track 03 — Search & Paid Acquisition',
                'title' => 'Technical SEO & Social Media Paid Ads Packages',
                'desc'  => 'Dominate Google search rankings and scale qualified leads with high-ROAS Meta (Facebook/Instagram), Google PPC, and TikTok ad campaigns.',
            ],
        ];

        foreach (['dev', 'design', 'growth'] as $trackKey):
            $trackPackages = $tracksData[$trackKey] ?? [];
            $tInfo = $trackDescriptions[$trackKey];
        ?>
        <div class="ag-pkg-track-block" data-pkg-panel="<?= e($trackKey) ?>" style="margin-bottom:56px;">
            <div class="ag-pkg-track-header">
                <div>
                    <div class="om-sec-tag"><span class="om-sec-dot"></span> <?= e($tInfo['tag']) ?></div>
                    <h2 style="font-size:clamp(22px,3vw,30px);margin:6px 0 8px;color:var(--text);"><?= e($tInfo['title']) ?></h2>
                    <p style="color:var(--muted);font-size:15px;margin:0;max-width:65ch;"><?= e($tInfo['desc']) ?></p>
                </div>
            </div>

            <div class="ag-pkg-grid active" style="margin-top:24px;">
                <?php foreach ($trackPackages as $pkg):
                    $isPop = !empty($pkg['is_popular']);
                    $featList = \App\Models\ServicePackage::parseFeatures($pkg['features'] ?? '');
                    $pkgSelectVal = $pkg['title'] . ' (' . $pkg['price_label'] . ')';
                ?>
                <article class="ag-pkg-card <?= $isPop ? 'popular' : '' ?>">
                    <?php if ($isPop): ?>
                        <div class="ag-pkg-pop-ribbon"><?= e(strtoupper($pkg['tier_badge'] ?: 'MOST POPULAR')) ?></div>
                    <?php endif; ?>
                    <div class="ag-pkg-head">
                        <div class="ag-pkg-top-meta">
                            <span class="ag-pkg-tier-cat"><?= e(strtoupper($trackKey)) ?> SPRINT</span>
                            <?php if (!empty($pkg['delivery_time'])): ?>
                                <span class="ag-pkg-delivery-pill"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:4px;vertical-align:-1px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><?= e($pkg['delivery_time']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="ag-pkg-tier"><?= e($pkg['title']) ?></h3>
                        <div class="ag-pkg-price"><?= e($pkg['price_label']) ?><span><?= e($pkg['billing_cycle'] ? '/' . ltrim($pkg['billing_cycle'], '/') : '') ?></span></div>
                        <p class="ag-pkg-sub"><?= e($pkg['subtitle']) ?></p>
                    </div>
                    <ul class="ag-pkg-list">
                        <?php foreach ($featList as $fItem): ?>
                            <li>&#10003; <?= e($fItem) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="#package-booking" data-select-package="<?= e($pkgSelectVal) ?>" class="<?= $isPop ? 'om-btn-gold' : 'om-btn-glass' ?>" style="width:100%;justify-content:center;margin-top:auto;">
                        <span><?= e($pkg['cta_text'] ?: 'Select Package') ?></span>
                        <span class="om-btn-circle-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></span>
                    </a>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Interactive Custom Scope & Bundle Estimator -->
        <div class="ag-scope-estimator rv s" id="scopeEstimator" style="margin-top:20px;">
            <div class="ag-se-left">
                <div class="om-sec-tag" style="margin-bottom:8px;"><span class="om-sec-dot"></span> Interactive Multi-Service Bundle Builder</div>
                <h3>Build Your Own Custom Agency Bundle (Save 15%)</h3>
                <p>Combine Web Development, Mobile Apps, Branding, SEO, or Paid Social Ads into a single unified roadmap and automatically unlock our <b>15% Multi-Service Agency Bundle Discount</b>.</p>
                <div class="ag-se-options">
                    <label class="ag-se-check">
                        <input type="checkbox" value="Custom Website / Redesign" data-price="350" data-days="7" checked>
                        <span class="ag-se-label"><span>Custom Web Dev / Redesign</span> <b>+$350</b></span>
                    </label>
                    <label class="ag-se-check">
                        <input type="checkbox" value="Flutter Mobile App (iOS & Android)" data-price="750" data-days="14">
                        <span class="ag-se-label"><span>Flutter Mobile App</span> <b>+$750</b></span>
                    </label>
                    <label class="ag-se-check">
                        <input type="checkbox" value="Logo & Complete Brand Identity" data-price="150" data-days="3" checked>
                        <span class="ag-se-label"><span>Logo &amp; Brand Identity</span> <b>+$150</b></span>
                    </label>
                    <label class="ag-se-check">
                        <input type="checkbox" value="Technical & On-Page SEO" data-price="220" data-days="5">
                        <span class="ag-se-label"><span>Technical SEO Setup</span> <b>+$220</b></span>
                    </label>
                    <label class="ag-se-check">
                        <input type="checkbox" value="Meta & Google Paid Ads Setup" data-price="250" data-days="4">
                        <span class="ag-se-label"><span>Social Media &amp; Paid Ads</span> <b>+$250</b></span>
                    </label>
                </div>
            </div>
            <div class="ag-se-right">
                <span class="ag-se-badge">15% BUNDLE SAVING APPLIED</span>
                <div class="ag-se-total-label">Estimated Bundle Investment</div>
                <div class="ag-se-total-price" id="seTotalPrice">$425</div>
                <div class="ag-se-timeline" id="seTimeline">Est. Kickoff &amp; Delivery: ~10 Days</div>
                <button type="button" class="om-btn-gold" id="seLockBtn" style="width:100%;justify-content:center;border:none;cursor:pointer;margin-top:14px;">
                    <span class="om-btn-circle-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></span>
                    <span>Lock In This Custom Bundle</span>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     BOOK A PACKAGE OR CUSTOM PROPOSAL (#package-booking)
     ====================================================================== -->
<section id="package-booking" class="om-contact-sec">
    <div class="wrap om-contact-wrap">
        <div class="om-contact-left">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Fast-Track Kickoff</div>
            <h2 class="om-sec-heading">Lock In Your Package Or Request A Custom Scope</h2>
            <p class="om-sec-sub">Select any package on this page or describe your project requirements below. Our senior engineering and growth team will respond within 2 hours with a clear milestone plan.</p>

            <div class="om-whatsapp-card">
                <div class="om-wa-header">
                    <span class="om-wa-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                    </span>
                    <div class="om-wa-header-info">
                        <b>Instant WhatsApp Package Desk</b>
                        <span>Discuss packages, timelines &amp; custom bundles live</span>
                    </div>
                    <span class="om-wa-active-pill"><i class="om-pulse-dot"></i> Online Now</span>
                </div>
                <a href="https://wa.me/<?= e($waPhone) ?>?text=<?= $waMsg ?>" target="_blank" rel="noopener" class="om-wa-btn" id="homeWaDynamicLink">
                    <span class="om-wa-btn-lead">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z"/></svg>
                        <span>Chat About Packages On WhatsApp</span>
                    </span>
                </a>
            </div>
        </div>

        <form id="form" class="om-contact-form" action="<?= url('/contact') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="text" name="_hp_check" value="" style="display:none!important;" tabindex="-1" autocomplete="off" aria-hidden="true">

            <div class="ag-form-head">
                <strong>Book Your Selected Package</strong>
                <span>Fixed milestones &bull; Live staging server &bull; 100% Ownership</span>
            </div>

            <label>Your Name<input name="name" required autocomplete="name" placeholder="John Doe"></label>
            <label>Your Email<input name="email" type="email" required autocomplete="email" placeholder="john@company.com"></label>
            <label>Phone / WhatsApp<input name="phone" type="tel" placeholder="+1 555 123 4567" autocomplete="tel"></label>
            <label>Selected Package or Service
                <select name="project_type" id="homeProjectTypeSelect">
                    <optgroup label="Service Packages">
                        <?php foreach ($tracksData as $tPkgs): ?>
                            <?php foreach ($tPkgs as $pOpt):
                                $optVal = $pOpt['title'] . ' (' . $pOpt['price_label'] . ')';
                            ?>
                                <option value="<?= e($optVal) ?>"><?= e($optVal) ?></option>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </optgroup>
                    <optgroup label="Custom Bundles & Ready-Made Software">
                        <option value="Custom Multi-Service Agency Bundle">Custom Multi-Service Agency Bundle (15% Off)</option>
                        <option value="Customize Ready-Made Software For My Business">Customize a Ready-Made Software Product For My Business</option>
                    </optgroup>
                </select>
            </label>
            <label>Project Details &amp; Goals<textarea name="message" id="homeProjectMessage" rows="4" required placeholder="Tell us about your business, goals, and target launch timeline..."></textarea></label>
            <button class="om-btn-gold" type="submit" style="width:100%;justify-content:center;">
                <span class="om-btn-circle-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></span>
                <span>Confirm &amp; Request Kickoff</span>
            </button>
        </form>
    </div>
</section>

<script src="<?= asset_v('js/hero-omoway.js') ?>" defer></script>
