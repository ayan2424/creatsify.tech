<div class="content-header">
    <div>
        <h1 class="page-title">CRM Notes, Call Logs & Reminders</h1>
        <p class="page-subtitle">Internal agency memos, phone conversation summaries, agreed deal terms, and scheduled follow-ups.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="<?= url('/admin/crm/clients') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            <span>Clients Pipeline &rarr;</span>
        </a>
    </div>
</div>

<!-- Pending Follow-ups Due Ribbon -->
<?php if (!empty($pendingFollowUps)): ?>
    <div class="card" style="margin-bottom: 24px; border: 1px solid rgba(245, 158, 11, 0.4); background: rgba(245, 158, 11, 0.04);">
        <div class="card-header" style="border-bottom: 1px solid rgba(245, 158, 11, 0.2);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <h3 class="card-title" style="color: #f59e0b; font-size: 14px;">Pending Follow-Up Actions & Scheduled Reminders</h3>
            </div>
            <span style="font-size: 11.5px; font-weight: 700; color: #f59e0b; background: rgba(245, 158, 11, 0.15); padding: 2px 8px; border-radius: 10px;">
                <?= count($pendingFollowUps) ?> Action<?= count($pendingFollowUps) > 1 ? 's' : '' ?> Due
            </span>
        </div>
        <div style="padding: 16px; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 12px;">
            <?php foreach ($pendingFollowUps as $fu): ?>
                <?php
                $isOverdue = strtotime($fu['follow_up_date']) < strtotime(date('Y-m-d'));
                ?>
                <div style="padding: 12px 14px; border-radius: 8px; background: var(--adm-surface); border: 1px solid <?= $isOverdue ? 'rgba(239, 68, 68, 0.4)' : 'var(--adm-line)' ?>; display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: <?= $isOverdue ? '#ef4444' : '#f59e0b' ?>; text-transform: uppercase;">
                            <?= $isOverdue ? 'OVERDUE' : 'DUE' ?> · <?= date('M j, Y', strtotime($fu['follow_up_date'])) ?>
                        </div>
                        <strong style="color: var(--adm-text); font-size: 13px; display: block; margin-top: 2px;"><?= e($fu['title']) ?></strong>
                        <?php if (!empty($fu['client_name'])): ?>
                            <div style="font-size: 11.5px; color: var(--adm-muted); margin-top: 2px;">
                                Client: <?= e($fu['client_name']) ?> <?= !empty($fu['client_company']) ? '(' . e($fu['client_company']) . ')' : '' ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <form action="<?= url('/admin/crm/notes/' . $fu['id'] . '/toggle-followup') ?>" method="POST" style="margin: 0; flex-shrink: 0;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="redirect" value="<?= url('/admin/crm/notes') ?>">
                        <button type="submit" class="btn btn-primary btn-sm" style="font-size: 11px; padding: 4px 8px;" title="Mark this follow-up as completed">
                            ✓ Done
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Quick Note Composer Card -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3 class="card-title">Log Internal Note, Call Minutes or Follow-Up</h3>
        <span style="font-size: 12px; color: var(--adm-muted);">Instantly records to timeline & linked client file</span>
    </div>

    <form action="<?= url('/admin/crm/notes') ?>" method="POST" style="padding: 20px;">
        <?= csrf_field() ?>
        <input type="hidden" name="redirect" value="<?= url('/admin/crm/notes') ?>">

        <div style="display: grid; grid-template-columns: 1fr 180px 220px; gap: 12px; margin-bottom: 12px;">
            <div class="form-group">
                <label for="title" style="font-size: 11px; text-transform: uppercase;">Note Subject / Meeting Topic <span style="color:#ef4444;">*</span></label>
                <input type="text" id="title" name="title" class="form-control form-control-sm" placeholder="e.g. Call with Tariq re: linehaul hub expansion" required>
            </div>

            <div class="form-group">
                <label for="type" style="font-size: 11px; text-transform: uppercase;">Record Type</label>
                <select id="type" name="type" class="form-control form-control-sm">
                    <option value="note">General Memo</option>
                    <option value="call_log">Phone / WhatsApp Call</option>
                    <option value="meeting">Zoom / In-Person Meeting</option>
                    <option value="deal_terms">Deal Terms & Payment</option>
                    <option value="follow_up">Follow-Up Action</option>
                </select>
            </div>

            <div class="form-group">
                <label for="client_id" style="font-size: 11px; text-transform: uppercase;">Link Client (Optional)</label>
                <select id="client_id" name="client_id" class="form-control form-control-sm">
                    <option value="">-- Global Agency Memo --</option>
                    <?php foreach ($clients as $cl): ?>
                        <option value="<?= $cl['id'] ?>"><?= e($cl['name']) ?> <?= !empty($cl['company']) ? '· ' . e($cl['company']) : '' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 12px;">
            <textarea name="content" rows="3" class="form-control" placeholder="Write internal notes, discussion summary, deliverables, or action items..." required style="font-size: 13px;"></textarea>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; gap: 16px; align-items: center;">
                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: var(--adm-muted); cursor: pointer; margin: 0;">
                    <input type="checkbox" name="is_pinned" value="1">
                    <span>Pin to top</span>
                </label>
                <div style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: var(--adm-muted);">
                    <span>Follow-Up Due:</span>
                    <input type="date" name="follow_up_date" class="form-control form-control-sm" style="width: 140px; font-size: 11.5px; padding: 2px 6px;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-sm" style="padding: 6px 18px;">
                Save CRM Note &rarr;
            </button>
        </div>
    </form>
