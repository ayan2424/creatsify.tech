<?php

namespace App\Models;

/**
 * Creatsify Studio - Agency Team Member & Staff Model
 * Manages workers, engineers, designers and creators in the agency.
 */
class TeamMember
{
    private static bool $checked = false;

    public static function ensureTableExists(): void
    {
        // First ensure dependent member_portfolios table exists
        MemberPortfolio::ensureTableExists();

        if (self::$checked) {
            return;
        }
        self::$checked = true;

        try {
            db()->query("SELECT 1 FROM `team_members` LIMIT 1");
        } catch (\Throwable $e) {
            // Auto-create table if missing
            db()->query("
                CREATE TABLE IF NOT EXISTS `team_members` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(150) NOT NULL,
                    `slug` VARCHAR(150) NOT NULL UNIQUE,
                    `role_title` VARCHAR(150) NOT NULL,
                    `bio` TEXT NULL,
                    `avatar` VARCHAR(255) NULL,
                    `skills` VARCHAR(255) NULL,
                    `github_url` VARCHAR(255) NULL,
                    `linkedin_url` VARCHAR(255) NULL,
                    `portfolio_url` VARCHAR(255) NULL,
                    `email` VARCHAR(150) NULL,
                    `phone` VARCHAR(50) NULL,
                    `sort_order` INT DEFAULT 0,
                    `is_active` TINYINT(1) DEFAULT 1,
                    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX `idx_team_slug` (`slug`),
                    INDEX `idx_team_active` (`is_active`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // Seed default founder & core teammates if table is freshly created
            self::seedDefaults();
        }
    }

    private static function seedDefaults(): void
    {
        try {
            $count = (int)db()->fetchValue("SELECT COUNT(*) FROM `team_members`");
            if ($count === 0) {
                db()->query("
                    INSERT INTO `team_members` (`id`, `name`, `slug`, `role_title`, `bio`, `avatar`, `skills`, `github_url`, `linkedin_url`, `portfolio_url`, `email`, `sort_order`, `is_active`) VALUES
                    (1, 'Muhammad Ayan Shamim', 'ayan-shamim', 'Founder & Lead Full-Stack Architect', 'Founder of Creatsify. ACCP AI student at Aptech Karachi. Specializes in building mission-critical courier platforms, multi-tenant SaaS backends, and full-stack software architectures.', '/assets/brand/creatsify-icon.png', 'PHP, Laravel, Python, React, Next.js, MySQL, Redis, Docker', 'https://github.com/ayan2424', 'https://linkedin.com/in/muhammadayanshamim', 'https://creatsify.tech', 'ayan@creatsify.tech', 1, 1),
                    (2, 'Bilal Ahmed', 'bilal-ahmed', 'Senior UI/UX & Design Systems Lead', 'Creative design architect with 4+ years of experience crafting high-conversion design systems, SaaS dashboards, and immersive micro-interactions.', '/assets/brand/creatsify-icon.png', 'Figma, UI/UX, Design Systems, Tailwind CSS, Interaction Design, Framer', 'https://github.com', 'https://linkedin.com', 'https://dribbble.com', 'bilal@creatsify.tech', 2, 1),
                    (3, 'Hamza Malik', 'hamza-malik', 'Mobile Apps & Flutter Specialist', 'Cross-platform mobile developer dedicated to building fluid, 60fps native iOS and Android apps with robust offline sync and clean state architectures.', '/assets/brand/creatsify-icon.png', 'Flutter, Dart, Android, iOS, REST APIs, Firebase, SQLite', 'https://github.com', 'https://linkedin.com', 'https://github.com', 'hamza@creatsify.tech', 3, 1)
                ");
            }
        } catch (\Throwable $e) {}
    }

    public static function all(): array
    {
        self::ensureTableExists();
        return db()->fetchAll("
            SELECT m.*, COUNT(p.id) as portfolio_count 
            FROM `team_members` m 
            LEFT JOIN `member_portfolios` p ON m.id = p.member_id 
            GROUP BY m.id 
            ORDER BY m.sort_order ASC, m.id ASC
        ");
    }

    public static function allActive(): array
    {
        self::ensureTableExists();
        return db()->fetchAll("
            SELECT m.*, COUNT(p.id) as portfolio_count 
            FROM `team_members` m 
            LEFT JOIN `member_portfolios` p ON m.id = p.member_id 
            WHERE m.is_active = 1 
            GROUP BY m.id 
            ORDER BY m.sort_order ASC, m.id ASC
        ");
    }

    public static function findById(int $id): ?array
    {
        self::ensureTableExists();
        return db()->fetchOne("SELECT * FROM `team_members` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function findBySlug(string $slug): ?array
    {
        self::ensureTableExists();
        return db()->fetchOne("SELECT * FROM `team_members` WHERE `slug` = :s LIMIT 1", ['s' => $slug]);
    }

    public static function create(array $data): int
    {
        self::ensureTableExists();
        $data['created_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('team_members', $data);
    }

    public static function update(int $id, array $data): int
    {
        self::ensureTableExists();
        $data['updated_at'] = date('Y-m-d H:i:s');
        return db()->update('team_members', $data, '`id` = :id', ['id' => $id]);
    }

    public static function toggleActive(int $id): bool
    {
        $member = self::findById($id);
        if (!$member) return false;
        $new = $member['is_active'] ? 0 : 1;
        self::update($id, ['is_active' => $new]);
        return (bool)$new;
    }

    public static function delete(int $id): int
    {
        self::ensureTableExists();
        // Also delete member portfolios
        db()->delete('member_portfolios', '`member_id` = :m', ['m' => $id]);
        return db()->delete('team_members', '`id` = :id', ['id' => $id]);
    }

    public static function portfolios(int $memberId): array
    {
        MemberPortfolio::ensureTableExists();
        return db()->fetchAll("
            SELECT * FROM `member_portfolios` 
            WHERE `member_id` = :mid 
            ORDER BY `sort_order` ASC, `id` DESC
        ", ['mid' => $memberId]);
    }
}
