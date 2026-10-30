<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Edit Service: <?= e($service['title']) ?></h3>
        <a href="<?= url('/admin/services') ?>" class="btn btn-secondary btn-sm"style="display: inline-flex; align-items: center; gap: 6px; min-height: 36px; padding: 6px 12px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg><span>Back to Services</span></a>
    </div>

    <form action="<?= url('/admin') ?>/services/<?= $service['id'] ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="title">Service Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= e($service['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="slug">URL Slug</label>
                <input type="text" id="slug" name="slug" class="form-control" value="<?= e($service['slug']) ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="short_description">Short Description</label>
            <input type="text" id="short_description" name="short_description" class="form-control" value="<?= e($service['short_description']) ?>">
        </div>

        <div class="form-group">
            <label for="full_description">Full Description</label>
            <textarea id="full_description" name="full_description" class="form-control" rows="4"><?= e($service['full_description']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="deliverables">Deliverables (comma separated)</label>
            <input type="text" id="deliverables" name="deliverables" class="form-control" value="<?= e($service['deliverables']) ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?= (int)$service['sort_order'] ?>">
            </div>
            <div class="form-group">
                <label for="is_active">Status</label>
                <select id="is_active" name="is_active" class="form-control">
                    <option value="1" <?= $service['is_active'] ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= !$service['is_active'] ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; min-height: 42px; padding: 10px 24px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                <span>Save Changes</span>
            </button>
            <a href="<?= url('/admin/services') ?>" class="btn btn-secondary" style="min-height: 42px; padding: 10px 20px;">Cancel</a>
        </div>
    </form>
</div>
