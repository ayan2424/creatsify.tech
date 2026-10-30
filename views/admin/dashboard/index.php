<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Dashboard Overview', 'meta_title' => 'Dashboard | Creatsify Admin']);

// 1. Calculate Real Dynamic Coordinates for 7-Day Traffic Area Graph
$trendData = $dailyTrend ?? [];
$hitsList = array_column($trendData, 'hits');
$maxHits = !empty($hitsList) ? max(1, max($hitsList)) : 1;
$total7Days = array_sum($hitsList);

$chartPoints = [];
$stepX = 700 / 6; // 6 intervals for 7 data points on 700px canvas
foreach ($trendData as $idx => $d) {
    $cx = round($idx * $stepX, 1);
    // Y maps from baseline (150) to peak (30)
    $ratio = $d['hits'] / $maxHits;
    $cy = round(150 - ($ratio * 115), 1);
    $chartPoints[] = [
        'x' => $cx,
        'y' => $cy,
        'day' => $d['day'],
        'date' => $d['date'],
        'hits' => (int)$d['hits']
    ];
}

// Generate smooth cubic bezier curve
$pathD = "M" . $chartPoints[0]['x'] . "," . $chartPoints[0]['y'];
for ($i = 0; $i < count($chartPoints) - 1; $i++) {
    $p0 = $chartPoints[$i];
    $p1 = $chartPoints[$i + 1];
    $cp1x = round($p0['x'] + ($p1['x'] - $p0['x']) / 2, 1);
    $cp1y = $p0['y'];
    $cp2x = $cp1x;
    $cp2y = $p1['y'];
    $pathD .= " C$cp1x,$cp1y $cp2x,$cp2y " . $p1['x'] . "," . $p1['y'];
}
$areaD = $pathD . " L700,180 L0,180 Z";

// Calculate circular dial dashoffset for 390 perimeter
$dialOffset = max(30, min(350, round(390 - min(300, (($dbLatencyMs ?? 5) * 20)))));
?>

<!-- Executive Command Header -->
<div class="content-header" style="align-items: center; margin-bottom: 22px;">
    <div>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px; flex-wrap: wrap;">
            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; background: rgba(0, 229, 153, 0.12); color: #00e599; border: 1px solid rgba(0, 229, 153, 0.3);">
                <span style="width: 7px; height: 7px; border-radius: 50%; background: #00e599; box-shadow: 0 0 10px #00e599;"></span>
                <span>Cheetah Core Engine Online</span>
            </span>
            <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 20px; font-size: 11px; font-weight: 600; background: var(--adm-surface); color: var(--adm-muted); border: 1px solid var(--adm-glass-border);">
                <span>DB Latency:</span>
                <strong style="color: var(--adm-green);"><?= $dbLatencyMs ?>ms Nominal</strong>
            </span>
            <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 20px; font-size: 11px; font-weight: 600; background: var(--adm-surface); color: var(--adm-muted); border: 1px solid var(--adm-glass-border);">
                <span>Memory:</span>
                <strong style="color: var(--adm-text);"><?= $memoryUsageMb ?>MB (<?= $memoryPercent ?>%)</strong>
            </span>
            <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 20px; font-size: 11px; font-weight: 600; background: var(--adm-surface); color: var(--adm-muted); border: 1px solid var(--adm-glass-border);">
                <span>MySQL Tables:</span>
                <strong style="color: var(--adm-text);"><?= $tableCount ?></strong>
            </span>
        </div>
        <h1 class="page-title" style="margin-bottom: 4px;">Studio Executive Command Desk</h1>
        <p class="page-subtitle">Real-time commercial pipeline, delivery workload, customer live triage, and server telemetry.</p>
    </div>
    <div class="dash-actions" style="margin-bottom: 0; align-items: center;">
        <a class="btn btn-primary" href="<?= url('/admin/projects/create') ?>" style="display: inline-flex; align-items: center; gap: 6px; min-height: 38px; padding: 8px 16px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Add Product</span>
        </a>
        <a class="btn btn-secondary" href="<?= url('/admin/agency-projects/create') ?>" style="display: inline-flex; align-items: center; gap: 6px; min-height: 38px; padding: 8px 14px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3z"/><path d="M7 8h10M7 12h10M7 16h6"/></svg>
            <span>Delivery Project</span>
        </a>
        <a class="btn btn-secondary" href="<?= url('/admin/crm/tickets/create') ?>" style="display: inline-flex; align-items: center; gap: 6px; min-height: 38px; padding: 8px 14px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>Open Ticket</span>
        </a>
        <a class="btn btn-secondary" href="<?= url('/admin/crm/clients/create') ?>" style="display: inline-flex; align-items: center; gap: 6px; min-height: 38px; padding: 8px 14px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            <span>New Client</span>
        </a>
        <a class="btn btn-secondary" href="<?= url('/') ?>" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; gap: 4px; min-height: 38px; padding: 8px 12px;" title="View Live Public Storefront">
            <span>Public Store &nearr;</span>
        </a>
    </div>
