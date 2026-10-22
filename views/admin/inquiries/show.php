<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Inquiry from <?= e($inquiry['name']) ?></h3>
            <span style="font-size: 12px; color: var(--muted);">Received on <?= date('F j, Y \a\t H:i', strtotime($inquiry['created_at'])) ?> (IP: <?= e($inquiry['ip_address']) ?>)</span>
        </div>
        <a href="<?= url('/admin/inquiries') ?>" class="btn btn-secondary btn-sm">&larr; Back to Inquiries</a>
    </div>

    <div style="background: var(--bg); padding: 25px; border-radius: 10px; border: 1px solid var(--line); margin-bottom: 30px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px; border-bottom: 1px solid var(--line); padding-bottom: 20px;">
            <div>
                <strong style="font-size: 12px; text-transform: uppercase; color: var(--muted); display: block;">Client Name</strong>
                <span style="font-size: 16px; color: var(--text); font-weight: 600;"><?= e($inquiry['name']) ?></span>
            </div>
            <div>
                <strong style="font-size: 12px; text-transform: uppercase; color: var(--muted); display: block;">Email</strong>
                <a href="mailto:<?= e($inquiry['email']) ?>" style="font-size: 15px; color: var(--accent);"><?= e($inquiry['email']) ?></a>
            </div>
            <div>
                <strong style="font-size: 12px; text-transform: uppercase; color: var(--muted); display: block;">Phone / WhatsApp</strong>
                <?php if (!empty($inquiry['phone'])): ?>
                    <a href="<?= url('/admin') ?>/whatsapp?phone=<?= urlencode($inquiry['phone']) ?>" style="font-size: 15px; color: #25D366; font-weight: 600;">
                        <?= e($inquiry['phone']) ?> (Chat on WhatsApp &rarr;)
                    </a>
                <?php else: ?>
                    <span style="color: var(--muted);">Not provided</span>
                <?php endif; ?>
            </div>
            <div>
                <strong style="font-size: 12px; text-transform: uppercase; color: var(--muted); display: block;">Project Focus</strong>
                <span style="font-size: 15px; color: var(--text);"><?= e($inquiry['project_type']) ?></span>
            </div>
        </div>

        <strong style="font-size: 12px; text-transform: uppercase; color: var(--muted); display: block; margin-bottom: 10px;">Message & Project Details</strong>
        <div style="font-size: 15px; line-height: 1.7; color: var(--text); background: var(--surface); padding: 20px; border-radius: 8px; border: 1px solid var(--line);">
            <?= nl2br(e($inquiry['message'])) ?>
        </div>
    </div>

    <!-- Status Change Form -->
    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--line); padding-top: 20px;">
        <form action="<?= url('/admin') ?>/inquiries/<?= $inquiry['id'] ?>/status" method="POST" style="display: flex; align-items: center; gap: 12px;">
            <?= csrf_field() ?>
            <label for="status" style="font-weight: 600; font-size: 13px;">Update Status:</label>
            <select name="status" id="status" class="form-control" style="width: 180px;">
                <option value="new" <?= $inquiry['status'] === 'new' ? 'selected' : '' ?>>New</option>
                <option value="in_discussion" <?= $inquiry['status'] === 'in_discussion' ? 'selected' : '' ?>>In Discussion</option>
                <option value="converted" <?= $inquiry['status'] === 'converted' ? 'selected' : '' ?>>Converted (Client)</option>
                <option value="closed" <?= $inquiry['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Update</button>
        </form>

        <a href="mailto:<?= e($inquiry['email']) ?>?subject=<?= urlencode('Regarding your inquiry on Creatsify Studio') ?>" class="btn btn-secondary btn-sm">
            Reply via Email &rarr;
        </a>
    </div>
</div>
