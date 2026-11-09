<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Portfolio Overview', 'meta_title' => 'Dashboard | Creatsify Admin']);

$pipelineVal = !empty($totalPipelineValue) ? '$' . number_format((float)$totalPipelineValue) : '$17.3K';
$todayRevenueVal = '$' . number_format((($stats['today'] ?? 142) * 24));
$dailyHits = $dailyTrend ?? [];

// 12 Months Data Breakdown for Capsule Bar Chart
$months = [
    ['month' => 'Jan', 'hits' => 19, 'pct' => '+66%', 'h' => 65, 'top' => 28],
    ['month' => 'Feb', 'hits' => 28, 'pct' => '+23%', 'h' => 88, 'top' => 38],
    ['month' => 'Mar', 'hits' => 39, 'pct' => '+56%', 'h' => 110, 'top' => 48],
    ['month' => 'Apr', 'hits' => 44, 'pct' => '+25%', 'h' => 125, 'top' => 55],
    ['month' => 'May', 'hits' => 45, 'pct' => '+26%', 'h' => 130, 'top' => 58],
    ['month' => 'Jun', 'hits' => 28, 'pct' => '+76%', 'h' => 90, 'top' => 40],
    ['month' => 'Jul', 'hits' => 32, 'pct' => '+23%', 'h' => 98, 'top' => 44],
    ['month' => 'Aug', 'hits' => 28, 'pct' => '+33%', 'h' => 90, 'top' => 38],
    ['month' => 'Sep', 'hits' => 29, 'pct' => '+34%', 'h' => 94, 'top' => 42],
    ['month' => 'Oct', 'hits' => 10, 'pct' => '+25%', 'h' => 48, 'top' => 22],
    ['month' => 'Nov', 'hits' => 14, 'pct' => '+36%', 'h' => 58, 'top' => 26],
    ['month' => 'Dec', 'hits' => 18, 'pct' => '+36%', 'h' => 70, 'top' => 32],
];
?>

<!-- 1. Inset Workspace Header (Reference Design) -->
<div class="adm-ws-header">
    <div>
        <h1 class="adm-ws-title">Portfolio Overview</h1>
        <div class="adm-ws-crumbs">
            <span>Overview</span>
            <span>&rsaquo;</span>
            <span style="color:#CBD5E1;">All Reports</span>
        </div>
    </div>
    <div class="adm-ws-actions">
        <button type="button" class="adm-ws-pill-btn">
            <span>Activity</span>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <a href="<?= url('/admin/audit-logs') ?>" class="adm-ws-pill-btn">
            <span>Journal</span>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </a>
        <a href="<?= url('/admin/inquiries') ?>" class="adm-ws-pill-btn">
            <span>Filter</span>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        </a>
    </div>
</div>

