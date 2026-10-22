<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Add Engineering Service</h3>
        <a href="<?= url('/admin/services') ?>" class="btn btn-secondary btn-sm">&larr; Back to Services</a>
    </div>

    <form action="<?= url('/admin/services') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="title">Service Title</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Web Platforms & SaaS" required>
            </div>
            <div class="form-group">
                <label for="slug">URL Slug</label>
                <input type="text" id="slug" name="slug" class="form-control" placeholder="web-platforms-saas">
            </div>
        </div>

        <div class="form-group">
            <label for="short_description">Short Description</label>
            <input type="text" id="short_description" name="short_description" class="form-control" placeholder="Bespoke web applications and scalable cloud APIs...">
        </div>

        <div class="form-group">
            <label for="full_description">Full Description</label>
            <textarea id="full_description" name="full_description" class="form-control" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="deliverables">Deliverables (comma separated)</label>
            <input type="text" id="deliverables" name="deliverables" class="form-control" placeholder="REST API, Database Schema, Payment integration, Unit tests">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="0">
            </div>
            <div class="form-group">
                <label for="is_active">Status</label>
                <select id="is_active" name="is_active" class="form-control">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Create Service &rarr;</button>
            <a href="<?= url('/admin/services') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
