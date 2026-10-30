<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Create Blog Article</h3>
        <a href="<?= url('/admin/posts') ?>" class="btn btn-secondary btn-sm"style="display: inline-flex; align-items: center; gap: 6px; min-height: 36px; padding: 6px 12px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg><span>Back to Posts</span></a>
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
            <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; min-height: 42px; padding: 10px 24px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                <span>Publish Post</span>
            </button>
            <a href="<?= url('/admin/posts') ?>" class="btn btn-secondary" style="min-height: 42px; padding: 10px 20px;">Cancel</a>
        </div>
    </form>
</div>
