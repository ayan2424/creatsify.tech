<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Add Portfolio Item for <?= e($member['name']) ?></h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Showcase a project, design system, or application crafted by <?= e($member['name']) ?>.</p>
        </div>
        <a href="<?= url('/admin/team/' . $member['id'] . '/portfolio') ?>" class="btn btn-secondary btn-sm">&larr; Back to Member Desk</a>
    </div>

    <form action="<?= url('/admin/team/' . $member['id'] . '/portfolio') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="title">Project / Showcase Title</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="e.g. Cheetah Enterprise Logistics Core" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" class="form-control" placeholder="e.g. Enterprise Architecture, Product Design, Mobile App" value="Full-Stack Development">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="slug">URL Slug</label>
                <input type="text" id="slug" name="slug" class="form-control" placeholder="leave blank to auto-generate">
            </div>
            <div class="form-group">
                <label for="role_in_project">Role In Project</label>
                <input type="text" id="role_in_project" name="role_in_project" class="form-control" value="<?= e($member['role_title']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="client">Client / Industry</label>
                <input type="text" id="client" name="client" class="form-control" placeholder="e.g. Logistics Network, Hospitality, Healthcare">
            </div>
            <div class="form-group">
                <label for="completion_date">Year / Completion Date</label>
                <input type="text" id="completion_date" name="completion_date" class="form-control" value="<?= date('Y') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="image_url">Project Artwork / Screenshot URL</label>
            <input type="text" id="image_url" name="image_url" class="form-control" value="/assets/images/hero-artwork.jpg">
            <small style="color:var(--muted); font-size:12px; margin-top:3px; display:block;">Use path from Central Media Library (e.g. /assets/images/...)</small>
        </div>

        <div class="form-group">
            <label for="project_url">Live URL / Demo Link</label>
            <input type="url" id="project_url" name="project_url" class="form-control" placeholder="https://example.com or GitHub repo">
        </div>

        <div class="form-group">
            <label for="tech_stack">Tech Stack (comma separated)</label>
            <input type="text" id="tech_stack" name="tech_stack" class="form-control" placeholder="e.g. PHP 8.2, MySQL, Laravel, WebSockets, Vue.js">
        </div>

        <div class="form-group">
            <label for="summary">Short Summary</label>
            <textarea id="summary" name="summary" class="form-control" rows="2" placeholder="One sentence summary of the project and impact..."></textarea>
        </div>

        <div class="form-group">
            <label for="description">Detailed Description & Engineering Challenges</label>
            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Deep dive into the architecture, technical decisions, and personal contributions..."></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="is_featured">Featured in Highlights</label>
                <select id="is_featured" name="is_featured" class="form-control">
                    <option value="1">Yes (Showcase on Top)</option>
                    <option value="0" selected>No (Standard Portfolio)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="sort_order">Display Priority (Sort Order)</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="0">
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">Save Portfolio Project &rarr;</button>
            <a href="<?= url('/admin/team/' . $member['id'] . '/portfolio') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
