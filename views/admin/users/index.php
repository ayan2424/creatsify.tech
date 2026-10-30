<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Users & Roles', 'meta_title' => 'Users | Creatsify Admin']);
?>

<div class="content-header">
    <div>
        <h1 class="page-title">User & Role Management</h1>
        <p class="page-subtitle">Role-based access controls for Super Admins, Admins, and Editors.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <a href="<?= url('/admin/audit-logs') ?>" class="btn btn-secondary" style="min-height: 40px; padding: 8px 16px;">View Audit Trail</a>
        <a href="<?= url('/admin/users/create') ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; min-height: 40px; padding: 8px 18px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>New User</span>
        </a>
    </div>
</div>

<div class="card">

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <strong style="color:var(--text); font-size:14px;"><?= e($u['name']) ?></strong>
                            <div style="font-size:12px; color:var(--muted);"><?= e($u['email']) ?></div>
                        </td>
                        <td>
                            <span class="user-role-badge"><?= strtoupper(e($u['role'])) ?></span>
                        </td>
                        <td style="font-size:13px; color:var(--muted);"><?= e($u['phone'] ?: '&mdash;') ?></td>
                        <td>
                            <span class="user-role-badge" style="background: rgba(0,200,83,0.15); color: #00c853;"><?= e($u['status']) ?></span>
                        </td>
                        <td style="font-size:12px; color:var(--muted);"><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
                        <td style="white-space: nowrap;">
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <a href="<?= url('/admin/users/' . $u['id'] . '/edit') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; min-height: 36px; padding: 6px 14px;">Edit</a>
                                <?php if ((int)$u['id'] !== auth_id()): ?>
                                    <form action="<?= url('/admin/users/' . $u['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this user account?');" style="margin: 0;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-secondary btn-sm" style="color: #ef4444; min-height: 36px; padding: 6px 12px;">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
