<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h3 class="card-title">Agency Creators & Team Portfolios</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Manage agency staff, specialists, and individual team member showcases.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="<?= url('/team') ?>" target="_blank" class="btn btn-secondary btn-sm">View Public Team &nearr;</a>
            <a href="<?= url('/admin/team/create') ?>" class="btn btn-primary btn-sm">+ Add Team Member</a>
        </div>
    </div>

    <?php if (empty($members)): ?>
        <div style="padding: 48px 20px; text-align: center; color: var(--muted);">
            <div style="font-size: 32px; margin-bottom: 8px;">👥</div>
            <div style="font-size: 15px; font-weight: 600; color: var(--text);">No agency team members yet</div>
            <p style="font-size: 13px; margin-top: 4px;">Add your engineers, UI/UX designers, and specialists to display their personal portfolios.</p>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Member Profile</th>
                    <th>Role & Specialization</th>
                    <th>Tech Stack / Skills</th>
                    <th>Portfolio Items</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $m): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="<?= asset($m['avatar'] ?: 'brand/creatsify-icon.png') ?>" alt="<?= e($m['name']) ?>" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid var(--line); flex-shrink: 0;">
                                <div>
                                    <strong style="color:var(--text); font-size:14px;"><?= e($m['name']) ?></strong>
                                    <div style="font-size:11.5px; color:var(--muted); font-family:monospace; margin-top: 1px;">
                                        /team/<?= e($m['slug']) ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong style="color: var(--text); font-size: 13px; display: block;"><?= e($m['role_title']) ?></strong>
                            <?php if (!empty($m['email'])): ?>
                                <span style="font-size: 11.5px; color: var(--muted);"><?= e($m['email']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 4px; flex-wrap: wrap; max-width: 220px;">
                                <?php foreach (explode(',', $m['skills'] ?: '') as $skill): ?>
                                    <?php if (trim($skill)): ?>
                                        <span style="font-size: 10.5px; background: var(--bg); color: var(--text); padding: 2px 6px; border-radius: 4px; border: 1px solid var(--line);">
                                            <?= e(trim($skill)) ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </td>
                        <td>
                            <a href="<?= url('/admin/team/' . $m['id'] . '/portfolio') ?>" class="btn btn-secondary btn-sm" style="font-size: 11.5px; padding: 4px 10px; font-weight: 600;">
                                🎨 <?= (int)($m['portfolio_count'] ?? 0) ?> Projects &rarr;
                            </a>
                        </td>
                        <td>
                            <form action="<?= url('/admin/team/' . $m['id'] . '/toggle') ?>" method="POST" style="margin: 0;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm" style="font-size: 11px; padding: 3px 8px; border-radius: 12px; <?= $m['is_active'] ? 'background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 700;' : 'background: rgba(163, 167, 187, 0.12); color: #a3a7bb; border: 1px solid var(--line);' ?>" title="Click to toggle member active status">
                                    <?= $m['is_active'] ? '● Active' : '○ Inactive' ?>
                                </button>
                            </form>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 6px; justify-content: flex-end;">
                                <a href="<?= url('/team/' . $m['slug']) ?>" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 11.5px; padding: 4px 8px;" title="View Public Portfolio">
                                    View &nearr;
                                </a>
                                <a href="<?= url('/admin/team/' . $m['id'] . '/edit') ?>" class="btn btn-secondary btn-sm" style="font-size: 11.5px; padding: 4px 10px;">Edit</a>
                                <form action="<?= url('/admin/team/' . $m['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Remove this team member and all their individual portfolio projects?');" style="margin: 0;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--danger); font-size: 11.5px; padding: 4px 8px;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
