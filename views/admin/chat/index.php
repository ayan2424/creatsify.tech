<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="chat-desk-grid <?= $activeSession ? '' : 'no-context' ?>">
    <!-- Left Pane: Conversations List -->
    <div class="card chat-desk-pane chat-desk-sessions-pane">
        <div style="padding: 16px 18px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: var(--surface);">
            <div>
                <h3 style="font-size: 14px; font-weight: 700; color: var(--text); margin: 0;">Live Visitor Sessions</h3>
                <span style="font-size: 11px; color: var(--muted);">Cheetah Chat Engine</span>
            </div>
            <span class="user-role-badge" style="background: rgba(139,123,255,0.15); color: var(--accent); font-weight: 700;"><?= count($sessions) ?> Active</span>
        </div>

        <div style="flex-grow: 1; overflow-y: auto; padding: 10px; display: flex; flex-direction: column; gap: 6px;">
            <?php if (empty($sessions)): ?>
                <div style="padding: 40px 16px; text-align: center; color: var(--muted); font-size: 13px;">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 8px; opacity: 0.5;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    <p style="margin: 0;">No active visitor sessions.</p>
                </div>
            <?php else: ?>
                <?php foreach ($sessions as $s): ?>
                    <?php $isActive = ($activeSession && (int)$activeSession['id'] === (int)$s['id']); ?>
                    <a href="<?= url('/admin/chat?session_id=' . $s['id']) ?>" style="display: block; padding: 12px; border-radius: 10px; background: <?= $isActive ? 'rgba(139,123,255,0.12)' : 'var(--surface)' ?>; border: 1px solid <?= $isActive ? 'var(--accent)' : 'var(--line)' ?>; text-decoration: none; color: inherit; transition: all 0.2s;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <strong style="color: var(--text); font-size: 13px; font-weight: 600;"><?= e($s['visitor_name']) ?></strong>
                            <?php if ($s['is_human_handoff']): ?>
                                <span style="font-size: 9.5px; padding: 2px 6px; border-radius: 4px; background: rgba(255,79,163,0.2); color: #ff4fa3; font-weight: 700;">HANDOFF</span>
                            <?php else: ?>
                                <span style="font-size: 9.5px; padding: 2px 6px; border-radius: 4px; background: rgba(0,200,83,0.15); color: #00c853; font-weight: 700;">BOT</span>
                            <?php endif; ?>
                        </div>
                        <p style="font-size: 11.5px; color: var(--muted); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.4;">
                            <?= e($s['last_message'] ?: 'Session initialized') ?>
                        </p>
                        <div style="font-size: 10.5px; color: var(--muted); margin-top: 6px; display: flex; justify-content: space-between;">
                            <span>IP: <?= e($s['ip_address']) ?></span>
                            <span><?= date('H:i', strtotime($s['last_active_at'])) ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Center Pane: Active Chat Room & Stream -->
    <div class="card chat-desk-pane chat-desk-room-pane">
        <?php if (!$activeSession): ?>
            <div style="display: flex; align-items: center; justify-content: center; flex-grow: 1; flex-direction: column; color: var(--muted); gap: 15px; padding: 40px; text-align: center;">
                <div style="width: 72px; height: 72px; border-radius: 50%; background: rgba(139,123,255,0.1); display: flex; align-items: center; justify-content: center; color: var(--accent);">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </div>
                <div>
                    <h4 style="color: var(--text); font-size: 17px; margin: 0 0 6px 0;">Creatsify Live Chat Desk</h4>
                    <p style="font-size: 13.5px; max-width: 360px; margin: 0 auto; line-height: 1.5;">Select a visitor conversation on the left to start real-time human assistance.</p>
                </div>
            </div>
        <?php else: ?>
            <!-- Room Header -->
            <div style="padding: 14px 22px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: var(--surface);">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), #ff4fa3); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 15px;">
                        <?= strtoupper(substr($activeSession['visitor_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <h3 style="font-size: 15px; font-weight: 700; color: var(--text); margin: 0; display: flex; align-items: center; gap: 8px;">
                            <?= e($activeSession['visitor_name']) ?>
                            <?php if ($activeSession['is_human_handoff']): ?>
                                <span style="font-size: 10px; padding: 2px 7px; border-radius: 999px; background: rgba(255,79,163,0.2); color: #ff4fa3; font-weight: 700;">Human Support Mode</span>
                            <?php endif; ?>
                        </h3>
                        <div style="font-size: 11.5px; color: var(--muted); margin-top: 2px;">
                            IP: <code><?= e($activeSession['ip_address']) ?></code> &bull; Status: <strong style="color:var(--accent);"><?= strtoupper(e($activeSession['status'])) ?></strong>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 8px;">
                    <form action="<?= url('/admin/chat/' . $activeSession['id'] . '/close') ?>" method="POST" onsubmit="return confirm('Close this live session?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-secondary btn-sm" style="font-size: 12px;">Close Session</button>
                    </form>
                </div>
            </div>

            <!-- Message Stream -->
            <div id="adminChatMessages" style="flex-grow: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 12px; background: var(--bg);">
                <?php foreach ($messages as $m): ?>
                    <?php $isAdmin = ($m['sender_type'] === 'admin'); ?>
                    <?php $isBot = ($m['sender_type'] === 'bot'); ?>
                    <div style="display: flex; flex-direction: column; align-items: <?= $isAdmin ? 'flex-end' : 'flex-start' ?>;">
                        <span style="font-size: 10.5px; color: var(--muted); margin-bottom: 3px;">
                            <?= e($m['sender_name']) ?> &bull; <?= date('H:i', strtotime($m['created_at'])) ?>
                        </span>
                        <div style="max-width: 72%; padding: 11px 15px; border-radius: 12px; font-size: 13.5px; line-height: 1.5; <?= $isAdmin ? 'background: var(--accent); color: #fff; border-bottom-right-radius: 2px;' : ($isBot ? 'background: rgba(139,123,255,0.12); border: 1px solid rgba(139,123,255,0.25); color: var(--text);' : 'background: var(--surface); border: 1px solid var(--line); color: var(--text); border-bottom-left-radius: 2px;') ?>">
                            <?= nl2br(e($m['message'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Cheetah-Style Canned Response Chips -->
            <div style="padding: 8px 16px; background: var(--surface); border-top: 1px solid var(--line); display: flex; gap: 6px; overflow-x: auto; white-space: nowrap;">
                <span style="font-size: 11px; color: var(--muted); align-self: center; margin-right: 4px;">Quick replies:</span>
                <button type="button" class="btn btn-secondary btn-sm canned-chip" style="font-size: 11px; padding: 4px 10px; border-radius: 20px;" data-reply="Hi! I am Muhammad Ayan Shamim, lead architect at Creatsify. How can I help you today?">Greeting</button>
                <button type="button" class="btn btn-secondary btn-sm canned-chip" style="font-size: 11px; padding: 4px 10px; border-radius: 20px;" data-reply="You can explore the live demo of our Cheetah Logistics Suite at https://cheetah.ayan24.me">Cheetah Demo</button>
                <button type="button" class="btn btn-secondary btn-sm canned-chip" style="font-size: 11px; padding: 4px 10px; border-radius: 20px;" data-reply="We would love to discuss your software specifications. Could you share your target timeline and budget?">Timeline & Quote</button>
                <button type="button" class="btn btn-secondary btn-sm canned-chip" style="font-size: 11px; padding: 4px 10px; border-radius: 20px;" data-reply="Feel free to email your technical documents directly to ayan@creatsify.tech.">Email Invite</button>
            </div>

            <!-- Reply Box -->
            <div style="padding: 12px 18px; border-top: 1px solid var(--line); background: var(--surface);">
                <form id="adminReplyForm" style="display: flex; gap: 10px;">
                    <input type="hidden" name="session_id" value="<?= $activeSession['id'] ?>">
                    <input type="text" id="adminReplyInput" class="form-control" placeholder="Type your response to visitor..." autocomplete="off" required style="border-radius: 20px; padding: 10px 18px; font-size: 13.5px;">
                    <button type="submit" class="btn btn-primary" id="adminSendBtn" style="border-radius: 20px; padding: 0 22px; font-weight: 600;">Reply &rarr;</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right Pane: Cheetah-Style Visitor Context Panel -->
    <?php if ($activeSession): ?>
        <div class="card chat-desk-pane chat-desk-context-pane" style="padding: 20px; overflow-y: auto; background: var(--surface);">
            <div style="text-align: center; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--line);">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), #ff4fa3); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; font-weight: 700; margin: 0 auto 10px auto;">
                    <?= strtoupper(substr($activeSession['visitor_name'], 0, 1)) ?>
                </div>
                <h4 style="margin: 0; font-size: 15px; color: var(--text);"><?= e($activeSession['visitor_name']) ?></h4>
                <span style="font-size: 12px; color: var(--muted);"><?= e($activeSession['visitor_email'] ?: 'Guest Visitor') ?></span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px; font-size: 12.5px;">
                <div>
                    <span style="color: var(--muted); text-transform: uppercase; font-size: 10.5px; font-weight: 700; display: block; margin-bottom: 2px;">Session Mode</span>
                    <?php if ($activeSession['is_human_handoff']): ?>
                        <span style="color: #ff4fa3; font-weight: 700;">Human Agent Handoff</span>
                    <?php else: ?>
                        <span style="color: #00c853; font-weight: 700;">AI Auto-Pilot</span>
                    <?php endif; ?>
                </div>

                <div>
                    <span style="color: var(--muted); text-transform: uppercase; font-size: 10.5px; font-weight: 700; display: block; margin-bottom: 2px;">Visitor IP</span>
                    <code style="color: var(--text); font-size: 12px;"><?= e($activeSession['ip_address']) ?></code>
                </div>

                <div>
                    <span style="color: var(--muted); text-transform: uppercase; font-size: 10.5px; font-weight: 700; display: block; margin-bottom: 2px;">Started At</span>
                    <span style="color: var(--text);"><?= date('M j, Y H:i', strtotime($activeSession['created_at'])) ?></span>
                </div>

                <div>
                    <span style="color: var(--muted); text-transform: uppercase; font-size: 10.5px; font-weight: 700; display: block; margin-bottom: 2px;">Last Active</span>
                    <span style="color: var(--text);"><?= date('H:i:s', strtotime($activeSession['last_active_at'])) ?></span>
                </div>
            </div>

            <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid var(--line); display: flex; flex-direction: column; gap: 8px;">
                <?php if (!empty($activeSession['visitor_email'])): ?>
                    <a href="mailto:<?= e($activeSession['visitor_email']) ?>" class="btn btn-secondary btn-sm" style="text-align: center; justify-content: center;">
                        Email Visitor
                    </a>
                <?php endif; ?>
                <a href="<?= url('/admin/inquiries') ?>" class="btn btn-secondary btn-sm" style="text-align: center; justify-content: center;">
                    View Inquiries Desk
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if ($activeSession): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stream = document.getElementById('adminChatMessages');
        if (stream) stream.scrollTop = stream.scrollHeight;

        let lastMessageId = <?= !empty($messages) ? end($messages)['id'] : 0 ?>;
        const sessionId = <?= (int)$activeSession['id'] ?>;
        const replyUrl = '<?= url('/admin/chat/reply') ?>';
        const fetchUrl = '<?= url('/admin/chat/fetch') ?>';

        // Canned response chips
        document.querySelectorAll('.canned-chip').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.getElementById('adminReplyInput');
                if (input) {
                    input.value = btn.dataset.reply;
                    input.focus();
                }
            });
        });

        // AJAX Send Reply
        document.getElementById('adminReplyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('adminReplyInput');
            const sendBtn = document.getElementById('adminSendBtn');
            const msg = input.value.trim();
            if (!msg) return;

            input.value = '';
            if (sendBtn) sendBtn.disabled = true;

            fetch(replyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    message: msg
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    appendMessage('You (Admin)', msg, true);
                    lastMessageId = data.message_id;
                }
            })
            .finally(() => {
                if (sendBtn) sendBtn.disabled = false;
                if (input) input.focus();
            });
        });

        function appendMessage(sender, text, isAdmin) {
            const wrapper = document.createElement('div');
            wrapper.style.display = 'flex';
            wrapper.style.flexDirection = 'column';
            wrapper.style.alignItems = isAdmin ? 'flex-end' : 'flex-start';

            const meta = document.createElement('span');
            meta.style.fontSize = '10.5px';
            meta.style.color = 'var(--muted)';
            meta.style.marginBottom = '3px';
            meta.textContent = sender + ' • Just now';

            const bubble = document.createElement('div');
            bubble.style.maxWidth = '72%';
            bubble.style.padding = '11px 15px';
            bubble.style.borderRadius = '12px';
            bubble.style.fontSize = '13.5px';
            bubble.style.lineHeight = '1.5';
            if (isAdmin) {
                bubble.style.background = 'var(--accent)';
                bubble.style.color = '#fff';
                bubble.style.borderBottomRightRadius = '2px';
            } else {
                bubble.style.background = 'var(--surface)';
                bubble.style.border = '1px solid var(--line)';
                bubble.style.color = 'var(--text)';
                bubble.style.borderBottomLeftRadius = '2px';
            }
            bubble.innerHTML = text.replace(/\n/g, '<br>');

            wrapper.appendChild(meta);
            wrapper.appendChild(bubble);
            stream.appendChild(wrapper);
            stream.scrollTop = stream.scrollHeight;
        }

        // Poll for new visitor messages in active room
        setInterval(function() {
            fetch(fetchUrl + '?session_id=' + sessionId + '&last_id=' + lastMessageId)
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.messages.length > 0) {
                        data.messages.forEach(m => {
                            if (m.sender_type === 'visitor') {
                                appendMessage(m.sender_name, m.message, false);
                                lastMessageId = m.id;
                                const chime = document.getElementById('adminDeskChime');
                                if (chime) try { chime.play(); } catch (_) {}
                            }
                        });
                    }
                })
                .catch(() => {});
        }, 3000);
    });
</script>
<?php endif; ?>
