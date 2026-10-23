<?php

namespace App\Models;

/**
 * Creatsify Studio - Services Model
 */
class Service
{
    public static function allActive(): array
    {
        return db()->fetchAll("SELECT * FROM `services` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
    }

    public static function all(): array
    {
        return db()->fetchAll("SELECT * FROM `services` ORDER BY `sort_order` ASC");
    }

    public static function findById(int $id): ?array
    {
        return db()->fetchOne("SELECT * FROM `services` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function toggleActive(int $id): bool
    {
        $service = self::findById($id);
        if (!$service) return false;
        $new = $service['is_active'] ? 0 : 1;
        self::update($id, ['is_active' => $new]);
        return (bool)$new;
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('services', $data);
    }

    public static function update(int $id, array $data): int
    {
        return db()->update('services', $data, '`id` = :id', ['id' => $id]);
    }

    public static function delete(int $id): int
    {
        return db()->delete('services', '`id` = :id', ['id' => $id]);
    }
}
