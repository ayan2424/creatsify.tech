<?php

namespace App\Models;

/**
 * Creatsify Studio - Portfolio Projects & Ready-Made Software Model
 *
 * Core Work Categories (7 Official Disciplines):
 * - web-dev           => Web Design & Development
 * - mobile-app        => Mobile App Development
 * - saas-crm          => SaaS & CRM
 * - software-eng      => Software & Product Engineering
 * - seo-geo           => SEO & GEO
 * - digital-marketing => Digital Marketing
 * - ui-ux-branding    => UI/UX Design & Branding
 *
 * Ready-Made Software is maintained strictly as an attribute tag (`is_software = 1`),
 * enabling dual-surface showcase on both /software (#products) and /portfolio (#work).
 */
class Project
{
    public const CATEGORIES = [
        'web-dev'           => 'Web Design & Development',
        'mobile-app'        => 'Mobile App Development',
        'saas-crm'          => 'SaaS & CRM',
        'software-eng'      => 'Software & Product Engineering',
        'seo-geo'           => 'SEO & GEO',
        'digital-marketing' => 'Digital Marketing',
        'ui-ux-branding'    => 'UI/UX Design & Branding',
    ];

    private static bool $checked = false;

    public static function categories(): array
    {
        return self::CATEGORIES;
    }

    public static function categoryLabel(string $key): string
    {
        $normalized = self::normalizeCategory($key);
        return self::CATEGORIES[$normalized] ?? ucfirst(str_replace('-', ' ', $key));
    }

    public static function normalizeCategory(?string $cat): string
    {
        if (empty($cat)) {
            return 'all';
        }
        $cat = strtolower(trim($cat));
        if (isset(self::CATEGORIES[$cat])) {
            return $cat;
        }

        return match ($cat) {
            'saas', 'crm'                               => 'saas-crm',
            'apps', 'mobile', 'flutter'                 => 'mobile-app',
            'stores', 'redesign', 'web', 'web-saas'     => 'web-dev',
            'software', 'engineering', 'eng', 'courier' => 'software-eng',
            'seo', 'geo'                                => 'seo-geo',
            'growth', 'marketing', 'ads'                => 'digital-marketing',
            'branding', 'design', 'ui-ux', 'ui/ux'      => 'ui-ux-branding',
            default                                     => $cat,
        };
    }

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

