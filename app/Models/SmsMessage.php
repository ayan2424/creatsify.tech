<?php

namespace App\Models;

/**
 * Creatsify Studio - SMS Message Model
 */
class SmsMessage
{
    public static function recentConversations(): array
    {
        return db()->fetchAll(
            "SELECT phone_number, message, direction, status, created_at 
             FROM sms_messages 
             WHERE id IN (SELECT MAX(id) FROM sms_messages GROUP BY phone_number)
             ORDER BY created_at DESC"
        );
    }

    public static function forContact(string $phone): array
    {
        $clean = clean_phone_number($phone, false);
        if (strlen($clean) < 7) {
            return [];
        }
        return db()->fetchAll(
            "SELECT * FROM sms_messages 
             WHERE phone_number LIKE :p 
             ORDER BY created_at ASC",
            ['p' => "%{$clean}%"]
        );
    }
}
