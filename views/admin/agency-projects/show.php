<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
$statusLabels = ['planning' => 'Planning', 'active' => 'Active', 'on_hold' => 'On hold', 'delivered' => 'Delivered', 'archived' => 'Archived'];
$milestoneLabels = ['not_started' => 'Not started', 'in_progress' => 'In progress', 'review' => 'In review', 'complete' => 'Complete'];
$taskLabels = ['todo' => 'To do', 'in_progress' => 'In progress', 'blocked' => 'Blocked', 'done' => 'Done'];
$assignedIds = array_column($members, 'team_member_id');
?>

<div class="content-header">
    <div>
        <a href="<?= url('/admin/agency-projects') ?>" style="font-size:12px; color:var(--adm-muted); text-decoration:none;">&larr; Delivery workspace</a>
        <h1 class="page-title" style="margin-top:8px;"><?= e($project['title']) ?></h1>
        <p class="page-subtitle"><?= e($project['project_code']) ?> · <?= e($project['project_type']) ?> · private agency delivery record</p>
    </div>
    <div style="display:flex; align-items:center; gap:10px;">
        <?php if ($project['client_id']): ?><a class="btn btn-secondary btn-sm" href="<?= url('/admin/crm/clients/' . $project['client_id']) ?>">Open CRM client</a><?php endif; ?>
        <form action="<?= url('/admin/agency-projects/' . $project['id'] . '/status') ?>" method="POST" style="display:flex; gap:7px; margin:0;">
            <?= csrf_field() ?>
            <select class="form-control form-control-sm" name="status" onchange="this.form.submit()" aria-label="Project status">
                <?php foreach ($statusLabels as $value => $label): ?><option value="<?= e($value) ?>" <?= $project['status'] === $value ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?>
            </select>
        </form>
    </div>
</div>

<div class="card" style="padding:22px; margin-bottom:22px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:18px;">
        <div><span style="display:block; font-size:11px; text-transform:uppercase; color:var(--adm-muted);">Client</span><strong><?= e($project['client_company'] ?: $project['client_name'] ?: 'Internal initiative') ?></strong></div>
        <div><span style="display:block; font-size:11px; text-transform:uppercase; color:var(--adm-muted);">Value</span><strong><?= e($project['currency']) ?> <?= number_format((float)$project['deal_value'], 2) ?></strong></div>
        <div><span style="display:block; font-size:11px; text-transform:uppercase; color:var(--adm-muted);">Start / target</span><strong><?= $project['start_date'] ? e(date('M j, Y', strtotime($project['start_date']))) : '—' ?> → <?= $project['target_date'] ? e(date('M j, Y', strtotime($project['target_date']))) : '—' ?></strong></div>
        <div><span style="display:block; font-size:11px; text-transform:uppercase; color:var(--adm-muted);">Priority</span><strong style="text-transform:capitalize;"><?= e($project['priority']) ?></strong></div>
    </div>
    <?php if ($project['summary']): ?><p style="margin:18px 0 0; color:var(--adm-text); line-height:1.6;"><?= nl2br(e($project['summary'])) ?></p><?php endif; ?>
    <?php if ($project['scope_notes']): ?><details style="margin-top:16px;"><summary style="cursor:pointer; color:var(--adm-accent); font-weight:600;">Internal scope and constraints</summary><p style="white-space:pre-wrap; color:var(--adm-muted); line-height:1.65; margin:12px 0 0;"><?= e($project['scope_notes']) ?></p></details><?php endif; ?>
</div>

