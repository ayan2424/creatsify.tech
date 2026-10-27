<?php

namespace App\Models;

/**
 * Creatsify Studio - Client Inquiries Model
 */
class Inquiry
{
    private static bool $schemaChecked = false;

    /**
     * Keep the inquiry capture form deployable on existing installations.
     * New qualification fields are additive and safe for live databases.
     */
    public static function ensureColumnsExist(): void
    {
        if (self::$schemaChecked) {
            return;
        }
        self::$schemaChecked = true;

        try {
            db()->query("SELECT `company`, `timeline`, `inquiry_kind` FROM `inquiries` LIMIT 1");
            return;
        } catch (\Throwable $e) {
            // Older deployments are upgraded below, one additive column at a time.
        }

        $migrations = [
            "ALTER TABLE `inquiries` ADD COLUMN `company` VARCHAR(150) NULL AFTER `phone`",
            "ALTER TABLE `inquiries` ADD COLUMN `timeline` VARCHAR(80) NULL AFTER `budget`",
            "ALTER TABLE `inquiries` ADD COLUMN `inquiry_kind` VARCHAR(60) NULL AFTER `timeline`",
        ];

        foreach ($migrations as $sql) {
            try {
                db()->query($sql);
            } catch (\Throwable $e) {
                // A column may already exist when a deployment is being upgraded.
            }
        }
    }

    public static function all(?string $status = null): array
    {
        self::ensureColumnsExist();
        if ($status && in_array($status, ['new', 'in_discussion', 'converted', 'closed'], true)) {
            return db()->fetchAll("SELECT * FROM `inquiries` WHERE `status` = :s ORDER BY `id` DESC", ['s' => $status]);
        }
        return db()->fetchAll("SELECT * FROM `inquiries` ORDER BY `id` DESC");
    }

    public static function statusCounts(): array
    {
        self::ensureColumnsExist();
        $rows = db()->fetchAll("SELECT `status`, COUNT(*) as cnt FROM `inquiries` GROUP BY `status`");
        $counts = ['all' => 0, 'new' => 0, 'in_discussion' => 0, 'converted' => 0, 'closed' => 0];
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

    public static function findById(int $id): ?array
    {
        self::ensureColumnsExist();
        return db()->fetchOne("SELECT * FROM `inquiries` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function create(array $data): int
    {
        self::ensureColumnsExist();
        $data['created_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('inquiries', $data);
    }

    public static function updateStatus(int $id, string $status): int
    {
        self::ensureColumnsExist();
        return db()->update('inquiries', ['status' => $status], '`id` = :id', ['id' => $id]);
    }

    public static function countByStatus(string $status = 'new'): int
    {
        self::ensureColumnsExist();
        return (int)db()->fetchValue("SELECT COUNT(*) FROM `inquiries` WHERE `status` = :s", ['s' => $status]);
    }

    public static function delete(int $id): int
    {
        self::ensureColumnsExist();
        return db()->delete('inquiries', '`id` = :id', ['id' => $id]);
    }
}
