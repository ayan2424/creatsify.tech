<?php

namespace App\Models;

/**
 * Creatsify Studio - Agency CRM Client Model
 */
class CrmClient
{
    public static function all(?string $stage = null, ?string $search = null): array
    {
        $params = [];
        $where = [];

        if (!empty($stage) && $stage !== 'all') {
            $where[] = "`stage` = :stage";
            $params['stage'] = $stage;
        }

        if (!empty($search)) {
            $where[] = "(`name` LIKE :search OR `company` LIKE :search OR `email` LIKE :search OR `phone` LIKE :search OR `tags` LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        return db()->fetchAll(
            "SELECT c.*, 
                (SELECT COUNT(*) FROM `crm_tickets` t WHERE t.client_id = c.id) as tickets_count,
                (SELECT COUNT(*) FROM `crm_tickets` t WHERE t.client_id = c.id AND t.status IN ('open', 'in_progress', 'waiting_client')) as open_tickets_count,
                (SELECT COUNT(*) FROM `crm_notes` n WHERE n.client_id = c.id) as notes_count
             FROM `crm_clients` c 
             {$whereClause} 
             ORDER BY c.`id` DESC",
            $params
        );
    }

    public static function findById(int $id): ?array
    {
        return db()->fetchOne(
            "SELECT c.*, 
                (SELECT COUNT(*) FROM `crm_tickets` t WHERE t.client_id = c.id) as tickets_count,
                (SELECT COUNT(*) FROM `crm_tickets` t WHERE t.client_id = c.id AND t.status IN ('open', 'in_progress', 'waiting_client')) as open_tickets_count,
                (SELECT COUNT(*) FROM `crm_notes` n WHERE n.client_id = c.id) as notes_count
             FROM `crm_clients` c 
             WHERE c.`id` = :id LIMIT 1",
            ['id' => $id]
        );
    }

    public static function stageCounts(): array
    {
        $rows = db()->fetchAll("SELECT `stage`, COUNT(*) as cnt, SUM(`deal_value`) as val FROM `crm_clients` GROUP BY `stage`");
        $stages = [
            'all' => 0,
            'lead' => 0,
            'discovery' => 0,
            'proposal_sent' => 0,
            'contract_signed' => 0,
            'active_client' => 0,
            'retained' => 0,
            'churned' => 0
        ];
        $totalVal = 0.0;

        foreach ($rows as $r) {
            $st = $r['stage'] ?? '';
            $cnt = (int)($r['cnt'] ?? 0);
            $val = (float)($r['val'] ?? 0);
            if (isset($stages[$st])) {
                $stages[$st] = $cnt;
            }
            $stages['all'] += $cnt;
            $totalVal += $val;
        }

        return [
            'counts' => $stages,
            'total_value' => $totalVal
        ];
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('crm_clients', $data);
    }

    public static function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return db()->update('crm_clients', $data, '`id` = :id', ['id' => $id]);
    }

    public static function delete(int $id): int
    {
        try {
            db()->beginTransaction();
            db()->delete('crm_notes', '`client_id` = :id', ['id' => $id]);
            db()->update('crm_tickets', ['client_id' => null], '`client_id` = :id', ['id' => $id]);
            $res = db()->delete('crm_clients', '`id` = :id', ['id' => $id]);
            db()->commit();
            return $res;
        } catch (\Throwable $e) {
            db()->rollback();
            throw $e;
        }
    }

    public static function getTickets(int $clientId): array
    {
        return db()->fetchAll(
            "SELECT * FROM `crm_tickets` WHERE `client_id` = :cid ORDER BY `id` DESC",
            ['cid' => $clientId]
        );
    }

    public static function getNotes(int $clientId): array
    {
        return db()->fetchAll(
            "SELECT * FROM `crm_notes` WHERE `client_id` = :cid ORDER BY `is_pinned` DESC, `id` DESC",
            ['cid' => $clientId]
        );
    }

    public static function createFromInquiry(int $inquiryId): int
    {
        Inquiry::ensureColumnsExist();
        $inquiry = db()->fetchOne("SELECT * FROM `inquiries` WHERE `id` = :id LIMIT 1", ['id' => $inquiryId]);
        if (!$inquiry) {
            return 0;
        }

        // Check if already converted
        $existing = db()->fetchOne("SELECT `id` FROM `crm_clients` WHERE `inquiry_id` = :iid OR `email` = :email LIMIT 1", [
            'iid' => $inquiryId,
            'email' => $inquiry['email']
        ]);
        if ($existing) {
            return (int)$existing['id'];
        }

        $clientId = self::create([
            'name' => $inquiry['name'],
            'email' => $inquiry['email'],
            'phone' => $inquiry['phone'] ?? null,
            'whatsapp' => !empty($inquiry['phone']) ? clean_phone_number($inquiry['phone'], false) : null,
            'company' => $inquiry['company'] ?? null,
            'stage' => 'lead',
            'deal_value' => (float)preg_replace('/[^0-9.]/', '', $inquiry['budget'] ?? '0'),
            'currency' => 'USD',
            'source' => 'Website Inquiry #' . $inquiry['id'],
            'inquiry_id' => $inquiry['id'],
            'tags' => $inquiry['project_type'] ?? 'General',
            'notes_summary' => "Imported from web inquiry. Intent: " . ($inquiry['inquiry_kind'] ?? 'general') . ". Timeline: " . ($inquiry['timeline'] ?? 'Not specified') . ". Client Message: " . $inquiry['message']
        ]);

        // Add initial note
        CrmNote::create([
            'client_id' => $clientId,
            'inquiry_id' => $inquiryId,
            'user_id' => (int)(auth()['id'] ?? 1),
            'author_name' => auth()['name'] ?? 'Super Admin',
            'type' => 'note',
            'title' => 'Inquiry Converted to CRM Lead',
            'content' => "Original Inquiry Details:\nIntent: " . ($inquiry['inquiry_kind'] ?? 'N/A') . "\nProject Focus: " . ($inquiry['project_type'] ?? 'N/A') . "\nTimeline: " . ($inquiry['timeline'] ?? 'N/A') . "\nBudget: " . ($inquiry['budget'] ?? 'N/A') . "\nMessage: " . $inquiry['message'],
            'is_pinned' => 1
        ]);

        // Update inquiry status to converted
        Inquiry::updateStatus($inquiryId, 'converted');

        return $clientId;
    }
}
