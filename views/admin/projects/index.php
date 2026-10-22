<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Portfolio Projects & Shipped Software</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Manage showcase platforms, SaaS tools, and marketplace templates.</p>
        </div>
        <a href="<?= url('/admin/projects/create') ?>" class="btn btn-primary btn-sm">+ Add Project</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Title & Slug</th>
                <th>Category</th>
                <th>Tech Stack</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projects as $p): ?>
                <tr>
                    <td>
                        <strong style="color:var(--text); font-size:14px;"><?= e($p['title']) ?></strong>
                        <div style="font-size:12px; color:var(--muted); font-family:monospace;">/portfolio/<?= e($p['slug']) ?></div>
                    </td>
                    <td>
                        <span class="user-role-badge"><?= strtoupper(e($p['category'])) ?></span>
                    </td>
                    <td style="font-size: 13px; color: var(--muted);"><?= e($p['tech_stack']) ?></td>
                    <td>
                        <span style="font-size: 12px; font-weight: 600; color: #00c853;"><?= e($p['status']) ?></span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="<?= url('/admin') ?>/projects/<?= $p['id'] ?>/edit" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="<?= url('/admin') ?>/projects/<?= $p['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this project?');">
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
