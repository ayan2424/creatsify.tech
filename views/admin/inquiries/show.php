<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
$status = $inquiry['status'] ?? 'new';
$badgeStyle = match($status) {
    'new' => 'background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);',
    'in_discussion' => 'background: rgba(245, 158, 11, 0.12); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3);',
    'converted' => 'background: rgba(139, 123, 255, 0.12); color: #8b7bff; border: 1px solid rgba(139, 123, 255, 0.3);',
    'closed' => 'background: rgba(163, 167, 187, 0.12); color: #a3a7bb; border: 1px solid rgba(163, 167, 187, 0.2);',
    default => 'background: var(--surface-hover); color: var(--muted); border: 1px solid var(--line);',
};
$phoneClean = clean_phone_number($inquiry['phone'] ?? '', false);
$waText = urlencode("Assalam-o-Alaikum " . $inquiry['name'] . "! This is Muhammad Ayan Shamim from Creatsify Studio. I am reaching out regarding your project inquiry for " . $inquiry['project_type'] . ".");
?>

<div style="max-width: 900px;">
    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="<?= url('/admin/inquiries') ?>" class="btn btn-secondary btn-sm">&larr; Back to Inquiries</a>
            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; <?= $badgeStyle ?>">
                <?= e(str_replace('_', ' ', $status)) ?>
            </span>
        </div>

        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <?php if (!empty($phoneClean)): ?>
                <a href="https://wa.me/<?= $phoneClean ?>?text=<?= $waText ?>" target="_blank" rel="noopener" class="btn btn-sm" style="background: #25D366; color: #000; font-weight: 700;">
                    💬 Open WhatsApp Direct &rarr;
                </a>
                <a href="<?= url('/admin/whatsapp?phone=' . urlencode($phoneClean)) ?>" class="btn btn-secondary btn-sm">
                    In-Desk WhatsApp
                </a>
                <a href="<?= url('/admin/sms?phone=' . urlencode($phoneClean)) ?>" class="btn btn-secondary btn-sm">
                    Send SMS
                </a>
            <?php endif; ?>
            <a href="mailto:<?= e($inquiry['email']) ?>?subject=<?= urlencode('Regarding your inquiry on Creatsify Studio: ' . $inquiry['project_type']) ?>" class="btn btn-primary btn-sm">
                ✉️ Reply via Email
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header" style="border-bottom: 1px solid var(--line); padding-bottom: 16px;">
            <div>
                <h3 class="card-title" style="font-size: 18px;">Inquiry #<?= $inquiry['id'] ?> &bull; <?= e($inquiry['name']) ?></h3>
                <span style="font-size: 12px; color: var(--muted);">Received on <?= date('F j, Y \a\t H:i', strtotime($inquiry['created_at'])) ?> &bull; Client IP: <?= e($inquiry['ip_address'] ?? 'Unknown') ?></span>
            </div>
        </div>

        <!-- Metadata Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; padding: 20px 0; border-bottom: 1px solid var(--line);">
            <div>
                <span style="font-size: 11px; text-transform: uppercase; color: var(--muted); font-weight: 600; letter-spacing: 0.05em; display: block; margin-bottom: 4px;">Client Name</span>
                <span style="font-size: 15px; color: var(--text); font-weight: 600;"><?= e($inquiry['name']) ?></span>
            </div>
            <div>
                <span style="font-size: 11px; text-transform: uppercase; color: var(--muted); font-weight: 600; letter-spacing: 0.05em; display: block; margin-bottom: 4px;">Email Address</span>
                <a href="mailto:<?= e($inquiry['email']) ?>" style="font-size: 14px; color: var(--accent); text-decoration: none; font-weight: 500;"><?= e($inquiry['email']) ?></a>
            </div>
            <div>
                <span style="font-size: 11px; text-transform: uppercase; color: var(--muted); font-weight: 600; letter-spacing: 0.05em; display: block; margin-bottom: 4px;">Phone / WhatsApp</span>
                <?php if (!empty($phoneClean)): ?>
                    <span style="font-size: 14px; color: #25D366; font-weight: 600;"><?= e(format_phone_display($inquiry['phone'])) ?></span>
                <?php else: ?>
                    <span style="font-size: 13px; color: var(--muted);">Not provided</span>
                <?php endif; ?>
            </div>
            <div>
                <span style="font-size: 11px; text-transform: uppercase; color: var(--muted); font-weight: 600; letter-spacing: 0.05em; display: block; margin-bottom: 4px;">Project Scope</span>
                <span style="font-size: 14px; font-weight: 600; color: var(--text);"><?= e($inquiry['project_type']) ?></span>
            </div>
        </div>

        <!-- Project Requirement Narrative -->
        <div style="padding-top: 20px;">
            <span style="font-size: 11px; text-transform: uppercase; color: var(--muted); font-weight: 600; letter-spacing: 0.05em; display: block; margin-bottom: 10px;">Client Requirements & Message</span>
            <div style="font-size: 14.5px; line-height: 1.7; color: var(--text); background: var(--bg); padding: 20px; border-radius: 8px; border: 1px solid var(--line); white-space: pre-wrap;">
                <?= nl2br(e($inquiry['message'])) ?>
            </div>
        </div>
    </div>

    <!-- Status Progression Card -->
    <div class="card" style="padding: 20px 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <h4 style="font-size: 14px; font-weight: 700; color: var(--text); margin: 0;">Lifecycle & Pipeline State</h4>
                <p style="font-size: 12px; color: var(--muted); margin: 4px 0 0 0;">Update the inquiry status to keep your CRM pipeline organized.</p>
            </div>

            <form action="<?= url('/admin/inquiries/' . $inquiry['id'] . '/status') ?>" method="POST" style="display: flex; align-items: center; gap: 10px;">
                <?= csrf_field() ?>
                <select name="status" class="form-control" style="width: 170px; font-size: 13px;">
                    <option value="new" <?= $status === 'new' ? 'selected' : '' ?>>New</option>
                    <option value="in_discussion" <?= $status === 'in_discussion' ? 'selected' : '' ?>>In Discussion</option>
                    <option value="converted" <?= $status === 'converted' ? 'selected' : '' ?>>Converted (Client)</option>
                    <option value="closed" <?= $status === 'closed' ? 'selected' : '' ?>>Closed</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm" style="font-size: 13px; font-weight: 600;">Save Status</button>
            </form>
        </div>
    </div>
</div>
