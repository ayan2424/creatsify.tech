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
        $message = trim((string)$request->input('message', ''));
        $name = trim((string)$request->input('name', 'Visitor'));
        $email = trim((string)$request->input('email', ''));

        if (empty($token) || empty($message)) {
            return Response::json(['success' => false, 'error' => 'Token and message required.'], 422);
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

            $reply = "I've flagged this conversation for **Muhammad Ayan Shamim** directly on the Admin Desk and WhatsApp. An engineer will respond here shortly. You can also email us directly at ayan@creatsify.tech or submit an inquiry.";
            ChatMessage::add((int)$session['id'], 'bot', 'Creatsify AI', $reply);

            return Response::json([
                'success' => true,
                'handoff' => true,
                'reply' => $reply
            ]);
        }

        // Local Smart Context Rule Engine
        $botReply = $this->getLocalChatResponse($message);
        ChatMessage::add((int)$session['id'], 'bot', 'Creatsify AI', $botReply);

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
        $triggers = [
            'talk to human', 'speak with human', 'real person', 'live agent',
            'speak with ayan', 'contact ayan', 'talk to founder', 'call me',
            'phone number', 'whatsapp me', 'operator', 'agent', 'support rep'
        ];

        foreach ($triggers as $t) {
            if (str_contains($m, $t)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Intelligent First-Party Knowledge Engine for Creatsify Studio
     */
    private function getLocalChatResponse(string $userMsg): string
    {
        $m = strtolower($userMsg);

        // Greetings
        if (preg_match('/\b(hi|hello|hey|salam|assalam|aoa)\b/', $m)) {
            return "Hello! Welcome to **Creatsify Studio**. I can assist you with our software products (like Cheetah Courier), custom web and mobile engineering, pricing, or connect you directly with founder Muhammad Ayan Shamim. How can I help you today?";
        }

        // Cheetah Courier Software inquiries
        if (str_contains($m, 'cheetah') || str_contains($m, 'courier') || str_contains($m, 'logistics') || str_contains($m, 'warehouse') || str_contains($m, 'parcel')) {
            return "Our flagship product line **Cheetah** is built specifically for courier and warehouse operators. It comes in 3 distinct editions:\n"
                 . "1. **Cheetah Starter**: Single-origin parcel delivery with 7 role portals.\n"
                 . "2. **Cheetah Pro**: Multi-origin, route optimization, fleet dispatch, and automated ePOD.\n"
                 . "3. **Cheetah SaaS**: Multi-tenant cloud platform with subscription billing.\n\n"
                 . "Would you like a live demo or technical pricing?";
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

        // Pricing and quote inquiries
        if (str_contains($m, 'price') || str_contains($m, 'cost') || str_contains($m, 'quote') || str_contains($m, 'rate') || str_contains($m, 'budget')) {
            return "Our pricing depends on your project scope. Pre-built software licenses (such as Cheetah editions or RestroMaster) start from marketplace rates on Codester and Coderobotics. For custom full-stack web or mobile builds, we provide transparent milestone-based contracts. Would you like to share your project details?";
        }

        // Founder and studio background
        if (str_contains($m, 'founder') || str_contains($m, 'ayan') || str_contains($m, 'who are you') || str_contains($m, 'about')) {
            return "**Creatsify** is founded by **Muhammad Ayan Shamim**, an AI software engineer and distinction holder from Aptech Karachi. We design and ship production-grade web systems, mobile apps, and marketplace software used globally.";
        }

        // Location / Karachi
        if (str_contains($m, 'location') || str_contains($m, 'where') || str_contains($m, 'karachi') || str_contains($m, 'office')) {
            return "Creatsify Studio is headquartered in **Karachi, Pakistan**, collaborating with clients across North America, the UK, the Middle East, and Pakistan.";
        }

        // Default intelligent fallback
        return "Thanks for your message! Creatsify builds custom web platforms, mobile apps, and flagship courier platforms like Cheetah. Could you tell me a bit more about what you're looking to build? You can also type 'talk to human' to connect with our founder.";
    }
}
