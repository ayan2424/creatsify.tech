<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Create Ticket', 'meta_title' => 'New Support Ticket | Creatsify Admin']);
?>
<div class="content-header">
    <div>
        <a href="<?= url('/admin/crm/tickets') ?>" class="btn btn-secondary btn-sm" style="margin-bottom: 10px; display: inline-flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg><span>Back to Tickets Desk</span>
        </a>
        <h1 class="page-title">Open Support or Milestone Ticket</h1>
        <p class="page-subtitle">Track client deliverables, technical issues, change orders, or maintenance requests.</p>
    </div>
</div>

<div class="card" style="max-width: 900px;">
    <div class="card-header" style="border-bottom: 1px solid var(--adm-line);">
        <h3 class="card-title">Ticket Specification</h3>
        <span class="user-role-badge" style="font-family: monospace; font-size: 13px; font-weight: 700;"><?= e($ticketNumber) ?></span>
    </div>

    <form action="<?= url('/admin/crm/tickets') ?>" method="POST" style="padding: 24px;">
        <?= csrf_field() ?>
        <input type="hidden" name="ticket_number" value="<?= e($ticketNumber) ?>">

        <!-- Client Association -->
        <div class="crm-form-section">
            <h4 class="crm-form-section-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>1. Client Account Association</span>
            </h4>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="client_id">CRM Client File (Autofill)</label>
                    <select id="client_id" name="client_id" class="form-control" onchange="fillClientFields(this)">
                        <option value="">-- Choose Existing Client (Optional) --</option>
                        <?php foreach ($clients as $cl): ?>
                            <option value="<?= $cl['id'] ?>" 
                                    data-name="<?= e($cl['name']) ?>" 
                                    data-email="<?= e($cl['email']) ?>" 
                                    data-phone="<?= e($cl['phone'] ?? '') ?>"
                                    <?= (!empty($preselectedClient) && (int)$preselectedClient['id'] === (int)$cl['id']) ? 'selected' : '' ?>>
                                <?= e($cl['name']) ?> <?= !empty($cl['company']) ? '· ' . e($cl['company']) : '' ?> (<?= e($cl['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="client_name">Client / Contact Name <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="client_name" name="client_name" class="form-control" placeholder="Tariq Rahim" value="<?= e($preselectedClient['name'] ?? '') ?>" required>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="client_email">Client Email Address <span style="color:#ef4444;">*</span></label>
                    <input type="email" id="client_email" name="client_email" class="form-control" placeholder="client@example.com" value="<?= e($preselectedClient['email'] ?? '') ?>" required>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="client_phone">Client Phone (Optional)</label>
                    <input type="text" id="client_phone" name="client_phone" class="form-control" placeholder="+92 300 1234567" value="<?= e($preselectedClient['phone'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Subject & Categorization -->
        <div class="crm-form-section">
            <h4 class="crm-form-section-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span>2. Deliverable & Priority SLA</span>
            </h4>

            <div class="form-group" style="margin-bottom: 16px;">
                <label for="subject">Ticket Subject / Deliverable Title <span style="color:#ef4444;">*</span></label>
                <input type="text" id="subject" name="subject" class="form-control" placeholder="e.g. Cheetah: Configure suburb geofence radius for branch dispatch" required>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 16px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="category">Category</label>
                    <select id="category" name="category" class="form-control">
                        <option value="maintenance">Maintenance / SLA</option>
                        <option value="feature_request">Feature Request / Enhancement</option>
                        <option value="bug">Bug Report / Defect</option>
                        <option value="milestone">Milestone Deliverable</option>
                        <option value="onboarding">Client Onboarding</option>
                        <option value="billing">Billing & Contract Terms</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="priority">Priority SLA</label>
                    <select id="priority" name="priority" class="form-control">
                        <option value="medium" selected>Medium (Standard Response)</option>
                        <option value="high">High (24h Turnaround)</option>
                        <option value="urgent">Urgent (Production Critical / Outage)</option>
                        <option value="low">Low (Routine / Backlog)</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="project_id">Associated Software / System</label>
                    <select id="project_id" name="project_id" class="form-control">
                        <option value="">-- General Studio Architecture --</option>
                        <?php foreach ($projects as $pj): ?>
                            <option value="<?= $pj['id'] ?>"><?= e($pj['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="assigned_to">Assigned Specialist</label>
                    <select id="assigned_to" name="assigned_to" class="form-control">
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['id'] ?>"><?= e($u['name']) ?> (<?= e($u['role']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="due_date">Target Due Date / SLA Deadline</label>
                    <input type="date" id="due_date" name="due_date" class="form-control">
                </div>
            </div>
        </div>

        <!-- Detailed Description -->
        <div class="crm-form-section">
            <h4 class="crm-form-section-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <span>3. Detailed Description & Technical Requirements</span>
            </h4>

            <div class="form-group" style="margin-bottom: 0;">
                <textarea id="description" name="description" rows="5" class="form-control" placeholder="Provide full context, affected URLs or endpoints, reproduction steps, or deliverable criteria..." required></textarea>
            </div>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center; border-top: 1px solid var(--adm-line); padding-top: 20px;">
            <a href="<?= url('/admin/crm/tickets') ?>" class="btn btn-secondary" style="min-height: 42px; padding: 10px 20px;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; min-height: 42px; padding: 10px 26px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>Open Support Ticket</span>
            </button>
        </div>
    </form>
</div>

<script>
function fillClientFields(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.dataset.name) {
        document.getElementById('client_name').value = opt.dataset.name;
        document.getElementById('client_email').value = opt.dataset.email;
        if (opt.dataset.phone) {
            document.getElementById('client_phone').value = opt.dataset.phone;
        }
    }
}
</script>
