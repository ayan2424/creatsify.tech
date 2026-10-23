<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Add Agency Team Member</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Create a new engineer, designer, or specialist profile for the agency.</p>
        </div>
        <a href="<?= url('/admin/team') ?>" class="btn btn-secondary btn-sm">&larr; Back to Team</a>
    </div>

    <form action="<?= url('/admin/team') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Bilal Ahmed" required>
            </div>
            <div class="form-group">
                <label for="role_title">Designation & Role Title</label>
                <input type="text" id="role_title" name="role_title" class="form-control" placeholder="e.g. Senior UI/UX & Design Systems Lead" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="slug">URL Identifier (Slug)</label>
                <input type="text" id="slug" name="slug" class="form-control" placeholder="e.g. bilal-ahmed (leave blank to auto-generate)">
            </div>
            <div class="form-group">
                <label for="avatar">Avatar Image URL</label>
                <input type="text" id="avatar" name="avatar" class="form-control" value="/assets/brand/creatsify-icon.png">
            </div>
        </div>

        <div class="form-group">
            <label for="skills">Skills & Tech Stack (comma separated)</label>
            <input type="text" id="skills" name="skills" class="form-control" placeholder="e.g. Figma, UI/UX, Design Systems, Tailwind CSS, Framer">
        </div>

        <div class="form-group">
            <label for="bio">Professional Bio & Experience</label>
            <textarea id="bio" name="bio" class="form-control" rows="4" placeholder="Brief summary of their skills, background, achievements, and impact..."></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Direct Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="e.g. member@creatsify.tech">
            </div>
            <div class="form-group">
                <label for="phone">Phone / WhatsApp</label>
                <input type="text" id="phone" name="phone" class="form-control" placeholder="e.g. +92 300 1234567">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="github_url">GitHub Profile URL</label>
                <input type="url" id="github_url" name="github_url" class="form-control" placeholder="https://github.com/username">
            </div>
            <div class="form-group">
                <label for="linkedin_url">LinkedIn Profile URL</label>
                <input type="url" id="linkedin_url" name="linkedin_url" class="form-control" placeholder="https://linkedin.com/in/username">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="portfolio_url">External Portfolio / Dribbble / Behance URL</label>
                <input type="url" id="portfolio_url" name="portfolio_url" class="form-control" placeholder="https://dribbble.com/username">
            </div>
            <div class="form-group">
                <label for="sort_order">Display Priority (Sort Order)</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="0">
            </div>
        </div>

        <div class="form-group">
            <label for="is_active">Status</label>
            <select id="is_active" name="is_active" class="form-control">
                <option value="1">Active (Visible in Team Directory)</option>
                <option value="0">Inactive (Hidden)</option>
            </select>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">Save Team Member &rarr;</button>
            <a href="<?= url('/admin/team') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
