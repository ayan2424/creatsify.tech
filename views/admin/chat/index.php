<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div style="display: grid; grid-template-columns: 320px 1fr; gap: 24px; height: calc(100vh - 150px);">
    <!-- Left Pane: Conversations List -->
    <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; padding: 0; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text);">Live Visitor Sessions</h3>
            <span class="user-role-badge"><?= count($sessions) ?> Active</span>
        </div>

        <div style="flex-grow: 1; overflow-y: auto; padding: 12px; display: flex; flex-direction: column; gap: 6px;">
            <?php if (empty($sessions)): ?>
                <p style="padding: 20px; font-size: 13px; color: var(--muted); text-align: center;">No active visitor sessions.</p>
            <?php else: ?>
                <?php foreach ($sessions as $s): ?>
                    <?php $isActive = ($activeSession && (int)$activeSession['id'] === (int)$s['id']); ?>
                    <a href="<?= url('/admin') ?>/chat?session_id=<?= $s['id'] ?>" style="display: block; padding: 12px; border-radius: 8px; background: <?= $isActive ? 'var(--surface-hover)' : 'transparent' ?>; border: 1px solid <?= $isActive ? 'var(--accent)' : 'transparent' ?>; text-decoration: none; color: inherit; transition: 0.2s;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <strong style="color: var(--text); font-size: 13.5px;"><?= e($s['visitor_name']) ?></strong>
                            <?php if ($s['is_human_handoff']): ?>
                                <span style="font-size: 10px; padding: 2px 6px; border-radius: 4px; background: rgba(255,79,163,0.2); color: #ff4fa3; font-weight: 700;">HANDOFF</span>
                            <?php endif; ?>
                        </div>
                        <p style="font-size: 12px; color: var(--muted); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= e($s['last_message'] ?: 'Session initialized') ?>
                        </p>
                        <div style="font-size: 11px; color: var(--muted); margin-top: 6px; display: flex; justify-content: space-between;">
                            <span>IP: <?= e($s['ip_address']) ?></span>
                            <span><?= date('H:i', strtotime($s['last_active_at'])) ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Pane: Active Chat Room -->
    <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; padding: 0; overflow: hidden;">
        <?php if (!$activeSession): ?>
            <div style="display: flex; align-items: center; justify-content: center; flex-grow: 1; flex-direction: column; color: var(--muted); gap: 15px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                <p>Select a live visitor session on the left to start live support.</p>
            </div>
        <?php else: ?>
            <!-- Room Header -->
            <div style="padding: 16px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: var(--surface);">
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--text); margin: 0;"><?= e($activeSession['visitor_name']) ?></h3>
                    <div style="font-size: 12px; color: var(--muted); margin-top: 2px;">
                        IP: <?= e($activeSession['ip_address']) ?> &bull; Status: <strong style="color:var(--accent);"><?= strtoupper(e($activeSession['status'])) ?></strong>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <form action="<?= url('/admin') ?>/chat/<?= $activeSession['id'] ?>/close" method="POST" onsubmit="return confirm('Close this live session?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-secondary btn-sm">Close Session</button>
                    </form>
                </div>
            </div>

            <!-- Message Stream -->
            <div id="adminChatMessages" style="flex-grow: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 14px; background: var(--bg);">
                <?php foreach ($messages as $m): ?>
                    <?php $isAdmin = ($m['sender_type'] === 'admin'); ?>
                    <?php $isBot = ($m['sender_type'] === 'bot'); ?>
                    <div style="display: flex; flex-direction: column; align-items: <?= $isAdmin ? 'flex-end' : 'flex-start' ?>;">
                        <span style="font-size: 11px; color: var(--muted); margin-bottom: 4px;">
                            <?= e($m['sender_name']) ?> &bull; <?= date('H:i', strtotime($m['created_at'])) ?>
                        </span>
                        <div style="max-width: 70%; padding: 12px 16px; border-radius: 12px; font-size: 14px; line-height: 1.5; <?= $isAdmin ? 'background: var(--accent); color: #fff; border-bottom-right-radius: 2px;' : ($isBot ? 'background: rgba(139,123,255,0.15); border: 1px solid rgba(139,123,255,0.3); color: var(--text);' : 'background: var(--surface); border: 1px solid var(--line); color: var(--text); border-bottom-left-radius: 2px;') ?>">
                            <?= nl2br(e($m['message'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Reply Box -->
            <div style="padding: 16px 20px; border-top: 1px solid var(--line); background: var(--surface);">
                <form id="adminReplyForm" style="display: flex; gap: 10px;">
                    <input type="hidden" name="session_id" value="<?= $activeSession['id'] ?>">
                    <input type="text" id="adminReplyInput" class="form-control" placeholder="Type your response to visitor..." autocomplete="off" required>
                    <button type="submit" class="btn btn-primary" id="adminSendBtn">Send</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($activeSession): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stream = document.getElementById('adminChatMessages');
        if (stream) stream.scrollTop = stream.scrollHeight;

        let lastMessageId = <?= !empty($messages) ? end($messages)['id'] : 0 ?>;
        const sessionId = <?= (int)$activeSession['id'] ?>;

        // AJAX Send Reply
        document.getElementById('adminReplyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('adminReplyInput');
            const msg = input.value.trim();
            if (!msg) return;

            input.value = '';

            fetch('/admin/chat/reply', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
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
            });
        });

        function appendMessage(sender, text, isAdmin) {
            const wrapper = document.createElement('div');
            wrapper.style.display = 'flex';
            wrapper.style.flexDirection = 'column';
            wrapper.style.alignItems = isAdmin ? 'flex-end' : 'flex-start';

            const meta = document.createElement('span');
            meta.style.fontSize = '11px';
            meta.style.color = 'var(--muted)';
            meta.style.marginBottom = '4px';
            meta.textContent = sender + ' • Just now';

            const bubble = document.createElement('div');
            bubble.style.maxWidth = '70%';
            bubble.style.padding = '12px 16px';
            bubble.style.borderRadius = '12px';
            bubble.style.fontSize = '14px';
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
            bubble.textContent = text;

            wrapper.appendChild(meta);
            wrapper.appendChild(bubble);
            stream.appendChild(wrapper);
            stream.scrollTop = stream.scrollHeight;
        }

        // Poll for new visitor messages in active room
        setInterval(function() {
            fetch('/admin/chat/fetch?session_id=' + sessionId + '&last_id=' + lastMessageId)
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.messages.length > 0) {
                        data.messages.forEach(m => {
                            if (m.sender_type === 'visitor') {
                                appendMessage(m.sender_name, m.message, false);
                                lastMessageId = m.id;
                            }
                        });
                    }
                })
                .catch(() => {});
        }, 3000);
    });
</script>
<?php endif; ?>
