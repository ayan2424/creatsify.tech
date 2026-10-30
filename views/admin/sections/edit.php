<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
$meta = $metadata ?? [];
$key = $section['section_key'];
?>

<div class="card" style="max-width: 860px;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Configure Section: <?= e($section['title'] ?: ucwords(str_replace('_', ' ', $key))) ?></h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Key: <code><?= e($key) ?></code> &bull; Cheezify-inspired visual section customizer</p>
        </div>
        <a href="<?= url('/admin/sections') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; min-height: 36px; padding: 6px 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            <span>Back to Sections</span>
        </a>
    </div>

    <form action="<?= url('/admin/sections/' . e($key)) ?>" method="POST">
        <?= csrf_field() ?>

        <!-- Base Section Header -->
        <div style="background: var(--bg); padding: 22px; border-radius: 12px; border: 1px solid var(--line); margin-bottom: 24px;">
            <h4 style="font-size: 14px; font-weight: 700; color: var(--text); margin: 0 0 16px 0; display:flex; align-items:center; gap:8px;">
                <span>Header & Descriptive Copy</span>
            </h4>

            <div class="form-row">
                <div class="form-group">
                    <label for="title">Main Heading / Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?= e($section['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="subtitle">Subtitle / Category Tag</label>
                    <input type="text" id="subtitle" name="subtitle" class="form-control" value="<?= e($section['subtitle']) ?>">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="content">Body Lead Text / Description</label>
                <textarea id="content" name="content" class="form-control" rows="3"><?= e($section['content']) ?></textarea>
            </div>
        </div>

        <!-- Section-Specific Visual Customizer (Cheezify Architecture) -->
        <div style="background: var(--bg); padding: 22px; border-radius: 12px; border: 1px solid var(--line); margin-bottom: 24px;">
            <h4 style="font-size: 14px; font-weight: 700; color: var(--text); margin: 0 0 16px 0; display:flex; align-items:center; gap:8px;">
                <span>Visual Component Parameters</span>
                <span class="user-role-badge" style="font-size: 10px; background: rgba(139,123,255,0.15); color: var(--accent);"><?= e($key) ?></span>
            </h4>

            <?php if ($key === 'hero'): ?>
                <!-- Hero Section Elements -->
                <div class="form-group">
                    <label for="meta_rotating_words">Rotating Animated Words (comma separated)</label>
                    <input type="text" id="meta_rotating_words" name="meta[rotating_words]" class="form-control" value="<?= e(implode(', ', $meta['rotating_words'] ?? ['courier companies', 'online stores', 'learning platforms', 'media brands', 'startups', 'restaurant chains'])) ?>">
                    <small style="color:var(--muted); font-size:12px; margin-top:4px; display:block;">These words animate sequentially in gradient typography in the hero headline.</small>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="meta_chips">Floating Glass Chips (comma separated)</label>
                    <input type="text" id="meta_chips" name="meta[chips]" class="form-control" value="<?= e(implode(', ', $meta['chips'] ?? ['7 role portals in Cheetah', 'Direct software licensing', 'Agency delivery team'])) ?>">
                    <small style="color:var(--muted); font-size:12px; margin-top:4px; display:block;">Frosted glass floating telemetry chips positioned over the 3D studio frame.</small>
                </div>

            <?php elseif ($key === 'cheetah_showcase'): ?>
                <!-- Cheetah Logistics Showcase Elements -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="meta_tracker_code">Sample Consignment Tracking Number</label>
                        <input type="text" id="meta_tracker_code" name="meta[tracker_code]" class="form-control" value="<?= e($meta['tracker']['code'] ?? 'PK-000007') ?>">
                    </div>
                    <div class="form-group">
                        <label for="meta_tracker_from">Route Origin City</label>
                        <input type="text" id="meta_tracker_from" name="meta[tracker_from]" class="form-control" value="<?= e($meta['tracker']['from'] ?? 'Karachi') ?>">
                    </div>
                    <div class="form-group">
                        <label for="meta_tracker_to">Route Destination City</label>
                        <input type="text" id="meta_tracker_to" name="meta[tracker_to]" class="form-control" value="<?= e($meta['tracker']['to'] ?? 'Lahore') ?>">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="meta_tracker_steps">Telemetry Milestone Steps (comma separated)</label>
                    <input type="text" id="meta_tracker_steps" name="meta[tracker_steps]" class="form-control" value="<?= e(implode(', ', $meta['tracker']['steps'] ?? ['Shipment created', 'Linehaul dispatched', 'Out for delivery', 'Delivered ePOD signed'])) ?>">
                </div>

            <?php elseif ($key === 'about_founder'): ?>
                <!-- Founder About Elements -->
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="meta_badge">Rotating Circular SVG Badge Text</label>
                    <input type="text" id="meta_badge" name="meta[badge]" class="form-control" value="<?= e($meta['badge'] ?? 'CREATSIFY • KARACHI • BUILD • SHIP • GROW • ') ?>">
                    <small style="color:var(--muted); font-size:12px; margin-top:4px; display:block;">Circular SVG text path rotating around the Creatsify studio emblem.</small>
                </div>

            <?php else: ?>
                <div style="font-size: 13px; color: var(--muted); line-height: 1.6;">
                    This section uses standard content and subtitle formatting. You can customize the lead copy above or inspect the raw parameters below.
                </div>
            <?php endif; ?>
        </div>

        <!-- Section Placement & Visibility -->
        <div class="form-row">
            <div class="form-group">
                <label for="sort_order">Storefront Display Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?= (int)$section['sort_order'] ?>">
            </div>
            <div class="form-group">
                <label for="is_active">Storefront Visibility</label>
                <select id="is_active" name="is_active" class="form-control">
                    <option value="1" <?= $section['is_active'] ? 'selected' : '' ?>>Visible (Active on Homepage)</option>
                    <option value="0" <?= !$section['is_active'] ? 'selected' : '' ?>>Hidden (Draft Mode)</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 24px; font-weight: 600; min-height: 40px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                <span>Save Section Changes</span>
            </button>
            <a href="<?= url('/admin/sections') ?>" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; min-height: 40px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                <span>Cancel</span>
            </a>
        </div>
    </form>
</div>