</div>

<!-- High-Craft Needs Attention Command Strip -->
<section class="dash-attn" aria-label="Actionable Operational Telemetry">
    <a class="attn-item<?= ((int)($newInquiries ?? 0)) > 0 ? ' hot-emerald' : '' ?>" href="<?= url('/admin/inquiries') ?>" title="Review Inbound Inquiries">
        <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(0, 229, 153, 0.12); color: #00e599; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        </div>
        <div>
            <b><?= (int)($newInquiries ?? 0) ?></b>
            <span>Inquiries Awaiting Reply</span>
        </div>
    </a>
    <a class="attn-item<?= ((int)($openTicketsCount ?? 0)) > 0 ? ' hot' : '' ?>" href="<?= url('/admin/crm/tickets') ?>" title="Open Support Tickets">
        <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(255, 95, 56, 0.12); color: #ff5f38; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div>
            <b><?= (int)($openTicketsCount ?? 0) ?></b>
            <span>Open Support Tickets</span>
        </div>
    </a>
    <a class="attn-item<?= count($activeChats ?? []) > 0 ? ' hot-violet' : '' ?>" href="<?= url('/admin/chat') ?>" title="Live Visitor Chats">
        <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(139, 123, 255, 0.12); color: var(--adm-accent); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div>
            <b><?= count($activeChats ?? []) ?></b>
            <span>Live Chat Sessions</span>
        </div>
    </a>
    <a class="attn-item<?= count($pendingFollowUps ?? []) > 0 ? ' hot-amber' : '' ?>" href="<?= url('/admin/crm/clients') ?>" title="Scheduled Client Follow-Ups">
        <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(245, 158, 11, 0.12); color: #f59e0b; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
            <b><?= count($pendingFollowUps ?? []) ?></b>
            <span>Follow-Ups Scheduled</span>
        </div>
    </a>
</section>

