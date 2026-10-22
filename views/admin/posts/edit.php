<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Edit Article: <?= e($post['title']) ?></h3>
        <a href="<?= url('/admin/posts') ?>" class="btn btn-secondary btn-sm">&larr; Back to Posts</a>
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
            <button type="submit" class="btn btn-primary">Save Changes &rarr;</button>
            <a href="<?= url('/admin/posts') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
