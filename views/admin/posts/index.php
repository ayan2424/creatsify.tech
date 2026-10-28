<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h3 class="card-title">Blog Articles & Technical Notes</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Publish engineering essays, release notes, and studio case studies.</p>
        </div>
        <a href="<?= url('/admin/posts/create') ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; min-height: 38px; padding: 8px 16px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>+ New Post</span>
        </a>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title & Slug</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $p): ?>
                    <tr>
                        <td>
                            <strong style="color:var(--text); font-size:14px;"><?= e($p['title']) ?></strong>
                            <div style="font-size:11.5px; color:var(--muted); font-family:monospace; margin-top: 1px;">
                                /blog/<?= e($p['slug']) ?>
                            </div>
                        </td>
                        <td>
                            <span class="user-role-badge" style="font-size: 11px;"><?= e($p['category']) ?></span>
                        </td>
                        <td style="font-size: 13px; color: var(--text);">
                            <?= e($p['author_name'] ?: 'Ayan') ?>
                        </td>
                        <td>
                            <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 600; color: var(--accent); background: var(--bg); padding: 2px 7px; border-radius: 6px; border: 1px solid var(--line);">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <span><?= number_format((int)$p['views_count']) ?></span>
                            </span>
                        </td>
                        <td>
                            <form action="<?= url('/admin/posts/' . $p['id'] . '/toggle') ?>" method="POST" style="margin: 0;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm" style="font-size: 11px; padding: 4px 10px; border-radius: 12px; <?= $p['is_published'] ? 'background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 700;' : 'background: rgba(163, 167, 187, 0.12); color: #a3a7bb; border: 1px solid var(--line);' ?>" title="Click to toggle published state">
                                    <?= $p['is_published'] ? '● Live' : '○ Draft' ?>
                                </button>
                            </form>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                <?php if ($p['is_published']): ?>
                                    <a href="<?= url('/blog/' . $p['slug']) ?>" target="_blank" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 4px; min-height: 36px; padding: 6px 12px;" title="View Live Post">
                                        <span>View</span>
                                        <span style="font-size: 13px;">&nearr;</span>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= url('/admin/posts/' . $p['id'] . '/edit') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; min-height: 36px; padding: 6px 14px;">Edit</a>
                                <form action="<?= url('/admin/posts/' . $p['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this post?');" style="margin: 0;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color: #ef4444; min-height: 36px; padding: 6px 12px;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
