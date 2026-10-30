<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Studio Services', 'meta_title' => 'Services | Creatsify Admin']);
?>

<div class="content-header">
    <div>
        <h1 class="page-title">Studio Engineering Services</h1>
        <p class="page-subtitle">Manage custom software engineering offerings, architectural scopes, and deliverables.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <a href="<?= url('/services') ?>" target="_blank" class="btn btn-secondary" style="min-height: 40px; padding: 8px 16px; display: inline-flex; align-items: center; gap: 6px;">
            <span>Public Services</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        </a>
        <a href="<?= url('/admin/services/create') ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; min-height: 40px; padding: 8px 18px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Add Service</span>
        </a>
    </div>
</div>

<div class="card">

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Service Name & Scope</th>
                    <th>Core Deliverables</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $s): ?>
                    <tr>
                        <td>
                            <strong style="color:var(--text); font-size:14px;"><?= e($s['title']) ?></strong>
                            <div style="font-size:12px; color:var(--muted); margin-top: 2px; max-width: 380px;">
                                <?= e($s['short_description']) ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 12.5px; color: var(--muted); max-width: 280px;">
                                <?= e($s['deliverables']) ?>
                            </div>
                        </td>
                        <td>
                            <form action="<?= url('/admin/services/' . $s['id'] . '/toggle') ?>" method="POST" style="margin: 0;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm" style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; padding: 4px 10px; border-radius: 12px; <?= $s['is_active'] ? 'background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 700;' : 'background: rgba(163, 167, 187, 0.12); color: #a3a7bb; border: 1px solid var(--line);' ?>" title="Click to toggle active state">
                                    <?php if ($s['is_active']): ?>
                                        <svg width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                                        <span>Active</span>
                                    <?php else: ?>
                                        <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/></svg>
                                        <span>Inactive</span>
                                    <?php endif; ?>
                                </button>
                            </form>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                <a href="<?= url('/admin/services/' . $s['id'] . '/edit') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; min-height: 36px; padding: 6px 14px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    <span>Edit</span>
                                </a>
                                <form action="<?= url('/admin/services/' . $s['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this service?');" style="margin: 0;">
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
</div>
