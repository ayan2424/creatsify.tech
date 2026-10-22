<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div style="display: grid; grid-template-columns: 320px 1fr; gap: 24px; height: calc(100vh - 150px);">
    <!-- Left Pane: WhatsApp Contacts List -->
    <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; padding: 0; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text);">WhatsApp Conversations</h3>
            <span class="user-role-badge">Meta v21.0</span>
        </div>

        <div style="flex-grow: 1; overflow-y: auto; padding: 12px; display: flex; flex-direction: column; gap: 6px;">
            <?php if (empty($conversations)): ?>
                <p style="padding: 20px; font-size: 13px; color: var(--muted); text-align: center;">No WhatsApp messages logged yet. Use the composer on the right to start a chat or receive webhooks.</p>
            <?php else: ?>
                <?php foreach ($conversations as $c): ?>
                    <?php $isActive = ($selectedPhone === $c['contact_phone']); ?>
                    <a href="<?= url('/admin') ?>/whatsapp?phone=<?= urlencode($c['contact_phone']) ?>" style="display: block; padding: 12px; border-radius: 8px; background: <?= $isActive ? 'var(--surface-hover)' : 'transparent' ?>; border: 1px solid <?= $isActive ? 'var(--accent)' : 'transparent' ?>; text-decoration: none; color: inherit;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <strong style="color: var(--text); font-size: 13.5px;"><?= e($c['contact_phone']) ?></strong>
                            <span style="font-size: 11px; color: var(--muted);"><?= date('M j', strtotime($c['created_at'])) ?></span>
                        </div>
                        <p style="font-size: 12px; color: var(--muted); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= e($c['message']) ?>
                        </p>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Pane: Thread Stream & Outbound Composer -->
    <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; padding: 0; overflow: hidden;">
        <div style="padding: 16px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: var(--surface);">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text); margin: 0;"><?= e($selectedPhone ?: 'Compose WhatsApp Message') ?></h3>
                <span style="font-size: 12px; color: var(--muted);">Meta Cloud API Dispatcher</span>
            </div>
        </div>

        <!-- Messages Stream -->
        <div style="flex-grow: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 14px; background: var(--bg);">
            <?php if (empty($messages)): ?>
                <div style="text-align: center; color: var(--muted); font-size: 13.5px; margin-top: 40px;">
                    Enter a phone number below to initiate a WhatsApp dispatch.
                </div>
            <?php else: ?>
                <?php foreach ($messages as $m): ?>
                    <?php $isOut = ($m['direction'] === 'outbound'); ?>
                    <div style="display: flex; flex-direction: column; align-items: <?= $isOut ? 'flex-end' : 'flex-start' ?>;">
                        <span style="font-size: 11px; color: var(--muted); margin-bottom: 4px;">
                            <?= $isOut ? 'Creatsify Studio' : e($m['from_number']) ?> &bull; <?= date('H:i', strtotime($m['created_at'])) ?> (<?= e($m['status']) ?>)
                        </span>
                        <div style="max-width: 70%; padding: 12px 16px; border-radius: 12px; font-size: 14px; line-height: 1.5; <?= $isOut ? 'background: #25D366; color: #000; border-bottom-right-radius: 2px; font-weight: 500;' : 'background: var(--surface); border: 1px solid var(--line); color: var(--text); border-bottom-left-radius: 2px;' ?>">
                            <?= nl2br(e($m['message'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Outbound Form -->
        <div style="padding: 16px 20px; border-top: 1px solid var(--line); background: var(--surface);">
            <form action="<?= url('/admin/whatsapp/send') ?>" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                <?= csrf_field() ?>
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="phone" class="form-control" placeholder="Recipient Phone (e.g. 923001234567)" value="<?= e($selectedPhone) ?>" style="max-width: 250px;" required>
                    <input type="text" name="message" class="form-control" placeholder="Type WhatsApp message..." required autocomplete="off">
                    <button type="submit" class="btn btn-primary" style="background:#25D366; color:#000; font-weight:700;">Send WhatsApp</button>
                </div>
            </form>
        </div>
    </div>
</div>
