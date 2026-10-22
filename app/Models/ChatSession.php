<?php

namespace App\Models;

/**
 * Creatsify Studio - Live Chat Session Model (Cheetah Architecture)
 */
class ChatSession
{
    public static function findOrCreate(string $token, string $ip, ?string $name = null, ?string $email = null): array
    {
        $session = db()->fetchOne("SELECT * FROM `chat_sessions` WHERE `session_token` = :t LIMIT 1", ['t' => $token]);
        if ($session) {
            db()->update('chat_sessions', [
                'last_active_at' => date('Y-m-d H:i:s'),
                'visitor_name' => $name ?: $session['visitor_name'],
                'visitor_email' => $email ?: $session['visitor_email']
            ], '`id` = :id', ['id' => $session['id']]);
            return db()->fetchOne("SELECT * FROM `chat_sessions` WHERE `id` = :id", ['id' => $session['id']]);
        }

        $id = db()->insert('chat_sessions', [
            'session_token' => $token,
            'visitor_name' => $name ?: 'Guest Visitor',
            'visitor_email' => $email,
            'ip_address' => $ip,
            'status' => 'active',
            'is_human_handoff' => 0,
            'last_active_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return db()->fetchOne("SELECT * FROM `chat_sessions` WHERE `id` = :id", ['id' => $id]);
    }

    public static function findById(int $id): ?array
    {
        return db()->fetchOne("SELECT * FROM `chat_sessions` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function allActiveWithUnread(): array
    {
        return db()->fetchAll(
            "SELECT s.*, 
             (SELECT COUNT(*) FROM `chat_messages` m WHERE m.session_id = s.id AND m.sender_type = 'visitor' AND m.is_read = 0) as unread_count,
             (SELECT message FROM `chat_messages` m WHERE m.session_id = s.id ORDER BY m.id DESC LIMIT 1) as last_message,
             (SELECT created_at FROM `chat_messages` m WHERE m.session_id = s.id ORDER BY m.id DESC LIMIT 1) as last_message_time
             FROM `chat_sessions` s
             ORDER BY s.last_active_at DESC"
        );
    }

    public static function setHandoff(int $id, bool $handoff = true): void
    {
        db()->update('chat_sessions', [
            'is_human_handoff' => $handoff ? 1 : 0,
            'status' => $handoff ? 'handoff' : 'active'
        ], '`id` = :id', ['id' => $id]);
    }

    public static function close(int $id): void
    {
        db()->update('chat_sessions', ['status' => 'closed'], '`id` = :id', ['id' => $id]);
    }
}
