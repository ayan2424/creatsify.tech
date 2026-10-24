<?php

namespace App\Models;

/**
 * Creatsify Studio - Agency CRM Internal Notes & Activity Model
 */
class CrmNote
{
    public static function all(?string $type = null, ?int $clientId = null, ?string $search = null): array
    {
        $params = [];
        $where = [];

        if (!empty($type) && $type !== 'all') {
            $where[] = "n.`type` = :type";
            $params['type'] = $type;
        }

        if (!empty($clientId)) {
            $where[] = "n.`client_id` = :client_id";
            $params['client_id'] = $clientId;
        }

        if (!empty($search)) {
            $where[] = "(n.`title` LIKE :search OR n.`content` LIKE :search OR n.`author_name` LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        return db()->fetchAll(
            "SELECT n.*, 
                c.name as client_name,
                c.company as client_company,
                t.ticket_number
             FROM `crm_notes` n
             LEFT JOIN `crm_clients` c ON c.id = n.client_id
             LEFT JOIN `crm_tickets` t ON t.id = n.ticket_id
             {$whereClause}
             ORDER BY n.`is_pinned` DESC, n.`id` DESC",
            $params
        );
    }

    public static function findById(int $id): ?array
    {
        return db()->fetchOne("SELECT * FROM `crm_notes` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('crm_notes', $data);
    }

    public static function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return db()->update('crm_notes', $data, '`id` = :id', ['id' => $id]);
    }

    public static function delete(int $id): int
    {
        return db()->delete('crm_notes', '`id` = :id', ['id' => $id]);
    }

    public static function togglePin(int $id): int
    {
        $note = self::findById($id);
        if (!$note) return 0;
        $newVal = $note['is_pinned'] ? 0 : 1;
        return db()->update('crm_notes', ['is_pinned' => $newVal], '`id` = :id', ['id' => $id]);
    }

    public static function toggleFollowUp(int $id): int
    {
        $note = self::findById($id);
        if (!$note) return 0;
        $newVal = $note['is_follow_up_done'] ? 0 : 1;
        return db()->update('crm_notes', ['is_follow_up_done' => $newVal], '`id` = :id', ['id' => $id]);
    }

    public static function pendingFollowUps(): array
    {
        return db()->fetchAll(
            "SELECT n.*, c.name as client_name, c.company as client_company, c.phone as client_phone
             FROM `crm_notes` n
             LEFT JOIN `crm_clients` c ON c.id = n.client_id
             WHERE n.`type` = 'follow_up' AND n.`is_follow_up_done` = 0 AND n.`follow_up_date` IS NOT NULL
             ORDER BY n.`follow_up_date` ASC LIMIT 10"
        );
    }

    public static function typeCounts(): array
    {
        $rows = db()->fetchAll("SELECT `type`, COUNT(*) as cnt FROM `crm_notes` GROUP BY `type`");
        $counts = [
            'all' => 0,
            'note' => 0,
            'call_log' => 0,
            'meeting' => 0,
            'deal_terms' => 0,
            'follow_up' => 0
        ];
        foreach ($rows as $r) {
            $t = $r['type'] ?? '';
            $cnt = (int)($r['cnt'] ?? 0);
            if (isset($counts[$t])) {
                $counts[$t] = $cnt;
            }
            $counts['all'] += $cnt;
        }
        return $counts;
    }
}
