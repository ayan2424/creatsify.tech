<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Team Member: <?= e($member['name']) ?></h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Update role, skills, bio, and social connections.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="<?= url('/admin/team/' . $member['id'] . '/portfolio') ?>" class="btn btn-primary btn-sm">Manage Portfolio &rarr;</a>
            <a href="<?= url('/admin/team') ?>" class="btn btn-secondary btn-sm">&larr; Back to Team</a>
        </div>
    </div>

    <form action="<?= url('/admin/team/' . $member['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= e($member['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="role_title">Designation & Role Title</label>
                <input type="text" id="role_title" name="role_title" class="form-control" value="<?= e($member['role_title']) ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="slug">URL Identifier (Slug)</label>
                <input type="text" id="slug" name="slug" class="form-control" value="<?= e($member['slug']) ?>">
            </div>
            <div class="form-group">
                <label for="avatar">Avatar Image URL</label>
                <input type="text" id="avatar" name="avatar" class="form-control" value="<?= e($member['avatar']) ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="skills">Skills & Tech Stack (comma separated)</label>
            <input type="text" id="skills" name="skills" class="form-control" value="<?= e($member['skills']) ?>">
        </div>

        <div class="form-group">
            <label for="bio">Professional Bio & Experience</label>
            <textarea id="bio" name="bio" class="form-control" rows="4"><?= e($member['bio']) ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Direct Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= e($member['email']) ?>">
            </div>
            <div class="form-group">
                <label for="phone">Phone / WhatsApp</label>
                <input type="text" id="phone" name="phone" class="form-control" value="<?= e($member['phone']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="github_url">GitHub Profile URL</label>
                <input type="url" id="github_url" name="github_url" class="form-control" value="<?= e($member['github_url']) ?>">
            </div>
            <div class="form-group">
                <label for="linkedin_url">LinkedIn Profile URL</label>
                <input type="url" id="linkedin_url" name="linkedin_url" class="form-control" value="<?= e($member['linkedin_url']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="portfolio_url">External Portfolio / Dribbble / Behance URL</label>
                <input type="url" id="portfolio_url" name="portfolio_url" class="form-control" value="<?= e($member['portfolio_url']) ?>">
            </div>
            <div class="form-group">
                <label for="sort_order">Display Priority (Sort Order)</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?= (int)$member['sort_order'] ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="is_active">Status</label>
            <select id="is_active" name="is_active" class="form-control">
                <option value="1" <?= $member['is_active'] ? 'selected' : '' ?>>Active (Visible in Team Directory)</option>
                <option value="0" <?= !$member['is_active'] ? 'selected' : '' ?>>Inactive (Hidden)</option>
            </select>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">Save Member Changes &rarr;</button>
            <a href="<?= url('/admin/team') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
