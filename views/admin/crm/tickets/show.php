<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Ticket #' . ($ticket['ticket_number'] ?? ''), 'meta_title' => 'Ticket #' . ($ticket['ticket_number'] ?? '') . ' | Creatsify Admin']);

$pColors = [
    'urgent' => ['color' => '#ef4444', 'bg' => 'rgba(239, 68, 68, 0.12)', 'border' => 'rgba(239, 68, 68, 0.3)'],
    'high' => ['color' => '#f97316', 'bg' => 'rgba(249, 115, 22, 0.12)', 'border' => 'rgba(249, 115, 22, 0.3)'],
    'medium' => ['color' => '#f59e0b', 'bg' => 'rgba(245, 158, 11, 0.12)', 'border' => 'rgba(245, 158, 11, 0.3)'],
    'low' => ['color' => '#10b981', 'bg' => 'rgba(16, 185, 129, 0.12)', 'border' => 'rgba(16, 185, 129, 0.3)'],
];
$pc = $pColors[$ticket['priority']] ?? $pColors['medium'];

$sColors = [
    'open' => ['color' => '#ef4444', 'bg' => 'rgba(239, 68, 68, 0.12)', 'border' => 'rgba(239, 68, 68, 0.25)'],
    'in_progress' => ['color' => '#f59e0b', 'bg' => 'rgba(245, 158, 11, 0.12)', 'border' => 'rgba(245, 158, 11, 0.25)'],
    'waiting_client' => ['color' => '#3b82f6', 'bg' => 'rgba(59, 130, 246, 0.12)', 'border' => 'rgba(59, 130, 246, 0.25)'],
    'resolved' => ['color' => '#10b981', 'bg' => 'rgba(16, 185, 129, 0.12)', 'border' => 'rgba(16, 185, 129, 0.25)'],
    'closed' => ['color' => '#94a3b8', 'bg' => 'rgba(148, 163, 184, 0.12)', 'border' => 'rgba(148, 163, 184, 0.25)'],
];
$sc = $sColors[$ticket['status']] ?? $sColors['open'];
$phoneClean = clean_phone_number($ticket['client_whatsapp'] ?: ($ticket['client_phone'] ?? ''), false);
?>