<!-- 2. Ultra-Luxury Bento Grid -->
<div class="adm-bento-grid">

    <!-- Card 1: Asset Allocation Matrix (Reference Top Left) -->
    <article class="adm-card adm-card-allocation">
        <div class="adm-card-header">
            <h3 class="adm-card-title">Asset Allocation</h3>
            <div class="adm-card-ctrls">
                <span class="adm-ctrl-pill">Weekly <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></span>
                <span class="adm-ctrl-more">&bull;&bull;&bull;</span>
            </div>
        </div>

        <div class="adm-alloc-total-row">
            <div>
                <div class="adm-alloc-big-val"><?= e($pipelineVal) ?></div>
                <div class="adm-alloc-total-label">Total Pipeline</div>
            </div>
            <div class="adm-alloc-legend">
                <span><span class="dot-cyan"></span> $1 - $500</span>
                <span><span class="dot-sapphire"></span> $500 - $2,000</span>
                <span><span class="dot-gray"></span> +$2,000</span>
            </div>
        </div>

        <!-- 3x7 Interactive Allocation Matrix Dots -->
        <div class="adm-matrix-table">
            <div class="adm-matrix-row">
                <span class="adm-matrix-label">Web &amp; SaaS</span>
                <div class="adm-matrix-dots">
                    <span class="adm-mdot cyan" title="PHP 8.4 MVC Sprint"></span>
                    <span class="adm-mdot charcoal"></span>
                    <span class="adm-mdot cyan"></span>
                    <span class="adm-mdot ice"></span>
                    <span class="adm-mdot dark"></span>
                    <span class="adm-mdot sapphire"></span>
                    <span class="adm-mdot cyan"></span>
                </div>
            </div>
            <div class="adm-matrix-row">
                <span class="adm-matrix-label">Flutter Apps</span>
                <div class="adm-matrix-dots">
                    <span class="adm-mdot charcoal"></span>
                    <span class="adm-mdot sapphire"></span>
                    <span class="adm-mdot striped"></span>
                    <span class="adm-mdot cyan"></span>
                    <span class="adm-mdot charcoal"></span>
                    <span class="adm-mdot striped"></span>
                    <span class="adm-mdot dark"></span>
                </div>
            </div>
            <div class="adm-matrix-row">
                <span class="adm-matrix-label">Brand &amp; Ads</span>
                <div class="adm-matrix-dots">
                    <span class="adm-mdot dark"></span>
                    <span class="adm-mdot cyan"></span>
                    <span class="adm-mdot dark"></span>
                    <span class="adm-mdot charcoal"></span>
                    <span class="adm-mdot sapphire"></span>
                    <span class="adm-mdot ice"></span>
                    <span class="adm-mdot striped"></span>
                </div>
            </div>
        </div>
        <div class="adm-matrix-days">
            <span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span><span>S</span>
        </div>
    </article>

    <!-- Card 2: Commercial License Mastery Banner (Reference Top Right) -->
    <article class="adm-card adm-card-mastery">
        <!-- Generative Vector Contour Waves Art (Electric Cyan Palette) -->
        <svg class="adm-mastery-topo-bg" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M50 250C120 180 200 290 300 210C350 170 380 120 400 90" stroke="#00D2EE" stroke-width="1.2" stroke-opacity="0.3"/>
            <path d="M30 220C100 150 180 260 280 180C330 140 370 100 400 60" stroke="#00D2EE" stroke-width="1.2" stroke-opacity="0.35"/>
            <path d="M10 190C80 120 160 230 260 150C310 110 360 80 400 30" stroke="#00D2EE" stroke-width="1.5" stroke-opacity="0.45"/>
            <path d="M0 160C70 90 140 200 240 120C290 80 340 50 400 0" stroke="#00D2EE" stroke-width="1.2" stroke-opacity="0.3"/>
        </svg>

        <div>
            <div class="adm-mastery-tag">
                <span>&#10022; 100% Unlocked Code &bull; Gumroad Direct Store</span>
            </div>
            <h2 class="adm-mastery-title">Commercial Software &amp; White-Label License Hub</h2>
            <p class="adm-mastery-sub">Deploy our 5 production-ready courier, logistics, POS, and 4K video engines with instant source code ownership and client tailoring.</p>
        </div>

        <div>
            <a href="<?= url('/admin/projects') ?>" class="adm-btn-cyan">
                <span>Explore Software</span>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
            </a>
        </div>
    </article>

    <!-- Card 3: Today's Inbound Velocity (Reference Mid Left) -->
    <article class="adm-card adm-card-velocity">
        <div class="adm-velocity-top">
            <div class="adm-velocity-icon-circle">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <span class="adm-velocity-label">Today's Inbound Activity</span>
        </div>
        <div class="adm-velocity-big-val"><?= e($todayRevenueVal) ?></div>
        <div class="adm-velocity-delta">
            <span>+73%</span>
            <span>Velocity &amp; Leads Surge</span>
        </div>
    </article>

    <!-- Card 4: Portfolio Report Fluid Card (Reference Mid Right) -->
    <article class="adm-card adm-card-report">
        <div class="adm-report-top">
            <div class="adm-report-icons">
                <span class="adm-report-icon-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></span>
                <span class="adm-report-icon-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg></span>
            </div>
            <a href="<?= url('/admin/audit-logs') ?>" class="adm-report-icon-btn" title="View Logs">&times;</a>
        </div>

        <div>
            <h3 class="adm-report-title">Agency Performance Report</h3>
            <p class="adm-report-sub">Generate and export an executive telemetry breakdown of your client deliveries, tickets, and Gumroad software revenue.</p>
        </div>
    </article>

    <!-- Card 5: Two-Tone Capsule Bar Graph (Reference Bottom Left) -->
    <article class="adm-card adm-card-chart">
        <div class="adm-card-header">
            <h3 class="adm-card-title">Earnings Breakdown</h3>
            <div class="adm-card-ctrls">
                <span class="adm-ctrl-pill">Sort by Month <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></span>
                <span class="adm-ctrl-more">&bull;&bull;&bull;</span>
            </div>
        </div>

        <div class="adm-capsule-chart-wrap">
            <div class="adm-capsules-row">
                <?php foreach ($months as $m): ?>
                    <div class="adm-capsule-col">
                        <span class="adm-capsule-delta"><?= e($m['pct']) ?></span>
                        <div class="adm-capsule-bar" style="height: <?= (int)$m['h'] ?>px;">
                            <div class="adm-capsule-bar-top" style="height: <?= (int)$m['top'] ?>px;"></div>
                        </div>
                        <span class="adm-capsule-month"><?= e($m['month']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </article>

    <!-- Card 6: Commercial Holdings & Core Stacks (Reference Bottom Right) -->
    <article class="adm-card adm-card-holdings">
        <div class="adm-card-header">
            <h3 class="adm-card-title">Commercial Holdings</h3>
            <a href="<?= url('/admin/projects') ?>" class="adm-card-ctrls" style="text-decoration:none;">
                <span style="font-size:12px;font-weight:700;color:#00D2EE;display:flex;align-items:center;gap:4px;">
                    See All <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                </span>
            </a>
        </div>

        <div class="adm-holdings-list">
            <!-- 1. Cheetah Enterprise -->
            <div class="adm-holding-item">
                <div class="adm-holding-left">
                    <div class="adm-holding-coin-icon orange">CE</div>
                    <div class="adm-holding-meta">
                        <strong>Cheetah Enterprise</strong>
                        <span>Courier &amp; Multi-Branch Suite</span>
                    </div>
                </div>
                <div class="adm-holding-right">
                    <div class="adm-holding-price">$99.00</div>
                    <div class="adm-holding-change">+10.79%</div>
                </div>
            </div>

            <!-- 2. Lumina Fleet Companion -->
            <div class="adm-holding-item">
                <div class="adm-holding-left">
                    <div class="adm-holding-coin-icon cyan">LF</div>
                    <div class="adm-holding-meta">
                        <strong>Lumina Companion</strong>
                        <span>Flutter iOS &amp; Android App</span>
                    </div>
                </div>
                <div class="adm-holding-right">
                    <div class="adm-holding-price">$49.00</div>
                    <div class="adm-holding-change">+20.52%</div>
                </div>
            </div>

            <!-- 3. Cheezify Storefront -->
            <div class="adm-holding-item">
                <div class="adm-holding-left">
                    <div class="adm-holding-coin-icon purple">CZ</div>
                    <div class="adm-holding-meta">
                        <strong>Cheezify Storefront</strong>
                        <span>Cold-Chain D2C Portal</span>
                    </div>
                </div>
                <div class="adm-holding-right">
                    <div class="adm-holding-price">$59.00</div>
                    <div class="adm-holding-change">+12.45%</div>
                </div>
            </div>

            <!-- 4. RestroMaster POS -->
            <div class="adm-holding-item">
                <div class="adm-holding-left">
                    <div class="adm-holding-coin-icon gold">RM</div>
                    <div class="adm-holding-meta">
                        <strong>RestroMaster KDS</strong>
                        <span>Real-Time Kitchen Display</span>
                    </div>
                </div>
                <div class="adm-holding-right">
                    <div class="adm-holding-price">$79.00</div>
                    <div class="adm-holding-change">+18.30%</div>
                </div>
            </div>
        </div>

        <div class="adm-holdings-action-row">
            <a href="<?= url('/admin/projects') ?>" class="adm-btn-cyan" style="padding: 7px 18px; font-size: 12.5px;">
                <span>Manage Stacks</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
            </a>
        </div>
    </article>

</div>

<!-- 3. Bottom Operational Telemetry Dock -->
<section class="adm-telemetry-dock" aria-label="System Telemetry and Fast Actions">
    <div class="adm-dock-stats">
        <span class="adm-dock-pill active-engine">
            <span style="width:7px;height:7px;border-radius:50%;background:#00D2EE;box-shadow:0 0 8px #00D2EE;"></span>
            <span>Cheetah Core Online</span>
        </span>
        <span class="adm-dock-pill">
            <span>DB Latency:</span>
            <strong style="color:#00D2EE;"><?= (int)($dbLatencyMs ?? 5) ?>ms Nominal</strong>
        </span>
        <span class="adm-dock-pill">
            <span>Memory:</span>
            <strong style="color:#FFFFFF;"><?= $memoryUsageMb ?? 14 ?>MB (<?= $memoryPercent ?? 12 ?>%)</strong>
        </span>
        <span class="adm-dock-pill">
            <span>MySQL Tables:</span>
            <strong style="color:#FFFFFF;"><?= $tableCount ?? 26 ?></strong>
        </span>
    </div>

    <div class="adm-dock-actions">
        <a class="adm-dock-btn primary" href="<?= url('/admin/projects/create') ?>">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Add Product</span>
        </a>
        <a class="adm-dock-btn" href="<?= url('/admin/agency-projects/create') ?>">
            <span>+ Delivery Sprint</span>
        </a>
        <a class="adm-dock-btn" href="<?= url('/admin/crm/tickets/create') ?>">
            <span>+ Open Ticket</span>
        </a>
        <a class="adm-dock-btn" href="<?= url('/admin/crm/clients/create') ?>">
            <span>+ New Client</span>
        </a>
        <a class="adm-dock-btn" href="<?= url('/') ?>" target="_blank" rel="noopener">
            <span>Storefront ↗</span>
        </a>
    </div>
</section>
