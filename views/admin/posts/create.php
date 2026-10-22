<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Create Blog Article</h3>
        <a href="<?= url('/admin/posts') ?>" class="btn btn-secondary btn-sm">&larr; Back to Posts</a>
    </div>

    <form action="<?= url('/admin/posts') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="title">Article Title</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Why We Built Cheetah Courier from Scratch" required>
            </div>
            <div class="form-group">
                <label for="slug">URL Slug</label>
                <input type="text" id="slug" name="slug" class="form-control" placeholder="why-we-built-cheetah-courier-from-scratch">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" class="form-control" value="Engineering">
            </div>
            <div class="form-group">
                <label for="is_published">Publish Status</label>
                <select id="is_published" name="is_published" class="form-control">
                    <option value="1">Published (Live)</option>
                    <option value="0">Draft</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="excerpt">Short Excerpt / Teaser</label>
            <textarea id="excerpt" name="excerpt" class="form-control" rows="2" placeholder="Brief summary displayed on archive and meta tags..."></textarea>
        </div>

        <div class="form-group">
            <label for="content">Article Body (HTML / Markdown)</label>
            <textarea id="content" name="content" class="form-control" rows="12" placeholder="Write article content here..." required></textarea>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Publish Post &rarr;</button>
            <a href="<?= url('/admin/posts') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
