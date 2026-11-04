<?php

namespace App\Controllers;

use App\Helpers\NotificationDispatcher;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Native Intelligent Live Chat Engine
 * Modeled directly on Cheetah's live chat architecture (smart context crawler + local rule engine + human handoff).
 */
class ChatController
{
    /**
     * Send message from visitor
     */
    public function send(Request $request): Response
    {
        $token = trim((string)$request->input('token', ''));
        $rawMessage = (string)$request->input('message', '');
        $message = trim($rawMessage);
        $rawName = trim((string)$request->input('name', 'Visitor'));
        $name = htmlspecialchars(strip_tags($rawName), ENT_QUOTES, 'UTF-8');
        if (empty($name) || mb_strlen($name) > 80) {
            $name = 'Visitor';
        }
        $email = trim((string)$request->input('email', ''));

        if (empty($token) || empty($message)) {
            return Response::json(['success' => false, 'error' => 'Token and message required.'], 422);
        }

        // Enforce 1,000 character length cap
        if (mb_strlen($message) > 1000) {
            return Response::json(['success' => false, 'error' => 'Message exceeds maximum length of 1,000 characters.'], 422);
        }

        // Flood / Rate Limiting (max 10 messages per minute per IP + token)
        $rateKey = 'chat_rl_' . md5($request->getIp() . '_' . $token);
        $now = time();
        $history = session()->get($rateKey, []);
        if (is_array($history)) {
            $history = array_filter($history, static fn($ts) => ($now - (int)$ts) < 60);
            if (count($history) >= 10) {
                return Response::json(['success' => false, 'error' => 'Rate limit exceeded. Please wait a moment before sending another message.'], 429);
            }
            $history[] = $now;
            session()->set($rateKey, $history);
        }

        // Get or initialize chat session
        $session = ChatSession::findOrCreate($token, $request->getIp(), $name, $email);

        // Record visitor message
        ChatMessage::add((int)$session['id'], 'visitor', $name, $message);

        // Check if session is already in human handoff mode
        if ((int)$session['is_human_handoff'] === 1) {
            // Visitor is chatting directly with human admin
            return Response::json([
                'success' => true,
                'session' => $session,
                'handoff' => true,
                'message' => 'Message delivered to Creatsify live support.'
            ]);
        }

        // Check for human handoff intent
        if ($this->isHumanHandoffRequest($message)) {
            ChatSession::setHandoff((int)$session['id'], true);

            // Dispatch alert to admin
            $dispatcher = new NotificationDispatcher();
            $dispatcher->dispatchChatHandoff($session, $message);

            $siteMail = setting('site_email', 'support@' . ($_SERVER['HTTP_HOST'] ?? 'example.com'));
            $reply = "I've flagged this conversation directly for our co-founders (**Muhammad Ayan Shamim**, **Syeda Sukaina Batool**, and **Abdul Moiz**) on the Admin Desk. We will respond here shortly. You can also reach us directly at {$siteMail} or submit a quote request on our Contact page.";
            ChatMessage::add((int)$session['id'], 'bot', 'Assistant', $reply);

            return Response::json([
                'success' => true,
                'handoff' => true,
                'reply' => $reply
            ]);
        }

        // Local Smart Context Rule Engine
        $botReply = $this->getLocalChatResponse($message);
        ChatMessage::add((int)$session['id'], 'bot', 'Assistant', $botReply);

        return Response::json([
            'success' => true,
            'reply' => $botReply,
            'handoff' => false
        ]);
    }

    /**
     * Fetch message history for visitor session
     */
    public function messages(Request $request): Response
    {
        $token = trim((string)$request->get('token', ''));
        if (empty($token)) {
            return Response::json(['success' => false, 'messages' => []]);
        }

        $session = ChatSession::findOrCreate($token, $request->getIp());
        $messages = ChatMessage::forSession((int)$session['id'], 100);

        return Response::json([
            'success' => true,
            'session' => $session,
            'messages' => $messages
        ]);
    }

