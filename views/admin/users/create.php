<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <h3 class="card-title">Create Admin / Team Member</h3>
        <a href="<?= url('/admin/users') ?>" class="btn btn-secondary btn-sm">&larr; Back to Users</a>
    </div>

    <form action="<?= url('/admin/users') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Ayan Shamim" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="ayan@creatsify.tech" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="form-group">
                <label for="role">Assigned Role</label>
                <select id="role" name="role" class="form-control">
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= e($r['slug']) ?>"><?= e($r['name']) ?> (<?= e($r['slug']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="phone">Phone / WhatsApp Number</label>
            <input type="text" id="phone" name="phone" class="form-control" placeholder="+92 300 1234567">
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Create User Account &rarr;</button>
            <a href="<?= url('/admin/users') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
