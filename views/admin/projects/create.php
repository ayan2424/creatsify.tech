<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Add New Portfolio Project</h3>
        <a href="<?= url('/admin/projects') ?>" class="btn btn-secondary btn-sm"style="display: inline-flex; align-items: center; gap: 6px; min-height: 36px; padding: 6px 12px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg><span>Back to Projects</span></a>
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
                <label for="live_url">Live Demo URL</label>
                <input type="text" id="live_url" name="live_url" class="form-control" placeholder="https://cheetah.ayan24.me">
            </div>
            <div class="form-group">
                <label for="gumroad_url">Gumroad Purchase URL (Direct Buy)</label>
                <input type="text" id="gumroad_url" name="gumroad_url" class="form-control" placeholder="https://gumroad.com/l/cheetah-courier">
                <small style="color:var(--muted);font-size:11.5px;margin-top:4px;display:block;">Direct license purchase checkout link on Gumroad.</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="price">Script Price (USD)</label>
                <input type="text" id="price" name="price" class="form-control" placeholder="$99" value="$99">
            </div>
            <div class="form-group">
                <label for="client">Client / Target Industry</label>
                <input type="text" id="client" name="client" class="form-control" placeholder="Logistics Operators, Courier Startups">
            </div>
        </div>

        <div class="form-group">
            <label for="image_url">Project Artwork / Preview Image URL</label>
            <input type="text" id="image_url" name="image_url" class="form-control" placeholder="/assets/images/cheetah-showcase.jpg or upload in Media Library" value="/assets/images/hero-artwork.jpg">
            <small style="color:var(--muted);font-size:12px;margin-top:4px;display:block;">Tip: You can use /assets/images/cheetah-showcase.jpg, /assets/images/restromaster-preview.jpg, /assets/images/viddmate-preview.jpg or upload in Media Library.</small>
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
            <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; min-height: 42px; padding: 10px 24px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>Create Project</span>
            </button>
            <a href="<?= url('/admin/projects') ?>" class="btn btn-secondary" style="min-height: 42px; padding: 10px 20px;">Cancel</a>
        </div>
    </form>
</div>
