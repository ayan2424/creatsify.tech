<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Portfolio Item: <?= e($item['title']) ?></h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Update project details for <?= e($member['name']) ?>.</p>
        </div>
        <a href="<?= url('/admin/team/' . $member['id'] . '/portfolio') ?>" class="btn btn-secondary btn-sm">&larr; Back to Member Desk</a>
    </div>

    <form action="<?= url('/admin/team/' . $member['id'] . '/portfolio/' . $item['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="title">Project / Showcase Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= e($item['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" class="form-control" value="<?= e($item['category']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="slug">URL Slug</label>
                <input type="text" id="slug" name="slug" class="form-control" value="<?= e($item['slug']) ?>">
            </div>
            <div class="form-group">
                <label for="role_in_project">Role In Project</label>
                <input type="text" id="role_in_project" name="role_in_project" class="form-control" value="<?= e($item['role_in_project']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="client">Client / Industry</label>
                <input type="text" id="client" name="client" class="form-control" value="<?= e($item['client']) ?>">
            </div>
            <div class="form-group">
                <label for="completion_date">Year / Completion Date</label>
                <input type="text" id="completion_date" name="completion_date" class="form-control" value="<?= e($item['completion_date']) ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="image_url">Project Artwork / Screenshot URL</label>
            <input type="text" id="image_url" name="image_url" class="form-control" value="<?= e($item['image_url']) ?>">
            <?php if (!empty($item['image_url'])): ?>
                <div style="margin-top: 8px;">
                    <img src="<?= asset($item['image_url']) ?>" alt="Preview" style="height: 60px; border-radius: 6px; border: 1px solid var(--line);">
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="project_url">Live URL / Demo Link</label>
            <input type="url" id="project_url" name="project_url" class="form-control" value="<?= e($item['project_url']) ?>">
        </div>

        <div class="form-group">
            <label for="tech_stack">Tech Stack (comma separated)</label>
            <input type="text" id="tech_stack" name="tech_stack" class="form-control" value="<?= e($item['tech_stack']) ?>">
        </div>

        <div class="form-group">
            <label for="summary">Short Summary</label>
            <textarea id="summary" name="summary" class="form-control" rows="2"><?= e($item['summary']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="description">Detailed Description & Engineering Challenges</label>
            <textarea id="description" name="description" class="form-control" rows="4"><?= e($item['description']) ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="is_featured">Featured in Highlights</label>
                <select id="is_featured" name="is_featured" class="form-control">
                    <option value="1" <?= $item['is_featured'] ? 'selected' : '' ?>>Yes (Showcase on Top)</option>
                    <option value="0" <?= !$item['is_featured'] ? 'selected' : '' ?>>No (Standard Portfolio)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="sort_order">Display Priority (Sort Order)</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?= (int)$item['sort_order'] ?>">
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">Save Changes &rarr;</button>
            <a href="<?= url('/admin/team/' . $member['id'] . '/portfolio') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
