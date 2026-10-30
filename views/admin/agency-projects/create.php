<?php /** @var Core\View $this */ $this->layout('layouts/admin'); ?>

<div class="content-header">
    <div><h1 class="page-title">Create delivery project</h1><p class="page-subtitle">This is internal agency work—not a public portfolio product or a client portal.</p></div>
    <a href="<?= url('/admin/agency-projects') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        <span>Delivery Workspace</span>
    </a>
</div>

<form action="<?= url('/admin/agency-projects') ?>" method="POST" class="card" style="padding:24px; max-width:980px;">
    <?= csrf_field() ?>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(min(100%, 280px), 1fr)); gap:18px;">
        <div style="grid-column:1/-1;"><label class="form-label" for="title">Project title *</label><input class="form-control" id="title" name="title" required maxlength="200" placeholder="e.g. Acme product launch website"></div>
        <div><label class="form-label" for="client_id">CRM client</label><select class="form-control" id="client_id" name="client_id"><option value="">Unlinked / internal initiative</option><?php foreach ($clients as $client): ?><option value="<?= (int)$client['id'] ?>" <?= (int)$client['id'] === (int)$selectedClientId ? 'selected' : '' ?>><?= e(($client['company'] ? $client['company'] . ' — ' : '') . $client['name']) ?></option><?php endforeach; ?></select></div>
        <div><label class="form-label" for="project_type">Work type *</label><input class="form-control" id="project_type" name="project_type" required maxlength="100" value="Custom Development" placeholder="AI video campaign, Web build, Brand system"></div>
        <div><label class="form-label" for="status">Initial status</label><select class="form-control" id="status" name="status"><option value="planning">Planning</option><option value="active">Active</option><option value="on_hold">On hold</option></select></div>
        <div><label class="form-label" for="priority">Priority</label><select class="form-control" id="priority" name="priority"><option value="medium">Medium</option><option value="low">Low</option><option value="high">High</option><option value="urgent">Urgent</option></select></div>
        <div><label class="form-label" for="deal_value">Agreed value</label><input class="form-control" type="number" min="0" step="0.01" id="deal_value" name="deal_value" placeholder="0.00"></div>
        <div><label class="form-label" for="currency">Currency</label><input class="form-control" id="currency" name="currency" maxlength="10" value="USD"></div>
        <div><label class="form-label" for="start_date">Start date</label><input class="form-control" type="date" id="start_date" name="start_date"></div>
        <div><label class="form-label" for="target_date">Target date</label><input class="form-control" type="date" id="target_date" name="target_date"></div>
        <div style="grid-column:1/-1;"><label class="form-label" for="summary">Client-facing summary</label><textarea class="form-control" id="summary" name="summary" rows="3" maxlength="3000" placeholder="One clear sentence about the outcome being delivered."></textarea></div>
        <div style="grid-column:1/-1;"><label class="form-label" for="scope_notes">Internal scope and constraints</label><textarea class="form-control" id="scope_notes" name="scope_notes" rows="6" maxlength="15000" placeholder="Include agreed deliverables, dependencies, exclusions, and decision context. This remains private to the agency."></textarea></div>
    </div>
    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:24px;">
        <a href="<?= url('/admin/agency-projects') ?>" class="btn btn-secondary" style="min-height: 42px; padding: 10px 20px;">Cancel</a>
        <button class="btn btn-primary" type="submit" style="display: inline-flex; align-items: center; gap: 6px; min-height: 42px; padding: 10px 24px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Create Workspace</span>
        </button>
    </div>
</form>