<!-- 1. World-Class Glassmorphic KPI Cards -->
<div class="metrics-row">
    <!-- Card 1: Commercial Contract Pipeline -->
    <div class="metric-card">
        <div class="metric-top">
            <span class="metric-label">Commercial Pipeline</span>
            <span class="metric-trend trend-up">
                <?= (int)($crmStageCounts['active_client'] ?? 0) ?> Active Deals
            </span>
        </div>
        <div class="metric-value-row">
            <span class="metric-val">$<?= number_format($totalPipelineValue, 2) ?></span>
            <svg class="metric-sparkline" viewBox="0 0 100 35" fill="none">
                <defs>
                    <linearGradient id="sgPipeline" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#8b7bff" stop-opacity="0.4"/>
                        <stop offset="100%" stop-color="#8b7bff" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="M0,30 Q25,12 50,22 T80,8 T100,4 L100,35 L0,35 Z" fill="url(#sgPipeline)"/>
                <path d="M0,30 Q25,12 50,22 T80,8 T100,4" stroke="#8b7bff" stroke-width="2.5" fill="none" class="sparkline-line"/>
            </svg>
        </div>
        <div class="metric-footer">
            <a href="<?= url('/admin/crm/clients') ?>" style="color: var(--adm-accent); text-decoration: none; font-weight: 600;">
                <?= (int)($crmStageCounts['retained'] ?? 0) ?> Accounts on Retainer &rarr;
            </a>
            <span>Contract SLA</span>
        </div>
    </div>

    <!-- Card 2: Total Traffic & Reach -->
    <div class="metric-card">
        <div class="metric-top">
            <span class="metric-label">Visitor Velocity</span>
            <span class="metric-trend trend-up" style="background:var(--adm-green-glow); color:var(--adm-green);">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"></polyline></svg>
                +<?= number_format($stats['today_visits'] ?? 0) ?> Today
            </span>
        </div>
        <div class="metric-value-row">
            <span class="metric-val" style="color:var(--adm-green);"><?= number_format($stats['total_visits'] ?? 0) ?></span>
            <svg class="metric-sparkline" viewBox="0 0 100 35" fill="none">
                <defs>
                    <linearGradient id="sgTraffic" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="var(--adm-green)" stop-opacity="0.4"/>
                        <stop offset="100%" stop-color="var(--adm-green)" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="M0,28 Q25,20 50,14 T80,18 T100,3 L100,35 L0,35 Z" fill="url(#sgTraffic)"/>
                <path d="M0,28 Q25,20 50,14 T80,18 T100,3" stroke="var(--adm-green)" stroke-width="2.5" fill="none" class="sparkline-line"/>
            </svg>
        </div>
        <div class="metric-footer">
            <span style="color:var(--adm-green); display:flex; align-items:center; gap:5px;">
                <span style="width:6px;height:6px;border-radius:50%;background:var(--adm-green);display:inline-block;box-shadow:0 0 6px var(--adm-green);"></span>
                <b><?= number_format($stats['unique_visitors'] ?? 0) ?></b> Unique Visitors
            </span>
            <span>Live Telemetry</span>
        </div>
    </div>

    <!-- Card 3: Agency Delivery Operations -->
    <div class="metric-card">
        <div class="metric-top">
            <span class="metric-label">Agency Delivery</span>
            <span class="metric-trend trend-coral">
                <?= (int)($deliveryMetrics['counts']['active'] ?? 0) ?> Active
            </span>
        </div>
        <div class="metric-value-row">
            <span class="metric-val" style="color: var(--adm-coral);"><?= (int)($deliveryMetrics['counts']['active'] ?? 0) ?> <span style="font-size: 15px; font-weight: normal; color: var(--adm-muted);">Projects</span></span>
            <svg class="metric-sparkline" viewBox="0 0 100 35" fill="none">
                <defs>
                    <linearGradient id="sgDelivery" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="var(--adm-coral)" stop-opacity="0.4"/>
                        <stop offset="100%" stop-color="var(--adm-coral)" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="M0,25 Q30,28 60,10 T100,8 L100,35 L0,35 Z" fill="url(#sgDelivery)"/>
                <path d="M0,25 Q30,28 60,10 T100,8" stroke="var(--adm-coral)" stroke-width="2.5" fill="none" class="sparkline-line"/>
            </svg>
        </div>
        <div class="metric-footer">
            <a href="<?= url('/admin/agency-projects') ?>" style="color: var(--adm-coral); text-decoration: none; font-weight: 600;">
                <?= (int)($deliveryMetrics['open_tasks'] ?? 0) ?> Tasks (<?= (int)($deliveryMetrics['overdue_tasks'] ?? 0) ?> Overdue) &rarr;
            </a>
            <span>Milestones</span>
        </div>
    </div>

    <!-- Card 4: Support SLA & Live Channels -->
    <div class="metric-card">
        <div class="metric-top">
            <span class="metric-label">Support SLA & Chats</span>
            <span class="metric-trend trend-live">
                Cheetah Realtime
            </span>
        </div>
        <div class="metric-value-row">
            <span class="metric-val" style="color:var(--adm-pink);"><?= (int)$openTicketsCount ?> <span style="font-size: 15px; font-weight: normal; color: var(--adm-muted);">Tickets</span></span>
            <svg class="metric-sparkline" viewBox="0 0 100 35" fill="none">
                <defs>
                    <linearGradient id="sgSupport" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="var(--adm-pink)" stop-opacity="0.4"/>
                        <stop offset="100%" stop-color="var(--adm-pink)" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="M0,32 Q25,12 50,26 T80,10 T100,6 L100,35 L0,35 Z" fill="url(#sgSupport)"/>
                <path d="M0,32 Q25,12 50,26 T80,10 T100,6" stroke="var(--adm-pink)" stroke-width="2.5" fill="none" class="sparkline-line"/>
            </svg>
        </div>
        <div class="metric-footer">
            <a href="<?= url('/admin/chat') ?>" style="color:var(--adm-pink); text-decoration: none; font-weight: 600;">
                <?= count($activeChats) ?> Active Visitor<?= count($activeChats) === 1 ? '' : 's' ?> &rarr;
            </a>
            <span>Live Channel</span>
        </div>
    </div>
</div>

