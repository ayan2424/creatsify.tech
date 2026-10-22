<?php

namespace App\Models;

/**
 * Creatsify Studio - Homepage Section Builder Model
 */
class Section
{
    public static function allActive(): array
    {
        return db()->fetchAll("SELECT * FROM `sections` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
    }

    public static function all(): array
    {
        return db()->fetchAll("SELECT * FROM `sections` ORDER BY `sort_order` ASC");
    }

    public static function findByKey(string $key): ?array
    {
        return db()->fetchOne("SELECT * FROM `sections` WHERE `section_key` = :k LIMIT 1", ['k' => $key]);
    }

    public static function updateByKey(string $key, array $data): int
    {
        return db()->update('sections', $data, '`section_key` = :k', ['k' => $key]);
    }
}
