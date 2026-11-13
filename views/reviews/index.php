<?php
/** @var Core\View $this */
$this->layout('layouts/main');

$avgRating    = number_format((float)($stats['avg_rating'] ?? 4.9), 1);
$totalReviews = max(1, (int)($stats['total'] ?? count($reviews ?? [])));
$fiveCount    = (int)($stats['five_star'] ?? $totalReviews);
$fourCount    = (int)($stats['four_star'] ?? 0);
$threeCount   = (int)($stats['three_star'] ?? 0);
$twoCount     = (int)($stats['two_star'] ?? 0);
$oneCount     = (int)($stats['one_star'] ?? 0);

$pct5 = round(($fiveCount / $totalReviews) * 100);
$pct4 = round(($fourCount / $totalReviews) * 100);
$pct3 = round(($threeCount / $totalReviews) * 100);
?>

<!-- ======================================================================
     REVIEWS HERO & AGGREGATE SCOREBOARD
     ====================================================================== -->
<section class="ag-subhero">
    <div class="wrap">
        <div class="om-sec-center">
            <div class="om-badge-pill" style="margin-bottom:16px;">
                <span class="om-badge-dot"></span>
                <span>100% Verified Client Feedback &bull; Real Project Outcomes</span>
            </div>
            <h1 class="om-sec-heading" style="font-size:clamp(34px,5vw,56px);max-width:18ch;margin-inline:auto;">
                Client Reviews &amp; Proven Results
            </h1>
            <p class="om-sec-sub" style="max-width:64ch;margin-inline:auto;">
                Read verified feedback from founders, marketing directors, and enterprise teams who partnered with Creatsify for web &amp; mobile app development, website redesigns, branding, SEO, and paid social ad campaigns.
            </p>
        </div>

        <!-- Aggregate Scoreboard Card -->
        <div class="ag-Scoreboard-card">
            <div class="ag-sb-left">
                <div class="ag-sb-big-num"><?= e($avgRating) ?></div>
                <div class="ag-sb-stars" aria-label="5 out of 5 stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                <div class="ag-sb-count">Based on <?= (int)$totalReviews ?> Verified Client Reviews</div>
                <a href="#submit-review" class="om-btn-gold" style="margin-top:16px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span>Write a Client Review</span>
                </a>
            </div>

            <div class="ag-sb-bars">
                <div class="ag-sb-bar-row">
                    <span class="ag-sb-label">5 Stars</span>
                    <div class="ag-sb-track"><div class="ag-sb-fill" style="width:<?= $pct5 ?>%"></div></div>
                    <span class="ag-sb-pct"><?= $pct5 ?>%</span>
                </div>
                <div class="ag-sb-bar-row">
                    <span class="ag-sb-label">4 Stars</span>
                    <div class="ag-sb-track"><div class="ag-sb-fill" style="width:<?= $pct4 ?>%"></div></div>
                    <span class="ag-sb-pct"><?= $pct4 ?>%</span>
                </div>
                <div class="ag-sb-bar-row">
                    <span class="ag-sb-label">3 Stars</span>
                    <div class="ag-sb-track"><div class="ag-sb-fill" style="width:<?= $pct3 ?>%"></div></div>
                    <span class="ag-sb-pct"><?= $pct3 ?>%</span>
                </div>
                <div class="ag-sb-bar-row">
                    <span class="ag-sb-label">2 Stars</span>
                    <div class="ag-sb-track"><div class="ag-sb-fill" style="width:0%"></div></div>
                    <span class="ag-sb-pct">0%</span>
                </div>
                <div class="ag-sb-bar-row">
                    <span class="ag-sb-label">1 Star</span>
                    <div class="ag-sb-track"><div class="ag-sb-fill" style="width:0%"></div></div>
                    <span class="ag-sb-pct">0%</span>
                </div>
            </div>

            <div class="ag-sb-highlights">
                <div class="ag-sbh-item">
                    <strong>98.6%</strong>
                    <span>On-Time Milestone Delivery</span>
                </div>
                <div class="ag-sbh-item">
                    <strong>100%</strong>
                    <span>Code &amp; Brand IP Ownership</span>
                </div>
                <div class="ag-sbh-item">
                    <strong>4 Divisions</strong>
                    <span>Web, Apps, Brand &amp; Growth</span>
                </div>
            </div>
        </div>

        <!-- Category Filter Bar -->
        <div class="om-filter-bar" style="margin-top:32px;justify-content:center;" role="navigation" aria-label="Filter reviews by service">
            <a href="<?= url('/reviews') ?>" class="om-filter-pill <?= ($currentCategory ?? 'all') === 'all' ? 'active' : '' ?>">All Reviews</a>
            <?php foreach (($categories ?? []) as $cat): ?>
                <a href="<?= url('/reviews?category=' . urlencode($cat)) ?>" class="om-filter-pill <?= ($currentCategory ?? '') === $cat ? 'active' : '' ?>">
                    <?= e($cat) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ======================================================================
     VERIFIED CLIENT REVIEWS GRID
     ====================================================================== -->