<!-- Private Agency Delivery Workspace Card -->
<div class="card" style="margin-top:24px;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Agency Delivery Workspace</h3>
            <span style="font-size:12px; color:var(--adm-muted);">Internal client engagements, development sprints, and milestone progress.</span>
        </div>
        <a href="<?= url('/admin/agency-projects') ?>" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 4px;">
            <span>Open Delivery Workspace</span>
            <span>&rarr;</span>
        </a>
    </div>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px; padding:16px;">
        <div style="padding:16px; border-radius:10px; background:var(--adm-surface); border:1px solid var(--adm-glass-border);">
            <span style="display:block; font-size:11px; color:var(--adm-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.04em;">Active Projects</span>
            <strong style="display:block; font-size:24px; font-family:'Bricolage Grotesque',sans-serif; margin-top:4px; color:var(--adm-text);"><?= (int)($deliveryMetrics['counts']['active'] ?? 0) ?></strong>
            <span style="font-size:12px; color:var(--adm-muted);">Under active engineering</span>
        </div>
        <div style="padding:16px; border-radius:10px; background:var(--adm-surface); border:1px solid var(--adm-glass-border);">
            <span style="display:block; font-size:11px; color:var(--adm-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.04em;">In Planning</span>
            <strong style="display:block; font-size:24px; font-family:'Bricolage Grotesque',sans-serif; margin-top:4px; color:#f59e0b;"><?= (int)($deliveryMetrics['counts']['planning'] ?? 0) ?></strong>
            <span style="font-size:12px; color:var(--adm-muted);">Ready to scope & assign</span>
        </div>
        <div style="padding:16px; border-radius:10px; background:var(--adm-surface); border:1px solid var(--adm-glass-border);">
            <span style="display:block; font-size:11px; color:var(--adm-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.04em;">Open Deliverables</span>
            <strong style="display:block; font-size:24px; font-family:'Bricolage Grotesque',sans-serif; margin-top:4px; color:var(--adm-accent);"><?= (int)($deliveryMetrics['open_tasks'] ?? 0) ?></strong>
            <span style="font-size:12px; color:var(--adm-muted);"><?= (int)($deliveryMetrics['overdue_tasks'] ?? 0) ?> tasks past target date</span>
        </div>
        <div style="padding:16px; border-radius:10px; background:var(--adm-surface); border:1px solid var(--adm-glass-border);">
            <span style="display:block; font-size:11px; color:var(--adm-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.04em;">Delivered Systems</span>
            <strong style="display:block; font-size:24px; font-family:'Bricolage Grotesque',sans-serif; margin-top:4px; color:#10b981;"><?= (int)($deliveryMetrics['counts']['delivered'] ?? 0) ?></strong>
            <span style="font-size:12px; color:var(--adm-muted);">Completed engagements</span>
        </div>
    </div>
</div>

