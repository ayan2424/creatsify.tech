<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Add New Portfolio Project</h3>
        <a href="<?= url('/admin/projects') ?>" class="btn btn-secondary btn-sm">&larr; Back to Projects</a>
    </div>

    <form action="<?= url('/admin/projects') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="title">Project Title</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Cheetah SaaS" required>
            </div>
            <div class="form-group">
                <label for="slug">URL Slug (leave empty for auto)</label>
                <input type="text" id="slug" name="slug" class="form-control" placeholder="cheetah-saas">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" class="form-control">
                    <option value="saas">Platforms & SaaS</option>
                    <option value="stores">Stores & Ordering</option>
                    <option value="apps">Apps & Tools</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status Badge</label>
                <input type="text" id="status" name="status" class="form-control" value="Shipped">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="client">Client / Market</label>
                <input type="text" id="client" name="client" class="form-control" placeholder="Logistics Operators">
            </div>
            <div class="form-group">
                <label for="live_url">Live URL / Marketplace Link</label>
                <input type="text" id="live_url" name="live_url" class="form-control" placeholder="https://creatsify.tech">
            </div>
        </div>

        <div class="form-group">
            <label for="tech_stack">Tech Stack (comma separated)</label>
            <input type="text" id="tech_stack" name="tech_stack" class="form-control" placeholder="PHP, Laravel, MySQL, Redis">
        </div>

        <div class="form-group">
            <label for="summary">Summary (Short Card Teaser)</label>
            <textarea id="summary" name="summary" class="form-control" rows="2" placeholder="Brief 1-sentence value proposition..."></textarea>
        </div>

        <div class="form-group">
            <label for="description">Full Description & Architecture</label>
            <textarea id="description" name="description" class="form-control" rows="5" placeholder="Detailed architectural breakdown..."></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="0">
            </div>
            <div class="form-group">
                <label for="featured">Featured On Homepage</label>
                <select id="featured" name="featured" class="form-control">
                    <option value="1">Yes (Featured)</option>
                    <option value="0">No (Standard Catalog)</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Create Project &rarr;</button>
            <a href="<?= url('/admin/projects') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
