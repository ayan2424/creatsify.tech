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
            <a href="<?= url('/') ?>" target="_blank" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px; min-height: 36px; padding: 6px 12px;">
                <span>Preview Storefront</span>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            </a>
        </div>
    </div>

    <div class="table-responsive">
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
                                    <button type="submit" class="btn btn-sm" style="display: inline-flex; align-items: center; gap: 5px; background: rgba(0,200,83,0.15); color: #00c853; border: 1px solid rgba(0,200,83,0.3); border-radius: 999px; padding: 4px 10px; font-weight: 700; cursor: pointer; font-size: 11.5px;" title="Click to hide from storefront">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <span>Visible</span>
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-sm" style="display: inline-flex; align-items: center; gap: 5px; background: rgba(255,77,109,0.15); color: #ff4d6d; border: 1px solid rgba(255,77,109,0.3); border-radius: 999px; padding: 4px 10px; font-weight: 700; cursor: pointer; font-size: 11.5px;" title="Click to show on storefront">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                        <span>Hidden</span>
                                    </button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?= url('/admin/sections/' . e($sec['section_key']) . '/edit') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; min-height: 34px; padding: 6px 12px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                <span>Configure</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
