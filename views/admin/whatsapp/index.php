<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
$prefillMsg = request()->get('msg', '');
?>

<div class="comm-desk-grid">
    <!-- Left Pane: WhatsApp Contacts List -->
    <div class="card comm-desk-contacts">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: var(--surface);">
            <div>
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text); margin: 0;">WhatsApp Threads</h3>
                <span style="font-size: 11px; color: var(--muted);">Meta Cloud API v21.0</span>
            </div>
            <span class="user-role-badge" style="background: rgba(37, 211, 102, 0.15); color: #25D366; border: 1px solid rgba(37, 211, 102, 0.3);">
                LIVE
            </span>
        </div>

        <!-- Conversations Stream -->
        <div style="flex-grow: 1; overflow-y: auto; padding: 10px; display: flex; flex-direction: column; gap: 4px;">
            <?php if (empty($conversations)): ?>
                <div style="padding: 30px 16px; font-size: 13px; color: var(--muted); text-align: center;">
                    <div style="margin-bottom: 12px; display: flex; justify-content: center;">
                        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color: var(--muted); opacity: 0.6;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    No WhatsApp logs yet.<br>Use the composer to start a conversation.
                </div>
            <?php else: ?>
                <?php foreach ($conversations as $c): ?>
                    <?php 
                    $phoneRaw = $c['contact_phone'];
                    $isActive = ($selectedPhone === $phoneRaw);
                    ?>
                    <a href="<?= url('/admin/whatsapp?phone=' . urlencode($phoneRaw)) ?>" style="display: block; padding: 12px 14px; border-radius: 8px; background: <?= $isActive ? 'var(--surface-hover)' : 'transparent' ?>; border: 1px solid <?= $isActive ? 'var(--accent)' : 'transparent' ?>; text-decoration: none; color: inherit; transition: background 0.15s ease;">
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
    <div class="card comm-desk-main">
        <!-- Header -->
        <div style="padding: 16px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: var(--surface);">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text); margin: 0;">
                    <?= !empty($selectedPhone) ? e(format_phone_display($selectedPhone)) : 'Direct WhatsApp Dispatcher' ?>
                </h3>
                <span style="font-size: 12px; color: var(--muted);">Direct integration with Meta Graph API & Webhooks</span>
            </div>
            <?php if (!empty($selectedPhone)): ?>
                <a href="https://wa.me/<?= clean_phone_number($selectedPhone, false) ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="font-size: 12px; color: #25D366; font-weight: 600;">
                    Open in WhatsApp App &rarr;
                </a>
            <?php endif; ?>
        </div>

        <!-- Messages Stream -->
        <div style="flex-grow: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 14px; background: var(--bg);" id="wa-stream">
            <?php if (empty($messages)): ?>
                <div style="text-align: center; color: var(--muted); font-size: 13.5px; margin: auto; max-width: 380px;">
                    <div style="margin-bottom: 12px; display: flex; justify-content: center;">
                        <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color: var(--muted); opacity: 0.6;"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                    </div>
                    <div style="font-weight: 600; color: var(--text); margin-bottom: 4px;">Start a WhatsApp Conversation</div>
                    Enter a phone number below or pick a contact from the left pane to send an official WhatsApp message.
                </div>
            <?php else: ?>
                <?php foreach ($messages as $m): ?>
                    <?php 
                    $isOut = ($m['direction'] === 'outbound');
                    $st = strtolower($m['status'] ?? 'sent');
                    ?>
                    <div style="display: flex; flex-direction: column; align-items: <?= $isOut ? 'flex-end' : 'flex-start' ?>;">
                        <span style="font-size: 11px; color: var(--muted); margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                            <span><?= $isOut ? 'Creatsify Studio' : e(format_phone_display($m['from_number'])) ?></span>
                            <span>&bull;</span>
                            <span><?= date('H:i', strtotime($m['created_at'])) ?></span>
                            <?php if ($isOut): ?>
                                <?php if ($st === 'read' || $st === 'delivered'): ?>
                                    <span style="color: #25D366; font-weight: bold; font-size: 13px;" title="Delivered & Read">✓✓</span>
                                <?php elseif ($st === 'sent'): ?>
                                    <span style="color: var(--muted); font-weight: bold; font-size: 13px;" title="Sent">✓✓</span>
                                <?php else: ?>
                                    <span style="color: #ef4444; font-size: 10px; background: rgba(239, 68, 68, 0.1); padding: 1px 5px; border-radius: 4px;">Sandbox/Failed</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </span>
                        <div style="max-width: 72%; padding: 12px 16px; border-radius: 14px; font-size: 14px; line-height: 1.5; <?= $isOut ? 'background: #25D366; color: #000; border-bottom-right-radius: 2px; font-weight: 500;' : 'background: var(--surface); border: 1px solid var(--line); color: var(--text); border-bottom-left-radius: 2px;' ?>">
                            <?= nl2br(e($m['message'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Outbound Form with Canned Quick Chips -->
        <div style="padding: 14px 20px; border-top: 1px solid var(--line); background: var(--surface);">
            <!-- Canned Responses (Cheetah Architecture) -->
            <div style="display: flex; gap: 6px; overflow-x: auto; padding-bottom: 10px; margin-bottom: 8px;">
                <span style="font-size: 11px; color: var(--muted); align-self: center; text-transform: uppercase; letter-spacing: 0.05em; margin-right: 4px; font-weight: 600;">Canned:</span>
                <button type="button" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; padding: 3px 10px; border-radius: 12px; white-space: nowrap;" onclick="setWaQuickMsg('Assalam-o-Alaikum! Thanks for connecting with Creatsify Studio. How can we help you today?')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                    <span>Salam & Welcome</span>
                </button>
                <button type="button" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; padding: 3px 10px; border-radius: 12px; white-space: nowrap;" onclick="setWaQuickMsg('We have reviewed your project requirements and would love to schedule a quick 15-minute discovery call.')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>Discovery Call</span>
                </button>
                <button type="button" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; padding: 3px 10px; border-radius: 12px; white-space: nowrap;" onclick="setWaQuickMsg('You can test our flagship courier & warehouse platform Cheetah live at https://creatsify.tech/#cheetah.')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                    <span>Cheetah Demo</span>
                </button>
                <button type="button" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; padding: 3px 10px; border-radius: 12px; white-space: nowrap;" onclick="setWaQuickMsg('Thank you for confirming. Our engineering proposal and milestone breakdown have been dispatched to your email.')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    <span>Quote Dispatched</span>
                </button>
            </div>

            <form action="<?= url('/admin/whatsapp/send') ?>" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                <?= csrf_field() ?>
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="phone" class="form-control" placeholder="Recipient Phone (e.g. 923001234567)" value="<?= e($selectedPhone) ?>" style="max-width: 240px; font-size: 13.5px;" required>
                    <input type="text" name="message" id="wa-input" class="form-control" placeholder="Type WhatsApp message..." value="<?= e($prefillMsg) ?>" required autocomplete="off" style="font-size: 13.5px;">
                    <button type="submit" class="btn btn-primary" style="background: #25D366; color: #000; font-weight: 700; white-space: nowrap; padding: 0 20px;">
                        Send WhatsApp
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setWaQuickMsg(text) {
    const input = document.getElementById('wa-input');
    if (input) {
        input.value = text;
        input.focus();
    }
}

// Auto-scroll stream to bottom
const stream = document.getElementById('wa-stream');
if (stream) {
    stream.scrollTop = stream.scrollHeight;
}
</script>
