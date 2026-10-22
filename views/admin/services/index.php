<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Engineering Services</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Manage service offerings and key deliverables.</p>
        </div>
        <a href="<?= url('/admin/services/create') ?>" class="btn btn-primary btn-sm">+ Add Service</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Deliverables</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $s): ?>
                <tr>
                    <td>
                        <strong style="color:var(--text); font-size:14px;"><?= e($s['title']) ?></strong>
                        <div style="font-size:12px; color:var(--muted);"><?= e($s['short_description']) ?></div>
                    </td>
                    <td style="font-size: 13px; color: var(--muted);"><?= e($s['deliverables']) ?></td>
                    <td>
                        <span class="user-role-badge" style="background: rgba(0,200,83,0.15); color: #00c853;"><?= $s['is_active'] ? 'Active' : 'Inactive' ?></span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="<?= url('/admin') ?>/services/<?= $s['id'] ?>/edit" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="<?= url('/admin') ?>/services/<?= $s['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this service?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--danger);">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
