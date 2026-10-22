<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Blog Articles & Technical Notes</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Publish engineering essays, release notes, and studio case studies.</p>
        </div>
        <a href="<?= url('/admin/posts/create') ?>" class="btn btn-primary btn-sm">+ New Post</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Title & Slug</th>
                <th>Category</th>
                <th>Author</th>
                <th>Views</th>
                <th>Published</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $p): ?>
                <tr>
                    <td>
                        <strong style="color:var(--text); font-size:14px;"><?= e($p['title']) ?></strong>
                        <div style="font-size:12px; color:var(--muted); font-family:monospace;">/blog/<?= e($p['slug']) ?></div>
                    </td>
                    <td><span class="user-role-badge"><?= e($p['category']) ?></span></td>
                    <td style="font-size: 13px; color: var(--text);"><?= e($p['author_name'] ?: 'Ayan') ?></td>
                    <td style="font-weight: 600; color: var(--accent);"><?= (int)$p['views_count'] ?></td>
                    <td>
                        <span class="user-role-badge" style="background: rgba(0,200,83,0.15); color: #00c853;"><?= $p['is_published'] ? 'Live' : 'Draft' ?></span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="<?= url('/admin') ?>/posts/<?= $p['id'] ?>/edit" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="<?= url('/admin') ?>/posts/<?= $p['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this post?');">
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
