<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Clients & Pipeline', 'meta_title' => 'CRM Clients | Creatsify Admin']);
?>
<div class="content-header">
    <div>
        <h1 class="page-title">Clients & Deal Pipeline</h1>
        <p class="page-subtitle">Track commercial leads, active clients, contractual retainers, and deal values.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <a href="<?= url('/admin/crm/tickets') ?>" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 6px; min-height: 40px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
            <span>Tickets Desk &rarr;</span>
        </a>
        <a href="<?= url('/admin/crm/clients/create') ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; min-height: 40px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>+ Add New Client</span>
        </a>
    </div>
</div>

<!-- CRM Pipeline Metrics -->
<div class="metrics-grid" style="grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); margin-bottom: 24px;">
    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(139, 123, 255, 0.12); color: var(--adm-accent);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">Pipeline Value</span>
            <strong class="metric-value">$<?= number_format($totalPipelineValue, 2) ?></strong>
            <span class="metric-sub">Commercial contract pipeline</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">Active & Retained</span>
            <strong class="metric-value"><?= (int)($stageCounts['active_client'] ?? 0) + (int)($stageCounts['retained'] ?? 0) ?></strong>
            <span class="metric-sub"><?= (int)($stageCounts['retained'] ?? 0) ?> on monthly retainers</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">Discovery & Proposals</span>
            <strong class="metric-value"><?= (int)($stageCounts['discovery'] ?? 0) + (int)($stageCounts['proposal_sent'] ?? 0) ?></strong>
            <span class="metric-sub">Active deals in negotiation</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">Total Leads</span>
            <strong class="metric-value"><?= (int)($stageCounts['all'] ?? 0) ?></strong>
            <span class="metric-sub">Across all pipeline stages</span>
        </div>
    </div>
</div>