</div>

<!-- Notes Stream with Filter Tabs -->
<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 14px; border-bottom: 1px solid var(--adm-line);">
        <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
            <?php
            $types = [
                '' => ['label' => 'All Records', 'count' => $typeCounts['all'] ?? 0],
                'note' => ['label' => 'General Memos', 'count' => $typeCounts['note'] ?? 0],
                'call_log' => ['label' => 'Call Logs', 'count' => $typeCounts['call_log'] ?? 0],
                'meeting' => ['label' => 'Meetings', 'count' => $typeCounts['meeting'] ?? 0],
                'deal_terms' => ['label' => 'Deal Terms', 'count' => $typeCounts['deal_terms'] ?? 0],
                'follow_up' => ['label' => 'Follow-Ups', 'count' => $typeCounts['follow_up'] ?? 0],
            ];
            ?>
            <?php foreach ($types as $key => $t): ?>
                <?php $isSelected = ($activeType === $key || (empty($activeType) && $key === '')); ?>
                <a href="<?= url('/admin/crm/notes' . ($key ? '?type=' . $key : '')) ?>" 
                   class="btn btn-sm <?= $isSelected ? 'btn-primary' : 'btn-secondary' ?>" 
                   style="border-radius: 20px; font-size: 11.5px; padding: 5px 12px; white-space: nowrap;">
                    <span><?= e($t['label']) ?></span>
                    <span style="opacity: 0.8; margin-left: 4px; font-size: 10.5px;"><?= $t['count'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <form action="<?= url('/admin/crm/notes') ?>" method="GET" style="display: flex; gap: 8px; margin: 0;">
            <?php if (!empty($activeType)): ?>
                <input type="hidden" name="type" value="<?= e($activeType) ?>">
            <?php endif; ?>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search notes..." value="<?= e($search ?? '') ?>" style="width: 200px; font-size: 12.5px;">
            <button type="submit" class="btn btn-secondary btn-sm" style="font-size: 12px;">Search</button>
            <?php if (!empty($search) || !empty($activeType)): ?>
                <a href="<?= url('/admin/crm/notes') ?>" class="btn btn-secondary btn-sm" style="font-size: 12px; color: var(--adm-muted);" title="Reset filters">&times;</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($notes)): ?>
        <div style="padding: 56px 20px; text-align: center; color: var(--adm-muted);">
            <div style="margin-bottom: 12px; display: flex; justify-content: center;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--adm-muted); opacity: 0.6;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </div>
            <div style="font-size: 15px; font-weight: 600; color: var(--adm-text);">No CRM notes found</div>
            <p style="font-size: 13px; margin-top: 4px;">Use the composer above to log your first client call or agency memo.</p>
        </div>
    <?php else: ?>
        <div style="padding: 20px; display: flex; flex-direction: column; gap: 14px;">
            <?php foreach ($notes as $n): ?>
                <div style="padding: 18px 20px; border-radius: 12px; background: var(--adm-bg); border: 1px solid <?= $n['is_pinned'] ? 'var(--adm-accent)' : 'var(--adm-line)' ?>; transition: border-color 0.2s ease;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <?php if ($n['is_pinned']): ?>
                                <span style="font-size: 10.5px; font-weight: 700; color: var(--adm-accent); background: rgba(139, 123, 255, 0.15); padding: 2px 7px; border-radius: 4px; border: 1px solid rgba(139, 123, 255, 0.3);">
                                    PINNED
                                </span>
                            <?php endif; ?>
                            <strong style="font-size: 14px; color: var(--adm-text);"><?= e($n['title']) ?></strong>
                            <span class="user-role-badge" style="font-size: 10px;"><?= e(strtoupper(str_replace('_', ' ', $n['type']))) ?></span>
                            <?php if (!empty($n['client_name'])): ?>
                                <a href="<?= url('/admin/crm/clients/' . $n['client_id']) ?>" style="font-size: 12px; color: var(--adm-accent); text-decoration: none; font-weight: 600;">
                                    · <?= e($n['client_name']) ?> <?= !empty($n['client_company']) ? '(' . e($n['client_company']) . ')' : '' ?>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($n['ticket_number'])): ?>
                                <a href="<?= url('/admin/crm/tickets/' . $n['ticket_id']) ?>" style="font-size: 11.5px; font-family: monospace; color: var(--adm-muted); text-decoration: none;">
                                    · #<?= e($n['ticket_number']) ?>
                                </a>
                            <?php endif; ?>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 11.5px; color: var(--adm-muted);">
                                <?= date('M j, Y H:i', strtotime($n['created_at'])) ?> · by <?= e($n['author_name']) ?>
                            </span>

                            <form action="<?= url('/admin/crm/notes/' . $n['id'] . '/toggle-pin') ?>" method="POST" style="margin:0;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="redirect" value="<?= url('/admin/crm/notes') ?>">
                                <button type="submit" class="btn btn-secondary btn-sm" style="font-size: 11px; padding: 2px 7px;" title="<?= $n['is_pinned'] ? 'Unpin note' : 'Pin note' ?>">
                                    <?= $n['is_pinned'] ? 'Unpin' : 'Pin' ?>
                                </button>
                            </form>

                            <form action="<?= url('/admin/crm/notes/' . $n['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this CRM note?');" style="margin:0;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="redirect" value="<?= url('/admin/crm/notes') ?>">
                                <button type="submit" style="background: none; border: none; color: var(--adm-muted); cursor: pointer; font-size: 16px; padding: 0 4px;" title="Delete note">&times;</button>
                            </form>
                        </div>
                    </div>

                    <p style="font-size: 13.5px; color: var(--adm-text); line-height: 1.6; margin: 0; white-space: pre-wrap;"><?= e($n['content']) ?></p>

                    <?php if (!empty($n['follow_up_date'])): ?>
                        <div style="margin-top: 12px; padding-top: 10px; border-top: 1px dashed var(--adm-line); display: flex; align-items: center; justify-content: space-between; font-size: 12px;">
                            <span style="color: #f59e0b; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>Follow-Up Target: <?= date('F j, Y', strtotime($n['follow_up_date'])) ?></span>
                            </span>
                            <form action="<?= url('/admin/crm/notes/' . $n['id'] . '/toggle-followup') ?>" method="POST" style="margin:0;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="redirect" value="<?= url('/admin/crm/notes') ?>">
                                <button type="submit" class="btn btn-sm" style="font-size: 11px; padding: 3px 10px; border-radius: 12px; <?= $n['is_follow_up_done'] ? 'background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 700;' : 'background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: 700;' ?>">
                                    <?= $n['is_follow_up_done'] ? '✓ Completed' : '○ Mark Done' ?>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
