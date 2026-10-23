<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
$activeStatus = $activeStatus ?? null;
$counts = $counts ?? ['all' => 0, 'new' => 0, 'in_discussion' => 0, 'converted' => 0, 'closed' => 0];
?>

<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h3 class="card-title">Client Inquiries & Project Leads</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Inbound consultation requests, enterprise quotations, and product inquiries.</p>
        </div>

        <!-- Filter Tabs -->
        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
            <a href="<?= url('/admin/inquiries') ?>" class="btn btn-sm <?= empty($activeStatus) ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: 20px; font-size: 12px; padding: 6px 14px;">
                All <span style="opacity: 0.8; margin-left: 4px; font-size: 11px;"><?= $counts['all'] ?></span>
            </a>
            <a href="<?= url('/admin/inquiries?status=new') ?>" class="btn btn-sm <?= $activeStatus === 'new' ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: 20px; font-size: 12px; padding: 6px 14px;">
                New <span style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 1px 6px; border-radius: 10px; margin-left: 4px; font-size: 11px; font-weight: 700;"><?= $counts['new'] ?></span>
            </a>
            <a href="<?= url('/admin/inquiries?status=in_discussion') ?>" class="btn btn-sm <?= $activeStatus === 'in_discussion' ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: 20px; font-size: 12px; padding: 6px 14px;">
                In Discussion <span style="opacity: 0.8; margin-left: 4px; font-size: 11px;"><?= $counts['in_discussion'] ?></span>
            </a>
            <a href="<?= url('/admin/inquiries?status=converted') ?>" class="btn btn-sm <?= $activeStatus === 'converted' ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: 20px; font-size: 12px; padding: 6px 14px;">
                Converted <span style="opacity: 0.8; margin-left: 4px; font-size: 11px;"><?= $counts['converted'] ?></span>
            </a>
            <a href="<?= url('/admin/inquiries?status=closed') ?>" class="btn btn-sm <?= $activeStatus === 'closed' ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: 20px; font-size: 12px; padding: 6px 14px;">
                Closed <span style="opacity: 0.8; margin-left: 4px; font-size: 11px;"><?= $counts['closed'] ?></span>
            </a>
        </div>
    </div>

    <?php if (empty($inquiries)): ?>
        <div style="padding: 48px 20px; text-align: center; color: var(--muted);">
            <div style="font-size: 32px; margin-bottom: 8px;">📬</div>
            <div style="font-size: 15px; font-weight: 600; color: var(--text);">No inquiries found</div>
            <p style="font-size: 13px; margin-top: 4px;">There are no client inquiries matching this filter tab.</p>
        </div>
    <?php else: ?>
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
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 6px; justify-content: flex-end;">
                                <a href="<?= url('/admin/inquiries/' . $inq['id']) ?>" class="btn btn-secondary btn-sm" style="font-size: 12px;">Inspect &rarr;</a>
                                <form action="<?= url('/admin/inquiries/' . $inq['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this inquiry?');" style="margin: 0;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--danger); font-size: 12px;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
