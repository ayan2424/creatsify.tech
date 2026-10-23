<?php

namespace App\Models;

/**
 * Creatsify Studio - Audit Log Model (Cheezify Security Architecture)
 */
class AuditLog
{
    public static function paginate(int $limit = 50, int $offset = 0, ?string $action = null, ?string $search = null): array
    {
        $where = [];
        $params = [];

        if (!empty($action)) {
            $where[] = "a.action LIKE :action";
            $params['action'] = "%{$action}%";
        }

        if (!empty($search)) {
            $where[] = "(a.details LIKE :search OR a.entity_type LIKE :search OR u.name LIKE :search OR a.ip_address LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        return db()->fetchAll(
            "SELECT a.*, u.name as user_name, u.email as user_email 
             FROM `audit_logs` a 
             LEFT JOIN `users` u ON a.user_id = u.id 
             {$whereClause}
             ORDER BY a.created_at DESC 
             LIMIT {$limit} OFFSET {$offset}",
            $params
        );
    }

    public static function count(?string $action = null, ?string $search = null): int
    {
        $where = [];
        $params = [];

        if (!empty($action)) {
            $where[] = "a.action LIKE :action";
            $params['action'] = "%{$action}%";
        }

        if (!empty($search)) {
            $where[] = "(a.details LIKE :search OR a.entity_type LIKE :search OR a.ip_address LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        return (int)db()->fetchValue(
            "SELECT COUNT(*) FROM `audit_logs` a {$whereClause}",
            $params
        );
    }
}
