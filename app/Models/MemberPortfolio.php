<?php

namespace App\Models;

/**
 * Creatsify Studio - Individual Team Member Portfolio Model
 * Stores dedicated projects, case studies, and contributions crafted by a specific worker.
 */
class MemberPortfolio
{
    private static bool $checked = false;

    public static function ensureTableExists(): void
    {
        if (self::$checked) {
            return;
        }
        self::$checked = true;

        try {
            db()->query("SELECT 1 FROM `member_portfolios` LIMIT 1");
        } catch (\Throwable $e) {
            db()->query("
                CREATE TABLE IF NOT EXISTS `member_portfolios` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `member_id` INT NOT NULL,
                    `title` VARCHAR(200) NOT NULL,
                    `slug` VARCHAR(200) NOT NULL,
                    `category` VARCHAR(100) DEFAULT 'Full-Stack Development',
                    `client` VARCHAR(150) NULL,
                    `role_in_project` VARCHAR(150) NULL,
                    `summary` VARCHAR(400) NULL,
                    `description` LONGTEXT NULL,
                    `image_url` VARCHAR(255) NULL,
                    `project_url` VARCHAR(255) NULL,
                    `tech_stack` VARCHAR(255) NULL,
                    `completion_date` VARCHAR(50) NULL,
                    `is_featured` TINYINT(1) DEFAULT 0,
                    `sort_order` INT DEFAULT 0,
                    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX `idx_mp_member` (`member_id`),
                    INDEX `idx_mp_featured` (`is_featured`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            self::seedDefaults();
        }
    }

    private static function seedDefaults(): void
    {
        try {
            $count = (int)db()->fetchValue("SELECT COUNT(*) FROM `member_portfolios`");
            if ($count === 0) {
                db()->query("
                    INSERT INTO `member_portfolios` (`id`, `member_id`, `title`, `slug`, `category`, `client`, `role_in_project`, `summary`, `description`, `image_url`, `project_url`, `tech_stack`, `completion_date`, `is_featured`, `sort_order`) VALUES
                    (1, 1, 'Cheetah Enterprise Logistics Core', 'cheetah-logistics-core', 'Enterprise Architecture', 'National Freight Network', 'Lead Architect & Backend Engineer', 'Designed the multi-origin dispatch engine, 7 role portals, and automated manifest generator.', 'Led complete system design, normalized 78 database tables, implemented sub-millisecond barcode tracking lookups, and built zero-dependency native chat.', '/assets/images/cheetah-showcase.jpg', 'https://creatsify.tech/#cheetah', 'PHP 8.2, MySQL, Laravel, WebSockets', '2026', 1, 1),
                    (2, 1, 'Multi-Tenant Cloud Dispatcher', 'multi-tenant-cloud-dispatcher', 'Cloud SaaS', 'Global Delivery Startups', 'Full-Stack Developer', 'Automated tenant subdomain provisioning with isolated database schemas and Stripe billing.', 'Built the automated provisioning pipeline allowing clients to launch branded courier instances in under 60 seconds.', '/assets/images/hero-artwork.jpg', 'https://creatsify.tech', 'PHP, MySQL, Redis, Docker', '2026', 1, 2),
                    (3, 2, 'Cheetah Obsidian UI/UX Design System', 'cheetah-design-system', 'Product Design', 'Creatsify Studio', 'Lead UI/UX Designer', 'Crafted a dark-mode obsidian design system with high-contrast accessibility tokens and 3D glass widgets.', 'Constructed 250+ atomic Figma components, telemetry chart layouts, and interactive mobile driver interfaces.', '/assets/images/cheetah-showcase.jpg', 'https://creatsify.tech', 'Figma, Design Tokens, CSS Variables', '2026', 1, 1),
                    (4, 2, 'RestroMaster POS & KDS Interface', 'restromaster-kds-interface', 'Interface Design', 'Hospitality Client', 'UI/UX Designer', 'Designed touch-optimized kitchen display system and contactless QR tabletop menu ordering.', 'Reduced order-to-kitchen latency by designing high-visibility order ticket states and ergonomic touch targets.', '/assets/images/restromaster-preview.jpg', 'https://creatsify.tech', 'Figma, Micro-animations, SVG Systems', '2026', 1, 2),
                    (5, 3, 'Cheetah Rider & Driver Companion', 'cheetah-rider-companion', 'Mobile Development', 'Courier Fleet', 'Lead Mobile Developer', 'Native Flutter mobile app for delivery couriers featuring offline signature ePOD and background GPS tracking.', 'Implemented background geofencing, thermal Bluetooth barcode scanner integrations, and instantaneous route recalculations.', '/assets/images/cheetah-showcase.jpg', 'https://creatsify.tech', 'Flutter, Dart, SQLite, Google Maps SDK', '2026', 1, 1),
                    (6, 3, 'Flavor Fleet Multi-Vendor Food App', 'flavor-fleet-mobile', 'Mobile App', 'Food Startup', 'Flutter Engineer', 'Customer-facing food ordering app with live delivery route animations and payment gateways.', 'Created a seamless multi-restaurant browsing experience with category filters, cart persistence, and order tracking.', '/assets/images/restromaster-preview.jpg', 'https://creatsify.tech', 'Flutter, Dart, Firebase, Stripe SDK', '2026', 1, 2)
                ");
            }
        } catch (\Throwable $e) {}
    }

    public static function forMember(int $memberId): array
    {
        self::ensureTableExists();
        return db()->fetchAll("
            SELECT * FROM `member_portfolios` 
            WHERE `member_id` = :mid 
            ORDER BY `sort_order` ASC, `id` DESC
        ", ['mid' => $memberId]);
    }

    public static function findByMemberId(int $memberId): array
    {
        return self::forMember($memberId);
    }

    public static function findById(int $id): ?array
    {
        self::ensureTableExists();
        return db()->fetchOne("SELECT * FROM `member_portfolios` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function create(array $data): int
    {
        self::ensureTableExists();
        $data['created_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('member_portfolios', $data);
    }

    public static function update(int $id, array $data): int
    {
        self::ensureTableExists();
        $data['updated_at'] = date('Y-m-d H:i:s');
        return db()->update('member_portfolios', $data, '`id` = :id', ['id' => $id]);
    }

    public static function toggleFeatured(int $id): bool
    {
        $item = self::findById($id);
        if (!$item) return false;
        $new = $item['is_featured'] ? 0 : 1;
        self::update($id, ['is_featured' => $new]);
        return (bool)$new;
    }

    public static function delete(int $id): int
    {
        self::ensureTableExists();
        return db()->delete('member_portfolios', '`id` = :id', ['id' => $id]);
    }
}
