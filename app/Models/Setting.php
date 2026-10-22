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
        foreach ($data as $key => $val) {
            self::set($key, $val, $group);
        }
    }
}
