<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Client Profile', 'meta_title' => ($client['name'] ?? 'Client') . ' | Creatsify Admin']);

$stageColors = [
    'lead' => ['bg' => 'rgba(100, 116, 139, 0.15)', 'color' => '#94a3b8', 'border' => 'rgba(100, 116, 139, 0.3)'],
    'discovery' => ['bg' => 'rgba(59, 130, 246, 0.15)', 'color' => '#60a5fa', 'border' => 'rgba(59, 130, 246, 0.3)'],
    'proposal_sent' => ['bg' => 'rgba(245, 158, 11, 0.15)', 'color' => '#fbbf24', 'border' => 'rgba(245, 158, 11, 0.3)'],
    'contract_signed' => ['bg' => 'rgba(16, 185, 129, 0.15)', 'color' => '#34d399', 'border' => 'rgba(16, 185, 129, 0.3)'],
    'active_client' => ['bg' => 'rgba(16, 185, 129, 0.22)', 'color' => '#10b981', 'border' => 'rgba(16, 185, 129, 0.45)'],
    'retained' => ['bg' => 'rgba(139, 123, 255, 0.2)', 'color' => 'var(--adm-accent)', 'border' => 'rgba(139, 123, 255, 0.4)'],
    'churned' => ['bg' => 'rgba(239, 68, 68, 0.15)', 'color' => '#f87171', 'border' => 'rgba(239, 68, 68, 0.3)'],
];
$sc = $stageColors[$client['stage']] ?? $stageColors['lead'];
$stageLabel = ucwords(str_replace('_', ' ', $client['stage']));
$phoneClean = clean_phone_number($client['whatsapp'] ?: ($client['phone'] ?? ''), false);
?>

<div class="content-header">
    <div>
        <a href="<?= url('/admin/crm/clients') ?>" class="btn btn-secondary btn-sm" style="margin-bottom: 8px;">&larr; Back to Clients Pipeline</a>
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <h1 class="page-title" style="margin-bottom: 0;"><?= e($client['name']) ?></h1>
            <?php if (!empty($client['company'])): ?>
                <span style="font-size: 15px; color: var(--adm-muted);">· <?= e($client['company']) ?></span>
            <?php endif; ?>
            <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 12px; background: <?= $sc['bg'] ?>; color: <?= $sc['color'] ?>; border: 1px solid <?= $sc['border'] ?>;">
                <span style="width: 5px; height: 5px; border-radius: 50%; background: currentColor;"></span>
                <span><?= e($stageLabel) ?></span>
            </span>
        </div>
    </div>

    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <?php if (!empty($phoneClean)): ?>
            <a href="https://wa.me/<?= $phoneClean ?>" target="_blank" rel="noopener" class="btn btn-sm" style="background: #25D366; color: #000; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <span>WhatsApp</span>
            </a>
        <?php endif; ?>
        <a href="mailto:<?= e($client['email']) ?>" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <span>Email Client</span>
        </a>
        <a href="<?= url('/admin/crm/tickets/create?client_id=' . $client['id']) ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
            <span>+ Open Ticket</span>
        </a>
        <a href="<?= url('/admin/agency-projects/create?client_id=' . $client['id']) ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3z"/><path d="M7 8h10M7 12h10M7 16h6"/></svg>
            <span>Start Delivery</span>
        </a>
        <a href="<?= url('/admin/crm/clients/' . $client['id'] . '/edit') ?>" class="btn btn-secondary btn-sm">Edit</a>
    </div>
</div>

