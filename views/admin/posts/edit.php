<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Edit Article: <?= e($post['title']) ?></h3>
        <a href="<?= url('/admin/posts') ?>" class="btn btn-secondary btn-sm"style="display: inline-flex; align-items: center; gap: 6px; min-height: 36px; padding: 6px 12px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg><span>Back to Posts</span></a>
    </div>

    <form action="<?= url('/admin') ?>/posts/<?= $post['id'] ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="title">Article Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= e($post['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="slug">URL Slug</label>
                <input type="text" id="slug" name="slug" class="form-control" value="<?= e($post['slug']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" class="form-control" value="<?= e($post['category']) ?>">
            </div>
            <div class="form-group">
                <label for="is_published">Publish Status</label>
                <select id="is_published" name="is_published" class="form-control">
                    <option value="1" <?= $post['is_published'] ? 'selected' : '' ?>>Published (Live)</option>
                    <option value="0" <?= !$post['is_published'] ? 'selected' : '' ?>>Draft</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="excerpt">Short Excerpt / Teaser</label>
            <textarea id="excerpt" name="excerpt" class="form-control" rows="2"><?= e($post['excerpt']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="content">Article Body (HTML / Markdown)</label>
            <textarea id="content" name="content" class="form-control" rows="12" required><?= e($post['content']) ?></textarea>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; min-height: 42px; padding: 10px 24px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                <span>Save Changes</span>
            </button>
            <a href="<?= url('/admin/posts') ?>" class="btn btn-secondary" style="min-height: 42px; padding: 10px 20px;">Cancel</a>
        </div>
    </form>
</div>