<!-- 2. Dual-Panel Visual Telemetry Row (Lumix AI Style) -->
<div class="telemetry-row">
    <!-- Left: Activity & Traffic Area Graph -->
    <div class="telemetry-card">
        <div class="telemetry-header">
            <div>
                <h3 class="telemetry-title">Studio Traffic & Inquiries Activity</h3>
                <span style="font-size:12px; color:var(--adm-muted);">Real-time visitor velocity and lead dispatch trajectory (Last 7 Days)</span>
            </div>
            <div class="telemetry-tabs">
                <button type="button" class="telemetry-tab active" data-pane="paneOverview">Overview</button>
                <button type="button" class="telemetry-tab" data-pane="paneTopPages">Top Pages</button>
                <button type="button" class="telemetry-tab" data-pane="paneDevices">Devices</button>
            </div>
        </div>

        <div class="telemetry-metrics-split">
            <div class="telemetry-sub-metric">
                <b><?= number_format($total7Days) ?> <span style="font-size:12px; color:var(--adm-green); font-weight:normal;">7-Day</span></b>
                <span>Weekly Engagements</span>
            </div>
            <div class="telemetry-sub-metric">
                <b><?= number_format($totalInquiries ?? 0) ?> <span style="font-size:12px; color:var(--adm-accent); font-weight:normal;"><?= (int)($newInquiries ?? 0) ?> New</span></b>
                <span>Client Inquiries</span>
            </div>
            <div class="telemetry-sub-metric">
                <b><?= $dbLatencyMs ?>ms <span style="font-size:12px; color:var(--adm-green); font-weight:normal;">Nominal</span></b>
                <span>DB Response</span>
            </div>
        </div>

        <!-- Pane 1: Dynamic SVG Area Chart -->
        <div id="paneOverview" class="telemetry-pane active">
            <div class="telemetry-graph-wrap">
                <svg viewBox="0 0 700 180" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="mainAreaGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#ff5f38" stop-opacity="0.45"/>
                            <stop offset="35%" stop-color="#ff4fa3" stop-opacity="0.25"/>
                            <stop offset="70%" stop-color="#8b7bff" stop-opacity="0.10"/>
                            <stop offset="100%" stop-color="transparent" stop-opacity="0"/>
                        </linearGradient>
                        <linearGradient id="trendGrad" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0%" stop-color="#ff5f38"/>
                            <stop offset="35%" stop-color="#ff4fa3"/>
                            <stop offset="70%" stop-color="#a855f7"/>
                            <stop offset="100%" stop-color="#8b7bff"/>
                        </linearGradient>
                    </defs>
                    <!-- Horizontal Grid Lines -->
                    <line x1="0" y1="35" x2="700" y2="35" stroke="var(--adm-line)" stroke-dasharray="4 4" stroke-width="1"/>
                    <line x1="0" y1="85" x2="700" y2="85" stroke="var(--adm-line)" stroke-dasharray="4 4" stroke-width="1"/>
                    <line x1="0" y1="135" x2="700" y2="135" stroke="var(--adm-line)" stroke-dasharray="4 4" stroke-width="1"/>
                    
                    <!-- Shaded Area -->
                    <path d="<?= $areaD ?>" fill="url(#mainAreaGrad)"/>
                    
                    <!-- Main Trend Line with Glowing Ribbon Gradient -->
                    <path d="<?= $pathD ?>" fill="none" stroke="url(#trendGrad)" stroke-width="3.5" class="telemetry-trend-line"/>
                    
                    <!-- Data Nodes with Pulsing Glow -->
                    <?php foreach ($chartPoints as $i => $pt): ?>
                        <circle cx="<?= $pt['x'] ?>" cy="<?= $pt['y'] ?>" r="<?= ($i === count($chartPoints) - 1) ? '6' : '4.5' ?>" fill="<?= ($i === count($chartPoints) - 1) ? '#8b7bff' : '#ff4fa3' ?>" stroke="#fff" stroke-width="2" class="telemetry-node <?= ($i === count($chartPoints) - 1) ? 'active-node' : '' ?>">
                            <title><?= e($pt['day']) ?> (<?= e($pt['date']) ?>): <?= $pt['hits'] ?> visits</title>
                        </circle>
                    <?php endforeach; ?>
                </svg>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:11px; color:var(--adm-muted); margin-top:8px;">
                <?php foreach ($chartPoints as $pt): ?>
                    <span style="font-weight: 600;"><?= e($pt['day']) ?></span>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Pane 2: Top Visited Pages -->
        <div id="paneTopPages" class="telemetry-pane" style="display:none; padding-top: 15px;">
            <?php if (empty($stats['top_pages'])): ?>
                <div style="text-align: center; color: var(--adm-muted); font-size: 13px; padding: 30px;">No page hits logged yet.</div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <?php foreach ($stats['top_pages'] as $tp): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; background: var(--adm-surface); border: 1px solid var(--adm-line); border-radius: 8px;">
                            <code style="color: var(--adm-accent); font-size: 13px;"><?= e($tp['path']) ?></code>
                            <span class="user-role-badge" style="font-size: 11px;"><?= number_format((int)$tp['hits']) ?> hits</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pane 3: Device Distribution -->
        <div id="paneDevices" class="telemetry-pane" style="display:none; padding-top: 15px;">
            <?php if (empty($stats['devices'])): ?>
                <div style="text-align: center; color: var(--adm-muted); font-size: 13px; padding: 30px;">No device data recorded yet.</div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <?php 
                    $totalDevHits = array_sum(array_column($stats['devices'], 'count'));
                    foreach ($stats['devices'] as $dev): 
                        $pct = $totalDevHits > 0 ? round(($dev['count'] / $totalDevHits) * 100) : 0;
                    ?>
                        <div>
                            <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px;">
                                <strong style="text-transform: capitalize; color: var(--adm-text);"><?= e($dev['device_type'] ?: 'Unknown') ?></strong>
                                <span style="color: var(--adm-muted);"><?= (int)$dev['count'] ?> (<?= $pct ?>%)</span>
                            </div>
                            <div style="width: 100%; height: 6px; background: var(--adm-surface); border-radius: 4px; overflow: hidden; border: 1px solid var(--adm-line);">
                                <div style="height: 100%; width: <?= $pct ?>%; background: var(--adm-accent); border-radius: 4px;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right: Lumix Circular Dial Load Meter & Resources -->
    <div class="telemetry-card">
        <div class="telemetry-header">
            <h3 class="telemetry-title">Processing & Load Meter</h3>
            <span class="engine-status-pill">Nominal</span>
        </div>

        <div class="dial-meter-box">
            <svg class="dial-meter-svg" viewBox="0 0 160 160">
                <!-- Track Circle -->
                <circle cx="80" cy="80" r="62" fill="none" stroke="var(--adm-line)" stroke-width="9"/>
                <!-- Value Arc (SVG Dasharray) -->
                <circle cx="80" cy="80" r="62" fill="none" stroke="url(#dialGrad)" stroke-width="9"
                        stroke-dasharray="390" stroke-dashoffset="<?= $dialOffset ?>" stroke-linecap="round"
                        transform="rotate(-90 80 80)"/>
                <defs>
                    <linearGradient id="dialGrad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="var(--adm-green)"/>
                        <stop offset="60%" stop-color="var(--adm-accent)"/>
                        <stop offset="100%" stop-color="var(--adm-coral)"/>
                    </linearGradient>
                </defs>
                <text x="80" y="76" text-anchor="middle" class="dial-center-val"><?= $dbLatencyMs ?><tspan font-size="14">ms</tspan></text>
                <text x="80" y="94" text-anchor="middle" class="dial-center-sub">DB Handshake</text>
            </svg>
        </div>

        <!-- Segmented Resource Allocation Bars -->
        <div class="resource-bars-list">
            <div class="resource-bar-item">
                <div class="resource-bar-labels">
                    <span>Database Architecture</span>
                    <span style="color:var(--adm-accent);"><?= $tableCount ?> Tables Active</span>
                </div>
                <div class="resource-bar-track">
                    <div class="resource-bar-fill fill-accent" style="width: <?= min(100, max(20, $tableCount * 4)) ?>%;"></div>
                </div>
            </div>

            <div class="resource-bar-item">
                <div class="resource-bar-labels">
                    <span>PHP Memory Pool</span>
                    <span style="color:var(--adm-green);"><?= $memoryUsageMb ?>MB / <?= $memoryLimitMb ?>MB (<?= $memoryPercent ?>%)</span>
                </div>
                <div class="resource-bar-track">
                    <div class="resource-bar-fill fill-green" style="width: <?= $memoryPercent ?>%;"></div>
                </div>
            </div>

            <div class="resource-bar-item">
                <div class="resource-bar-labels">
                    <span>Live Visitor Channels</span>
                    <span style="color:var(--adm-coral);"><?= count($activeChats) ?> Active Chats</span>
                </div>
                <div class="resource-bar-track">
                    <div class="resource-bar-fill fill-coral" style="width: <?= min(100, max(15, count($activeChats) * 20)) ?>%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. Operational Command Tables Grid -->
