<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
$activeCategory = $activeCategory ?? null;
$counts = $counts ?? ['all' => 0, 'saas' => 0, 'stores' => 0, 'apps' => 0];
?>

<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h3 class="card-title">Portfolio Projects & Shipped Software</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Manage showcase platforms, SaaS tools, and marketplace templates.</p>
        </div>

        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <!-- Category Filter Tabs -->
            <div class="crm-filter-pills">
                <a href="<?= url('/admin/projects') ?>" class="crm-filter-pill <?= empty($activeCategory) ? 'active' : '' ?>">
                    <span>All Projects</span>
                    <span class="pill-count"><?= $counts['all'] ?></span>
                </a>
                <a href="<?= url('/admin/projects?category=saas') ?>" class="crm-filter-pill <?= $activeCategory === 'saas' ? 'active' : '' ?>">
                    <span>Platforms & SaaS</span>
                    <span class="pill-count"><?= $counts['saas'] ?></span>
                </a>
                <a href="<?= url('/admin/projects?category=stores') ?>" class="crm-filter-pill <?= $activeCategory === 'stores' ? 'active' : '' ?>">
                    <span>Stores & Ordering</span>
                    <span class="pill-count"><?= $counts['stores'] ?></span>
                </a>
                <a href="<?= url('/admin/projects?category=apps') ?>" class="crm-filter-pill <?= $activeCategory === 'apps' ? 'active' : '' ?>">
                    <span>Apps & Tools</span>
                    <span class="pill-count"><?= $counts['apps'] ?></span>
                </a>
            </div>

            <a href="<?= url('/admin/projects/create') ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; min-height: 38px; padding: 8px 16px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>+ Add Project</span>
            </a>
        </div>
    </div>

    <?php if (empty($projects)): ?>
        <div style="padding: 48px 20px; text-align: center; color: var(--muted);">
            <div style="margin-bottom: 12px; display: flex; justify-content: center;">
                <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--muted); opacity: 0.6;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            </div>
            <div style="font-size: 15px; font-weight: 600; color: var(--text);">No projects in this category</div>
            <p style="font-size: 13px; margin-top: 4px;">Click "+ Add Project" to publish a new software showcase.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Category</th>
                        <th>Tech Stack</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $p): ?>
                        <?php
                        $img = $p['image_url'] ?? '';
                        if (empty($img) || str_contains($img, 'creatsify-icon.png')) {
                            $t = strtolower($p['title']);
                            if (str_contains($t, 'cheetah')) {
                                $img = asset('images/cheetah-showcase.jpg');
                            } elseif (str_contains($t, 'restro') || str_contains($t, 'flavor')) {
                                $img = asset('images/restromaster-preview.jpg');
                            } elseif (str_contains($t, 'vidd') || str_contains($t, 'media') || str_contains($t, 'browse')) {
                                $img = asset('images/viddmate-preview.jpg');
                            } else {
                                $img = asset('images/hero-artwork.jpg');
                            }
                        } else {
                            $img = asset($img);
                        }
                        ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?= $img ?>" alt="<?= e($p['title']) ?>" style="width: 52px; height: 34px; object-fit: cover; border-radius: 6px; border: 1px solid var(--line); flex-shrink: 0;">
                                    <div>
                                        <strong style="color:var(--text); font-size:14px;"><?= e($p['title']) ?></strong>
                                        <div style="font-size:11.5px; color:var(--muted); font-family:monospace; margin-top: 1px;">
                                            /portfolio/<?= e($p['slug']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="user-role-badge" style="font-size: 11px;">
                                    <?= e(match($p['category']) {
                                        'saas' => 'Platforms & SaaS',
                                        'stores' => 'Stores & Ordering',
                                        'apps' => 'Apps & Tools',
                                        default => strtoupper($p['category'])
                                    }) ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 4px; flex-wrap: wrap; max-width: 220px;">
                                    <?php foreach (explode(',', $p['tech_stack'] ?: 'PHP') as $tech): ?>
                                        <span style="font-size: 11px; background: var(--bg); color: var(--text); padding: 2px 6px; border-radius: 4px; border: 1px solid var(--line); font-weight: 500;">
                                            <?= e(trim($tech)) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 11px; font-weight: 700; color: #10b981; background: rgba(16, 185, 129, 0.1); padding: 3px 8px; border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.25);">
                                    <?= e($p['status'] ?: 'Shipped') ?>
                                </span>
                            </td>
                            <td>
                                <form action="<?= url('/admin/projects/' . $p['id'] . '/toggle-featured') ?>" method="POST" style="margin: 0;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm" style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px; padding: 3px 8px; border-radius: 12px; <?= $p['featured'] ? 'background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: 700;' : 'background: var(--bg); color: var(--muted); border: 1px solid var(--line);' ?>" title="Click to toggle featured state">
                                        <?php if ($p['featured']): ?>
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                            <span>Featured</span>
                                        <?php else: ?>
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                            <span>Standard</span>
                                        <?php endif; ?>
                                    </button>
                                </form>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                    <?php if (!empty($p['live_url']) && $p['live_url'] !== '#'): ?>
                                        <a href="<?= e($p['live_url']) ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 4px; min-height: 36px; padding: 6px 12px;" title="View Live Demo">
                                            <span>Demo</span>
                                            <span style="font-size: 13px;">&nearr;</span>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= url('/admin/projects/' . $p['id'] . '/edit') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; min-height: 36px; padding: 6px 14px;">Edit</a>
                                    <form action="<?= url('/admin/projects/' . $p['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this project?');" style="margin: 0;">
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
    <?php endif; ?>
</div>
