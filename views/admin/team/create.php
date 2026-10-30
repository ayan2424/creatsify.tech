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
        <a href="<?= url('/admin/team') ?>" class="btn btn-secondary btn-sm"style="display: inline-flex; align-items: center; gap: 6px; min-height: 36px; padding: 6px 12px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg><span>Back to Team</span></a>
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
            <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; min-height: 42px; padding: 10px 24px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                <span>Save Team Member</span>
            </button>
            <a href="<?= url('/admin/team') ?>" class="btn btn-secondary" style="min-height: 42px; padding: 10px 20px;">Cancel</a>
        </div>
    </form>
</div>