    /**
     * Poll for newly arrived admin messages
     */
    public function poll(Request $request): Response
    {
        $token = trim((string)$request->get('token', ''));
        $lastId = (int)$request->get('last_id', 0);

        if (empty($token)) {
            return Response::json(['success' => false, 'messages' => []]);
        }

        $session = ChatSession::findOrCreate($token, $request->getIp());
        $messages = db()->fetchAll(
            "SELECT * FROM `chat_messages` WHERE `session_id` = :sid AND `id` > :lid ORDER BY `id` ASC",
            ['sid' => $session['id'], 'lid' => $lastId]
        );

        return Response::json([
            'success' => true,
            'messages' => $messages,
            'is_handoff' => (bool)$session['is_human_handoff']
        ]);
    }

    /**
     * Detects if visitor intends to speak with a real human agent
     */
    private function isHumanHandoffRequest(string $message): bool
    {
        $m = strtolower($message);
        return (bool)preg_match('/\b(talk\s+to(\s+a)?\s+human|speak\s+(with|to)(\s+a)?\s+human|real\s+person|live\s+agent|human\s+support|human\s+agent|human\s+please|connect\s+with\s+(a\s+)?human|talk\s+to\s+(founder|founders|team|ayan|sukaina|moiz)|speak\s+with\s+(ayan|sukaina|moiz)|call\s+me|phone\s+number|whatsapp\s+me|operator|support\s+rep)\b/i', $m);
    }

