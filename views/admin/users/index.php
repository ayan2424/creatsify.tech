<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">User & Role Management</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Role-based access controls for Super Admins, Admins, and Editors.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="<?= url('/admin/audit-logs') ?>" class="btn btn-secondary btn-sm">View Audit Trail</a>
            <a href="<?= url('/admin/users/create') ?>" class="btn btn-primary btn-sm">+ New User</a>
        </div>
    </div>

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
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="<?= url('/admin') ?>/users/<?= $u['id'] ?>/edit" class="btn btn-secondary btn-sm">Edit</a>
                                <?php if ((int)$u['id'] !== auth_id()): ?>
                                    <form action="<?= url('/admin') ?>/users/<?= $u['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this user account?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--danger);">Delete</button>
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
