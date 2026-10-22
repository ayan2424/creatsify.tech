<?php

namespace App\Models;

/**
 * Creatsify Studio - Live Chat Message Model (Cheetah Architecture)
 */
class ChatMessage
{
    public static function forSession(int $sessionId, int $limit = 50): array
    {
        return db()->fetchAll(
            "SELECT * FROM `chat_messages` WHERE `session_id` = :sid ORDER BY `created_at` ASC, `id` ASC LIMIT {$limit}",
            ['sid' => $sessionId]
        );
    }

    public static function add(int $sessionId, string $senderType, string $senderName, string $message): int
    {
        return (int)db()->insert('chat_messages', [
            'session_id' => $sessionId,
            'sender_type' => $senderType,
            'sender_name' => $senderName,
            'message' => $message,
            'is_read' => ($senderType === 'admin') ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function markReadForSession(int $sessionId, string $senderType = 'visitor'): void
    {
        db()->query(
            "UPDATE `chat_messages` SET `is_read` = 1 WHERE `session_id` = :sid AND `sender_type` = :st",
            ['sid' => $sessionId, 'st' => $senderType]
        );
    }

    public static function countUnread(): int
    {
        return (int)db()->fetchValue(
            "SELECT COUNT(*) FROM `chat_messages` WHERE `sender_type` = 'visitor' AND `is_read` = 0"
        );
    }
}
