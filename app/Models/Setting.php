<?php

namespace App\Models;

/**
 * Creatsify Studio - Settings Store
 */
class Setting
{
    public static function all(): array
    {
        $rows = db()->fetchAll("SELECT * FROM `settings` ORDER BY `group_name` ASC, `key` ASC");
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['group_name']][$row['key']] = $row['value'];
        }
        return $grouped;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return setting($key, $default);
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        $exists = db()->fetchOne("SELECT id FROM `settings` WHERE `key` = :k", ['k' => $key]);
        if ($exists) {
            db()->update('settings', ['value' => (string)$value, 'group_name' => $group], '`key` = :k', ['k' => $key]);
        } else {
            db()->insert('settings', ['key' => $key, 'value' => (string)$value, 'group_name' => $group]);
        }
    }

    public static function bulkUpdate(array $data, string $group = 'general'): void
    {
        try {
            db()->beginTransaction();
            $checkStmt = db()->getPdo()->prepare("SELECT `id` FROM `settings` WHERE `key` = :k LIMIT 1");
            $updateStmt = db()->getPdo()->prepare("UPDATE `settings` SET `value` = :v, `group_name` = :g WHERE `key` = :k");
            $insertStmt = db()->getPdo()->prepare("INSERT INTO `settings` (`key`, `value`, `group_name`) VALUES (:k, :v, :g)");

            foreach ($data as $key => $val) {
                $checkStmt->execute(['k' => $key]);
                if ($checkStmt->fetch()) {
                    $updateStmt->execute(['v' => (string)$val, 'g' => $group, 'k' => $key]);
                } else {
                    $insertStmt->execute(['k' => $key, 'v' => (string)$val, 'g' => $group]);
                }
            }
            db()->commit();
        } catch (\Throwable $e) {
            db()->rollback();
            throw $e;
        }
    }
}
