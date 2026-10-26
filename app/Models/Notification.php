<?php

namespace App\Models;

/**
 * Creatsify Studio - In-App Notification Center
 */
class Notification
{
    public static function unread(int $limit = 10): array
    {
        return db()->fetchAll(
            "SELECT * FROM `notifications` WHERE `is_read` = 0 ORDER BY `created_at` DESC LIMIT {$limit}"
        );
    }

    public static function countUnread(): int
    {
        return (int)db()->fetchValue("SELECT COUNT(*) FROM `notifications` WHERE `is_read` = 0");
    }

    public static function markAllRead(): void
    {
        db()->query("UPDATE `notifications` SET `is_read` = 1 WHERE `is_read` = 0");
    }

    public static function create(array $data): int
    {
        $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
        $data['is_read'] = $data['is_read'] ?? 0;
        return (int)db()->insert('notifications', $data);
    }

    public static function all(int $limit = 50): array
    {
        return db()->fetchAll("SELECT * FROM `notifications` ORDER BY `created_at` DESC LIMIT {$limit}");
    }
}