    /**
     * Intelligent First-Party Knowledge Engine for Creatsify Studio
     */
    private function getLocalChatResponse(string $userMsg): string
    {
        $m = strtolower($userMsg);

        // Greetings
        if (preg_match('/\b(hi|hello|hey|salam|assalam|aoa)\b/', $m)) {
            return "Hello! Welcome to **Creatsify Studio**. I am your Assistant. We build ready-made courier and e-commerce software (Cheetah Enterprise, Cheetah Lite, Cheezify) and high-velocity custom web applications. How can I help you today?";
        }

        // Cheetah Courier Software inquiries
        if (str_contains($m, 'cheetah') || str_contains($m, 'courier') || str_contains($m, 'logistics') || str_contains($m, 'warehouse') || str_contains($m, 'parcel')) {
            return "Our flagship courier software **Cheetah** is sold with 100% full unencrypted source code and white-label rights via our official Gumroad store:\n\n"
                 . "1. **Cheetah Enterprise**: Complete logistics & warehouse platform featuring 7 role portals, driver & picker Flutter app, cash-on-delivery reconciliation, live tracking, and merchant portals. Live demo: https://cheetah.ayan24.me\n"
                 . "2. **Cheetah Lite**: Courier-only operations platform without warehouse overhead. Fast dispatch, live tracking, and rider assignment. Live demo: https://cheetah-lite.ayan24.me\n\n"
                 . "Would you like a purchase link or direct technical details?";
        }

        // Cheezify Online Store
        if (str_contains($m, 'cheezify') || str_contains($m, 'store') || str_contains($m, 'ecommerce') || str_contains($m, 'e-commerce') || str_contains($m, 'shop')) {
            return "**Cheezify** is our complete online store platform and admin panel written in pure PHP with zero framework bloat. Features cold-chain delivery zones, responsive catalog, cart persistence, and order management. Sold directly on Gumroad with full source code. Live demo: https://cheezify.ayan24.me";
        }

        // White-label & Source Code
        if (str_contains($m, 'white label') || str_contains($m, 'whitelabel') || str_contains($m, 'source code') || str_contains($m, 'license') || str_contains($m, 'gumroad')) {
            return "All commercial software sold by Creatsify (Cheetah Enterprise, Cheetah Lite, Cheezify) comes with **100% full unencrypted source code** and lifetime white-label rights. You can rebrand it, host it on your own server (aaPanel, cPanel, VPS), and operate it without recurring vendor fees. Licenses are sold directly via our official Gumroad store with instant download and direct in-house technical support.";
        }

        // Installation & Server Setup
        if (str_contains($m, 'install') || str_contains($m, 'setup') || str_contains($m, 'aapanel') || str_contains($m, 'nginx') || str_contains($m, 'cpanel') || str_contains($m, 'server')) {
            return "Our scripts are engineered for simple, zero-downtime deployment on aaPanel, cPanel, or standard Linux VPS:\n"
                 . "• **Requirements**: PHP 8.2+ with PDO MySQL and standard extensions\n"
                 . "• **Web Server**: Nginx or Apache (includes built-in Front Controller URL rewrite rules)\n"
                 . "• **1-Click Installer**: Every download includes a graphical browser wizard (`install.php`) that checks requirements and imports the database in seconds\n"
                 . "Our co-founders provide direct in-house support if you need assistance with server setup.";
        }

        // Pricing and quote inquiries
        if (str_contains($m, 'price') || str_contains($m, 'cost') || str_contains($m, 'quote') || str_contains($m, 'rate') || str_contains($m, 'budget') || str_contains($m, 'buy')) {
            return "Our software pricing is transparent and direct:\n"
                 . "• **Cheetah Enterprise**: Flagship logistics & warehouse suite with 7 portals & driver app (available on Gumroad)\n"
                 . "• **Cheetah Lite**: Lightweight courier-only operations edition (available on Gumroad)\n"
                 . "• **Cheezify**: Complete PHP MVC online store & admin system (available on Gumroad)\n"
                 . "• **Custom Development**: Transparent milestone contracts tailored to your exact specifications.\n\n"
                 . "You can explore live demos on our Software page or click 'Get a quote' to discuss your requirements.";
        }

        // Tech stack inquiries
        if (str_contains($m, 'stack') || str_contains($m, 'technology') || str_contains($m, 'php') || str_contains($m, 'laravel') || str_contains($m, 'flutter') || str_contains($m, 'react')) {
            return "We specialize in high-velocity, robust full-stack engineering:\n"
                 . "• **Backend**: PHP 8.2+ (Clean MVC & Laravel), Python, Node.js\n"
                 . "• **Frontend**: Modern ES6, React, Next.js, Tailored Vanilla CSS/JS\n"
                 . "• **Mobile & Desktop**: Flutter (Android/iOS) and Electron\n"
                 . "• **Databases**: MySQL, MariaDB, Redis, SQLite\n"
                 . "• **Integrations**: Meta WhatsApp Cloud API v21.0, Twilio SMS, Stripe, Paymob.";
        }

        // Founders and studio background
        if (str_contains($m, 'founder') || str_contains($m, 'ayan') || str_contains($m, 'sukaina') || str_contains($m, 'moiz') || str_contains($m, 'who are you') || str_contains($m, 'about') || str_contains($m, 'team')) {
            return "**Creatsify** is an independent software studio led by three co-founders:\n"
                 . "• **Muhammad Ayan Shamim**: Co-Founder & Lead Full-Stack Architect (PHP 8 MVC, MySQL, Systems)\n"
                 . "• **Syeda Sukaina Batool**: Co-Founder & UI/UX Product Design Lead (Design Systems, Figma)\n"
                 . "• **Abdul Moiz**: Co-Founder & Technical Operations Lead (aaPanel, Nginx, Deployments)\n\n"
                 . "Together, we build and license production-grade software and craft high-performance web systems for clients globally.";
        }

        // Location
        if (str_contains($m, 'location') || str_contains($m, 'where') || str_contains($m, 'office')) {
            return "Creatsify Studio is a remote-first software studio, working with clients across North America, the UK, the Middle East and beyond.";
        }

        // Default intelligent fallback
        return "Thanks for your message! Creatsify builds high-velocity web platforms, courier platforms like Cheetah, and ready-made store software. Could you tell me a bit more about what you're looking to build? You can also type 'talk to a human' to connect directly with our co-founders.";
    }
}
