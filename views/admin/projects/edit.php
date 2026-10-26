<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Edit Project: <?= e($project['title']) ?></h3>
        <a href="<?= url('/admin/projects') ?>" class="btn btn-secondary btn-sm">&larr; Back to Projects</a>
    </div>

    <form action="<?= url('/admin') ?>/projects/<?= $project['id'] ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="title">Project Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= e($project['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="slug">URL Slug</label>
                <input type="text" id="slug" name="slug" class="form-control" value="<?= e($project['slug']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" class="form-control">
                    <option value="saas" <?= $project['category'] === 'saas' ? 'selected' : '' ?>>Platforms & SaaS</option>
                    <option value="stores" <?= $project['category'] === 'stores' ? 'selected' : '' ?>>Stores & Ordering</option>
                    <option value="apps" <?= $project['category'] === 'apps' ? 'selected' : '' ?>>Apps & Tools</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status Badge</label>
                <input type="text" id="status" name="status" class="form-control" value="<?= e($project['status']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="live_url">Live Demo URL</label>
                <input type="text" id="live_url" name="live_url" class="form-control" value="<?= e($project['live_url'] ?? '') ?>" placeholder="https://cheetah.ayan24.me">
            </div>
            <div class="form-group">
                <label for="gumroad_url">Gumroad Purchase URL (Direct Buy)</label>
                <input type="text" id="gumroad_url" name="gumroad_url" class="form-control" value="<?= e($project['gumroad_url'] ?? '') ?>" placeholder="https://gumroad.com/l/cheetah-courier">
                <small style="color:var(--muted);font-size:11.5px;margin-top:4px;display:block;">Direct license purchase checkout link on Gumroad.</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="price">Script Price (USD)</label>
                <input type="text" id="price" name="price" class="form-control" value="<?= e($project['price'] ?? '$99') ?>" placeholder="$99">
            </div>
            <div class="form-group">
                <label for="client">Client / Target Industry</label>
                <input type="text" id="client" name="client" class="form-control" value="<?= e($project['client'] ?? '') ?>" placeholder="Logistics Operators, Courier Startups">
            </div>
        </div>

        <div class="form-group">
            <label for="image_url">Project Artwork / Preview Image URL</label>
            <input type="text" id="image_url" name="image_url" class="form-control" value="<?= e($project['image_url']) ?>">
            <?php if (!empty($project['image_url'])): ?>
                <div style="margin-top:8px;display:flex;align-items:center;gap:12px;">
                    <img src="<?= asset($project['image_url']) ?>" alt="Preview" style="width:80px;height:48px;object-fit:cover;border-radius:6px;border:1px solid var(--line);">
                    <span style="font-size:12px;color:var(--muted);">Current asset: <?= e($project['image_url']) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="tech_stack">Tech Stack (comma separated)</label>
            <input type="text" id="tech_stack" name="tech_stack" class="form-control" value="<?= e($project['tech_stack']) ?>">
        </div>

        <div class="form-group">
            <label for="summary">Summary</label>
            <textarea id="summary" name="summary" class="form-control" rows="2"><?= e($project['summary']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="description">Full Description & Architecture</label>
            <textarea id="description" name="description" class="form-control" rows="5"><?= e($project['description']) ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?= (int)$project['sort_order'] ?>">
            </div>
            <div class="form-group">
                <label for="featured">Featured On Homepage</label>
                <select id="featured" name="featured" class="form-control">
                    <option value="1" <?= $project['featured'] ? 'selected' : '' ?>>Yes (Featured)</option>
                    <option value="0" <?= !$project['featured'] ? 'selected' : '' ?>>No (Standard Catalog)</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Save Project Changes &rarr;</button>
            <a href="<?= url('/admin/projects') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
