<?php

namespace App\Models;

/**
 * Creatsify Studio - Role Model (Cheezify Architecture)
 */
class Role
{
    public static function all(): array
    {
        return db()->fetchAll("SELECT * FROM `roles` ORDER BY id ASC");
    }

    public static function findBySlug(string $slug): ?array
    {
        return db()->fetchOne("SELECT * FROM `roles` WHERE `slug` = :slug LIMIT 1", ['slug' => $slug]);
    }

    public static function create(array $data): int
    {
        return (int)db()->insert('roles', $data);
    }

    public static function update(int $id, array $data): int
    {
        return db()->update('roles', $data, '`id` = :id', ['id' => $id]);
    }

    public static function delete(int $id): int
    {
        return db()->delete('roles', '`id` = :id', ['id' => $id]);
    }
}
