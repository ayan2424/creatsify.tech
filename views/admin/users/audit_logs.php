<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Security & Audit Logs</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Immutable security log tracking user actions, logins, updates, and dispatches.</p>
        </div>
        <a href="<?= url('/admin/users') ?>" class="btn btn-secondary btn-sm">&larr; Back to Users</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>Operator</th>
                <th>Action</th>
                <th>Entity</th>
                <th>IP & Client</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td style="font-size: 12px; color: var(--muted); white-space: nowrap;">
                        <?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?>
                    </td>
                    <td>
                        <strong style="color:var(--text);"><?= e($log['user_name'] ?: 'System / Visitor') ?></strong>
                    </td>
                    <td>
                        <span class="user-role-badge"><?= e($log['action']) ?></span>
                    </td>
                    <td style="font-size: 12px; color: var(--muted); font-family: monospace;">
                        <?= e($log['entity_type'] ? $log['entity_type'] . '#' . $log['entity_id'] : '—') ?>
                    </td>
                    <td style="font-size: 12px; color: var(--muted);">
                        <?= e($log['ip_address']) ?>
                    </td>
                    <td style="font-size: 12px; color: var(--text); max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <?= e($log['details']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