        // Ensure is_software & subcategory columns exist for unified Work & Software architecture
        try {
            db()->query("SELECT `is_software`, `subcategory` FROM `projects` LIMIT 1");
        } catch (\Throwable $e) {
            try {
                db()->query("ALTER TABLE `projects` 
                    ADD COLUMN `is_software` TINYINT(1) NOT NULL DEFAULT 0 AFTER `featured`, 
                    ADD COLUMN `subcategory` VARCHAR(100) NULL AFTER `category`");
            } catch (\Throwable $e2) {}
        }

        // Backfill & normalize projects into the 7 official categories with proper Ready-Made tags
        try {
            // 1. Cheetah Enterprise (SaaS & CRM Backbone + Ready-Made Software)
            db()->query("UPDATE `projects` SET 
                `category` = 'saas-crm',
                `subcategory` = 'Courier & Logistics Backbone',
                `is_software` = 1,
                `status` = 'For sale',
                `price` = COALESCE(NULLIF(`price`, ''), '$99')
                WHERE `slug` = 'cheetah-enterprise'");

            // 2. Cheetah Lite (Software & Product Engineering + Ready-Made Software)
            db()->query("UPDATE `projects` SET 
                `category` = 'software-eng',
                `subcategory` = 'Courier Dispatch Suite',
                `is_software` = 1,
                `status` = 'For sale',
                `price` = COALESCE(NULLIF(`price`, ''), '$49')
                WHERE `slug` = 'cheetah-lite'");

            // 3. Cheezify POS (Web Design & Development + Ready-Made Software)
            db()->query("UPDATE `projects` SET 
                `category` = 'web-dev',
                `subcategory` = 'Food Ordering & POS',
                `is_software` = 1,
                `status` = 'For sale',
                `price` = COALESCE(NULLIF(`price`, ''), '$79')
                WHERE `slug` = 'cheezify'");

            // 4. Apex Retail Redesign (Web Design & Development client work)
            db()->query("UPDATE `projects` SET 
                `category` = 'web-dev',
                `subcategory` = 'Storefront Redesign & CRO',
                `is_software` = 0,
                `status` = 'Delivered'
                WHERE `slug` = 'apex-retail-redesign'");

            // 5. Lumina Fleet Companion (Mobile App Development client work)
            db()->query("UPDATE `projects` SET 
                `category` = 'mobile-app',
                `subcategory` = 'Flutter Mobile Companion',
                `is_software` = 0,
                `status` = 'Delivered'
                WHERE `slug` = 'lumina-fleet-app'");

            // 6. Kinetix Brand System (UI/UX Design & Branding client work)
            db()->query("UPDATE `projects` SET 
                `category` = 'ui-ux-branding',
                `subcategory` = 'Brand Identity & Ads',
                `is_software` = 0,
                `status` = 'Delivered'
                WHERE `slug` = 'kinetix-brand-system'");
        } catch (\Throwable $e) {}

        // Ensure RestroMaster and ViddMate projects exist for real showcase
        try {
            $restro = db()->fetchOne("SELECT `id` FROM `projects` WHERE `slug` = 'restromaster-kds-interface' LIMIT 1");
            if (!$restro) {
                db()->query("INSERT INTO `projects` 
                    (`title`, `slug`, `category`, `subcategory`, `client`, `featured`, `is_software`, `summary`, `description`, `image_url`, `live_url`, `gumroad_url`, `price`, `tech_stack`, `status`, `sort_order`) 
                    VALUES 
                    ('RestroMaster POS & KDS Interface', 'restromaster-kds-interface', 'saas-crm', 'Restaurant POS & KDS', 'Zenith Hospitality Group', 1, 1, 
                     'Touch-optimized kitchen display system, contactless QR tabletop ordering, and real-time order routing for high-volume venues.',
                     'Complete restaurant management ecosystem featuring low-latency kitchen order tickets (KOT), multi-station display routing, QR digital menu with real-time bill settlement, and thermal printer integration.',
                     '/assets/images/restromaster-preview.jpg', '', '', '$79', 'PHP 8.4, Vue.js, Tailwind CSS, WebSocket, MySQL 8', 'For sale', 4)");
            } else {
                db()->query("UPDATE `projects` SET `category` = 'saas-crm', `subcategory` = 'Restaurant POS & KDS', `is_software` = 1, `status` = 'For sale', `price` = '$79' WHERE `slug` = 'restromaster-kds-interface'");
            }

            $viddmate = db()->fetchOne("SELECT `id` FROM `projects` WHERE `slug` = 'viddmate-video-suite' LIMIT 1");
            if (!$viddmate) {
                db()->query("INSERT INTO `projects` 
                    (`title`, `slug`, `category`, `subcategory`, `client`, `featured`, `is_software`, `summary`, `description`, `image_url`, `live_url`, `gumroad_url`, `price`, `tech_stack`, `status`, `sort_order`) 
                    VALUES 
                    ('ViddMate 4K Video Transcoding Suite', 'viddmate-video-suite', 'software-eng', 'Video Encoding & Transcoding', 'Kinetix Cloud', 1, 1, 
                     'High-throughput cloud media encoding and transcoding web platform with GPU acceleration and automated storage delivery.',
                     'Scalable video processing infrastructure engineered for automated 4K H.265/AV1 transcoding, audio waveform extraction, watermarking, and instant cloud CDN distribution with webhooks.',
                     '/assets/images/viddmate-preview.jpg', '', '', '$89', 'PHP 8.4, Node.js, FFmpeg, Redis, Tailwind CSS', 'For sale', 5)");
            } else {
                db()->query("UPDATE `projects` SET `category` = 'software-eng', `subcategory` = 'Video Encoding & Transcoding', `is_software` = 1, `status` = 'For sale', `price` = '$89' WHERE `slug` = 'viddmate-video-suite'");
            }

            // Ensure SEO & GEO Showcase Case Study
            $pulse = db()->fetchOne("SELECT `id` FROM `projects` WHERE `slug` = 'pulserank-geo-seo' LIMIT 1");
            if (!$pulse) {
                db()->query("INSERT INTO `projects` 
                    (`title`, `slug`, `category`, `subcategory`, `client`, `featured`, `is_software`, `summary`, `description`, `image_url`, `live_url`, `gumroad_url`, `price`, `tech_stack`, `status`, `sort_order`) 
                    VALUES 
                    ('PulseRank SEO & GEO Engine', 'pulserank-geo-seo', 'seo-geo', 'AI Search & Local Entity Authority', 'HealthScale Networks', 1, 0, 
                     'Dominating local search and Generative AI engine overviews (Perplexity, ChatGPT, Google SGE) with structured knowledge graphs and entity-first technical SEO.',
                     'HealthScale Networks operated 42 regional clinics but lacked organic visibility in generative AI overviews. Creatsify deployed comprehensive entity-based semantic SEO, automated Schema.org JSON-LD microdata, medical knowledge graph linking, and high-velocity content clusters, securing 1st-tier AI answer citations and +310% qualified patient appointments.',
                     '/assets/images/pulserank-preview.jpg', '', '', '', 'Generative Engine Optimization (GEO), Schema.org, Next.js, Entity SEO', 'Delivered', 6)");
            } else {
                db()->query("UPDATE `projects` SET `category` = 'seo-geo', `subcategory` = 'AI Search & Local Entity Authority', `is_software` = 0 WHERE `slug` = 'pulserank-geo-seo'");
            }

            // Ensure Digital Marketing Showcase Case Study
            $nova = db()->fetchOne("SELECT `id` FROM `projects` WHERE `slug` = 'novagrowth-performance-ads' LIMIT 1");
            if (!$nova) {
                db()->query("INSERT INTO `projects` 
                    (`title`, `slug`, `category`, `subcategory`, `client`, `featured`, `is_software`, `summary`, `description`, `image_url`, `live_url`, `gumroad_url`, `price`, `tech_stack`, `status`, `sort_order`) 
                    VALUES 
                    ('NovaGrowth Omnichannel Ad Engine', 'novagrowth-performance-ads', 'digital-marketing', 'Omnichannel Performance Ad Engine', 'NovaFin Capital', 1, 0, 
                     'Engineered high-converting creative ad variations, landing page CRO funnels, and algorithmic Meta & Google PPC campaigns scaling qualified enterprise leads.',
                     'NovaFin Capital required scalable B2B customer acquisition with rigorous unit economics. Creatsify designed and managed end-to-end full-funnel digital campaigns across Meta, Google Search, and LinkedIn, pairing algorithmic audience bidding with high-velocity landing page split testing to achieve a sustained 4.1x ROAS.',
                     '/assets/images/novagrowth-preview.jpg', '', '', '', 'Meta Ads API, Google PPC, CRO Funnels, PostHog, GA4', 'Delivered', 7)");
            } else {
                db()->query("UPDATE `projects` SET `category` = 'digital-marketing', `subcategory` = 'Omnichannel Performance Ad Engine', `is_software` = 0 WHERE `slug` = 'novagrowth-performance-ads'");
            }

            // Migrate any remaining legacy category values across the table
            db()->query("UPDATE `projects` SET `category` = 'web-dev' WHERE `category` IN ('stores', 'redesign')");
            db()->query("UPDATE `projects` SET `category` = 'mobile-app' WHERE `category` = 'apps'");
            db()->query("UPDATE `projects` SET `category` = 'saas-crm' WHERE `category` = 'saas'");
            db()->query("UPDATE `projects` SET `category` = 'digital-marketing' WHERE `category` = 'growth'");
            db()->query("UPDATE `projects` SET `category` = 'ui-ux-branding' WHERE `category` = 'branding'");

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
            WHERE (`is_software` = 1 OR `status` = 'For sale') 
            AND `slug` != 'sawi-store' 
            ORDER BY `sort_order` ASC, `id` DESC");
    }

    public static function portfolioWorks(?string $category = null): array
    {
        self::ensureColumnsExist();
        $sql = "SELECT * FROM `projects` WHERE `slug` != 'sawi-store'";
        $params = [];

        if ($category && $category !== 'all') {
            $norm = self::normalizeCategory($category);
            if (isset(self::CATEGORIES[$norm])) {
                $sql .= " AND (`category` = :cat";
                $params['cat'] = $norm;

                // Also support legacy aliases in case any rows haven't updated
                if ($norm === 'web-dev') {
                    $sql .= " OR `category` IN ('stores', 'redesign', 'web')";
                } elseif ($norm === 'mobile-app') {
                    $sql .= " OR `category` IN ('apps', 'mobile')";
                } elseif ($norm === 'saas-crm') {
                    $sql .= " OR `category` = 'saas'";
                } elseif ($norm === 'digital-marketing') {
                    $sql .= " OR `category` IN ('growth', 'ads')";
                } elseif ($norm === 'ui-ux-branding') {
                    $sql .= " OR `category` = 'branding'";
                }
                $sql .= ")";
            }
        }
        $sql .= " ORDER BY `sort_order` ASC, `id` DESC";
        return db()->fetchAll($sql, $params);
    }

    public static function allByCategory(?string $category = null): array
    {
        self::ensureColumnsExist();
        if ($category === 'software') {
            return self::readyMadeProducts();
        }
        if ($category === 'client-work') {
            return db()->fetchAll("SELECT * FROM `projects` WHERE (`is_software` = 0 OR `is_software` IS NULL) AND `status` != 'For sale' AND `slug` != 'sawi-store' ORDER BY `sort_order` ASC, `id` DESC");
        }
        return self::portfolioWorks($category);
    }

    public static function categoryCounts(): array
    {
        self::ensureColumnsExist();
        $all = (int)(db()->fetchOne("SELECT COUNT(*) as cnt FROM `projects` WHERE `slug` != 'sawi-store'")['cnt'] ?? 0);
        $software = (int)(db()->fetchOne("SELECT COUNT(*) as cnt FROM `projects` WHERE (`is_software` = 1 OR `status` = 'For sale') AND `slug` != 'sawi-store'")['cnt'] ?? 0);
        $clientWork = (int)(db()->fetchOne("SELECT COUNT(*) as cnt FROM `projects` WHERE (`is_software` = 0 OR `is_software` IS NULL) AND `status` != 'For sale' AND `slug` != 'sawi-store'")['cnt'] ?? 0);

        $counts = [
            'all'         => $all,
            'software'    => $software,
            'client_work' => $clientWork,
        ];

        foreach (self::CATEGORIES as $key => $name) {
            $cnt = (int)(db()->fetchOne("SELECT COUNT(*) as cnt FROM `projects` WHERE `slug` != 'sawi-store' AND `category` = :cat", ['cat' => $key])['cnt'] ?? 0);
            $counts[$key] = $cnt;
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
