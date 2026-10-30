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
            <a href="<?= url('/admin/team/' . $member['id'] . '/portfolio') ?>" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; min-height: 36px; padding: 6px 14px;"><span>Manage Portfolio</span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
            <a href="<?= url('/admin/team') ?>" class="btn btn-secondary btn-sm"style="display: inline-flex; align-items: center; gap: 6px; min-height: 36px; padding: 6px 12px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg><span>Back to Team</span></a>
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
            <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; min-height: 42px; padding: 10px 24px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                <span>Save Member Changes</span>
            </button>
            <a href="<?= url('/admin/team') ?>" class="btn btn-secondary" style="min-height: 42px; padding: 10px 20px;">Cancel</a>
        </div>
    </form>
</div>
