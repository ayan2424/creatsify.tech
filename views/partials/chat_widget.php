<?php
/** @var Core\View $this */
?>
<!-- Creatsify Native Live Chat Widget (Cheetah Architecture) -->
<div class="creatsify-chat-container" id="creatsifyChatWidget">
    <!-- Chat Launcher Button -->
    <button type="button" class="chat-launcher-btn" id="chatLauncherBtn" aria-label="Open live chat">
        <span class="chat-pulse"></span>
        <svg class="chat-icon-open" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
        </svg>
        <svg class="chat-icon-close" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
        <span class="chat-badge" id="chatUnreadBadge" style="display:none;">1</span>
    </button>

    <!-- Chat Dialog Window -->
    <div class="chat-window" id="chatWindow" style="display: none;">
        <div class="chat-header">
            <div class="chat-header-info">
                <div class="chat-avatar">
                    <img src="<?= asset('brand/creatsify-icon.png') ?>" alt="Creatsify" width="32" height="32">
                    <span class="status-dot"></span>
                </div>
                <div>
                    <h4 class="chat-title">Creatsify Desk</h4>
                    <p class="chat-status" id="chatStatusText">AI Assistant &bull; Live Support Ready</p>
                </div>
            </div>
            <button type="button" class="chat-close-btn" id="chatCloseBtn" aria-label="Close chat window">&times;</button>
        </div>

        <div class="chat-body" id="chatMessageList">
            <div class="chat-timestamp"><?= date('l, M j') ?></div>
            <div class="chat-msg bot-msg">
                <div class="msg-bubble">
                    Hello! Welcome to <strong>Creatsify Studio</strong>. How can we help you today? Ask about our <strong>Cheetah Courier software</strong>, request a custom build, or talk directly with <strong>Muhammad Ayan Shamim</strong>.
                </div>
                <span class="msg-time"><?= date('H:i') ?></span>
            </div>

            <!-- Quick Action Suggestion Chips -->
            <div class="chat-chips" id="chatQuickChips">
                <button type="button" class="chat-chip" data-query="Tell me about Cheetah courier software">Cheetah Courier</button>
                <button type="button" class="chat-chip" data-query="What is your tech stack?">Tech Stack</button>
                <button type="button" class="chat-chip" data-query="How much does a custom SaaS cost?">Pricing & Quotes</button>
                <button type="button" class="chat-chip" data-query="I want to talk to human agent">Talk to Human &rarr;</button>
            </div>
        </div>

        <!-- Chat Input Form -->
        <form class="chat-footer" id="chatForm">
            <input type="text" id="chatInput" placeholder="Type a message..." autocomplete="off" required>
            <button type="submit" class="chat-send-btn" id="chatSendBtn" aria-label="Send message">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </form>
    </div>
</div>
