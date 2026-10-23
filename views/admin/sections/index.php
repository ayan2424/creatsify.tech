<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Homepage Section Builder</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Cheezify-inspired visual section manager. Show, hide, reorder, and configure storefront homepage blocks.</p>
        </div>
        <div>
            <a href="<?= url('/') ?>" target="_blank" class="btn btn-secondary btn-sm" style="display:flex; align-items:center; gap:6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                <span>Preview Storefront &rarr;</span>
            </a>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 70px;">Order</th>
                <th>Section Identifier</th>
                <th>Live Title & Subtitle</th>
                <th style="width: 120px;">Visibility</th>
                <th style="width: 160px; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sections as $sec): ?>
                <tr>
                    <td style="font-weight: 700; color: var(--accent); font-size: 14px;">#<?= (int)$sec['sort_order'] ?></td>
                    <td>
                        <strong style="color:var(--text); font-family:monospace; font-size: 13px;"><?= e($sec['section_key']) ?></strong>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text); font-size: 13.5px;"><?= e($sec['title']) ?></div>
                        <div style="font-size: 12px; color: var(--muted); margin-top: 2px;"><?= e($sec['subtitle'] ?: 'Default block') ?></div>
                    </td>
                    <td>
                        <form action="<?= url('/admin/sections/' . $sec['section_key'] . '/toggle') ?>" method="POST" style="display:inline;">
                            <?= csrf_field() ?>
                            <?php if ($sec['is_active']): ?>
                                <button type="submit" class="btn btn-sm" style="background: rgba(0,200,83,0.15); color: #00c853; border: 1px solid rgba(0,200,83,0.3); border-radius: 999px; padding: 3px 10px; font-weight: 700; cursor: pointer; font-size: 11.5px;" title="Click to hide from storefront">
                                    &bull; Visible
                                </button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-sm" style="background: rgba(255,77,109,0.15); color: #ff4d6d; border: 1px solid rgba(255,77,109,0.3); border-radius: 999px; padding: 3px 10px; font-weight: 700; cursor: pointer; font-size: 11.5px;" title="Click to show on storefront">
                                    &times; Hidden
                                </button>
                            <?php endif; ?>
                        </form>
                    </td>
                    <td style="text-align: right;">
                        <a href="<?= url('/admin/sections/' . e($sec['section_key']) . '/edit') ?>" class="btn btn-secondary btn-sm" style="font-size: 12px;">
                            Configure &rarr;
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
