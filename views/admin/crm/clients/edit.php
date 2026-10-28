<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Edit Client', 'meta_title' => 'Edit Client | Creatsify Admin']);
?>
<div class="content-header">
    <div>
        <a href="<?= url('/admin/crm/clients/' . $client['id']) ?>" class="btn btn-secondary btn-sm" style="margin-bottom: 8px;">&larr; Back to Client Profile</a>
        <h1 class="page-title">Edit Client: <?= e($client['name']) ?></h1>
        <p class="page-subtitle">Update contact details, company information, pipeline stage, and deal metrics.</p>
    </div>
</div>

<div class="card" style="max-width: 860px;">
    <div class="card-header">
        <h3 class="card-title">Edit Client Profile #<?= $client['id'] ?></h3>
        <form action="<?= url('/admin/crm/clients/' . $client['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this client file? All associated notes will be removed.');" style="margin:0;">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-danger btn-sm" style="background: rgba(239, 68, 68, 0.12); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);">Delete Client</button>
        </form>
    </div>

    <form action="<?= url('/admin/crm/clients/' . $client['id']) ?>" method="POST" style="padding: 24px;">
        <?= csrf_field() ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="name">Contact Person / Principal Name <span style="color:#ef4444;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="<?= e($client['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="company">Company / Organization / Brand</label>
                <input type="text" id="company" name="company" class="form-control" value="<?= e($client['company'] ?? '') ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="email">Direct Email Address <span style="color:#ef4444;">*</span></label>
                <input type="email" id="email" name="email" class="form-control" value="<?= e($client['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Direct Phone</label>
                <input type="text" id="phone" name="phone" class="form-control" value="<?= e($client['phone'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="whatsapp">WhatsApp Number</label>
                <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="<?= e($client['whatsapp'] ?? '') ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="stage">Pipeline Stage</label>
                <select id="stage" name="stage" class="form-control">
                    <option value="lead" <?= $client['stage'] === 'lead' ? 'selected' : '' ?>>Lead (Initial Contact)</option>
                    <option value="discovery" <?= $client['stage'] === 'discovery' ? 'selected' : '' ?>>Discovery (Scoping)</option>
                    <option value="proposal_sent" <?= $client['stage'] === 'proposal_sent' ? 'selected' : '' ?>>Proposal Sent</option>
                    <option value="contract_signed" <?= $client['stage'] === 'contract_signed' ? 'selected' : '' ?>>Contract Signed</option>
                    <option value="active_client" <?= $client['stage'] === 'active_client' ? 'selected' : '' ?>>Active Client (Execution)</option>
                    <option value="retained" <?= $client['stage'] === 'retained' ? 'selected' : '' ?>>Retained (Architecture SLA)</option>
                    <option value="churned" <?= $client['stage'] === 'churned' ? 'selected' : '' ?>>Churned / Lost</option>
                </select>
            </div>

            <div class="form-group">
                <label for="deal_value">Estimated Deal / Contract Value</label>
                <div style="display: flex; gap: 8px;">
                    <select name="currency" class="form-control" style="width: 90px; flex-shrink: 0;">
                        <option value="USD" <?= $client['currency'] === 'USD' ? 'selected' : '' ?>>USD</option>
                        <option value="PKR" <?= $client['currency'] === 'PKR' ? 'selected' : '' ?>>PKR</option>
                        <option value="EUR" <?= $client['currency'] === 'EUR' ? 'selected' : '' ?>>EUR</option>
                        <option value="AED" <?= $client['currency'] === 'AED' ? 'selected' : '' ?>>AED</option>
                        <option value="GBP" <?= $client['currency'] === 'GBP' ? 'selected' : '' ?>>GBP</option>
                    </select>
                    <input type="number" step="0.01" id="deal_value" name="deal_value" class="form-control" value="<?= e($client['deal_value']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="source">Acquisition Source</label>
                <input type="text" id="source" name="source" class="form-control" value="<?= e($client['source']) ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" class="form-control" value="<?= e($client['city'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" class="form-control" value="<?= e($client['country'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="website">Company Website</label>
                <input type="url" id="website" name="website" class="form-control" value="<?= e($client['website'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="tags">Industry Tags / Product Focus (Comma separated)</label>
            <input type="text" id="tags" name="tags" class="form-control" value="<?= e($client['tags'] ?? '') ?>">
        </div>

        <div class="form-group" style="margin-bottom: 26px;">
            <label for="notes_summary">Scope Briefing & Requirements Summary</label>
            <textarea id="notes_summary" name="notes_summary" rows="4" class="form-control"><?= e($client['notes_summary'] ?? '') ?></textarea>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; border-top: 1px solid var(--adm-line); padding-top: 20px;">
            <a href="<?= url('/admin/crm/clients/' . $client['id']) ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                Update Client Profile &rarr;
            </button>
        </div>
    </form>
</div>
