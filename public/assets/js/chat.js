/**
 * Creatsify Studio - Live Chat Widget Controller (Cheetah Architecture)
 */

(function () {
    const launcher = document.getElementById('chatLauncherBtn');
    const chatWindow = document.getElementById('chatWindow');
    const closeBtn = document.getElementById('chatCloseBtn');
    const form = document.getElementById('chatForm');
    const input = document.getElementById('chatInput');
    const list = document.getElementById('chatMessageList');
    const unreadBadge = document.getElementById('chatUnreadBadge');
    const quickChips = document.getElementById('chatQuickChips');
    const statusText = document.getElementById('chatStatusText');
    const chime = document.getElementById('notifChime');
    const getBaseUrl = () => (window.CREATSIFY_BASE_URL || '').replace(/\/$/, '');

    if (!launcher || !chatWindow || !form) return;

    // Retrieve or create visitor session token
    let sessionToken = localStorage.getItem('creatsify_chat_token');
    if (!sessionToken) {
        sessionToken = 'cs_' + Math.random().toString(36).substring(2, 15) + Date.now().toString(36);
        localStorage.setItem('creatsify_chat_token', sessionToken);
    }

    let isOpen = false;
    let lastPollId = 0;
    let pollInterval = null;

    // Toggle Chat Window
    launcher.addEventListener('click', () => {
        isOpen = !isOpen;
        chatWindow.style.display = isOpen ? 'flex' : 'none';
        launcher.classList.toggle('active', isOpen);
        if (unreadBadge) unreadBadge.style.display = 'none';

        if (isOpen) {
            input.focus();
            scrollBottom();
            startPolling();
        } else {
            stopPolling();
        }
    });

    closeBtn.addEventListener('click', () => {
        isOpen = false;
        chatWindow.style.display = 'none';
        launcher.classList.remove('active');
        stopPolling();
    });

    // Handle Quick Action Chips
    if (quickChips) {
        quickChips.addEventListener('click', (e) => {
            const btn = e.target.closest('.chat-chip');
            if (!btn) return;
            const query = btn.getAttribute('data-query');
            if (query) {
                sendMessage(query);
            }
        });
    }

    // Submit Chat Form
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const msg = input.value.trim();
        if (!msg) return;

        input.value = '';
        sendMessage(msg);
    });

    function sendMessage(text) {
        appendMessage('visitor', text);

        fetch(getBaseUrl() + '/api/chat/send', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                token: sessionToken,
                message: text,
                name: 'Visitor'
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.reply) {
                setTimeout(() => {
                    appendMessage('bot', data.reply);
                    playChime();
                    if (data.handoff && statusText) {
                        statusText.textContent = '● Human Agent Alerted & Connecting...';
                        statusText.style.color = '#ff4fa3';
                    }
                }, 400);
            }
        })
        .catch(() => {
            appendMessage('bot', "We couldn't reach the server. Please check your connection or contact ayan@creatsify.tech.");
        });
    }

    function appendMessage(sender, text) {
        const wrap = document.createElement('div');
        wrap.className = 'chat-msg ' + (sender === 'visitor' ? 'visitor-msg' : 'bot-msg');

        const bubble = document.createElement('div');
        bubble.className = 'msg-bubble';
        bubble.appendChild(formatText(text));

        const time = document.createElement('span');
        time.className = 'msg-time';
        const now = new Date();
        time.textContent = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');

        wrap.appendChild(bubble);
        wrap.appendChild(time);
        list.appendChild(wrap);
        scrollBottom();
    }

    function formatText(str) {
        const fragment = document.createDocumentFragment();
        const lines = String(str).split('\n');

        lines.forEach((line, lineIndex) => {
            line.split(/(\*\*[^*]+\*\*)/g).forEach(part => {
                const boldMatch = part.match(/^\*\*([^*]+)\*\*$/);
                if (boldMatch) {
                    const strong = document.createElement('strong');
                    strong.textContent = boldMatch[1];
                    fragment.appendChild(strong);
                } else {
                    fragment.appendChild(document.createTextNode(part));
                }
            });

            if (lineIndex < lines.length - 1) {
                fragment.appendChild(document.createElement('br'));
            }
        });

        return fragment;
    }

    function scrollBottom() {
        list.scrollTop = list.scrollHeight;
    }

    function playChime() {
        try {
            if (chime) chime.play();
        } catch (_) {}
    }

    function startPolling() {
        if (pollInterval) return;
        pollInterval = setInterval(() => {
            fetch(`${getBaseUrl()}/api/chat/poll?token=${encodeURIComponent(sessionToken)}&last_id=${lastPollId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.messages.length > 0) {
                        data.messages.forEach(m => {
                            if (m.sender_type === 'admin') {
                                appendMessage('bot', m.message);
                                playChime();
                            }
                            lastPollId = Math.max(lastPollId, m.id);
                        });
                        if (data.is_handoff && statusText) {
                            statusText.textContent = '● Connected to Human Support Agent';
                            statusText.style.color = '#00c853';
                        }
                    }
                })
                .catch(() => {});
        }, 4000);
    }

    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }
})();
