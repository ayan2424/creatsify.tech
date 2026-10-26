<?php

namespace App\Models;

/**
 * Creatsify Studio - Portfolio Projects Model
 */
class Project
{
    private static bool $checked = false;

    public static function ensureColumnsExist(): void
    {
        if (self::$checked) {
            return;
        }
        self::$checked = true;

        try {
            db()->query("SELECT `gumroad_url`, `price` FROM `projects` LIMIT 1");
        } catch (\Throwable $e) {
            try {
                db()->query("ALTER TABLE `projects` 
                    ADD COLUMN `gumroad_url` VARCHAR(255) NULL AFTER `live_url`, 
                    ADD COLUMN `price` VARCHAR(50) NULL DEFAULT '$99' AFTER `gumroad_url`");
            } catch (\Throwable $e2) {}
        }
    }

    public static function all(): array
    {
        self::ensureColumnsExist();
        return db()->fetchAll("SELECT * FROM `projects` ORDER BY `sort_order` ASC, `id` DESC");
    }

    public static function allByCategory(?string $category = null): array
    {
        self::ensureColumnsExist();
        if ($category && in_array($category, ['saas', 'stores', 'apps'], true)) {
            return db()->fetchAll("SELECT * FROM `projects` WHERE `category` = :cat ORDER BY `sort_order` ASC, `id` DESC", ['cat' => $category]);
        }
        return db()->fetchAll("SELECT * FROM `projects` ORDER BY `sort_order` ASC, `id` DESC");
    }

    public static function categoryCounts(): array
    {
        self::ensureColumnsExist();
        $rows = db()->fetchAll("SELECT `category`, COUNT(*) as cnt FROM `projects` GROUP BY `category`");
        $counts = ['all' => 0, 'saas' => 0, 'stores' => 0, 'apps' => 0];
        foreach ($rows as $r) {
            $cat = $r['category'] ?? '';
            $cnt = (int)($r['cnt'] ?? 0);
            if (isset($counts[$cat])) {
                $counts[$cat] = $cnt;
            }
            $counts['all'] += $cnt;
        }
        return $counts;
    }

    public static function toggleFeatured(int $id): bool
    {
        self::ensureColumnsExist();
        $project = self::findById($id);
        if (!$project) return false;
        $newStatus = $project['featured'] ? 0 : 1;
        self::update($id, ['featured' => $newStatus]);
        return (bool)$newStatus;
    }

    public static function findBySlug(string $slug): ?array
    {
        self::ensureColumnsExist();
        return db()->fetchOne("SELECT * FROM `projects` WHERE `slug` = :slug LIMIT 1", ['slug' => $slug]);
    }

    public static function findById(int $id): ?array
    {
        self::ensureColumnsExist();
        return db()->fetchOne("SELECT * FROM `projects` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function getFeatured(): array
    {
        self::ensureColumnsExist();
        return db()->fetchAll("SELECT * FROM `projects` WHERE `featured` = 1 ORDER BY `sort_order` ASC");
    }

    public static function create(array $data): int
    {
        self::ensureColumnsExist();
        $data['created_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('projects', $data);
    }

    public static function update(int $id, array $data): int
    {
        self::ensureColumnsExist();
        $data['updated_at'] = date('Y-m-d H:i:s');
        return db()->update('projects', $data, '`id` = :id', ['id' => $id]);
    }

    public static function delete(int $id): int
    {
        self::ensureColumnsExist();
        return db()->delete('projects', '`id` = :id', ['id' => $id]);
    }
}
