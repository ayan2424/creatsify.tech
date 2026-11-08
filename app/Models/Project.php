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
                     '/assets/images/restromaster-preview.jpg', '', '', '$79', 'PHP 8.4, Vue.js, Tailwind CSS, WebSocket, MySQL 8', 'For sale', 4)");
            } else {
                db()->query("UPDATE `projects` SET `status` = 'For sale', `price` = '$79' WHERE `slug` = 'restromaster-kds-interface'");
            }

            $viddmate = db()->fetchOne("SELECT `id` FROM `projects` WHERE `slug` = 'viddmate-video-suite' LIMIT 1");
            if (!$viddmate) {
                db()->query("INSERT INTO `projects` 
                    (`title`, `slug`, `category`, `client`, `featured`, `summary`, `description`, `image_url`, `live_url`, `gumroad_url`, `price`, `tech_stack`, `status`, `sort_order`) 
                    VALUES 
                    ('ViddMate 4K Video Transcoding Suite', 'viddmate-video-suite', 'saas', 'Kinetix Cloud', 1, 
                     'High-throughput cloud media encoding and transcoding web platform with GPU acceleration and automated storage delivery.',
                     'Scalable video processing infrastructure engineered for automated 4K H.265/AV1 transcoding, audio waveform extraction, watermarking, and instant cloud CDN distribution with webhooks.',
                     '/assets/images/viddmate-preview.jpg', '', '', '$89', 'PHP 8.4, Node.js, FFmpeg, Redis, Tailwind CSS', 'For sale', 5)");
            } else {
                db()->query("UPDATE `projects` SET `status` = 'For sale', `price` = '$89' WHERE `slug` = 'viddmate-video-suite'");
            }

            // Client Portfolio Case Studies
            $apex = db()->fetchOne("SELECT `id` FROM `projects` WHERE `slug` = 'apex-retail-redesign' LIMIT 1");
            if (!$apex) {
                db()->query("INSERT INTO `projects` 
                    (`title`, `slug`, `category`, `client`, `featured`, `summary`, `description`, `image_url`, `live_url`, `gumroad_url`, `price`, `tech_stack`, `status`, `sort_order`) 
                    VALUES 
                    ('Apex Retail — High-Speed Storefront Redesign', 'apex-retail-redesign', 'redesign', 'Apex Retail Group', 1, 
                     'Re-engineered a slow legacy e-commerce site into a mobile-first, conversion-optimized experience with streamlined 1-step checkout and sub-second load times.',
                     'Apex Retail Group was suffering high cart abandonment on mobile devices due to slow legacy rendering and disjointed page flows. Creatsify executed an end-to-end design and engineering sprint: replacing the bloated monolithic theme with a high-speed Next.js + Tailwind CSS architecture, consolidating the checkout to a frictionless 1-step flow, and implementing automated CRO analytics silos.',
                     '/assets/images/apex-preview.jpg', '', '', '', 'Next.js, Tailwind CSS, UI/UX Redesign, CRO Funnels', 'Delivered', 1)");
            }

            $lumina = db()->fetchOne("SELECT `id` FROM `projects` WHERE `slug` = 'lumina-fleet-app' LIMIT 1");
            if (!$lumina) {
                db()->query("INSERT INTO `projects` 
                    (`title`, `slug`, `category`, `client`, `featured`, `summary`, `description`, `image_url`, `live_url`, `gumroad_url`, `price`, `tech_stack`, `status`, `sort_order`) 
                    VALUES 
                    ('Lumina Fleet Companion — Cross-Platform Mobile Suite', 'lumina-fleet-app', 'apps', 'Lumina Health Tech', 1, 
                     'Engineered native-speed iOS and Android mobile applications featuring offline-first state synchronization, digital signature capture, and cloud API integration.',
                     'Engineered cross-platform mobile companion apps in Flutter & Dart for Lumina field operators. Featuring 60fps fluid UI animations, offline-first SQLite state caching with automated cloud sync, biometric authentication, digital proof-of-delivery signatures, and instant push notification routing.',
                     '/assets/images/lumina-preview.jpg', '', '', '', 'Flutter, Dart, Node.js API, SQLite, Cloud Sync', 'Delivered', 2)");
            }

            $kinetix = db()->fetchOne("SELECT `id` FROM `projects` WHERE `slug` = 'kinetix-brand-system' LIMIT 1");
            if (!$kinetix) {
                db()->query("INSERT INTO `projects` 
                    (`title`, `slug`, `category`, `client`, `featured`, `summary`, `description`, `image_url`, `live_url`, `gumroad_url`, `price`, `tech_stack`, `status`, `sort_order`) 
                    VALUES 
                    ('Kinetix Cloud — Complete Brand Identity & Performance Ad Funnel', 'kinetix-brand-system', 'growth', 'Kinetix Cloud Solutions', 1, 
                     'Designed a distinctive 3D & vector logo system, dark/light UI tokens, and executed high-CTR Meta and Google PPC ad campaigns to scale qualified inbound leads.',
                     'Kinetix Cloud required a high-authority brand identity to compete in global B2B cloud infrastructure markets. Creatsify developed a 3D procedural logo mark, scalable Figma design tokens, and launched full-funnel Meta and Google Ads campaigns that achieved a 3.8x verified return on ad spend (ROAS).',
                     '/assets/images/kinetix-preview.jpg', '', '', '', 'Logo Design, Brand Book, Meta Ads, Google PPC, Figma Tokens', 'Delivered', 3)");
            }

            // Ensure Sawi Store is deleted from table
            db()->query("DELETE FROM `projects` WHERE `slug` = 'sawi-store'");
        } catch (\Throwable $e) {}
    }

    public static function all(): array
    {
        self::ensureColumnsExist();
        return db()->fetchAll("SELECT * FROM `projects` WHERE `slug` != 'sawi-store' ORDER BY `sort_order` ASC, `id` DESC");
    }

    public static function readyMadeProducts(): array
    {
        self::ensureColumnsExist();
        return db()->fetchAll("SELECT * FROM `projects` 
            WHERE `slug` IN ('cheetah-enterprise', 'cheetah-lite', 'cheezify', 'restromaster-kds-interface', 'viddmate-video-suite') 
            ORDER BY FIELD(`slug`, 'cheetah-enterprise', 'cheetah-lite', 'cheezify', 'restromaster-kds-interface', 'viddmate-video-suite')");
    }

    public static function portfolioWorks(?string $category = null): array
    {
        self::ensureColumnsExist();
        $sql = "SELECT * FROM `projects` WHERE `slug` NOT IN ('cheetah-enterprise', 'cheetah-lite', 'cheezify', 'restromaster-kds-interface', 'viddmate-video-suite', 'sawi-store')";
        $params = [];
        if ($category && $category !== 'all') {
            if ($category === 'web-saas' || $category === 'saas') {
                $sql .= " AND (`category` IN ('saas', 'web-saas') OR `tech_stack` LIKE '%PHP%' OR `tech_stack` LIKE '%Next.js%')";
            } elseif ($category === 'mobile' || $category === 'apps') {
                $sql .= " AND (`category` = 'apps' OR `tech_stack` LIKE '%Flutter%')";
            } elseif ($category === 'redesign' || $category === 'branding') {
                $sql .= " AND (`category` IN ('redesign', 'branding', 'growth') OR `tech_stack` LIKE '%UI/UX%' OR `tech_stack` LIKE '%Logo%')";
            } elseif ($category === 'growth') {
                $sql .= " AND (`category` IN ('growth', 'ads', 'seo') OR `tech_stack` LIKE '%Ads%')";
            } else {
                $sql .= " AND `category` = :cat";
                $params['cat'] = $category;
            }
        }
        $sql .= " ORDER BY `sort_order` ASC, `id` DESC";
        return db()->fetchAll($sql, $params);
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
