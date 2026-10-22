<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Homepage Section Builder</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Manage and customize every section of your portfolio homepage in real time.</p>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Section Key</th>
                <th>Title & Subtitle</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sections as $sec): ?>
                <tr>
                    <td style="font-weight: 700; color: var(--accent);"><?= (int)$sec['sort_order'] ?></td>
                    <td>
                        <strong style="color:var(--text); font-family:monospace;"><?= e($sec['section_key']) ?></strong>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text);"><?= e($sec['title']) ?></div>
                        <div style="font-size: 12px; color: var(--muted);"><?= e($sec['subtitle']) ?></div>
                    </td>
                    <td>
                        <?php if ($sec['is_active']): ?>
                            <span class="user-role-badge" style="background: rgba(0,200,83,0.15); color: #00c853;">Active</span>
                        <?php else: ?>
                            <span class="user-role-badge" style="background: rgba(255,77,109,0.15); color: #ff4d6d;">Hidden</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= url('/admin') ?>/sections/<?= e($sec['section_key']) ?>/edit" class="btn btn-secondary btn-sm">Edit Section &rarr;</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
