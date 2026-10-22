<?php

namespace App\Models;

/**
 * Creatsify Studio - Blog Post Model
 */
class Post
{
    public static function allPublished(): array
    {
        return db()->fetchAll(
            "SELECT p.*, u.name as author_name 
             FROM `posts` p 
             LEFT JOIN `users` u ON p.author_id = u.id 
             WHERE p.is_published = 1 
             ORDER BY p.published_at DESC"
        );
    }

    public static function all(): array
    {
        return db()->fetchAll(
            "SELECT p.*, u.name as author_name 
             FROM `posts` p 
             LEFT JOIN `users` u ON p.author_id = u.id 
             ORDER BY p.id DESC"
        );
    }

    public static function findBySlug(string $slug): ?array
    {
        return db()->fetchOne(
            "SELECT p.*, u.name as author_name, u.avatar as author_avatar 
             FROM `posts` p 
             LEFT JOIN `users` u ON p.author_id = u.id 
             WHERE p.slug = :s AND p.is_published = 1 LIMIT 1",
            ['s' => $slug]
        );
    }

    public static function findById(int $id): ?array
    {
        return db()->fetchOne("SELECT * FROM `posts` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('posts', $data);
    }

    public static function update(int $id, array $data): int
    {
        return db()->update('posts', $data, '`id` = :id', ['id' => $id]);
    }

    public static function delete(int $id): int
    {
        return db()->delete('posts', '`id` = :id', ['id' => $id]);
    }

    public static function incrementViews(int $id): void
    {
        db()->query("UPDATE `posts` SET `views_count` = `views_count` + 1 WHERE `id` = :id", ['id' => $id]);
    }
}
