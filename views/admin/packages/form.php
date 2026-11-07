<?php
/** @var Core\View $this */
$isEdit = !empty($package);
$pageTitle = $isEdit ? 'Edit Service Package' : 'Create Service Package';
$this->layout('layouts/admin', ['page_title' => $pageTitle, 'meta_title' => $pageTitle . ' | Creatsify Admin']);
$actionUrl = $isEdit ? url('/admin/packages/' . $package['id']) : url('/admin/packages');
?>

<div class="content-header">
    <div>
        <h1 class="page-title"><?= e($pageTitle) ?></h1>
        <p class="page-subtitle">Configure pricing, track category, turnaround time, and bulleted deliverables.</p>
    </div>
    <a href="<?= url('/admin/packages') ?>" class="btn btn-secondary" style="min-height:40px;padding:8px 16px;">&larr; Back to Packages</a>
</div>

<div class="card" style="max-width:860px;padding:28px;">
    <form action="<?= $actionUrl ?>" method="POST">
        <?= csrf_field() ?>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:18px;margin-bottom:18px;">
            <div class="form-group">
                <label class="form-label">Package Title *</label>
                <input type="text" name="title" class="form-control" required value="<?= e($package['title'] ?? '') ?>" placeholder="e.g., Growth Web App & Store">
            </div>
            <div class="form-group">
                <label class="form-label">Service Track *</label>
                <select name="track" class="form-control">
                    <?php foreach (($tracks ?? []) as $tKey => $tInfo): ?>
                        <option value="<?= e($tKey) ?>" <?= (($package['track'] ?? 'dev') === $tKey) ? 'selected' : '' ?>>
                            <?= e($tInfo['label'] ?? $tKey) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:18px;margin-bottom:18px;">
            <div class="form-group">
                <label class="form-label">Price Label *</label>
                <input type="text" name="price_label" class="form-control" required value="<?= e($package['price_label'] ?? '$299') ?>" placeholder="$299 or $1,499+">
            </div>
            <div class="form-group">
                <label class="form-label">Billing Cycle</label>
                <input type="text" name="billing_cycle" class="form-control" value="<?= e($package['billing_cycle'] ?? 'project') ?>" placeholder="project, pack, or month">
            </div>
            <div class="form-group">
                <label class="form-label">Delivery Time Badge</label>
                <input type="text" name="delivery_time" class="form-control" value="<?= e($package['delivery_time'] ?? '7–14 Days') ?>" placeholder="7–10 Days or Monthly Retainer">
            </div>
            <div class="form-group">
                <label class="form-label">Ribbon / Tier Badge</label>
                <input type="text" name="tier_badge" class="form-control" value="<?= e($package['tier_badge'] ?? 'Most Popular') ?>" placeholder="Most Popular / Highest ROI">
            </div>
        </div>

        <div class="form-group" style="margin-bottom:18px;">
            <label class="form-label">Package Subtitle / Summary</label>
            <input type="text" name="subtitle" class="form-control" value="<?= e($package['subtitle'] ?? '') ?>" placeholder="Short 1-sentence description of who this package is for">
        </div>

        <div class="form-group" style="margin-bottom:18px;">
            <label class="form-label">Included Features &amp; Deliverables (One feature per line) *</label>
            <textarea name="features" class="form-control" rows="7" required placeholder="Up to 7 Custom Responsive Pages&#10;Mobile-First UI/UX & 95+ Speed Optimization&#10;Technical On-Page SEO & Schema Setup"><?= e($package['features'] ?? '') ?></textarea>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:18px;margin-bottom:22px;">
            <div class="form-group">
                <label class="form-label">CTA Button Text</label>
                <input type="text" name="cta_text" class="form-control" value="<?= e($package['cta_text'] ?? 'Select Package') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="<?= (int)($package['sort_order'] ?? 1) ?>">
            </div>
            <div class="form-group" style="display:flex;flex-direction:column;justify-content:center;gap:10px;padding-top:18px;">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="is_popular" value="1" <?= !empty($package['is_popular']) ? 'checked' : '' ?>>
                    <span>Highlight as <b>Most Popular / Featured Tier</b></span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" <?= (!isset($package['is_active']) || !empty($package['is_active'])) ? 'checked' : '' ?>>
                    <span>Active &amp; Visible on Frontend</span>
                </label>
            </div>
        </div>

        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <a href="<?= url('/admin/packages') ?>" class="btn btn-secondary" style="padding:10px 20px;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding:10px 24px;">
                <?= $isEdit ? 'Save Package Changes' : 'Create Package' ?>
            </button>
        </div>
    </form>
</div>
