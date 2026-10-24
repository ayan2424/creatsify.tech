<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <img src="<?= asset($member['avatar'] ?: 'brand/creatsify-icon.png') ?>" alt="<?= e($member['name']) ?>" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent);">
            <div>
                <h3 class="card-title"><?= e($member['name']) ?>'s Portfolio Desk</h3>
                <span style="font-size: 12px; color: var(--muted);"><?= e($member['role_title']) ?> &bull; <?= count($items) ?> Project Showcases</span>
            </div>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="<?= url('/team/' . $member['slug']) ?>" target="_blank" class="btn btn-secondary btn-sm">Public Portfolio &nearr;</a>
            <a href="<?= url('/admin/team') ?>" class="btn btn-secondary btn-sm">&larr; Back to Team</a>
            <a href="<?= url('/admin/team/' . $member['id'] . '/portfolio/create') ?>" class="btn btn-primary btn-sm">+ Add Project for <?= e(explode(' ', $member['name'])[0]) ?></a>
        </div>
    </div>

    <?php if (empty($items)): ?>
        <div style="padding: 48px 20px; text-align: center; color: var(--muted);">
            <div style="margin-bottom: 12px; display: flex; justify-content: center;">
                <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--muted); opacity: 0.6;"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            </div>
            <div style="font-size: 15px; font-weight: 600; color: var(--text);">No individual projects added yet</div>
            <p style="font-size: 13px; margin-top: 4px;">Showcase case studies, architectures, or designs crafted by <?= e($member['name']) ?>.</p>
            <a href="<?= url('/admin/team/' . $member['id'] . '/portfolio/create') ?>" class="btn btn-primary btn-sm" style="margin-top: 14px;">+ Add First Project</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Category & Role</th>
                        <th>Tech Stack</th>
                        <th>Year</th>
                        <th>Featured</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?= asset($item['image_url'] ?: 'images/hero-artwork.jpg') ?>" alt="<?= e($item['title']) ?>" style="width: 54px; height: 36px; border-radius: 6px; object-fit: cover; border: 1px solid var(--line); flex-shrink: 0;">
                                    <div>
                                        <strong style="color:var(--text); font-size:14px;"><?= e($item['title']) ?></strong>
                                        <?php if (!empty($item['client'])): ?>
                                            <div style="font-size:11.5px; color:var(--muted);">Client: <?= e($item['client']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="user-role-badge" style="font-size: 11px;"><?= e($item['category']) ?></span>
                                <?php if (!empty($item['role_in_project'])): ?>
                                    <div style="font-size: 11px; color: var(--muted); margin-top: 2px;"><?= e($item['role_in_project']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 4px; flex-wrap: wrap; max-width: 220px;">
                                    <?php foreach (explode(',', $item['tech_stack'] ?: '') as $tech): ?>
                                        <?php if (trim($tech)): ?>
                                            <span style="font-size: 10.5px; background: var(--bg); color: var(--text); padding: 2px 6px; border-radius: 4px; border: 1px solid var(--line);">
                                                <?= e(trim($tech)) ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td style="font-size: 12px; color: var(--muted); font-weight: 500;">
                                <?= e($item['completion_date'] ?: '—') ?>
                            </td>
                            <td>
                                <form action="<?= url('/admin/team/' . $member['id'] . '/portfolio/' . $item['id'] . '/toggle-featured') ?>" method="POST" style="margin: 0;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm" style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px; padding: 3px 8px; border-radius: 12px; <?= $item['is_featured'] ? 'background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: 700;' : 'background: var(--bg); color: var(--muted); border: 1px solid var(--line);' ?>" title="Click to toggle featured state">
                                        <?php if ($item['is_featured']): ?>
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                            <span>Featured</span>
                                        <?php else: ?>
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                            <span>Standard</span>
                                        <?php endif; ?>
                                    </button>
                                </form>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px; justify-content: flex-end;">
                                    <?php if (!empty($item['project_url']) && $item['project_url'] !== '#'): ?>
                                        <a href="<?= e($item['project_url']) ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="font-size: 11.5px; padding: 4px 8px;" title="View Live Project">
                                            Live &nearr;
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= url('/admin/team/' . $member['id'] . '/portfolio/' . $item['id'] . '/edit') ?>" class="btn btn-secondary btn-sm" style="font-size: 11.5px; padding: 4px 10px;">Edit</a>
                                    <form action="<?= url('/admin/team/' . $member['id'] . '/portfolio/' . $item['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this portfolio item?');" style="margin: 0;">
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
    <?php endif; ?>
</div>
