<?php

namespace App\Controllers\Admin;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Live Chat Desk Controller (Cheetah Architecture)
 */
class ChatController
{
    public function index(Request $request): Response
    {
        $sessions = ChatSession::allActiveWithUnread();
        $activeSessionId = (int)$request->get('session_id', $sessions[0]['id'] ?? 0);

        $activeSession = null;
        $messages = [];
        if ($activeSessionId > 0) {
            $activeSession = ChatSession::findById($activeSessionId);
            if ($activeSession) {
                // Mark visitor messages as read
                ChatMessage::markReadForSession($activeSessionId, 'visitor');
                $messages = ChatMessage::forSession($activeSessionId, 100);
            }
        }

        return view('admin/chat/index', [
            'sessions' => $sessions,
            'activeSession' => $activeSession,
            'messages' => $messages,
            'meta_title' => 'Live Chat Desk | Creatsify Admin'
        ]);
    }

    /**
     * Admin replies to live chat visitor
     */
    public function reply(Request $request): Response
    {
        $sessionId = (int)$request->input('session_id', 0);
        $message = trim((string)$request->input('message', ''));

        if ($sessionId <= 0 || empty($message)) {
            return Response::json(['success' => false, 'error' => 'Invalid session or message.'], 422);
        }

        $session = ChatSession::findById($sessionId);
        if (!$session) {
            return Response::json(['success' => false, 'error' => 'Session not found.'], 404);
        }

        $adminName = auth()['name'] ?? 'Support Team';
        $msgId = ChatMessage::add($sessionId, 'admin', $adminName, $message);

        // Ensure session remains in human handoff active mode
        ChatSession::setHandoff($sessionId, true);

        return Response::json([
            'success' => true,
            'message_id' => $msgId,
            'message' => [
                'id' => $msgId,
                'session_id' => $sessionId,
                'sender_type' => 'admin',
                'sender_name' => $adminName,
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Fetch messages for polling inside admin desk
     */
    public function fetchMessages(Request $request): Response
    {
        $sessionId = (int)$request->get('session_id', 0);
        $lastId = (int)$request->get('last_id', 0);

        if ($sessionId <= 0) {
            return Response::json(['success' => false, 'messages' => []]);
        }

        $messages = db()->fetchAll(
            "SELECT * FROM `chat_messages` WHERE `session_id` = :sid AND `id` > :lid ORDER BY `id` ASC",
            ['sid' => $sessionId, 'lid' => $lastId]
        );

        if (!empty($messages)) {
            ChatMessage::markReadForSession($sessionId, 'visitor');
        }

        return Response::json(['success' => true, 'messages' => $messages]);
    }

    /**
     * Close a live chat session
     */
    public function close(Request $request, string $id): Response
    {
        ChatSession::close((int)$id);
        flash('success', 'Chat session closed.');
        return Response::redirect('/admin/chat');
    }
}
