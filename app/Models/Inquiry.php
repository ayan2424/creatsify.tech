<?php

namespace App\Models;

/**
 * Creatsify Studio - Client Inquiries Model
 */
class Inquiry
{
    public static function all(): array
    {
        return db()->fetchAll("SELECT * FROM `inquiries` ORDER BY `id` DESC");
    }

    public static function findById(int $id): ?array
    {
        return db()->fetchOne("SELECT * FROM `inquiries` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('inquiries', $data);
    }

    public static function updateStatus(int $id, string $status): int
    {
        return db()->update('inquiries', ['status' => $status], '`id` = :id', ['id' => $id]);
    }

    public static function countByStatus(string $status = 'new'): int
    {
        return (int)db()->fetchValue("SELECT COUNT(*) FROM `inquiries` WHERE `status` = :s", ['s' => $status]);
    }

    public static function delete(int $id): int
    {
        return db()->delete('inquiries', '`id` = :id', ['id' => $id]);
    }
}
