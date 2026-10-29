<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
$statusLabels = [
    'planning' => 'Planning', 'active' => 'Active', 'on_hold' => 'On hold',
    'delivered' => 'Delivered', 'archived' => 'Archived',
];
?>

<div class="content-header">
    <div>
        <h1 class="page-title">Delivery Workspace</h1>
        <p class="page-subtitle">Private project operations for your agency—separate from public products and client portals.</p>
    </div>
    <a href="<?= url('/admin/agency-projects/create') ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; min-height: 40px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>Create Delivery Project</span>
    </a>
</div>

<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(139, 123, 255, 0.12); color: var(--adm-accent);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">Active delivery</span>
            <strong class="metric-value"><?= (int)$metrics['counts']['active'] ?></strong>
            <span class="metric-sub">Projects being delivered</span>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">In planning</span>
            <strong class="metric-value"><?= (int)$metrics['counts']['planning'] ?></strong>
            <span class="metric-sub">Ready to scope and assign</span>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">Open tasks</span>
            <strong class="metric-value"><?= (int)$metrics['open_tasks'] ?></strong>
            <span class="metric-sub"><?= (int)$metrics['overdue_tasks'] ?> past due</span>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">Delivered</span>
            <strong class="metric-value"><?= (int)$metrics['counts']['delivered'] ?></strong>
            <span class="metric-sub">Completed agency engagements</span>
        </div>
    </div>
</div>

<div class="card">
    <div class="crm-filter-bar">
        <div class="crm-filter-pills">
            <a class="crm-filter-pill <?= empty($activeStatus) ? 'active' : '' ?>" href="<?= url('/admin/agency-projects') ?>">
                <span>All Projects</span>
                <span class="pill-count"><?= array_sum($metrics['counts']) ?></span>
            </a>
            <?php foreach ($statusLabels as $value => $label): ?>
                <a class="crm-filter-pill <?= $activeStatus === $value ? 'active' : '' ?>" href="<?= url('/admin/agency-projects?status=' . $value) ?>">
                    <span><?= e($label) ?></span>
                    <span class="pill-count"><?= (int)$metrics['counts'][$value] ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        <form action="<?= url('/admin/agency-projects') ?>" method="GET" class="crm-search-form">
            <?php if ($activeStatus): ?><input type="hidden" name="status" value="<?= e($activeStatus) ?>"><?php endif; ?>
            <input class="form-control" name="search" value="<?= e($search) ?>" placeholder="Search project or client...">
            <button class="btn btn-secondary" type="submit">Search</button>
        </form>
    </div>

    <?php if (!$projects): ?>
        <div style="padding:52px 24px; text-align:center; color:var(--adm-muted);">
            <strong style="display:block; color:var(--adm-text); font-size:16px; margin-bottom:8px;">No delivery projects yet</strong>
            <span>Create one after a client accepts the scope; then add the delivery team, milestones, and tasks.</span>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead><tr><th>Delivery project</th><th>Client</th><th>Team</th><th>Workload</th><th>Target</th><th>Status</th><th style="text-align:right;">Actions</th></tr></thead>
                <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><strong><?= e($project['title']) ?></strong><div style="font:11px monospace; color:var(--adm-muted); margin-top:4px;"><?= e($project['project_code']) ?> · <?= e($project['project_type']) ?></div></td>
                        <td><?= e($project['client_company'] ?: $project['client_name'] ?: 'Unlinked') ?></td>
                        <td><?= (int)$project['member_count'] ?> assigned</td>
                        <td><?= (int)$project['open_task_count'] ?> open task<?= (int)$project['open_task_count'] === 1 ? '' : 's' ?><div style="font-size:11px; color:var(--adm-muted); margin-top:3px;"><?= (int)$project['milestone_count'] ?> milestones</div></td>
                        <td style="white-space:nowrap;"><?= $project['target_date'] ? e(date('M j, Y', strtotime($project['target_date']))) : '—' ?></td>
                        <td><span style="display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:700; text-transform:uppercase; padding:3px 8px; border-radius:12px; background:rgba(139,123,255,0.15); color:var(--adm-accent); border:1px solid rgba(139,123,255,0.3);"><?= e($statusLabels[$project['status']] ?? $project['status']) ?></span></td>
                        <td style="text-align:right; white-space:nowrap;">
                            <a class="btn btn-secondary btn-sm" href="<?= url('/admin/agency-projects/' . $project['id']) ?>" style="display:inline-flex; align-items:center; gap:4px; min-height:36px; padding:6px 12px;">
                                <span>Open Workspace</span>
                                <span style="font-size:13px;">&rarr;</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
