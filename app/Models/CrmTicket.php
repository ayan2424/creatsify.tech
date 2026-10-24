<?php

namespace App\Models;

/**
 * Creatsify Studio - Agency CRM Ticket Model
 */
class CrmTicket
{
    public static function all(?string $status = null, ?string $priority = null, ?string $search = null): array
    {
        $params = [];
        $where = [];

        if (!empty($status) && $status !== 'all') {
            $where[] = "t.`status` = :status";
            $params['status'] = $status;
        }

        if (!empty($priority) && $priority !== 'all') {
            $where[] = "t.`priority` = :priority";
            $params['priority'] = $priority;
        }

        if (!empty($search)) {
            $where[] = "(t.`ticket_number` LIKE :search OR t.`subject` LIKE :search OR t.`client_name` LIKE :search OR t.`client_email` LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        return db()->fetchAll(
            "SELECT t.*, 
                c.company as client_company,
                p.title as project_title,
                u.name as assigned_name,
                (SELECT COUNT(*) FROM `crm_ticket_replies` r WHERE r.ticket_id = t.id) as replies_count
             FROM `crm_tickets` t
             LEFT JOIN `crm_clients` c ON c.id = t.client_id
             LEFT JOIN `projects` p ON p.id = t.project_id
             LEFT JOIN `users` u ON u.id = t.assigned_to
             {$whereClause}
             ORDER BY 
                CASE t.`priority` 
                    WHEN 'urgent' THEN 1 
                    WHEN 'high' THEN 2 
                    WHEN 'medium' THEN 3 
                    WHEN 'low' THEN 4 
                    ELSE 5 
                END,
                t.`id` DESC",
            $params
        );
    }

    public static function findById(int $id): ?array
    {
        return db()->fetchOne(
            "SELECT t.*, 
                c.company as client_company,
                c.whatsapp as client_whatsapp,
                p.title as project_title,
                u.name as assigned_name
             FROM `crm_tickets` t
             LEFT JOIN `crm_clients` c ON c.id = t.client_id
             LEFT JOIN `projects` p ON p.id = t.project_id
             LEFT JOIN `users` u ON u.id = t.assigned_to
             WHERE t.`id` = :id LIMIT 1",
            ['id' => $id]
        );
    }

    public static function statusCounts(): array
    {
        $rows = db()->fetchAll("SELECT `status`, COUNT(*) as cnt FROM `crm_tickets` GROUP BY `status`");
        $counts = [
            'all' => 0,
            'open' => 0,
            'in_progress' => 0,
            'waiting_client' => 0,
            'resolved' => 0,
            'closed' => 0
        ];
        foreach ($rows as $r) {
            $st = $r['status'] ?? '';
            $cnt = (int)($r['cnt'] ?? 0);
            if (isset($counts[$st])) {
                $counts[$st] = $cnt;
            }
            $counts['all'] += $cnt;
        }
        return $counts;
    }

    public static function generateTicketNumber(): string
    {
        $lastId = (int)db()->fetchValue("SELECT MAX(`id`) FROM `crm_tickets`");
        return 'TCK-' . (1000 + $lastId + 1);
    }

    public static function create(array $data): int
    {
        if (empty($data['ticket_number'])) {
            $data['ticket_number'] = self::generateTicketNumber();
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('crm_tickets', $data);
    }

    public static function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        if (isset($data['status']) && in_array($data['status'], ['resolved', 'closed'], true)) {
            $data['resolved_at'] = date('Y-m-d H:i:s');
        }
        return db()->update('crm_tickets', $data, '`id` = :id', ['id' => $id]);
    }

    public static function delete(int $id): int
    {
        db()->delete('crm_ticket_replies', '`ticket_id` = :id', ['id' => $id]);
        db()->delete('crm_notes', '`ticket_id` = :id', ['id' => $id]);
        return db()->delete('crm_tickets', '`id` = :id', ['id' => $id]);
    }

    public static function getReplies(int $ticketId): array
    {
        return db()->fetchAll(
            "SELECT * FROM `crm_ticket_replies` WHERE `ticket_id` = :tid ORDER BY `id` ASC",
            ['tid' => $ticketId]
        );
    }

    public static function addReply(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $replyId = (int)db()->insert('crm_ticket_replies', $data);

        // Update ticket updated_at
        db()->update('crm_tickets', ['updated_at' => date('Y-m-d H:i:s')], '`id` = :id', ['id' => $data['ticket_id']]);

        return $replyId;
    }
}