<section class="ag-reviews-sec" style="padding-top:24px;">
    <div class="wrap">
        <?php if ($msg = flash('success')): ?>
            <div class="ag-alert-success" style="margin-bottom:28px;">
                <strong>&#10003; Thank You!</strong> <?= e($msg) ?>
            </div>
        <?php endif; ?>
        <?php if ($err = flash('error')): ?>
            <div class="ag-alert-error" style="margin-bottom:28px;">
                <strong>Notice:</strong> <?= e($err) ?>
            </div>
        <?php endif; ?>

        <div class="ag-reviews-grid">
            <?php foreach (($reviews ?? []) as $idx => $rev):
                $stars = max(1, min(5, (int)($rev['rating'] ?? 5)));
                $initials = strtoupper(substr(trim((string)($rev['client_name'] ?? 'C')), 0, 1));
            ?>
                <article class="ag-review-card">
                    <div class="ag-rc-top">
                        <div class="ag-rc-stars" aria-label="<?= $stars ?> out of 5 stars">
                            <?= str_repeat('&#9733;', $stars) ?><?= str_repeat('&#9734;', 5 - $stars) ?>
                        </div>
                        <?php if (!empty($rev['outcome_metric'])): ?>
                            <span class="ag-rc-outcome"><?= e($rev['outcome_metric']) ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 class="ag-rc-title">“<?= e($rev['review_title']) ?>”</h3>
                    <p class="ag-rc-text"><?= e($rev['review_text']) ?></p>
                    <div class="ag-rc-footer">
                        <div class="ag-rc-avatar"><?= e($initials) ?></div>
                        <div class="ag-rc-author">
                            <strong><?= e($rev['client_name']) ?></strong>
                            <span><?= e($rev['client_role'] ?: 'Verified Client') ?><?= !empty($rev['company_name']) ? ' &bull; ' . e($rev['company_name']) : '' ?></span>
                        </div>
                        <span class="ag-rc-service-tag"><?= e($rev['service_category']) ?></span>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ======================================================================
     INTERACTIVE "SUBMIT YOUR REVIEW" FORM (#submit-review)
     ====================================================================== -->
<section id="submit-review" class="om-contact-sec">
    <div class="wrap om-contact-wrap">
        <div class="om-contact-left">
            <div class="om-sec-tag"><span class="om-sec-dot"></span> Share Your Experience</div>
            <h2 class="om-sec-heading">Worked With Creatsify? Leave Your Review</h2>
            <p class="om-sec-sub">
                Your feedback helps us continuously raise the bar across our web engineering, mobile app, branding, and digital growth divisions—and helps future clients make confident decisions.
            </p>

            <div class="ag-strength-card" style="margin-top:24px;">
                <h3 style="margin-top:0;">How Our Verification Works</h3>
                <p style="margin-bottom:10px;">Every review submitted through this portal is verified against our project delivery or software license records before going live on our public scoreboard.</p>
                <div class="om-prod-chips on-light">
                    <span>Verified clients</span><span>Real results</span><span>Moderated reviews</span>
                </div>
            </div>
        </div>

        <form class="om-contact-form" action="<?= url('/reviews') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="text" name="_hp_check" value="" style="display:none!important;" tabindex="-1" autocomplete="off" aria-hidden="true">

            <div class="ag-form-head">
                <strong>Submit Your Client Review &amp; Star Rating</strong>
                <span>Takes less than 60 seconds</span>
            </div>

            <!-- Interactive 1-5 Star Rating Selector -->
            <div class="ag-star-picker-wrap">
                <span class="ag-sp-label">Your Overall Rating *</span>
                <input type="hidden" name="rating" id="reviewRatingInput" value="5">
                <div class="ag-star-picker" id="interactiveStarPicker" role="radiogroup" aria-label="Star Rating">
                    <?php for ($s = 1; $s <= 5; $s++): ?>
                        <button type="button" class="ag-star-btn active" data-star-val="<?= $s ?>" aria-label="<?= $s ?> Stars">&#9733;</button>
                    <?php endfor; ?>
                    <span class="ag-star-text" id="starPickerText">5.0 — Exceptional Experience</span>
                </div>
            </div>

            <div class="form-row">
                <label>Your Full Name *<input name="client_name" required placeholder="Alex Morgan"></label>
                <label>Your Role / Title<input name="client_role" placeholder="Founder / Marketing Director"></label>
            </div>

            <div class="form-row">
                <label>Company / Brand Name<input name="company_name" placeholder="Apex Commerce"></label>
                <label>Service You Used *
                    <select name="service_category" required>
                        <?php foreach (($categories ?? []) as $cat): ?>
                            <option value="<?= e($cat) ?>"><?= e($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>

            <div class="form-row">
                <label>Review Headline *<input name="review_title" required placeholder="e.g., Flawless website redesign and 3x faster load times"></label>
                <label>Key Result / Outcome (Optional)<input name="outcome_metric" placeholder="e.g., +150% Leads, 4.9★ App, 3.5x ROAS"></label>
            </div>

            <label>Your Detailed Feedback *
                <textarea name="review_text" rows="4" required placeholder="Share how our team handled your web/app build, branding, redesign, SEO, or paid social ads..."></textarea>
            </label>

            <button class="om-btn-gold" type="submit" style="width:100%;justify-content:center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span>Submit Verified Review</span>
            </button>
        </form>
    </div>
</section>

<script src="<?= asset_v('js/hero-omoway.js') ?>" defer></script>