<div class="content-header">
    <div>
        <a href="<?= url('/admin/crm/tickets') ?>" class="btn btn-secondary btn-sm" style="margin-bottom: 8px;">&larr; Back to Tickets</a>
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <span style="font-family: monospace; font-size: 15px; font-weight: 700; color: var(--adm-accent);"><?= e($ticket['ticket_number']) ?></span>
            <h1 class="page-title" style="margin-bottom: 0; font-size: 18px;"><?= e($ticket['subject']) ?></h1>
            <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; background: <?= $pc['bg'] ?>; color: <?= $pc['color'] ?>; border: 1px solid <?= $pc['border'] ?>; text-transform: uppercase;">
                ● <?= e($ticket['priority']) ?>
            </span>
            <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 12px; background: <?= $sc['bg'] ?>; color: <?= $sc['color'] ?>; border: 1px solid <?= $sc['border'] ?>;">
                <?= e(ucwords(str_replace('_', ' ', $ticket['status']))) ?>
            </span>
        </div>
    </div>

    <div style="display: flex; gap: 8px;">
        <?php if (!empty($phoneClean)): ?>
            <a href="https://wa.me/<?= $phoneClean ?>?text=<?= urlencode('Hello ' . $ticket['client_name'] . ', regarding ticket ' . $ticket['ticket_number'] . ' (' . $ticket['subject'] . '): ') ?>" target="_blank" rel="noopener" class="btn btn-sm" style="background: #25D366; color: #000; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <span>WhatsApp Client</span>
            </a>
        <?php endif; ?>
        <a href="mailto:<?= e($ticket['client_email']) ?>?subject=<?= urlencode('[' . $ticket['ticket_number'] . '] ' . $ticket['subject']) ?>" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <span>Email Client</span>
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px;">
    <!-- Left Column: Conversation Stream & Composer -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <!-- Original Description Card -->
        <div class="card" style="padding: 22px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid var(--adm-line); padding-bottom: 12px;">
                <div>
                    <strong style="color: var(--adm-text); font-size: 14px;"><?= e($ticket['client_name']) ?></strong>
                    <span style="font-size: 12px; color: var(--adm-muted); margin-left: 6px;">opened this ticket</span>
                </div>
                <span style="font-size: 12px; color: var(--adm-muted);">
                    <?= date('F j, Y \a\t H:i', strtotime($ticket['created_at'])) ?>
                </span>
            </div>
            <div style="font-size: 13.5px; color: var(--adm-text); line-height: 1.6; white-space: pre-wrap;">
                <?= e($ticket['description']) ?>
            </div>
        </div>

        <!-- Replies & Internal Notes Stream -->
        <div style="display: flex; flex-direction: column; gap: 14px;">
            <?php foreach ($replies as $r): ?>
                <?php 
                $isInternal = (bool)$r['is_internal_note'];
                $isStaff = ($r['author_type'] === 'staff');
                ?>
                <div class="card" style="padding: 18px; <?= $isInternal ? 'background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.3);' : '' ?>">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 28px; height: 28px; border-radius: 50%; background: <?= $isInternal ? 'rgba(245, 158, 11, 0.2)' : ($isStaff ? 'rgba(139, 123, 255, 0.2)' : 'rgba(59, 130, 246, 0.2)') ?>; color: <?= $isInternal ? '#f59e0b' : ($isStaff ? 'var(--adm-accent)' : '#3b82f6') ?>; display: flex; align-items: center; justify-content: center; font-size: 11.5px; font-weight: 700;">
                                <?= strtoupper(substr($r['author_name'], 0, 1)) ?>
                            </div>
                            <strong style="font-size: 13.5px; color: var(--adm-text);"><?= e($r['author_name']) ?></strong>
                            <?php if ($isInternal): ?>
                                <span style="font-size: 10.5px; font-weight: 700; color: #f59e0b; background: rgba(245, 158, 11, 0.15); padding: 1px 7px; border-radius: 4px; border: 1px solid rgba(245, 158, 11, 0.3);">
                                    STAFF ONLY NOTE
                                </span>
                            <?php elseif ($isStaff): ?>
                                <span class="user-role-badge" style="font-size: 10px;">STUDIO ENGINEER</span>
                            <?php else: ?>
                                <span style="font-size: 10.5px; color: var(--adm-muted); background: var(--adm-bg); padding: 1px 6px; border-radius: 4px;">CLIENT</span>
                            <?php endif; ?>
                        </div>
                        <span style="font-size: 11.5px; color: var(--adm-muted);">
                            <?= date('M j, Y H:i', strtotime($r['created_at'])) ?>
                        </span>
                    </div>

                    <div style="font-size: 13px; color: var(--adm-text); line-height: 1.6; white-space: pre-wrap;">
                        <?= e($r['message']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Reply Composer -->
        <div class="card" style="padding: 22px;">
            <h3 style="font-size: 14px; font-weight: 700; color: var(--adm-text); margin-bottom: 14px;">Post Response or Private Staff Note</h3>
            
            <form action="<?= url('/admin/crm/tickets/' . $ticket['id'] . '/reply') ?>" method="POST">
                <?= csrf_field() ?>

                <div style="margin-bottom: 14px;">
                    <textarea name="message" id="replyMessage" rows="4" class="form-control" placeholder="Type your response to the client or log technical findings..." required style="font-size: 13px;"></textarea>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; border-top: 1px solid var(--adm-line); padding-top: 14px;">
                    <div style="display: flex; gap: 16px; align-items: center;">
                        <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; color: var(--adm-text); cursor: pointer; margin: 0;">
                            <input type="radio" name="is_internal_note" value="0" checked>
                            <span>Client-Visible Reply</span>
                        </label>
                        <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; color: #f59e0b; cursor: pointer; margin: 0; font-weight: 600;">
                            <input type="radio" name="is_internal_note" value="1">
                            <span>Private Staff Note Only</span>
                        </label>
                    </div>

                    <div style="display: flex; gap: 10px; align-items: center;">
                        <select name="update_status" class="form-control form-control-sm" style="font-size: 12px; width: 140px;">
                            <option value="">Leave Status (<?= e($ticket['status']) ?>)</option>
                            <option value="in_progress">Set In Progress</option>
                            <option value="waiting_client">Set Waiting Client</option>
                            <option value="resolved">Mark Resolved</option>
                            <option value="closed">Close Ticket</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm" style="padding: 6px 18px;">
                            Submit Post &rarr;
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Ticket Telemetry & Client File -->
    <div style="display: flex; flex-direction: column; gap: 18px;">
        <!-- Status & Assignment Card -->
        <div class="card" style="padding: 20px;">
            <h4 style="font-size: 13px; font-weight: 700; color: var(--adm-text); margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.05em;">Ticket Controls</h4>
            
            <form action="<?= url('/admin/crm/tickets/' . $ticket['id'] . '/status') ?>" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="status" style="font-size: 11px; text-transform: uppercase;">Status</label>
                    <select id="status" name="status" class="form-control form-control-sm">
                        <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                        <option value="in_progress" <?= $ticket['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="waiting_client" <?= $ticket['status'] === 'waiting_client' ? 'selected' : '' ?>>Waiting Client</option>
                        <option value="resolved" <?= $ticket['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                        <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="priority" style="font-size: 11px; text-transform: uppercase;">Priority</label>
                    <select id="priority" name="priority" class="form-control form-control-sm">
                        <option value="urgent" <?= $ticket['priority'] === 'urgent' ? 'selected' : '' ?>>Urgent</option>
                        <option value="high" <?= $ticket['priority'] === 'high' ? 'selected' : '' ?>>High</option>
                        <option value="medium" <?= $ticket['priority'] === 'medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="low" <?= $ticket['priority'] === 'low' ? 'selected' : '' ?>>Low</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="assigned_to" style="font-size: 11px; text-transform: uppercase;">Assigned Specialist</label>
                    <select id="assigned_to" name="assigned_to" class="form-control form-control-sm">
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['id'] ?>" <?= (int)$ticket['assigned_to'] === (int)$u['id'] ? 'selected' : '' ?>>
                                <?= e($u['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-secondary btn-sm" style="width: 100%; margin-top: 4px;">
                    Apply Changes
                </button>
            </form>
        </div>

        <!-- Client Info Card -->
        <div class="card" style="padding: 20px;">
            <h4 style="font-size: 13px; font-weight: 700; color: var(--adm-text); margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.05em;">Client Profile</h4>

            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 12.5px;">
                <div>
                    <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">Client Name</span>
                    <?php if (!empty($ticket['client_id'])): ?>
                        <a href="<?= url('/admin/crm/clients/' . $ticket['client_id']) ?>" style="font-weight: 600; color: var(--adm-accent); text-decoration: none;">
                            <?= e($ticket['client_name']) ?> &rarr;
                        </a>
                    <?php else: ?>
                        <strong style="color: var(--adm-text);"><?= e($ticket['client_name']) ?></strong>
                    <?php endif; ?>
                </div>

                <?php if (!empty($ticket['client_company'])): ?>
                    <div>
                        <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">Company</span>
                        <span style="color: var(--adm-text);"><?= e($ticket['client_company']) ?></span>
                    </div>
                <?php endif; ?>

                <div>
                    <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">Email</span>
                    <a href="mailto:<?= e($ticket['client_email']) ?>" style="color: var(--adm-text);">
                        <?= e($ticket['client_email']) ?>
                    </a>
                </div>

                <?php if (!empty($ticket['client_phone'])): ?>
                    <div>
                        <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">Phone</span>
                        <span style="color: var(--adm-text);"><?= e($ticket['client_phone']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($ticket['project_title'])): ?>
                    <div>
                        <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">System / Product</span>
                        <span style="color: var(--adm-text); font-weight: 600;"><?= e($ticket['project_title']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($ticket['due_date'])): ?>
                    <div>
                        <span style="color: var(--adm-muted); font-size: 11px; display: block; text-transform: uppercase;">SLA Deadline</span>
                        <span style="color: #f59e0b; font-weight: 600;"><?= date('F j, Y', strtotime($ticket['due_date'])) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="card" style="padding: 16px; text-align: center;">
            <form action="<?= url('/admin/crm/tickets/' . $ticket['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this ticket and all conversation history?');" style="margin: 0;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-sm" style="color: #ef4444; background: transparent; border: 1px solid rgba(239, 68, 68, 0.3); width: 100%; font-size: 11.5px;">
                    Delete Ticket File
                </button>
            </form>
        </div>
    </div>
</div>
