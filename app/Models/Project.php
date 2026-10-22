<?php

namespace App\Models;

/**
 * Creatsify Studio - Portfolio Projects Model
 */
class Project
{
    public static function all(): array
    {
        return db()->fetchAll("SELECT * FROM `projects` ORDER BY `sort_order` ASC, `id` DESC");
    }

    public static function findBySlug(string $slug): ?array
    {
        return db()->fetchOne("SELECT * FROM `projects` WHERE `slug` = :slug LIMIT 1", ['slug' => $slug]);
    }

    public static function findById(int $id): ?array
    {
        return db()->fetchOne("SELECT * FROM `projects` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function getFeatured(): array
    {
        return db()->fetchAll("SELECT * FROM `projects` WHERE `featured` = 1 ORDER BY `sort_order` ASC");
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('projects', $data);
    }

    public static function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return db()->update('projects', $data, '`id` = :id', ['id' => $id]);
    }

    public static function delete(int $id): int
    {
        return db()->delete('projects', '`id` = :id', ['id' => $id]);
    }
}
