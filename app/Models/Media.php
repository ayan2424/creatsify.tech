<?php

namespace App\Models;

/**
 * Creatsify Studio - Media Library Model
 */
class Media
{
    public static function all(): array
    {
        return db()->fetchAll("SELECT * FROM `media` ORDER BY `id` DESC");
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('media', $data);
    }

    public static function delete(int $id): bool
    {
        $item = db()->fetchOne("SELECT * FROM `media` WHERE `id` = :id", ['id' => $id]);
        if ($item) {
            $fullPath = app()->getBasePath() . '/public' . $item['path'];
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
            db()->delete('media', '`id` = :id', ['id' => $id]);
            return true;
        }
        return false;
    }
}
