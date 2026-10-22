<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <h3 class="card-title">Edit User: <?= e($user['name']) ?></h3>
        <a href="<?= url('/admin/users') ?>" class="btn btn-secondary btn-sm">&larr; Back to Users</a>
    </div>

    <form action="<?= url('/admin') ?>/users/<?= $user['id'] ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password">New Password (leave empty to keep current)</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••">
            </div>
            <div class="form-group">
                <label for="role">Assigned Role</label>
                <select id="role" name="role" class="form-control">
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= e($r['slug']) ?>" <?= $user['role'] === $r['slug'] ? 'selected' : '' ?>><?= e($r['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="phone">Phone / WhatsApp</label>
                <input type="text" id="phone" name="phone" class="form-control" value="<?= e($user['phone']) ?>">
            </div>
            <div class="form-group">
                <label for="status">Account Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    <option value="suspended" <?= $user['status'] === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Save User Changes &rarr;</button>
            <a href="<?= url('/admin/users') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