<div class="tables-grid">
    <!-- Recent Client Inquiries Table -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Recent Client Inquiries</h3>
                <span style="font-size:12px; color:var(--adm-muted);">Direct consultation requests & product leads</span>
            </div>
            <a href="<?= url('/admin/inquiries') ?>" class="btn btn-secondary btn-sm">View All &rarr;</a>
        </div>

        <?php if (empty($recentInquiries)): ?>
            <div style="padding: 35px 20px; text-align: center; color: var(--adm-muted);">
                <div style="margin-bottom: 12px; color: var(--adm-muted); opacity: 0.6;">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </div>
                <div style="font-weight: 600; color: var(--adm-text); font-size: 14px;">No client inquiries yet</div>
                <p style="font-size: 12.5px; margin-top: 4px;">Inbound leads submitted from the website will stream here in real time.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Client / Email</th>
                            <th>Project Scope</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentInquiries as $inq): ?>
                            <?php
                            $st = strtolower($inq['status'] ?? 'new');
                            $badgeStyle = match($st) {
                                'new' => 'background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-weight:700;',
                                'in_discussion' => 'background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); font-weight:700;',
                                'converted' => 'background: rgba(139, 123, 255, 0.15); color: #8b7bff; border: 1px solid rgba(139, 123, 255, 0.3); font-weight:700;',
                                'closed' => 'background: rgba(163, 167, 187, 0.15); color: #a3a7bb; border: 1px solid var(--adm-line); font-weight:700;',
                                default => 'background: var(--adm-surface); color: var(--adm-muted); border: 1px solid var(--adm-line);'
                            };
                            ?>
                            <tr>
                                <td>
                                    <strong style="color:var(--adm-text); display:block; font-size:13.5px;"><?= e($inq['name']) ?></strong>
                                    <span style="font-size:12px; color:var(--adm-muted);"><?= e($inq['email']) ?></span>
                                </td>
                                <td>
                                    <span style="background:var(--adm-bg); border:1px solid var(--adm-line); padding:3px 8px; border-radius:6px; font-size:12px;">
                                        <?= e($inq['project_type'] ?: 'Custom System') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="user-role-badge" style="<?= $badgeStyle ?>"><?= strtoupper(e(str_replace('_', ' ', $st))) ?></span>
                                </td>
                                <td style="font-size: 12px; color: var(--adm-muted); white-space: nowrap;">
                                    <?= date('M j, H:i', strtotime($inq['created_at'])) ?>
                                </td>
                                <td>
                                    <a href="<?= url('/admin/inquiries/' . $inq['id']) ?>" class="btn btn-secondary btn-sm" style="font-size: 11.5px; padding: 4px 10px;">Inspect</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Active Live Chat Visitors Feed -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Live Chat Visitors</h3>
                <span style="font-size:12px; color:var(--adm-muted);">Cheetah real-time communication</span>
            </div>
            <a href="<?= url('/admin/chat') ?>" class="btn btn-primary btn-sm">Open Desk</a>
        </div>

        <?php if (empty($activeChats)): ?>
            <div style="padding: 35px 20px; text-align: center; color: var(--adm-muted);">
                <div style="margin-bottom: 12px; display: flex; justify-content: center;">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color: var(--adm-muted); opacity: 0.6;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div style="font-weight: 600; color: var(--adm-text); font-size: 14px;">AI Auto-Pilot Idle</div>
                <p style="font-size: 12.5px; margin-top: 4px; line-height: 1.5;">No active visitor chats right now. Incoming visitor inquiries will immediately stream here.</p>
                <a href="<?= url('/admin/chat') ?>" class="btn btn-secondary btn-sm" style="margin-top: 8px;">View Past Sessions &rarr;</a>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <?php foreach ($activeChats as $chat): ?>
                    <a href="<?= url('/admin/chat?session_id=' . $chat['id']) ?>" class="chat-session-item">
                        <div class="chat-session-top">
                            <strong style="color: var(--adm-text); font-size: 13.5px;"><?= e($chat['visitor_name']) ?></strong>
                            <?php if ($chat['is_human_handoff']): ?>
                                <span style="font-size: 10px; padding: 2px 6px; border-radius: 4px; background: var(--adm-pink-glow); color: var(--adm-pink); font-weight: 700;">HANDOFF</span>
                            <?php else: ?>
                                <span style="font-size: 10px; padding: 2px 6px; border-radius: 4px; background: var(--adm-green-glow); color: var(--adm-green); font-weight: 700;">BOT ACTIVE</span>
                            <?php endif; ?>
                        </div>
                        <p style="font-size: 12px; color: var(--adm-muted); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= e($chat['last_message'] ?: 'Conversation active...') ?>
                        </p>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Quick System Diagnostic Badge -->
        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--adm-line); font-size: 12px; color: var(--adm-muted); display:flex; flex-direction:column; gap:6px;">
            <div style="display:flex; justify-content:space-between;">
                <span>Engine Runtime:</span>
                <strong style="color:var(--adm-text);">PHP <?= PHP_VERSION ?> (Zero Framework)</strong>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span>Server Software:</span>
                <span style="color:var(--adm-text);"><?= e($_SERVER['SERVER_SOFTWARE'] ?? 'PHP Development Server') ?></span>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span>Server Time:</span>
                <span style="color:var(--adm-muted);"><?= date('H:i:s T') ?></span>
            </div>
        </div>
    </div>
