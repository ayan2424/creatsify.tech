<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
$prefillMsg = request()->get('msg', '');
?>

<div style="display: grid; grid-template-columns: 320px 1fr; gap: 20px; height: calc(100vh - 140px); min-height: 550px;">
    <!-- Left Pane: SMS Contacts List -->
    <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; padding: 0; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: var(--surface);">
            <div>
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text); margin: 0;">SMS Threads</h3>
                <span style="font-size: 11px; color: var(--muted);">Multi-Gateway GSM / Twilio</span>
            </div>
            <span class="user-role-badge">GATEWAY</span>
        </div>

        <div style="flex-grow: 1; overflow-y: auto; padding: 10px; display: flex; flex-direction: column; gap: 4px;">
            <?php if (empty($conversations)): ?>
                <div style="padding: 30px 16px; font-size: 13px; color: var(--muted); text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">📨</div>
                    No SMS logs yet.<br>Dispatch a message using the composer.
                </div>
            <?php else: ?>
                <?php foreach ($conversations as $c): ?>
                    <?php 
                    $phoneRaw = $c['phone_number'];
                    $isActive = ($selectedPhone === $phoneRaw);
                    ?>
                    <a href="<?= url('/admin/sms?phone=' . urlencode($phoneRaw)) ?>" style="display: block; padding: 12px 14px; border-radius: 8px; background: <?= $isActive ? 'var(--surface-hover)' : 'transparent' ?>; border: 1px solid <?= $isActive ? 'var(--accent)' : 'transparent' ?>; text-decoration: none; color: inherit; transition: background 0.15s ease;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <strong style="color: var(--text); font-size: 13.5px;"><?= e(format_phone_display($phoneRaw)) ?></strong>
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
        <!-- Header -->
        <div style="padding: 16px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: var(--surface);">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text); margin: 0;">
                    <?= !empty($selectedPhone) ? e(format_phone_display($selectedPhone)) : 'Direct SMS Dispatcher' ?>
                </h3>
                <span style="font-size: 12px; color: var(--muted);">Multi-Gateway SMS Dispatch & Delivery Receipts</span>
            </div>
        </div>

        <!-- Messages Stream -->
        <div style="flex-grow: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 14px; background: var(--bg);" id="sms-stream">
            <?php if (empty($messages)): ?>
                <div style="text-align: center; color: var(--muted); font-size: 13.5px; margin: auto; max-width: 380px;">
                    <div style="font-size: 32px; margin-bottom: 8px;">💬</div>
                    <div style="font-weight: 600; color: var(--text); margin-bottom: 4px;">Dispatch Direct SMS</div>
                    Send single-destination SMS alerts, OTPs, or follow-ups through your configured gateway.
                </div>
            <?php else: ?>
                <?php foreach ($messages as $m): ?>
                    <?php 
                    $isOut = ($m['direction'] === 'outbound');
                    $st = strtolower($m['status'] ?? 'sent');
                    ?>
                    <div style="display: flex; flex-direction: column; align-items: <?= $isOut ? 'flex-end' : 'flex-start' ?>;">
                        <span style="font-size: 11px; color: var(--muted); margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                            <span><?= $isOut ? 'Outbound SMS' : e(format_phone_display($m['phone_number'])) ?></span>
                            <span>&bull;</span>
                            <span><?= date('H:i', strtotime($m['created_at'])) ?></span>
                            <?php if ($isOut): ?>
                                <span style="font-size: 10px; background: var(--surface); padding: 1px 5px; border-radius: 4px; border: 1px solid var(--line);">
                                    <?= e(strtoupper($st)) ?>
                                </span>
                            <?php endif; ?>
                        </span>
                        <div style="max-width: 72%; padding: 12px 16px; border-radius: 14px; font-size: 14px; line-height: 1.5; <?= $isOut ? 'background: var(--accent); color: #fff; border-bottom-right-radius: 2px;' : 'background: var(--surface); border: 1px solid var(--line); color: var(--text); border-bottom-left-radius: 2px;' ?>">
                            <?= nl2br(e($m['message'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Outbound Form with Canned Quick Chips -->
        <div style="padding: 14px 20px; border-top: 1px solid var(--line); background: var(--surface);">
            <!-- Canned Quick Chips -->
            <div style="display: flex; gap: 6px; overflow-x: auto; padding-bottom: 10px; margin-bottom: 8px;">
                <span style="font-size: 11px; color: var(--muted); align-self: center; text-transform: uppercase; letter-spacing: 0.05em; margin-right: 4px; font-weight: 600;">Canned:</span>
                <button type="button" class="btn btn-secondary btn-sm" style="font-size: 11px; padding: 3px 10px; border-radius: 12px; white-space: nowrap;" onclick="setSmsQuickMsg('Creatsify: Thank you for your inquiry. Our team will contact you shortly.')">
                    🔔 Inquiry Received
                </button>
                <button type="button" class="btn btn-secondary btn-sm" style="font-size: 11px; padding: 3px 10px; border-radius: 12px; white-space: nowrap;" onclick="setSmsQuickMsg('Creatsify: Your project quotation and timeline have been sent to your email.')">
                    📄 Quote Emailed
                </button>
                <button type="button" class="btn btn-secondary btn-sm" style="font-size: 11px; padding: 3px 10px; border-radius: 12px; white-space: nowrap;" onclick="setSmsQuickMsg('Creatsify: Your demo environment credentials are ready. Visit https://creatsify.tech.')">
                    🚀 Demo Ready
                </button>
            </div>

            <form action="<?= url('/admin/sms/send') ?>" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                <?= csrf_field() ?>
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="phone" class="form-control" placeholder="Recipient Phone (+923001234567)" value="<?= e($selectedPhone) ?>" style="max-width: 240px; font-size: 13.5px;" required>
                    <input type="text" name="message" id="sms-input" class="form-control" placeholder="Type SMS message..." value="<?= e($prefillMsg) ?>" required autocomplete="off" style="font-size: 13.5px;">
                    <button type="submit" class="btn btn-primary" style="white-space: nowrap; padding: 0 20px;">
                        Send SMS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setSmsQuickMsg(text) {
    const input = document.getElementById('sms-input');
    if (input) {
        input.value = text;
        input.focus();
    }
}

// Auto-scroll stream to bottom
const smsStream = document.getElementById('sms-stream');
if (smsStream) {
    smsStream.scrollTop = smsStream.scrollHeight;
}
</script>
