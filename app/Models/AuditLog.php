<?php

namespace App\Models;

/**
 * Creatsify Studio - Audit Log Model (Cheezify Security Architecture)
 */
class AuditLog
{
    public static function paginate(int $limit = 50, int $offset = 0): array
    {
        return db()->fetchAll(
            "SELECT a.*, u.name as user_name, u.email as user_email 
             FROM `audit_logs` a 
             LEFT JOIN `users` u ON a.user_id = u.id 
             ORDER BY a.created_at DESC 
             LIMIT {$limit} OFFSET {$offset}"
        );
    }

    public static function count(): int
    {
        return (int)db()->fetchValue("SELECT COUNT(*) FROM `audit_logs`");
    }
}
