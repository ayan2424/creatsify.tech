<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Work & Software Portfolio', 'meta_title' => 'Work & Software | Creatsify Admin']);
$activeCategory = $activeCategory ?? null;
$counts = $counts ?? ['all' => 0, 'software' => 0, 'client_work' => 0, 'saas' => 0, 'stores' => 0, 'apps' => 0];
?>

<div class="content-header">
    <div>
        <h1 class="page-title">Work &amp; Software Portfolio</h1>
        <p class="page-subtitle">Publish and manage studio client case studies and ready-made commercial software platforms sold on Gumroad.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <a href="<?= url('/software') ?>" target="_blank" class="btn btn-secondary" style="min-height: 40px; padding: 8px 16px; display: inline-flex; align-items: center; gap: 6px;">
            <span>Software Store</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        </a>
        <a href="<?= url('/portfolio') ?>" target="_blank" class="btn btn-secondary" style="min-height: 40px; padding: 8px 16px; display: inline-flex; align-items: center; gap: 6px;">
            <span>Work Showcase</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        </a>
        <a href="<?= url('/admin/projects/create') ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; min-height: 40px; padding: 8px 18px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Add Work / Software</span>
        </a>
    </div>
</div>

<div class="card">
    <div class="crm-filter-bar">
        <div class="crm-filter-pills" style="flex-wrap: wrap;">
            <a href="<?= url('/admin/projects') ?>" class="crm-filter-pill <?= empty($activeCategory) ? 'active' : '' ?>">
                <span>All Items</span>
                <span class="pill-count"><?= $counts['all'] ?? 0 ?></span>
            </a>
            <a href="<?= url('/admin/projects?category=software') ?>" class="crm-filter-pill <?= $activeCategory === 'software' ? 'active' : '' ?>">
                <span>Ready-Made Software (Tag)</span>
                <span class="pill-count"><?= $counts['software'] ?? 0 ?></span>
            </a>
            <a href="<?= url('/admin/projects?category=client-work') ?>" class="crm-filter-pill <?= $activeCategory === 'client-work' ? 'active' : '' ?>">
                <span>Client Case Studies (Tag)</span>
                <span class="pill-count"><?= $counts['client_work'] ?? 0 ?></span>
            </a>
            <?php foreach (\App\Models\Project::CATEGORIES as $catKey => $catLabel): ?>
                <a href="<?= url('/admin/projects?category=' . $catKey) ?>" class="crm-filter-pill <?= $activeCategory === $catKey ? 'active' : '' ?>">
                    <span><?= e($catLabel) ?></span>
                    <span class="pill-count"><?= $counts[$catKey] ?? 0 ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (empty($projects)): ?>
        <div style="padding: 48px 20px; text-align: center; color: var(--muted);">
            <div style="margin-bottom: 12px; display: flex; justify-content: center;">
                <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--muted); opacity: 0.6;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            </div>
            <div style="font-size: 15px; font-weight: 600; color: var(--text);">No projects in this category</div>
            <p style="font-size: 13px; margin-top: 4px;">Click "+ Add Work / Software" to publish a new showcase item.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Project &amp; Specialty</th>
                        <th>Type / Channel</th>
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
                            } elseif (str_contains($t, 'apex')) {
                                $img = asset('images/apex-preview.jpg');
                            } elseif (str_contains($t, 'lumina')) {
                                $img = asset('images/lumina-preview.jpg');
                            } elseif (str_contains($t, 'kinetix')) {
                                $img = asset('images/kinetix-preview.jpg');
                            } else {
                                $img = asset('images/hero-artwork.jpg');
                            }
                        } else {
                            $img = asset($img);
                        }
                        $isSoftware = !empty($p['is_software']) || ($p['status'] ?? '') === 'For sale';
                        $catName = \App\Models\Project::categoryLabel($p['category']);
                        $subcat = !empty($p['subcategory']) ? $p['subcategory'] : $catName;
                        ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?= $img ?>" alt="<?= e($p['title']) ?>" style="width: 52px; height: 34px; object-fit: cover; border-radius: 6px; border: 1px solid var(--line); flex-shrink: 0;">
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <strong style="color:var(--text); font-size:14px;"><?= e($p['title']) ?></strong>
                                            <span style="font-size: 10.5px; font-weight: 600; padding: 2px 7px; border-radius: 999px; background: rgba(0, 210, 238, 0.1); color: var(--accent); border: 1px solid rgba(0, 210, 238, 0.25);">
                                                <?= e($subcat) ?>
                                            </span>
                                        </div>
                                        <div style="font-size:11.5px; color:var(--muted); font-family:monospace; margin-top: 2px;">
                                            /portfolio/<?= e($p['slug']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($isSoftware): ?>
                                    <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; color: #f59e0b; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); padding: 3px 9px; border-radius: 999px;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                        <span>Ready-Made Software (<?= e($p['price'] ?: '$99') ?>)</span>
                                    </span>
                                <?php else: ?>
                                    <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 600; color: #3b82f6; background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.3); padding: 3px 9px; border-radius: 999px;">
                                        <span>Client Case Study</span>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 4px; flex-wrap: wrap; max-width: 200px;">
                                    <?php foreach (explode(',', $p['tech_stack'] ?: 'PHP') as $tech): ?>
                                        <span style="font-size: 11px; background: var(--bg); color: var(--text); padding: 2px 6px; border-radius: 4px; border: 1px solid var(--line); font-weight: 500;">
                                            <?= e(trim($tech)) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 11px; font-weight: 700; color: #10b981; background: rgba(16, 185, 129, 0.1); padding: 3px 8px; border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.25);">
                                    <?= e($p['status'] ?: 'Delivered') ?>
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
                                        <a href="<?= e($p['live_url']) ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; min-height: 36px; padding: 6px 12px;" title="View Live Demo">
                                            <span>Demo</span>
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= url('/admin/projects/' . $p['id'] . '/edit') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; min-height: 36px; padding: 6px 14px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        <span>Edit</span>
                                    </a>
                                    <form action="<?= url('/admin/projects/' . $p['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this project?');" style="margin: 0;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; color: #ef4444; min-height: 36px; padding: 6px 12px;">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            <span>Delete</span>
                                        </button>
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
