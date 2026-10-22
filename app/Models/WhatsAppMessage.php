<?php

namespace App\Models;

/**
 * Creatsify Studio - WhatsApp Message Model
 */
class WhatsAppMessage
{
    public static function recentConversations(): array
    {
        return db()->fetchAll(
            "SELECT 
                CASE WHEN direction = 'inbound' THEN from_number ELSE to_number END as contact_phone,
                message,
                direction,
                status,
                created_at
             FROM whatsapp_messages
             WHERE id IN (
                 SELECT MAX(id) FROM whatsapp_messages 
                 GROUP BY CASE WHEN direction = 'inbound' THEN from_number ELSE to_number END
             )
             ORDER BY created_at DESC"
        );
    }

    public static function forContact(string $phone): array
    {
        $clean = clean_phone_number($phone, false);
        return db()->fetchAll(
            "SELECT * FROM whatsapp_messages 
             WHERE from_number LIKE :p1 OR to_number LIKE :p2 
             ORDER BY created_at ASC",
            ['p1' => "%{$clean}%", 'p2' => "%{$clean}%"]
        );
    }
}
