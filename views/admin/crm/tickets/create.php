<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Create Ticket', 'meta_title' => 'New Support Ticket | Creatsify Admin']);
?>
<div class="content-header">
    <div>
        <a href="<?= url('/admin/crm/tickets') ?>" class="btn btn-secondary btn-sm" style="margin-bottom: 8px;">&larr; Back to Tickets</a>
        <h1 class="page-title">Open New Support or Milestone Ticket</h1>
        <p class="page-subtitle">Track client deliverables, technical issues, change orders, or maintenance requests.</p>
    </div>
</div>

<div class="card" style="max-width: 860px;">
    <div class="card-header">
        <h3 class="card-title">Ticket Details</h3>
        <span class="user-role-badge" style="font-family: monospace; font-size: 12px;"><?= e($ticketNumber) ?></span>
    </div>

    <form action="<?= url('/admin/crm/tickets') ?>" method="POST" style="padding: 24px;">
        <?= csrf_field() ?>
        <input type="hidden" name="ticket_number" value="<?= e($ticketNumber) ?>">

        <!-- Client Association -->
        <div style="margin-bottom: 20px; padding: 16px; background: var(--adm-surface); border-radius: 10px; border: 1px solid var(--adm-line);">
            <label style="font-size: 13px; font-weight: 700; color: var(--adm-text); margin-bottom: 10px; display: block;">Select Existing Client or Enter Direct Contact</label>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 14px;">
                <div class="form-group">
                    <label for="client_id">CRM Client File</label>
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

                <div class="form-group">
                    <label for="client_name">Client / Contact Name <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="client_name" name="client_name" class="form-control" placeholder="Tariq Rahim" value="<?= e($preselectedClient['name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="client_email">Client Email Address <span style="color:#ef4444;">*</span></label>
                    <input type="email" id="client_email" name="client_email" class="form-control" placeholder="client@example.com" value="<?= e($preselectedClient['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="client_phone">Client Phone (Optional)</label>
                    <input type="text" id="client_phone" name="client_phone" class="form-control" placeholder="+92 300 1234567" value="<?= e($preselectedClient['phone'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Subject & Categorization -->
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="subject">Ticket Subject / Deliverable Title <span style="color:#ef4444;">*</span></label>
            <input type="text" id="subject" name="subject" class="form-control" placeholder="e.g. Cheetah: Configure suburb geofence radius for branch dispatch" required>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 20px;">
            <div class="form-group">
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

            <div class="form-group">
                <label for="priority">Priority SLA</label>
                <select id="priority" name="priority" class="form-control">
                    <option value="medium" selected>Medium (Standard Response)</option>
                    <option value="high">High (24h Turnaround)</option>
                    <option value="urgent">Urgent (Production Critical / Outage)</option>
                    <option value="low">Low (Routine / Backlog)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="project_id">Associated Software / System</label>
                <select id="project_id" name="project_id" class="form-control">
                    <option value="">-- General Studio Architecture --</option>
                    <?php foreach ($projects as $pj): ?>
                        <option value="<?= $pj['id'] ?>"><?= e($pj['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="assigned_to">Assigned Engineer / Specialist</label>
                <select id="assigned_to" name="assigned_to" class="form-control">
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>"><?= e($u['name']) ?> (<?= e($u['role']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="due_date">Target Due Date / SLA Deadline</label>
                <input type="date" id="due_date" name="due_date" class="form-control">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 26px;">
            <label for="description">Detailed Description & Technical Requirements <span style="color:#ef4444;">*</span></label>
            <textarea id="description" name="description" rows="5" class="form-control" placeholder="Provide full context, affected URLs or endpoints, reproduction steps, or deliverable criteria..." required></textarea>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; border-top: 1px solid var(--adm-line); padding-top: 20px;">
            <a href="<?= url('/admin/crm/tickets') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                Open Support Ticket &rarr;
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
