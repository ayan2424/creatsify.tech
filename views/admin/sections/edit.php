<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Section: <?= e($section['section_key']) ?></h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Update text, callouts, and layout parameters for this homepage block.</p>
        </div>
        <a href="<?= url('/admin/sections') ?>" class="btn btn-secondary btn-sm">&larr; Back to Sections</a>
    </div>

    <form action="<?= url('/admin') ?>/sections/<?= e($section['section_key']) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="title">Section Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= e($section['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="subtitle">Subtitle / Category Tag</label>
                <input type="text" id="subtitle" name="subtitle" class="form-control" value="<?= e($section['subtitle']) ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="content">Main Section Content / Body</label>
            <textarea id="content" name="content" class="form-control" rows="5"><?= e($section['content']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="metadata">Metadata & Layout JSON Config</label>
            <textarea id="metadata" name="metadata" class="form-control" rows="6" style="font-family: monospace; font-size: 13px;"><?= e(is_string($section['metadata']) ? $section['metadata'] : json_encode($section['metadata'], JSON_PRETTY_PRINT)) ?></textarea>
            <small style="color: var(--muted); font-size: 12px; margin-top: 4px; display: block;">Supports arrays such as chips, rotating words, stats numbers, or tracker steps.</small>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="sort_order">Display Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?= (int)$section['sort_order'] ?>">
            </div>
            <div class="form-group">
                <label for="is_active">Visibility</label>
                <select id="is_active" name="is_active" class="form-control">
                    <option value="1" <?= $section['is_active'] ? 'selected' : '' ?>>Active (Visible on Homepage)</option>
                    <option value="0" <?= !$section['is_active'] ? 'selected' : '' ?>>Hidden (Draft)</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Save Section Changes &rarr;</button>
            <a href="<?= url('/admin/sections') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