<!-- Client Summary Grid -->
<div style="display: grid; grid-template-columns: 320px 1fr; gap: 24px; margin-bottom: 24px;">
    <!-- Left Column: Client File Card -->
    <div style="display: flex; flex-direction: column; gap: 18px;">
        <div class="card" style="padding: 22px;">
            <h3 style="font-size: 14px; font-weight: 700; color: var(--adm-text); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.05em;">Commercial File</h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13px;">
                <div>
                    <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">Pipeline Stage</span>
                    <form action="<?= url('/admin/crm/clients/' . $client['id'] . '/stage') ?>" method="POST" style="margin-top: 4px; display: flex; gap: 6px;">
                        <?= csrf_field() ?>
                        <select name="stage" class="form-control form-control-sm" style="font-size: 12px;" onchange="this.form.submit()">
                            <option value="lead" <?= $client['stage'] === 'lead' ? 'selected' : '' ?>>Lead</option>
                            <option value="discovery" <?= $client['stage'] === 'discovery' ? 'selected' : '' ?>>Discovery</option>
                            <option value="proposal_sent" <?= $client['stage'] === 'proposal_sent' ? 'selected' : '' ?>>Proposal Sent</option>
                            <option value="contract_signed" <?= $client['stage'] === 'contract_signed' ? 'selected' : '' ?>>Contract Signed</option>
                            <option value="active_client" <?= $client['stage'] === 'active_client' ? 'selected' : '' ?>>Active Client</option>
                            <option value="retained" <?= $client['stage'] === 'retained' ? 'selected' : '' ?>>Retained SLA</option>
                            <option value="churned" <?= $client['stage'] === 'churned' ? 'selected' : '' ?>>Churned</option>
                        </select>
                    </form>
                </div>

                <div>
                    <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">Contract / Deal Value</span>
                    <strong style="font-size: 16px; color: var(--adm-text); font-weight: 700;">
                        <?= e($client['currency']) ?> <?= number_format((float)$client['deal_value'], 2) ?>
                    </strong>
                </div>

                <div>
                    <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">Email</span>
                    <a href="mailto:<?= e($client['email']) ?>" style="color: var(--adm-accent); font-weight: 500;">
                        <?= e($client['email']) ?>
                    </a>
                </div>

                <?php if (!empty($client['phone'])): ?>
                    <div>
                        <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">Phone</span>
                        <span style="color: var(--adm-text);"><?= e($client['phone']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($client['website'])): ?>
                    <div>
                        <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">Website</span>
                        <a href="<?= e($client['website']) ?>" target="_blank" rel="noopener" style="color: var(--adm-accent);">
                            <?= e($client['website']) ?> &nearr;
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($client['city']) || !empty($client['country'])): ?>
                    <div>
                        <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">Location</span>
                        <span style="color: var(--adm-text);"><?= e(implode(', ', array_filter([$client['city'], $client['country']]))) ?></span>
                    </div>
                <?php endif; ?>

                <div>
                    <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">Acquisition Source</span>
                    <span style="color: var(--adm-text);"><?= e($client['source']) ?></span>
                </div>

                <div>
                    <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">Created On</span>
                    <span style="color: var(--adm-text);"><?= date('F j, Y', strtotime($client['created_at'])) ?></span>
                </div>
            </div>

            <?php if (!empty($client['tags'])): ?>
                <div style="margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--adm-line);">
                    <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase; margin-bottom: 6px;">Focus Tags</span>
                    <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                        <?php foreach (explode(',', $client['tags']) as $t): ?>
                            <span style="font-size: 11px; background: var(--adm-bg); color: var(--adm-text); padding: 2px 7px; border-radius: 6px; border: 1px solid var(--adm-line);">
                                <?= e(trim($t)) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($client['notes_summary'])): ?>
            <div class="card" style="padding: 20px;">
                <h4 style="font-size: 13px; font-weight: 700; color: var(--adm-text); margin-bottom: 8px;">Scope Briefing</h4>
                <p style="font-size: 12.5px; color: var(--adm-muted); line-height: 1.6; margin: 0;">
                    <?= nl2br(e($client['notes_summary'])) ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right Column: Support Tickets & Internal Notes Stream -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <!-- Section 1: Support & Milestone Tickets -->
        <div class="card" id="tickets">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Support & Milestone Tickets</h3>
                    <span style="font-size: 12px; color: var(--adm-muted);"><?= count($tickets) ?> tickets recorded</span>
                </div>
                <a href="<?= url('/admin/crm/tickets/create?client_id=' . $client['id']) ?>" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 5px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    <span>Open Ticket</span>
                </a>
            </div>

            <?php if (empty($tickets)): ?>
                <div style="padding: 32px 20px; text-align: center; color: var(--adm-muted); font-size: 13px;">
                    No tickets opened for this client yet. Click "+ Open Ticket" to track bugs, change orders, or milestone deliverables.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Ticket #</th>
                                <th>Subject</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tickets as $t): ?>
                                <tr>
                                    <td>
                                        <a href="<?= url('/admin/crm/tickets/' . $t['id']) ?>" style="font-family: monospace; font-weight: 700; color: var(--adm-accent); text-decoration: none;">
                                            <?= e($t['ticket_number']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?= url('/admin/crm/tickets/' . $t['id']) ?>" style="font-weight: 600; color: var(--adm-text); text-decoration: none; font-size: 13px;">
                                            <?= e($t['subject']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="user-role-badge" style="font-size: 10.5px;"><?= e(strtoupper($t['category'])) ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $pColors = [
                                            'urgent' => '#ef4444',
                                            'high' => '#f97316',
                                            'medium' => '#f59e0b',
                                            'low' => '#10b981'
                                        ];
                                        $pc = $pColors[$t['priority']] ?? '#94a3b8';
                                        ?>
                                        <span style="font-size: 11px; font-weight: 700; color: <?= $pc ?>; text-transform: uppercase;">
                                            ● <?= e($t['priority']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-size: 11px; font-weight: 600; padding: 2px 7px; border-radius: 8px; background: var(--adm-bg); border: 1px solid var(--adm-line);">
                                            <?= e(str_replace('_', ' ', $t['status'])) ?>
                                        </span>
                                    </td>
                                    <td style="font-size: 12px; color: var(--adm-muted);">
                                        <?= !empty($t['due_date']) ? date('M j', strtotime($t['due_date'])) : '—' ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="<?= url('/admin/crm/tickets/' . $t['id']) ?>" class="btn btn-secondary btn-sm" style="font-size: 11.5px; padding: 3px 8px;">
                                            View &rarr;
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Section 2: Internal Notes, Meeting Minutes & Activity Log -->
        <div class="card" id="notes">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Internal CRM Notes & Call Logs</h3>
                    <span style="font-size: 12px; color: var(--adm-muted);">Staff-only knowledge base for this account</span>
                </div>
            </div>

            <!-- Composer Form -->
            <form action="<?= url('/admin/crm/notes') ?>" method="POST" style="padding: 18px; border-bottom: 1px solid var(--adm-line); background: var(--adm-surface);">
                <?= csrf_field() ?>
                <input type="hidden" name="client_id" value="<?= $client['id'] ?>">
                <input type="hidden" name="redirect" value="<?= url('/admin/crm/clients/' . $client['id'] . '#notes') ?>">

                <div style="display: grid; grid-template-columns: 1fr 140px 140px; gap: 10px; margin-bottom: 10px;">
                    <input type="text" name="title" class="form-control form-control-sm" placeholder="Note headline (e.g. Discovery Call Summary, Milestone Discussion...)" required>
                    <select name="type" class="form-control form-control-sm">
                        <option value="note">General Note</option>
                        <option value="call_log">Phone / Call Log</option>
                        <option value="meeting">Meeting Minutes</option>
                        <option value="deal_terms">Deal Terms</option>
                        <option value="follow_up">Follow-Up Task</option>
                    </select>
                    <input type="date" name="follow_up_date" class="form-control form-control-sm" title="Optional Follow-up deadline">
                </div>

                <textarea name="content" rows="3" class="form-control" placeholder="Write internal memo, scope updates, or client conversation points..." required style="font-size: 13px; margin-bottom: 10px;"></textarea>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: var(--adm-muted); cursor: pointer; margin: 0;">
                        <input type="checkbox" name="is_pinned" value="1">
                        <span>Pin to top of client file</span>
                    </label>
                    <button type="submit" class="btn btn-primary btn-sm" style="padding: 5px 16px;">
                        Save CRM Note
                    </button>
                </div>
            </form>

            <!-- Notes Feed -->
            <div style="padding: 18px; display: flex; flex-direction: column; gap: 14px;">
                <?php if (empty($notes)): ?>
                    <div style="text-align: center; color: var(--adm-muted); font-size: 13px; padding: 20px 0;">
                        No internal notes logged yet. Use the box above to log meetings, agreements, or reminders.
                    </div>
                <?php else: ?>
                    <?php foreach ($notes as $n): ?>
                        <div style="padding: 14px 16px; border-radius: 10px; background: var(--adm-bg); border: 1px solid <?= $n['is_pinned'] ? 'var(--adm-accent)' : 'var(--adm-line)' ?>; position: relative;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <?php if ($n['is_pinned']): ?>
                                        <span style="font-size: 10.5px; font-weight: 700; color: var(--adm-accent); background: rgba(139, 123, 255, 0.15); padding: 1px 6px; border-radius: 4px; border: 1px solid rgba(139, 123, 255, 0.3);">PINNED</span>
                                    <?php endif; ?>
                                    <strong style="font-size: 13.5px; color: var(--adm-text);"><?= e($n['title']) ?></strong>
                                    <span class="user-role-badge" style="font-size: 10px;"><?= e(strtoupper(str_replace('_', ' ', $n['type']))) ?></span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 11.5px; color: var(--adm-muted);">
                                        <?= date('M j, Y H:i', strtotime($n['created_at'])) ?> · by <?= e($n['author_name']) ?>
                                    </span>
                                    <form action="<?= url('/admin/crm/notes/' . $n['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this note?');" style="margin:0;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="redirect" value="<?= url('/admin/crm/clients/' . $client['id'] . '#notes') ?>">
                                        <button type="submit" style="background: none; border: none; color: var(--adm-muted); cursor: pointer; font-size: 14px; padding: 0 4px;" title="Delete note">&times;</button>
                                    </form>
                                </div>
                            </div>
                            <p style="font-size: 13px; color: var(--adm-text); line-height: 1.6; margin: 0; white-space: pre-wrap;"><?= e($n['content']) ?></p>

                            <?php if (!empty($n['follow_up_date'])): ?>
                                <div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed var(--adm-line); display: flex; align-items: center; justify-content: space-between; font-size: 11.5px;">
                                    <span style="color: #f59e0b; font-weight: 600;">
                                        Follow-up target: <?= date('F j, Y', strtotime($n['follow_up_date'])) ?>
                                    </span>
                                    <form action="<?= url('/admin/crm/notes/' . $n['id'] . '/toggle-followup') ?>" method="POST" style="margin:0;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="redirect" value="<?= url('/admin/crm/clients/' . $client['id'] . '#notes') ?>">
                                        <button type="submit" class="btn btn-sm" style="font-size: 11px; padding: 2px 8px; <?= $n['is_follow_up_done'] ? 'background: rgba(16, 185, 129, 0.15); color: #10b981;' : 'background: rgba(245, 158, 11, 0.15); color: #f59e0b;' ?>">
                                            <?= $n['is_follow_up_done'] ? 'Resolved' : 'Mark Done' ?>
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
