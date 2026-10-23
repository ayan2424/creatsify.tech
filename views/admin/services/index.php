<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h3 class="card-title">Studio Engineering Services</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Manage custom software engineering offerings, architectural scopes, and deliverables.</p>
        </div>
        <a href="<?= url('/admin/services/create') ?>" class="btn btn-primary btn-sm">+ Add Service</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Service Name & Scope</th>
                <th>Core Deliverables</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $s): ?>
                <tr>
                    <td>
                        <strong style="color:var(--text); font-size:14px;"><?= e($s['title']) ?></strong>
                        <div style="font-size:12px; color:var(--muted); margin-top: 2px; max-width: 380px;">
                            <?= e($s['short_description']) ?>
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 12.5px; color: var(--muted); max-width: 280px;">
                            <?= e($s['deliverables']) ?>
                        </div>
                    </td>
                    <td>
                        <form action="<?= url('/admin/services/' . $s['id'] . '/toggle') ?>" method="POST" style="margin: 0;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm" style="font-size: 11px; padding: 3px 8px; border-radius: 12px; <?= $s['is_active'] ? 'background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 700;' : 'background: rgba(163, 167, 187, 0.12); color: #a3a7bb; border: 1px solid var(--line);' ?>" title="Click to toggle active state">
                                <?= $s['is_active'] ? '● Active' : '○ Inactive' ?>
                            </button>
                        </form>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 6px; justify-content: flex-end;">
                            <a href="<?= url('/admin/services/' . $s['id'] . '/edit') ?>" class="btn btn-secondary btn-sm" style="font-size: 11.5px; padding: 4px 10px;">Edit</a>
                            <form action="<?= url('/admin/services/' . $s['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this service?');" style="margin: 0;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--danger); font-size: 11.5px; padding: 4px 8px;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
