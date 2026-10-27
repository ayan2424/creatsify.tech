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
    <a href="<?= url('/admin/agency-projects/create') ?>" class="btn btn-primary btn-sm">+ Create delivery project</a>
</div>

<div class="metrics-grid" style="grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); margin-bottom:24px;">
    <div class="metric-card"><div class="metric-info"><span class="metric-label">Active delivery</span><strong class="metric-value"><?= (int)$metrics['counts']['active'] ?></strong><span class="metric-sub">Projects being delivered</span></div></div>
    <div class="metric-card"><div class="metric-info"><span class="metric-label">In planning</span><strong class="metric-value"><?= (int)$metrics['counts']['planning'] ?></strong><span class="metric-sub">Ready to scope and assign</span></div></div>
    <div class="metric-card"><div class="metric-info"><span class="metric-label">Open tasks</span><strong class="metric-value"><?= (int)$metrics['open_tasks'] ?></strong><span class="metric-sub"><?= (int)$metrics['overdue_tasks'] ?> past due</span></div></div>
    <div class="metric-card"><div class="metric-info"><span class="metric-label">Delivered</span><strong class="metric-value"><?= (int)$metrics['counts']['delivered'] ?></strong><span class="metric-sub">Completed agency engagements</span></div></div>
</div>

<div class="card">
    <div class="card-header" style="gap:14px; flex-wrap:wrap;">
        <div style="display:flex; gap:6px; flex-wrap:wrap;">
            <a class="btn btn-secondary btn-sm" href="<?= url('/admin/agency-projects') ?>">All</a>
            <?php foreach ($statusLabels as $value => $label): ?>
                <a class="btn btn-secondary btn-sm" style="<?= $activeStatus === $value ? 'border-color:var(--adm-accent); color:var(--adm-accent);' : '' ?>" href="<?= url('/admin/agency-projects?status=' . $value) ?>">
                    <?= e($label) ?> (<?= (int)$metrics['counts'][$value] ?>)
                </a>
            <?php endforeach; ?>
        </div>
        <form action="<?= url('/admin/agency-projects') ?>" method="GET" style="display:flex; gap:8px; margin-left:auto;">
            <?php if ($activeStatus): ?><input type="hidden" name="status" value="<?= e($activeStatus) ?>"><?php endif; ?>
            <input class="form-control form-control-sm" name="search" value="<?= e($search) ?>" placeholder="Search project or client">
            <button class="btn btn-secondary btn-sm" type="submit">Search</button>
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
                <thead><tr><th>Delivery project</th><th>Client</th><th>Team</th><th>Workload</th><th>Target</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><strong><?= e($project['title']) ?></strong><div style="font:11px monospace; color:var(--adm-muted); margin-top:4px;"><?= e($project['project_code']) ?> · <?= e($project['project_type']) ?></div></td>
                        <td><?= e($project['client_company'] ?: $project['client_name'] ?: 'Unlinked') ?></td>
                        <td><?= (int)$project['member_count'] ?> assigned</td>
                        <td><?= (int)$project['open_task_count'] ?> open task<?= (int)$project['open_task_count'] === 1 ? '' : 's' ?><div style="font-size:11px; color:var(--adm-muted); margin-top:3px;"><?= (int)$project['milestone_count'] ?> milestones</div></td>
                        <td><?= $project['target_date'] ? e(date('M j, Y', strtotime($project['target_date']))) : '—' ?></td>
                        <td><span style="font-size:11px; font-weight:700; text-transform:uppercase; color:var(--adm-accent);"><?= e($statusLabels[$project['status']] ?? $project['status']) ?></span></td>
                        <td style="text-align:right;"><a class="btn btn-secondary btn-sm" href="<?= url('/admin/agency-projects/' . $project['id']) ?>">Open workspace</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
