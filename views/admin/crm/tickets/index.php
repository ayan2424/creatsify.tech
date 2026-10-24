<div class="content-header">
    <div>
        <h1 class="page-title">Client Support & Project Tickets</h1>
        <p class="page-subtitle">Manage client requests, bug reports, milestone deliverables, and maintenance SLAs.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="<?= url('/admin/crm/notes') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            <span>CRM Notes &rarr;</span>
        </a>
        <a href="<?= url('/admin/crm/tickets/create') ?>" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>+ Open Ticket</span>
        </a>
    </div>
</div>

<!-- Ticket Metrics Ribbon -->
<div class="metrics-grid" style="grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); margin-bottom: 24px;">
    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(239, 68, 68, 0.12); color: #ef4444;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">Open / New</span>
            <strong class="metric-value"><?= (int)($statusCounts['open'] ?? 0) ?></strong>
            <span class="metric-sub">Awaiting first engineer triage</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">In Progress</span>
            <strong class="metric-value"><?= (int)($statusCounts['in_progress'] ?? 0) ?></strong>
            <span class="metric-sub">Currently under active development</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">Waiting Client</span>
            <strong class="metric-value"><?= (int)($statusCounts['waiting_client'] ?? 0) ?></strong>
            <span class="metric-sub">Pending feedback or demo sign-off</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">Resolved / Closed</span>
            <strong class="metric-value"><?= (int)($statusCounts['resolved'] ?? 0) + (int)($statusCounts['closed'] ?? 0) ?></strong>
            <span class="metric-sub">Completed deliverables</span>
        </div>
    </div>
</div>