</div>

<!-- CRM Operations & Deliverables Grid -->
<div class="crm-split-grid">
    <!-- Active Support & Milestone Tickets -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Client Support & Milestone Tickets</h3>
                <span style="font-size: 12px; color: var(--adm-muted);"><?= (int)$openTicketsCount ?> active SLA tickets pending triage</span>
            </div>
            <div style="display: flex; gap: 8px;">
                <a href="<?= url('/admin/crm/tickets/create') ?>" class="btn btn-secondary btn-sm">+ Open Ticket</a>
                <a href="<?= url('/admin/crm/tickets') ?>" class="btn btn-primary btn-sm">All Tickets &rarr;</a>
            </div>
        </div>

        <?php if (empty($recentTickets)): ?>
            <div style="padding: 35px 20px; text-align: center; color: var(--adm-muted);">
                <div style="font-weight: 600; color: var(--adm-text); font-size: 14px;">No active support tickets</div>
                <p style="font-size: 12.5px; margin-top: 4px;">Bug reports, client change requests, and milestone deliverables will display here.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ticket</th>
                            <th>Subject</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentTickets as $t): ?>
                            <?php
                            $pColors = [
                                'urgent' => '#ef4444',
                                'high' => '#f97316',
                                'medium' => '#f59e0b',
                                'low' => '#10b981'
                            ];
                            $pc = $pColors[$t['priority']] ?? '#94a3b8';
                            ?>
                            <tr>
                                <td>
                                    <a href="<?= url('/admin/crm/tickets/' . $t['id']) ?>" style="font-family: monospace; font-weight: 700; color: var(--adm-accent); text-decoration: none; font-size: 12.5px;">
                                        <?= e($t['ticket_number']) ?>
                                    </a>
                                </td>
                                <td>
                                    <strong style="color: var(--adm-text); font-size: 13px; display: block;"><?= e($t['subject']) ?></strong>
                                    <span style="font-size: 11.5px; color: var(--adm-muted);"><?= e($t['client_name']) ?> <?= !empty($t['client_company']) ? '(' . e($t['client_company']) . ')' : '' ?></span>
                                </td>
                                <td>
                                    <span style="font-size: 11px; font-weight: 700; color: <?= $pc ?>; text-transform: uppercase;">
                                        ● <?= e($t['priority']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="font-size: 11px; padding: 2px 7px; border-radius: 6px; background: var(--adm-bg); border: 1px solid var(--adm-line);">
                                        <?= e(str_replace('_', ' ', $t['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= url('/admin/crm/tickets/' . $t['id']) ?>" class="btn btn-secondary btn-sm" style="font-size: 11px; padding: 3px 8px;">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Client Deals & Follow-Up Reminders -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Commercial Pipeline & Follow-Ups</h3>
                <span style="font-size: 12px; color: var(--adm-muted);">Total Pipeline: $<?= number_format($totalPipelineValue, 2) ?></span>
            </div>
            <a href="<?= url('/admin/crm/clients') ?>" class="btn btn-secondary btn-sm">Clients File &rarr;</a>
        </div>

        <div style="padding: 18px; display: flex; flex-direction: column; gap: 14px;">
            <!-- Pipeline Quick Bar -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: var(--adm-surface); border-radius: 8px; border: 1px solid var(--adm-line);">
                <div>
                    <span style="font-size: 11px; color: var(--adm-muted); text-transform: uppercase; font-weight: 600;">Active Retainers</span>
                    <strong style="font-size: 16px; color: var(--adm-text); display: block;"><?= (int)($crmStageCounts['retained'] ?? 0) ?> Accounts</strong>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 11px; color: var(--adm-muted); text-transform: uppercase; font-weight: 600;">In Execution</span>
                    <strong style="font-size: 16px; color: #10b981; display: block;"><?= (int)($crmStageCounts['active_client'] ?? 0) ?> Clients</strong>
                </div>
            </div>

            <!-- Follow-up tasks -->
            <div>
                <span style="font-size: 11.5px; font-weight: 700; color: var(--adm-text); text-transform: uppercase; letter-spacing: 0.04em; display: block; margin-bottom: 8px;">
                    Scheduled Follow-Up Actions
                </span>

                <?php if (empty($pendingFollowUps)): ?>
                    <p style="font-size: 12.5px; color: var(--adm-muted); margin: 0;">All scheduled client follow-ups are up to date.</p>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <?php foreach (array_slice($pendingFollowUps, 0, 3) as $fu): ?>
                            <div style="padding: 10px 12px; border-radius: 6px; background: var(--adm-bg); border: 1px solid var(--adm-line); display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 12.5px; color: var(--adm-text); display: block;"><?= e($fu['title']) ?></strong>
                                    <span style="font-size: 11px; color: #f59e0b;">Target: <?= date('M j, Y', strtotime($fu['follow_up_date'])) ?></span>
                                </div>
                                <a href="<?= url('/admin/crm/notes') ?>" class="btn btn-secondary btn-sm" style="font-size: 10.5px; padding: 2px 7px;">Action</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Telemetry Tab Switcher
    document.querySelectorAll('.telemetry-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.telemetry-tab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const targetId = this.getAttribute('data-pane');
            document.querySelectorAll('.telemetry-pane').forEach(p => {
                p.style.display = 'none';
                p.classList.remove('active');
            });

            const targetPane = document.getElementById(targetId);
            if (targetPane) {
                targetPane.style.display = 'block';
                targetPane.classList.add('active');
            }
        });
    });
</script>