<div class="card">
    <!-- Filter Tabs & Search Bar -->
    <div class="crm-filter-bar">
        <div class="crm-filter-pills">
            <?php
            $stages = [
                '' => ['label' => 'All Clients', 'count' => $stageCounts['all'] ?? 0],
                'lead' => ['label' => 'Leads', 'count' => $stageCounts['lead'] ?? 0],
                'discovery' => ['label' => 'Discovery', 'count' => $stageCounts['discovery'] ?? 0],
                'proposal_sent' => ['label' => 'Proposal Sent', 'count' => $stageCounts['proposal_sent'] ?? 0],
                'contract_signed' => ['label' => 'Signed', 'count' => $stageCounts['contract_signed'] ?? 0],
                'active_client' => ['label' => 'Active', 'count' => $stageCounts['active_client'] ?? 0],
                'retained' => ['label' => 'Retainer SLA', 'count' => $stageCounts['retained'] ?? 0],
            ];
            ?>
            <?php foreach ($stages as $key => $st): ?>
                <?php $isSelected = ($activeStage === $key || (empty($activeStage) && $key === '')); ?>
                <a href="<?= url('/admin/crm/clients' . ($key ? '?stage=' . $key : '')) ?>" 
                   class="crm-filter-pill <?= $isSelected ? 'active' : '' ?>">
                    <span><?= e($st['label']) ?></span>
                    <span class="pill-count"><?= $st['count'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <form action="<?= url('/admin/crm/clients') ?>" method="GET" class="crm-search-form">
            <?php if (!empty($activeStage)): ?>
                <input type="hidden" name="stage" value="<?= e($activeStage) ?>">
            <?php endif; ?>
            <input type="text" name="search" class="form-control" placeholder="Search name, company, email, tag..." value="<?= e($search ?? '') ?>">
            <button type="submit" class="btn btn-secondary">Search</button>
            <?php if (!empty($search) || !empty($activeStage)): ?>
                <a href="<?= url('/admin/crm/clients') ?>" class="btn btn-secondary" style="color: var(--adm-muted); padding: 8px 12px;" title="Reset filters">&times;</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($clients)): ?>
        <div style="padding: 56px 20px; text-align: center; color: var(--adm-muted);">
            <div style="margin-bottom: 12px; display: flex; justify-content: center;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--adm-muted); opacity: 0.6;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div style="font-size: 15px; font-weight: 600; color: var(--adm-text);">No clients found</div>
            <p style="font-size: 13px; margin-top: 4px;">There are no client records matching this pipeline filter.</p>
            <a href="<?= url('/admin/crm/clients/create') ?>" class="btn btn-primary btn-sm" style="margin-top: 14px;">+ Add New Client</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Client & Company</th>
                        <th>Direct Contact</th>
                        <th>Pipeline Stage</th>
                        <th>Deal Value</th>
                        <th>Tickets & Notes</th>
                        <th>Created</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $c): ?>
                        <?php
                        $stageColors = [
                            'lead' => ['bg' => 'rgba(100, 116, 139, 0.15)', 'color' => '#94a3b8', 'border' => 'rgba(100, 116, 139, 0.3)'],
                            'discovery' => ['bg' => 'rgba(59, 130, 246, 0.15)', 'color' => '#60a5fa', 'border' => 'rgba(59, 130, 246, 0.3)'],
                            'proposal_sent' => ['bg' => 'rgba(245, 158, 11, 0.15)', 'color' => '#fbbf24', 'border' => 'rgba(245, 158, 11, 0.3)'],
                            'contract_signed' => ['bg' => 'rgba(16, 185, 129, 0.15)', 'color' => '#34d399', 'border' => 'rgba(16, 185, 129, 0.3)'],
                            'active_client' => ['bg' => 'rgba(16, 185, 129, 0.22)', 'color' => '#10b981', 'border' => 'rgba(16, 185, 129, 0.45)'],
                            'retained' => ['bg' => 'rgba(139, 123, 255, 0.2)', 'color' => 'var(--adm-accent)', 'border' => 'rgba(139, 123, 255, 0.4)'],
                            'churned' => ['bg' => 'rgba(239, 68, 68, 0.15)', 'color' => '#f87171', 'border' => 'rgba(239, 68, 68, 0.3)'],
                        ];
                        $sc = $stageColors[$c['stage']] ?? $stageColors['lead'];
                        $stageLabel = ucwords(str_replace('_', ' ', $c['stage']));
                        $phoneClean = clean_phone_number($c['whatsapp'] ?: ($c['phone'] ?? ''), false);
                        ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(139, 123, 255, 0.12); color: var(--adm-accent); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; border: 1px solid rgba(139, 123, 255, 0.25); flex-shrink: 0;">
                                        <?= strtoupper(substr($c['name'], 0, 2)) ?>
                                    </div>
                                    <div>
                                        <a href="<?= url('/admin/crm/clients/' . $c['id']) ?>" style="font-weight: 600; color: var(--adm-text); text-decoration: none; font-size: 13.5px;">
                                            <?= e($c['name']) ?>
                                        </a>
                                        <?php if (!empty($c['company'])): ?>
                                            <div style="font-size: 11.5px; color: var(--adm-muted);"><?= e($c['company']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($c['tags'])): ?>
                                            <div style="display: flex; gap: 4px; margin-top: 3px; flex-wrap: wrap;">
                                                <?php foreach (array_slice(explode(',', $c['tags']), 0, 2) as $tag): ?>
                                                    <span style="font-size: 10px; background: var(--adm-bg); color: var(--adm-muted); padding: 1px 5px; border-radius: 4px; border: 1px solid var(--adm-line);">
                                                        <?= e(trim($tag)) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 12.5px; color: var(--adm-text);">
                                    <a href="mailto:<?= e($c['email']) ?>" style="color: inherit; text-decoration: none;">
                                        <?= e($c['email']) ?>
                                    </a>
                                </div>
                                <?php if (!empty($c['phone'])): ?>
                                    <div style="font-size: 11.5px; color: var(--adm-muted); margin-top: 2px;">
                                        <?= e($c['phone']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 12px; background: <?= $sc['bg'] ?>; color: <?= $sc['color'] ?>; border: 1px solid <?= $sc['border'] ?>;">
                                    <span style="width: 5px; height: 5px; border-radius: 50%; background: currentColor;"></span>
                                    <span><?= e($stageLabel) ?></span>
                                </span>
                            </td>
                            <td>
                                <strong style="font-size: 13px; color: var(--adm-text);">
                                    <?= e($c['currency']) ?> <?= number_format((float)$c['deal_value'], 2) ?>
                                </strong>
                                <div style="font-size: 11px; color: var(--adm-muted);"><?= e($c['source']) ?></div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 6px; align-items: center;">
                                    <a href="<?= url('/admin/crm/clients/' . $c['id'] . '#tickets') ?>" class="btn btn-secondary btn-sm" style="font-size: 11px; padding: 2px 7px; border-radius: 6px;" title="View Tickets">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 3px;"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/></svg>
                                        <span><?= (int)$c['tickets_count'] ?></span>
                                        <?php if ((int)$c['open_tickets_count'] > 0): ?>
                                            <span style="color: #ef4444; font-weight: 700; margin-left: 2px;">(<?= (int)$c['open_tickets_count'] ?>)</span>
                                        <?php endif; ?>
                                    </a>
                                    <a href="<?= url('/admin/crm/clients/' . $c['id'] . '#notes') ?>" class="btn btn-secondary btn-sm" style="font-size: 11px; padding: 2px 7px; border-radius: 6px;" title="View Notes">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 3px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        <span><?= (int)$c['notes_count'] ?></span>
                                    </a>
                                </div>
                            </td>
                            <td style="font-size: 12px; color: var(--adm-muted);">
                                <?= date('M j, Y', strtotime($c['created_at'])) ?>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                    <?php if (!empty($phoneClean)): ?>
                                        <a href="https://wa.me/<?= $phoneClean ?>" target="_blank" rel="noopener" class="crm-btn-action crm-btn-whatsapp" title="Direct WhatsApp Chat">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                            <span>WhatsApp</span>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= url('/admin/crm/clients/' . $c['id']) ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; min-height: 36px;">
                                        <span>View File</span>
                                        <span style="font-size: 13px;">&rarr;</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