<div class="crm-ticket-grid">
    <div style="display:flex; flex-direction:column; gap:22px;">
        <section class="card" id="milestones">
            <div class="card-header"><div><h2 class="card-title">Milestones</h2><p style="font-size:12px; color:var(--adm-muted); margin:4px 0 0;">Commercial and delivery checkpoints.</p></div></div>
            <div style="padding:0 20px 18px;">
                <?php if (!$milestones): ?><p style="color:var(--adm-muted); font-size:13px;">No milestones yet. Add the first agreed checkpoint below.</p><?php endif; ?>
                <?php foreach ($milestones as $milestone): ?>
                    <div style="padding:14px 0; border-bottom:1px solid var(--adm-line); display:flex; justify-content:space-between; gap:14px; flex-wrap:wrap;">
                        <div><strong><?= e($milestone['title']) ?></strong><?php if ($milestone['description']): ?><p style="margin:5px 0 0; font-size:12px; color:var(--adm-muted);"><?= e($milestone['description']) ?></p><?php endif; ?><div style="margin-top:7px; font-size:11px; color:var(--adm-muted);"><?= $milestone['due_date'] ? 'Due ' . e(date('M j, Y', strtotime($milestone['due_date']))) : 'No due date' ?><?= $milestone['amount'] !== null ? ' · ' . e($project['currency']) . ' ' . number_format((float)$milestone['amount'], 2) : '' ?></div></div>
                        <form action="<?= url('/admin/agency-projects/' . $project['id'] . '/milestones/' . $milestone['id'] . '/status') ?>" method="POST" style="margin:0; align-self:center;">
                            <?= csrf_field() ?><select class="form-control form-control-sm" name="status" onchange="this.form.submit()" aria-label="Milestone status"><?php foreach ($milestoneLabels as $value => $label): ?><option value="<?= e($value) ?>" <?= $milestone['status'] === $value ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select>
                        </form>
                    </div>
                <?php endforeach; ?>
                <form action="<?= url('/admin/agency-projects/' . $project['id'] . '/milestones') ?>" method="POST" style="margin-top:18px; padding-top:18px; border-top:1px solid var(--adm-line);">
                    <?= csrf_field() ?><strong style="font-size:13px;">Add milestone</strong>
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:8px; margin-top:10px;"><input class="form-control form-control-sm" name="title" required maxlength="200" placeholder="Milestone title"><input class="form-control form-control-sm" type="date" name="due_date" aria-label="Due date"><input class="form-control form-control-sm" type="number" min="0" step="0.01" name="amount" placeholder="Amount"></div>
                    <textarea class="form-control form-control-sm" name="description" maxlength="2000" rows="2" style="margin-top:8px;" placeholder="Private delivery context (optional)"></textarea>
                    <button class="btn btn-secondary btn-sm" type="submit" style="margin-top:8px;">Add milestone</button>
                </form>
            </div>
        </section>

        <section class="card" id="tasks">
            <div class="card-header"><div><h2 class="card-title">Tasks</h2><p style="font-size:12px; color:var(--adm-muted); margin:4px 0 0;">Track the work the agency has committed to deliver.</p></div></div>
            <div style="padding:0 20px 18px;">
                <?php if (!$tasks): ?><p style="color:var(--adm-muted); font-size:13px;">No tasks yet. Add the next concrete action below.</p><?php endif; ?>
                <?php foreach ($tasks as $task): ?>
                    <div style="padding:14px 0; border-bottom:1px solid var(--adm-line); display:flex; justify-content:space-between; gap:14px; flex-wrap:wrap;">
                        <div><strong><?= e($task['title']) ?></strong><?php if ($task['description']): ?><p style="margin:5px 0 0; font-size:12px; color:var(--adm-muted);"><?= e($task['description']) ?></p><?php endif; ?><div style="margin-top:7px; font-size:11px; color:var(--adm-muted);"><?= e($task['assignee_name'] ?: 'Unassigned') ?><?= $task['milestone_title'] ? ' · ' . e($task['milestone_title']) : '' ?><?= $task['due_date'] ? ' · due ' . e(date('M j', strtotime($task['due_date']))) : '' ?></div></div>
                        <form action="<?= url('/admin/agency-projects/' . $project['id'] . '/tasks/' . $task['id'] . '/status') ?>" method="POST" style="margin:0; align-self:center;">
                            <?= csrf_field() ?><select class="form-control form-control-sm" name="status" onchange="this.form.submit()" aria-label="Task status"><?php foreach ($taskLabels as $value => $label): ?><option value="<?= e($value) ?>" <?= $task['status'] === $value ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select>
                        </form>
                    </div>
                <?php endforeach; ?>
                <form action="<?= url('/admin/agency-projects/' . $project['id'] . '/tasks') ?>" method="POST" style="margin-top:18px; padding-top:18px; border-top:1px solid var(--adm-line);">
                    <?= csrf_field() ?><strong style="font-size:13px;">Add task</strong>
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:8px; margin-top:10px;"><input class="form-control form-control-sm" name="title" required maxlength="200" placeholder="Concrete action"><select class="form-control form-control-sm" name="assigned_team_member_id"><option value="">Unassigned</option><?php foreach ($members as $member): ?><option value="<?= (int)$member['team_member_id'] ?>"><?= e($member['name']) ?></option><?php endforeach; ?></select><input class="form-control form-control-sm" type="date" name="due_date" aria-label="Due date"></div>
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:8px; margin-top:8px;"><select class="form-control form-control-sm" name="milestone_id"><option value="">No milestone</option><?php foreach ($milestones as $milestone): ?><option value="<?= (int)$milestone['id'] ?>"><?= e($milestone['title']) ?></option><?php endforeach; ?></select><select class="form-control form-control-sm" name="priority"><option value="medium">Medium priority</option><option value="low">Low priority</option><option value="high">High priority</option><option value="urgent">Urgent</option></select></div>
                    <textarea class="form-control form-control-sm" name="description" maxlength="2000" rows="2" style="margin-top:8px;" placeholder="Definition of done or internal context (optional)"></textarea>
                    <button class="btn btn-secondary btn-sm" type="submit" style="margin-top:8px;">Add task</button>
                </form>
            </div>
        </section>

        <section class="card" id="notes">
            <div class="card-header"><div><h2 class="card-title">Internal notes</h2><p style="font-size:12px; color:var(--adm-muted); margin:4px 0 0;">Never public and not visible to clients.</p></div></div>
            <div style="padding:0 20px 18px;">
                <form action="<?= url('/admin/agency-projects/' . $project['id'] . '/notes') ?>" method="POST">
                    <?= csrf_field() ?><textarea class="form-control" name="body" required maxlength="15000" rows="4" placeholder="Decision, risk, meeting outcome, or handoff note"></textarea><button class="btn btn-secondary btn-sm" type="submit" style="margin-top:8px;">Save internal note</button>
                </form>
                <?php foreach ($notes as $note): ?><article style="padding:15px 0; border-top:1px solid var(--adm-line); margin-top:15px;"><div style="font-size:11px; color:var(--adm-muted); margin-bottom:6px;"><?= e($note['author_name']) ?> · <?= e(date('M j, Y g:i A', strtotime($note['created_at']))) ?></div><div style="white-space:pre-wrap; color:var(--adm-text); line-height:1.6;"><?= e($note['body']) ?></div></article><?php endforeach; ?>
            </div>
        </section>
    </div>

    <aside style="display:flex; flex-direction:column; gap:22px;">
        <section class="card">
            <div class="card-header"><div><h2 class="card-title">Assigned team</h2><p style="font-size:12px; color:var(--adm-muted); margin:4px 0 0;">People accountable for this delivery.</p></div></div>
            <div style="padding:0 20px 18px;">
                <?php if (!$members): ?><p style="color:var(--adm-muted); font-size:13px;">Nobody assigned yet.</p><?php endif; ?>
                <?php foreach ($members as $member): ?>
                    <div style="display:flex; align-items:center; gap:10px; padding:12px 0; border-bottom:1px solid var(--adm-line);">
                        <img src="<?= asset($member['avatar'] ?: 'brand/creatsify-icon.png') ?>" alt="" style="width:32px; height:32px; border-radius:50%; object-fit:cover;"><div style="min-width:0; flex:1;"><strong style="font-size:13px;"><?= e($member['name']) ?></strong><div style="font-size:11px; color:var(--adm-muted);"><?= e($member['delivery_role']) ?> · <?= (int)$member['allocation_percent'] ?>%</div></div>
                        <form action="<?= url('/admin/agency-projects/' . $project['id'] . '/members/' . $member['id'] . '/remove') ?>" method="POST" style="margin:0;" onsubmit="return confirm('Remove this member from the delivery team?');"><?= csrf_field() ?><button class="btn btn-secondary btn-sm" style="color:var(--danger);" type="submit">Remove</button></form>
                    </div>
                <?php endforeach; ?>
                <form action="<?= url('/admin/agency-projects/' . $project['id'] . '/members') ?>" method="POST" style="margin-top:18px; padding-top:18px; border-top:1px solid var(--adm-line);">
                    <?= csrf_field() ?><select class="form-control form-control-sm" name="team_member_id" required><option value="">Select active team member</option><?php foreach ($teamMembers as $member): ?><option value="<?= (int)$member['id'] ?>" <?= in_array((int)$member['id'], array_map('intval', $assignedIds), true) ? 'disabled' : '' ?>><?= e($member['name']) ?> — <?= e($member['role_title']) ?></option><?php endforeach; ?></select>
                    <input class="form-control form-control-sm" name="delivery_role" maxlength="120" placeholder="Delivery role (uses profile role if blank)" style="margin-top:8px;"><div style="display:flex; gap:8px; margin-top:8px;"><input class="form-control form-control-sm" type="number" min="1" max="100" name="allocation_percent" value="100" aria-label="Allocation percentage"><button class="btn btn-secondary btn-sm" type="submit">Assign</button></div>
                </form>
            </div>
        </section>
    </aside>
</div>
