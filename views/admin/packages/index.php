<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Service Packages & Pricing', 'meta_title' => 'Service Packages | Creatsify Admin']);
$trackMeta = $tracks ?? [];
?>

<div class="content-header">
    <div>
        <h1 class="page-title">Service Packages &amp; Pricing Manager</h1>
        <p class="page-subtitle">Manage the packages displayed on the Homepage (#packages) and the public /packages page across Web/App Dev, Branding/Redesign, and SEO/Ads tracks.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <a href="<?= url('/packages') ?>" target="_blank" class="btn btn-secondary" style="min-height: 40px; padding: 8px 16px; display: inline-flex; align-items: center; gap: 6px;">
            <span>View Public /packages</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        </a>
        <a href="<?= url('/admin/packages/create') ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; min-height: 40px; padding: 8px 18px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Add New Package</span>
        </a>
    </div>
</div>

<!-- Track Filter Bar -->
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;">
    <a href="<?= url('/admin/packages') ?>" class="btn btn-sm <?= ($trackFilter ?? 'all') === 'all' ? 'btn-primary' : 'btn-secondary' ?>" style="padding:7px 14px;border-radius:999px;">
        All Tracks (<?= count($allPackages ?? []) ?>)
    </a>
    <?php foreach ($trackMeta as $tKey => $tInfo): ?>
        <a href="<?= url('/admin/packages?track=' . urlencode($tKey)) ?>" class="btn btn-sm <?= ($trackFilter ?? '') === $tKey ? 'btn-primary' : 'btn-secondary' ?>" style="padding:7px 14px;border-radius:999px;">
            <?= e($tInfo['short'] ?? $tKey) ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Package Title &amp; Track</th>
                    <th>Price &amp; Billing</th>
                    <th>Delivery &amp; Features</th>
                    <th>Highlight</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($packages)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center;padding:36px;color:var(--muted);">No service packages found in this track.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($packages as $pkg):
                    $tShort = $trackMeta[$pkg['track']]['short'] ?? strtoupper($pkg['track']);
                    $feats = \App\Models\ServicePackage::parseFeatures($pkg['features'] ?? '');
                ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                <strong style="color:var(--text); font-size:14.5px;"><?= e($pkg['title']) ?></strong>
                                <span style="font-size:11px;padding:2px 8px;border-radius:999px;background:rgba(37,99,235,0.12);color:#3b82f6;font-weight:600;">
                                    <?= e($tShort) ?>
                                </span>
                            </div>
                            <div style="font-size:12px; color:var(--muted); margin-top: 4px; max-width: 360px;">
                                <?= e($pkg['subtitle']) ?>
                            </div>
                        </td>
                        <td>
                            <strong style="font-size:16px;color:var(--text);"><?= e($pkg['price_label']) ?></strong>
                            <div style="font-size:11.5px;color:var(--muted);">/<?= e(ltrim($pkg['billing_cycle'] ?? 'project', '/')) ?></div>
                        </td>
                        <td>
                            <div style="font-size:12px;font-weight:600;color:#38bdf8;margin-bottom:3px;">
                                &#9201; <?= e($pkg['delivery_time'] ?: 'Custom') ?>
                            </div>
                            <div style="font-size:11.5px;color:var(--muted);max-width:280px;">
                                <?= count($feats) ?> features &bull; <?= e(implode(', ', array_slice($feats, 0, 2))) ?>...
                            </div>
                        </td>
                        <td>
                            <form action="<?= url('/admin/packages/' . $pkg['id'] . '/toggle-popular') ?>" method="POST" style="margin:0;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm" style="font-size:11px;padding:4px 10px;border-radius:12px;<?= !empty($pkg['is_popular']) ? 'background:rgba(245,158,11,0.15);color:#f59e0b;border:1px solid rgba(245,158,11,0.35);font-weight:700;' : 'background:rgba(163,178,199,0.1);color:var(--muted);border:1px solid var(--line);' ?>">
                                    <?= !empty($pkg['is_popular']) ? '★ Popular (' . e($pkg['tier_badge']) . ')' : '☆ Standard' ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="<?= url('/admin/packages/' . $pkg['id'] . '/toggle') ?>" method="POST" style="margin: 0;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm" style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; padding: 4px 10px; border-radius: 12px; <?= !empty($pkg['is_active']) ? 'background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 700;' : 'background: rgba(163,178,199,0.12); color: #A3B2C7; border: 1px solid var(--line);' ?>">
                                    <span><?= !empty($pkg['is_active']) ? '● Active' : '○ Hidden' ?></span>
                                </button>
                            </form>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                <a href="<?= url('/admin/packages/' . $pkg['id'] . '/edit') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; min-height: 36px; padding: 6px 14px;">
                                    <span>Edit</span>
                                </a>
                                <form action="<?= url('/admin/packages/' . $pkg['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this service package?');" style="margin: 0;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; color: #ef4444; min-height: 36px; padding: 6px 12px;">
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
