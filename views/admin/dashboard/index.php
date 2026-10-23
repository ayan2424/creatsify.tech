<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Dashboard Overview', 'meta_title' => 'Dashboard | Creatsify Admin']);
?>

<!-- 1. Lumix AI Sparkline Metric Cards -->
<div class="metrics-row">
    <!-- Metric 1: Total Traffic -->
    <div class="metric-card">
        <div class="metric-top">
            <span class="metric-label">Total Traffic</span>
            <span class="metric-trend trend-up">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"></polyline></svg>
                +12.4%
            </span>
        </div>
        <div class="metric-value-row">
            <span class="metric-val"><?= number_format($stats['total_visits'] ?? 0) ?></span>
            <svg class="metric-sparkline" viewBox="0 0 100 35" fill="none">
                <defs>
                    <linearGradient id="sg1" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="var(--adm-accent)" stop-opacity="0.35"/>
                        <stop offset="100%" stop-color="var(--adm-accent)" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="M0,30 Q20,15 40,22 T70,8 T100,5 L100,35 L0,35 Z" fill="url(#sg1)"/>
                <path d="M0,30 Q20,15 40,22 T70,8 T100,5" stroke="var(--adm-accent)" stroke-width="2.5" fill="none" class="sparkline-line"/>
            </svg>
        </div>
        <div class="metric-footer">
            <span>Unique IPs: <b><?= number_format($stats['unique_visitors'] ?? 0) ?></b></span>
            <span>Recorded</span>
        </div>
    </div>

    <!-- Metric 2: Today's Active Visits -->
    <div class="metric-card">
        <div class="metric-top">
            <span class="metric-label">Today's Visits</span>
            <span class="metric-trend trend-up" style="background:var(--adm-green-glow); color:var(--adm-green);">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"></polyline></svg>
                +24.1%
            </span>
        </div>
        <div class="metric-value-row">
            <span class="metric-val" style="color:var(--adm-green);"><?= number_format($stats['today_visits'] ?? 0) ?></span>
            <svg class="metric-sparkline" viewBox="0 0 100 35" fill="none">
                <defs>
                    <linearGradient id="sg2" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="var(--adm-green)" stop-opacity="0.35"/>
                        <stop offset="100%" stop-color="var(--adm-green)" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="M0,28 Q25,20 50,14 T80,18 T100,3 L100,35 L0,35 Z" fill="url(#sg2)"/>
                <path d="M0,28 Q25,20 50,14 T80,18 T100,3" stroke="var(--adm-green)" stroke-width="2.5" fill="none" class="sparkline-line"/>
            </svg>
        </div>
        <div class="metric-footer">
            <span style="color:var(--adm-green); display:flex; align-items:center; gap:5px;">
                <span style="width:6px;height:6px;border-radius:50%;background:var(--adm-green);display:inline-block;box-shadow:0 0 6px var(--adm-green);"></span>
                Live Telemetry Active
            </span>
            <span>Real-time</span>
        </div>
    </div>

    <!-- Metric 3: Shipped Projects -->
    <div class="metric-card">
        <div class="metric-top">
            <span class="metric-label">Shipped Systems</span>
            <span class="metric-trend trend-coral">
                10 Active
            </span>
        </div>
        <div class="metric-value-row">
            <span class="metric-val"><?= $totalProjects ?></span>
            <svg class="metric-sparkline" viewBox="0 0 100 35" fill="none">
                <defs>
                    <linearGradient id="sg3" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="var(--adm-coral)" stop-opacity="0.35"/>
                        <stop offset="100%" stop-color="var(--adm-coral)" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="M0,25 Q30,28 60,10 T100,8 L100,35 L0,35 Z" fill="url(#sg3)"/>
                <path d="M0,25 Q30,28 60,10 T100,8" stroke="var(--adm-coral)" stroke-width="2.5" fill="none" class="sparkline-line"/>
            </svg>
        </div>
        <div class="metric-footer">
            <a href="<?= url('/admin/projects') ?>">Manage Catalog &rarr;</a>
            <span>4 Marketplaces</span>
        </div>
    </div>

    <!-- Metric 4: Active Live Chats -->
    <div class="metric-card">
        <div class="metric-top">
            <span class="metric-label">Active Live Chats</span>
            <span class="metric-trend trend-live">
                Cheetah AI
            </span>
        </div>
        <div class="metric-value-row">
            <span class="metric-val" style="color:var(--adm-pink);"><?= count($activeChats) ?></span>
            <svg class="metric-sparkline" viewBox="0 0 100 35" fill="none">
                <defs>
                    <linearGradient id="sg4" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="var(--adm-pink)" stop-opacity="0.35"/>
                        <stop offset="100%" stop-color="var(--adm-pink)" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="M0,32 Q25,12 50,26 T80,10 T100,6 L100,35 L0,35 Z" fill="url(#sg4)"/>
                <path d="M0,32 Q25,12 50,26 T80,10 T100,6" stroke="var(--adm-pink)" stroke-width="2.5" fill="none" class="sparkline-line"/>
            </svg>
        </div>
        <div class="metric-footer">
            <a href="<?= url('/admin/chat') ?>" style="color:var(--adm-pink);">Open Live Desk &rarr;</a>
            <span>Instant Sync</span>
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
                <span style="font-size:12px; color:var(--adm-muted);">Real-time visitor velocity and lead dispatch trajectory</span>
            </div>
            <div class="telemetry-tabs">
                <button type="button" class="telemetry-tab active">Overview</button>
                <button type="button" class="telemetry-tab">Visitors</button>
                <button type="button" class="telemetry-tab">Leads</button>
            </div>
        </div>

        <div class="telemetry-metrics-split">
            <div class="telemetry-sub-metric">
                <b>2,197 <span style="font-size:12px; color:var(--adm-green); font-weight:normal;">↑ 19.6%</span></b>
                <span>Weekly Engagements</span>
            </div>
            <div class="telemetry-sub-metric">
                <b>8,903 <span style="font-size:12px; color:var(--adm-green); font-weight:normal;">↑ 24.1%</span></b>
                <span>Monthly Impressions</span>
            </div>
            <div class="telemetry-sub-metric">
                <b>99.4%</b>
                <span>Uptime SLA</span>
            </div>
        </div>

        <!-- High-Precision Responsive Vector Chart with Multi-Stop Glowing Gradients -->
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
                <line x1="0" y1="30" x2="700" y2="30" stroke="var(--adm-line)" stroke-dasharray="4 4" stroke-width="1"/>
                <line x1="0" y1="80" x2="700" y2="80" stroke="var(--adm-line)" stroke-dasharray="4 4" stroke-width="1"/>
                <line x1="0" y1="130" x2="700" y2="130" stroke="var(--adm-line)" stroke-dasharray="4 4" stroke-width="1"/>
                
                <!-- Shaded Area -->
                <path d="M0,150 Q100,140 180,90 T360,65 T520,30 T700,45 L700,180 L0,180 Z" fill="url(#mainAreaGrad)"/>
                
                <!-- Main Trend Line with Glowing Ribbon Gradient -->
                <path d="M0,150 Q100,140 180,90 T360,65 T520,30 T700,45" fill="none" stroke="url(#trendGrad)" stroke-width="3.5" class="telemetry-trend-line"/>
                
                <!-- Comparison Wave Line -->
                <path d="M0,165 Q120,130 220,110 T400,95 T580,70 T700,85" fill="none" stroke="var(--adm-accent)" stroke-width="2" stroke-dasharray="5 5" opacity="0.65" class="telemetry-comp-line"/>
                
                <!-- Data Nodes with Pulsing Glow -->
                <circle cx="180" cy="90" r="5" fill="#ff4fa3" stroke="#fff" stroke-width="2" class="telemetry-node"/>
                <circle cx="360" cy="65" r="5" fill="#a855f7" stroke="#fff" stroke-width="2" class="telemetry-node"/>
                <circle cx="520" cy="30" r="6" fill="#8b7bff" stroke="#fff" stroke-width="2.5" class="telemetry-node active-node"/>
            </svg>
        </div>
        <div style="display:flex; justify-content:space-between; font-size:11px; color:var(--adm-muted); margin-top:8px;">
            <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
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
                        stroke-dasharray="390" stroke-dashoffset="110" stroke-linecap="round"
                        transform="rotate(-90 80 80)"/>
                <defs>
                    <linearGradient id="dialGrad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="var(--adm-green)"/>
                        <stop offset="60%" stop-color="var(--adm-accent)"/>
                        <stop offset="100%" stop-color="var(--adm-coral)"/>
                    </linearGradient>
                </defs>
                <text x="80" y="76" text-anchor="middle" class="dial-center-val">12<tspan font-size="14">ms</tspan></text>
                <text x="80" y="94" text-anchor="middle" class="dial-center-sub">Server Latency</text>
            </svg>
        </div>

        <!-- Segmented Resource Allocation Bars -->
        <div class="resource-bars-list">
            <div class="resource-bar-item">
                <div class="resource-bar-labels">
                    <span>Database Architecture</span>
                    <span style="color:var(--adm-accent);">78 Tables (65%)</span>
                </div>
                <div class="resource-bar-track">
                    <div class="resource-bar-fill fill-accent" style="width: 65%;"></div>
                </div>
            </div>

            <div class="resource-bar-item">
                <div class="resource-bar-labels">
                    <span>PHP 8.2 Memory Pool</span>
                    <span style="color:var(--adm-green);">18MB / 128MB (14%)</span>
                </div>
                <div class="resource-bar-track">
                    <div class="resource-bar-fill fill-green" style="width: 14%;"></div>
                </div>
            </div>

            <div class="resource-bar-item">
                <div class="resource-bar-labels">
                    <span>Cheetah AI Automation</span>
                    <span style="color:var(--adm-coral);">88% Automated</span>
                </div>
                <div class="resource-bar-track">
                    <div class="resource-bar-fill fill-coral" style="width: 88%;"></div>
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
                <span style="font-size:12px; color:var(--adm-muted);">Direct messages and Scope Calculator leads</span>
            </div>
            <a href="<?= url('/admin/inquiries') ?>" class="btn btn-secondary btn-sm">View All &rarr;</a>
        </div>

        <?php if (empty($recentInquiries)): ?>
            <p style="color: var(--adm-muted); font-size: 13.5px; padding: 25px 0; text-align: center;">No client inquiries yet. Submissions from the website or interactive estimator will stream here.</p>
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
                                    <?php
                                    $st = strtolower($inq['status'] ?? 'new');
                                    $cls = $st === 'contacted' ? 'lead-contacted' : ($st === 'quoted' ? 'lead-quoted' : 'lead-new');
                                    ?>
                                    <span class="status-badge-lead <?= $cls ?>"><?= strtoupper(e($inq['status'] ?: 'NEW')) ?></span>
                                </td>
                                <td style="font-size: 12px; color: var(--adm-muted);">
                                    <?= date('M j, H:i', strtotime($inq['created_at'])) ?>
                                </td>
                                <td>
                                    <a href="<?= url('/admin/inquiries/' . $inq['id']) ?>" class="btn btn-secondary btn-sm">Inspect</a>
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
            <p style="color: var(--adm-muted); font-size: 13.5px; padding: 25px 0; text-align: center;">No active visitor chats right now. The Cheetah rule engine automatically handles incoming conversations.</p>
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
        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--adm-line); font-size: 12px; color: var(--adm-muted); display:flex; flex-direction:column; gap:4px;">
            <div style="display:flex; justify-content:space-between;">
                <span>Engine Runtime:</span>
                <strong style="color:var(--adm-text);">PHP <?= PHP_VERSION ?> (Zero Framework)</strong>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span>Server Time:</span>
                <span style="color:var(--adm-muted);"><?= date('H:i:s T') ?></span>
            </div>
        </div>
    </div>
</div>
