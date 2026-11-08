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

        // Ensure RestroMaster and ViddMate projects exist for real showcase
        try {
            $restro = db()->fetchOne("SELECT `id` FROM `projects` WHERE `slug` = 'restromaster-kds-interface' LIMIT 1");
            if (!$restro) {
                db()->query("INSERT INTO `projects` 
                    (`title`, `slug`, `category`, `client`, `featured`, `summary`, `description`, `image_url`, `live_url`, `gumroad_url`, `price`, `tech_stack`, `status`, `sort_order`) 
                    VALUES 
                    ('RestroMaster POS & KDS Interface', 'restromaster-kds-interface', 'saas', 'Zenith Hospitality Group', 1, 
                     'Touch-optimized kitchen display system, contactless QR tabletop ordering, and real-time order routing for high-volume venues.',
                     'Complete restaurant management ecosystem featuring low-latency kitchen order tickets (KOT), multi-station display routing, QR digital menu with real-time bill settlement, and thermal printer integration.',
                     '/assets/images/restromaster-preview.jpg', '', '', '$79', 'PHP 8.4, Vue.js, Tailwind CSS, WebSocket, MySQL 8', 'Shipped', 4)");
            }

            $viddmate = db()->fetchOne("SELECT `id` FROM `projects` WHERE `slug` = 'viddmate-video-suite' LIMIT 1");
            if (!$viddmate) {
                db()->query("INSERT INTO `projects` 
                    (`title`, `slug`, `category`, `client`, `featured`, `summary`, `description`, `image_url`, `live_url`, `gumroad_url`, `price`, `tech_stack`, `status`, `sort_order`) 
                    VALUES 
                    ('ViddMate 4K Video Transcoding Suite', 'viddmate-video-suite', 'saas', 'Kinetix Cloud', 1, 
                     'High-throughput cloud media encoding and transcoding web platform with GPU acceleration and automated storage delivery.',
                     'Scalable video processing infrastructure engineered for automated 4K H.265/AV1 transcoding, audio waveform extraction, watermarking, and instant cloud CDN distribution with webhooks.',
                     '/assets/images/viddmate-preview.jpg', '', '', '$89', 'PHP 8.4, Node.js, FFmpeg, Redis, Tailwind CSS', 'Shipped', 5)");
            }
        } catch (\Throwable $e) {}
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
