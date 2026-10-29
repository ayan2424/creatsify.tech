<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
$activeStatus = $activeStatus ?? null;
$counts = $counts ?? ['all' => 0, 'new' => 0, 'in_discussion' => 0, 'converted' => 0, 'closed' => 0];
?>

<div class="content-header">
    <div>
        <h1 class="page-title">Client Inquiries & Project Leads</h1>
        <p class="page-subtitle">Inbound consultation requests, enterprise quotations, and product inquiries.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <a href="<?= url('/admin/crm/clients') ?>" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 6px; min-height: 40px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            <span>Clients Pipeline &rarr;</span>
        </a>
    </div>
</div>

<div class="card">
    <!-- Filter Tabs -->
    <div class="crm-filter-bar">
        <div class="crm-filter-pills">
            <a href="<?= url('/admin/inquiries') ?>" class="crm-filter-pill <?= empty($activeStatus) ? 'active' : '' ?>">
                <span>All Inquiries</span>
                <span class="pill-count"><?= $counts['all'] ?></span>
            </a>
            <a href="<?= url('/admin/inquiries?status=new') ?>" class="crm-filter-pill <?= $activeStatus === 'new' ? 'active' : '' ?>">
                <span>New</span>
                <span class="pill-count" style="<?= $activeStatus !== 'new' ? 'background: rgba(16, 185, 129, 0.2); color: #10b981;' : '' ?>"><?= $counts['new'] ?></span>
            </a>
            <a href="<?= url('/admin/inquiries?status=in_discussion') ?>" class="crm-filter-pill <?= $activeStatus === 'in_discussion' ? 'active' : '' ?>">
                <span>In Discussion</span>
                <span class="pill-count"><?= $counts['in_discussion'] ?></span>
            </a>
            <a href="<?= url('/admin/inquiries?status=converted') ?>" class="crm-filter-pill <?= $activeStatus === 'converted' ? 'active' : '' ?>">
                <span>Converted</span>
                <span class="pill-count"><?= $counts['converted'] ?></span>
            </a>
            <a href="<?= url('/admin/inquiries?status=closed') ?>" class="crm-filter-pill <?= $activeStatus === 'closed' ? 'active' : '' ?>">
                <span>Closed</span>
                <span class="pill-count"><?= $counts['closed'] ?></span>
            </a>
        </div>
    </div>

    <?php if (empty($inquiries)): ?>
        <div style="padding: 48px 20px; text-align: center; color: var(--muted);">
            <div style="margin-bottom: 12px; display: flex; justify-content: center;">
                <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--muted); opacity: 0.6;"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
            </div>
            <div style="font-size: 15px; font-weight: 600; color: var(--text);">No inquiries found</div>
            <p style="font-size: 13px; margin-top: 4px;">There are no client inquiries matching this filter tab.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Project Focus</th>
                        <th>Direct Contact</th>
                        <th>Status</th>
                        <th>Received</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inquiries as $inq): ?>
                        <?php
                        $status = $inq['status'] ?? 'new';
                        $badgeStyle = match($status) {
                            'new' => 'background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);',
                            'in_discussion' => 'background: rgba(245, 158, 11, 0.12); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3);',
                            'converted' => 'background: rgba(139, 123, 255, 0.12); color: #8b7bff; border: 1px solid rgba(139, 123, 255, 0.3);',
                            'closed' => 'background: rgba(163, 167, 187, 0.12); color: #a3a7bb; border: 1px solid rgba(163, 167, 187, 0.2);',
                            default => 'background: var(--surface-hover); color: var(--muted); border: 1px solid var(--line);',
                        };
                        $phoneClean = clean_phone_number($inq['phone'] ?? '', false);
                        ?>
                        <tr>
                            <td>
                                <strong style="color:var(--text); font-size: 14px;"><?= e($inq['name']) ?></strong>
                                <div style="font-size:12px; color:var(--muted); margin-top: 2px;">
                                    <a href="mailto:<?= e($inq['email']) ?>" style="color: var(--muted); text-decoration: none;"><?= e($inq['email']) ?></a>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 13px; font-weight: 500; color: var(--text); background: var(--bg); padding: 4px 10px; border-radius: 6px; border: 1px solid var(--line);">
                                    <?= e($inq['project_type']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($phoneClean)): ?>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span style="font-size: 12.5px; color: var(--text); font-weight: 600;"><?= e(format_phone_display($inq['phone'])) ?></span>
                                        <a href="<?= url('/admin/whatsapp?phone=' . urlencode($phoneClean)) ?>" title="Open WhatsApp Desk" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; background: rgba(37, 211, 102, 0.15); color: #25D366; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 700;">
                                            WA
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <span style="color:var(--muted); font-size: 12px;">Email only</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; <?= $badgeStyle ?>">
                                    <?php if ($status === 'new'): ?>
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; animation: pulse 1.8s infinite;"></span>
                                    <?php endif; ?>
                                    <?= e(str_replace('_', ' ', $status)) ?>
                                </span>
                            </td>
                            <td style="font-size:12px; color:var(--muted); white-space: nowrap;">
                                <?= date('M j, Y', strtotime($inq['created_at'])) ?>
                                <div style="font-size: 11px; color: var(--muted); opacity: 0.7;"><?= date('H:i', strtotime($inq['created_at'])) ?></div>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                    <a href="<?= url('/admin/inquiries/' . $inq['id']) ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; min-height: 36px; padding: 6px 12px;">
                                        <span>Inspect</span>
                                        <span style="font-size: 13px;">&rarr;</span>
                                    </a>
                                    <form action="<?= url('/admin/inquiries/' . $inq['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this inquiry?');" style="margin: 0;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-secondary btn-sm" style="color: #ef4444; min-height: 36px; padding: 6px 12px;">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
