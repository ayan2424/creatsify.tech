<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
$actionFilter = $actionFilter ?? '';
$search = $search ?? '';
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$total = $total ?? 0;
?>

<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h3 class="card-title">Security & Audit Logs</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">
                Immutable security trail tracking user authentications, data mutations, and dispatches (<?= number_format($total) ?> total recorded events).
            </p>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="<?= url('/admin/users') ?>" class="btn btn-secondary btn-sm"style="display: inline-flex; align-items: center; gap: 6px; min-height: 36px; padding: 6px 12px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg><span>Back to Users</span></a>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div style="padding: 16px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; background: var(--bg);">
        <!-- Quick Action Category Filter Tabs -->
        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
            <a href="<?= url('/admin/audit-logs') ?>" class="btn btn-sm <?= empty($actionFilter) ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: 20px; font-size: 12px; padding: 4px 12px;">
                All Events
            </a>
            <a href="<?= url('/admin/audit-logs?action=login') ?>" class="btn btn-sm <?= $actionFilter === 'login' ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: 20px; font-size: 12px; padding: 4px 12px;">
                Logins
            </a>
            <a href="<?= url('/admin/audit-logs?action=create') ?>" class="btn btn-sm <?= $actionFilter === 'create' ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: 20px; font-size: 12px; padding: 4px 12px;">
                Creates
            </a>
            <a href="<?= url('/admin/audit-logs?action=update') ?>" class="btn btn-sm <?= $actionFilter === 'update' ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: 20px; font-size: 12px; padding: 4px 12px;">
                Updates
            </a>
            <a href="<?= url('/admin/audit-logs?action=delete') ?>" class="btn btn-sm <?= $actionFilter === 'delete' ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: 20px; font-size: 12px; padding: 4px 12px;">
                Deletions
            </a>
        </div>

        <!-- Search Form -->
        <form action="<?= url('/admin/audit-logs') ?>" method="GET" style="display: flex; gap: 8px; margin: 0;">
            <?php if (!empty($actionFilter)): ?>
                <input type="hidden" name="action" value="<?= e($actionFilter) ?>">
            <?php endif; ?>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search IP, details, entity..." value="<?= e($search) ?>" style="width: 220px; font-size: 12px;">
            <button type="submit" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; padding: 4px 10px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <span>Search</span>
            </button>
            <?php if (!empty($search) || !empty($actionFilter)): ?>
                <a href="<?= url('/admin/audit-logs') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; padding: 0; font-size: 12px; color: var(--muted);" title="Reset filters">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($logs)): ?>
        <div style="padding: 48px 20px; text-align: center; color: var(--muted);">
            <div style="margin-bottom: 12px; display: flex; justify-content: center;">
                <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--muted); opacity: 0.6;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div style="font-size: 15px; font-weight: 600; color: var(--text);">No security logs found</div>
            <p style="font-size: 13px; margin-top: 4px;">No security actions match your filter criteria.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Operator</th>
                        <th>Action</th>
                        <th>Target Entity</th>
                        <th>IP Address</th>
                        <th>Event Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <?php
                        $act = strtolower($log['action'] ?? '');
                        $badgeStyle = match(true) {
                            str_contains($act, 'failed') || str_contains($act, 'delete') => 'background: rgba(239, 68, 68, 0.12); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);',
                            str_contains($act, 'success') || str_contains($act, 'create') || str_contains($act, 'login') => 'background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);',
                            str_contains($act, 'update') || str_contains($act, 'toggle') || str_contains($act, 'edit') => 'background: rgba(139, 123, 255, 0.12); color: #8b7bff; border: 1px solid rgba(139, 123, 255, 0.3);',
                            str_contains($act, 'send') || str_contains($act, 'sms') || str_contains($act, 'whatsapp') => 'background: rgba(6, 182, 212, 0.12); color: #06b6d4; border: 1px solid rgba(6, 182, 212, 0.3);',
                            default => 'background: var(--surface-hover); color: var(--muted); border: 1px solid var(--line);',
                        };
                        ?>
                        <tr>
                            <td style="font-size: 12px; color: var(--muted); white-space: nowrap;">
                                <?= date('M j, Y', strtotime($log['created_at'])) ?>
                                <div style="font-size: 11px; opacity: 0.7;"><?= date('H:i:s', strtotime($log['created_at'])) ?></div>
                            </td>
                            <td>
                                <strong style="color:var(--text); font-size: 13.5px;"><?= e($log['user_name'] ?: 'System / Anonymous') ?></strong>
                                <?php if (!empty($log['user_email'])): ?>
                                    <div style="font-size: 11px; color: var(--muted);"><?= e($log['user_email']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="display: inline-block; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; <?= $badgeStyle ?>">
                                    <?= e(str_replace('_', ' ', $log['action'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($log['entity_type'])): ?>
                                    <span style="font-size: 11.5px; font-family: monospace; background: var(--bg); padding: 3px 6px; border-radius: 4px; border: 1px solid var(--line); color: var(--text);">
                                        <?= e($log['entity_type']) ?><?= !empty($log['entity_id']) ? '#' . e((string)$log['entity_id']) : '' ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--muted); font-size: 12px;">&mdash;</span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size: 12px; color: var(--muted); font-family: monospace;">
                                <?= e($log['ip_address'] ?? '—') ?>
                            </td>
                            <td style="font-size: 12.5px; color: var(--text); max-width: 320px;">
                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= e($log['details']) ?>">
                                    <?= e($log['details']) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <?php if ($totalPages > 1): ?>
            <div style="padding: 16px 24px; border-top: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: var(--surface);">
                <div style="font-size: 12px; color: var(--muted);">
                    Page <?= $page ?> of <?= $totalPages ?>
                </div>

                <div style="display: flex; gap: 8px;">
                    <?php if ($page > 1): ?>
                        <a href="<?= url('/admin/audit-logs?page=' . ($page - 1) . (!empty($actionFilter) ? '&action=' . urlencode($actionFilter) : '') . (!empty($search) ? '&search=' . urlencode($search) : '')) ?>" class="btn btn-secondary btn-sm" style="font-size: 12px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> <span>Previous</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?= url('/admin/audit-logs?page=' . ($page + 1) . (!empty($actionFilter) ? '&action=' . urlencode($actionFilter) : '') . (!empty($search) ? '&search=' . urlencode($search) : '')) ?>" class="btn btn-secondary btn-sm" style="font-size: 12px;">
                            <span>Next</span> <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