<div class="card">
    <!-- Filter Tabs & Controls -->
    <div class="card-header" style="flex-wrap: wrap; gap: 14px; border-bottom: 1px solid var(--adm-line);">
        <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
            <?php
            $statuses = [
                '' => ['label' => 'All Tickets', 'count' => $statusCounts['all'] ?? 0],
                'open' => ['label' => 'Open', 'count' => $statusCounts['open'] ?? 0],
                'in_progress' => ['label' => 'In Progress', 'count' => $statusCounts['in_progress'] ?? 0],
                'waiting_client' => ['label' => 'Waiting Client', 'count' => $statusCounts['waiting_client'] ?? 0],
                'resolved' => ['label' => 'Resolved', 'count' => $statusCounts['resolved'] ?? 0],
                'closed' => ['label' => 'Closed', 'count' => $statusCounts['closed'] ?? 0],
            ];
            ?>
            <?php foreach ($statuses as $key => $st): ?>
                <?php $isSelected = ($activeStatus === $key || (empty($activeStatus) && $key === '')); ?>
                <a href="<?= url('/admin/crm/tickets' . ($key ? '?status=' . $key : '')) ?>" 
                   class="btn btn-sm <?= $isSelected ? 'btn-primary' : 'btn-secondary' ?>" 
                   style="border-radius: 20px; font-size: 11.5px; padding: 5px 12px; white-space: nowrap;">
                    <span><?= e($st['label']) ?></span>
                    <span style="opacity: 0.8; margin-left: 4px; font-size: 10.5px;"><?= $st['count'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <form action="<?= url('/admin/crm/tickets') ?>" method="GET" style="display: flex; gap: 8px; margin: 0;">
            <?php if (!empty($activeStatus)): ?>
                <input type="hidden" name="status" value="<?= e($activeStatus) ?>">
            <?php endif; ?>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search ticket #, subject, client..." value="<?= e($search ?? '') ?>" style="width: 220px; font-size: 12.5px;">
            <button type="submit" class="btn btn-secondary btn-sm" style="font-size: 12px;">Search</button>
            <?php if (!empty($search) || !empty($activeStatus)): ?>
                <a href="<?= url('/admin/crm/tickets') ?>" class="btn btn-secondary btn-sm" style="font-size: 12px; color: var(--adm-muted);" title="Reset filters">&times;</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($tickets)): ?>
        <div style="padding: 56px 20px; text-align: center; color: var(--adm-muted);">
            <div style="margin-bottom: 12px; display: flex; justify-content: center;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--adm-muted); opacity: 0.6;"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="13" y2="17"/></svg>
            </div>
            <div style="font-size: 15px; font-weight: 600; color: var(--adm-text);">No support tickets found</div>
            <p style="font-size: 13px; margin-top: 4px;">There are no tickets matching your current filter.</p>
            <a href="<?= url('/admin/crm/tickets/create') ?>" class="btn btn-primary btn-sm" style="margin-top: 14px;">+ Open First Ticket</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Subject & Category</th>
                        <th>Client / Organization</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned</th>
                        <th>Due Date</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $t): ?>
                        <?php
                        $pColors = [
                            'urgent' => ['color' => '#ef4444', 'bg' => 'rgba(239, 68, 68, 0.12)', 'border' => 'rgba(239, 68, 68, 0.3)'],
                            'high' => ['color' => '#f97316', 'bg' => 'rgba(249, 115, 22, 0.12)', 'border' => 'rgba(249, 115, 22, 0.3)'],
                            'medium' => ['color' => '#f59e0b', 'bg' => 'rgba(245, 158, 11, 0.12)', 'border' => 'rgba(245, 158, 11, 0.3)'],
                            'low' => ['color' => '#10b981', 'bg' => 'rgba(16, 185, 129, 0.12)', 'border' => 'rgba(16, 185, 129, 0.3)'],
                        ];
                        $pc = $pColors[$t['priority']] ?? $pColors['medium'];

                        $sColors = [
                            'open' => ['color' => '#ef4444', 'bg' => 'rgba(239, 68, 68, 0.12)', 'border' => 'rgba(239, 68, 68, 0.25)'],
                            'in_progress' => ['color' => '#f59e0b', 'bg' => 'rgba(245, 158, 11, 0.12)', 'border' => 'rgba(245, 158, 11, 0.25)'],
                            'waiting_client' => ['color' => '#3b82f6', 'bg' => 'rgba(59, 130, 246, 0.12)', 'border' => 'rgba(59, 130, 246, 0.25)'],
                            'resolved' => ['color' => '#10b981', 'bg' => 'rgba(16, 185, 129, 0.12)', 'border' => 'rgba(16, 185, 129, 0.25)'],
                            'closed' => ['color' => '#94a3b8', 'bg' => 'rgba(148, 163, 184, 0.12)', 'border' => 'rgba(148, 163, 184, 0.25)'],
                        ];
                        $sc = $sColors[$t['status']] ?? $sColors['open'];
                        ?>
                        <tr>
                            <td>
                                <a href="<?= url('/admin/crm/tickets/' . $t['id']) ?>" style="font-family: monospace; font-size: 13px; font-weight: 700; color: var(--adm-accent); text-decoration: none;">
                                    <?= e($t['ticket_number']) ?>
                                </a>
                                <?php if ((int)$t['replies_count'] > 0): ?>
                                    <div style="font-size: 10.5px; color: var(--adm-muted); margin-top: 1px;">
                                        <?= (int)$t['replies_count'] ?> messages
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= url('/admin/crm/tickets/' . $t['id']) ?>" style="font-weight: 600; color: var(--adm-text); text-decoration: none; font-size: 13.5px; display: block;">
                                    <?= e($t['subject']) ?>
                                </a>
                                <div style="display: flex; gap: 6px; align-items: center; margin-top: 3px;">
                                    <span class="user-role-badge" style="font-size: 10px; padding: 1px 6px;">
                                        <?= e(strtoupper(str_replace('_', ' ', $t['category']))) ?>
                                    </span>
                                    <?php if (!empty($t['project_title'])): ?>
                                        <span style="font-size: 11px; color: var(--adm-muted);">· System: <?= e($t['project_title']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($t['client_id'])): ?>
                                    <a href="<?= url('/admin/crm/clients/' . $t['client_id']) ?>" style="font-weight: 600; color: var(--adm-text); text-decoration: none; font-size: 13px;">
                                        <?= e($t['client_name']) ?>
                                    </a>
                                <?php else: ?>
                                    <strong style="color: var(--adm-text); font-size: 13px;"><?= e($t['client_name']) ?></strong>
                                <?php endif; ?>
                                <?php if (!empty($t['client_company'])): ?>
                                    <div style="font-size: 11.5px; color: var(--adm-muted);"><?= e($t['client_company']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; background: <?= $pc['bg'] ?>; color: <?= $pc['color'] ?>; border: 1px solid <?= $pc['border'] ?>; text-transform: uppercase;">
                                    ● <?= e($t['priority']) ?>
                                </span>
                            </td>
                            <td>
                                <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 12px; background: <?= $sc['bg'] ?>; color: <?= $sc['color'] ?>; border: 1px solid <?= $sc['border'] ?>;">
                                    <?= e(ucwords(str_replace('_', ' ', $t['status']))) ?>
                                </span>
                            </td>
                            <td style="font-size: 12px; color: var(--adm-text);">
                                <?= e($t['assigned_name'] ?: 'Muhammad Ayan Shamim') ?>
                            </td>
                            <td style="font-size: 12px; color: var(--adm-muted);">
                                <?= !empty($t['due_date']) ? date('M j, Y', strtotime($t['due_date'])) : '—' ?>
                            </td>
                            <td style="text-align: right;">
                                <a href="<?= url('/admin/crm/tickets/' . $t['id']) ?>" class="btn btn-secondary btn-sm" style="font-size: 11.5px; padding: 4px 10px;">
                                    Open Ticket &rarr;
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
