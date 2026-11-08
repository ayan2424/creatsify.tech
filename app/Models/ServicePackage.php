<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Creatsify Digital Agency - Service Packages & Pricing Tiers Model
 * Self-healing schema, dual-key normalization, and 3-track agency packages seeder.
 */
class ServicePackage
{
    public static function ensureTableExists(): void
    {
        static $checked = false;
        if ($checked) {
            return;
        }
        $checked = true;

        try {
            db()->query("SELECT `id`, `category_track`, `is_popular` FROM `service_packages` LIMIT 1");
        } catch (\Throwable $e) {
            try {
                db()->query("CREATE TABLE IF NOT EXISTS `service_packages` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `title` VARCHAR(180) NOT NULL,
                    `slug` VARCHAR(180) NOT NULL,
                    `category_track` VARCHAR(50) NOT NULL DEFAULT 'dev',
                    `price` VARCHAR(60) NOT NULL DEFAULT '$299',
                    `billing_unit` VARCHAR(40) NOT NULL DEFAULT '/project',
                    `delivery_time` VARCHAR(80) NOT NULL DEFAULT '7–14 Days',
                    `badge_text` VARCHAR(80) NULL,
                    `is_popular` TINYINT(1) NOT NULL DEFAULT 0,
                    `summary` VARCHAR(350) NULL,
                    `features` TEXT NOT NULL,
                    `cta_label` VARCHAR(80) NOT NULL DEFAULT 'Select Package',
                    `sort_order` INT NOT NULL DEFAULT 0,
                    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
                    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX `idx_pkg_track` (`category_track`),
                    INDEX `idx_pkg_active` (`is_active`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            } catch (\Throwable $e2) {
                return;
            }
        }

        // Self-heal delivery_time column if table was created earlier without it
        try {
            db()->query("SELECT `delivery_time` FROM `service_packages` LIMIT 1");
        } catch (\Throwable $e) {
            try {
                db()->query("ALTER TABLE `service_packages` ADD COLUMN `delivery_time` VARCHAR(80) NOT NULL DEFAULT '7–14 Days' AFTER `billing_unit`");
            } catch (\Throwable $e2) {
            }
        }

        try {
            $row = db()->fetchOne("SELECT COUNT(*) AS cnt FROM `service_packages`");
            if ((int)($row['cnt'] ?? 0) === 0) {
                self::seedDefaults();
            }
        } catch (\Throwable $e) {
        }
    }

    public static function tracks(): array
    {
        return [
            'dev'    => ['label' => '01. Web, Mobile App & SaaS Dev', 'short' => 'Web, App & SaaS'],
            'design' => ['label' => '02. Branding, Logo & Website Redesign', 'short' => 'Branding & Redesign'],
            'growth' => ['label' => '03. SEO & Social Media Paid Ads', 'short' => 'SEO & Paid Ads'],
        ];
    }

    private static function seedDefaults(): void
    {
        $seeds = [
            // TRACK 1: Web, App & SaaS Development
            [
                'title'          => 'Launchpad Web',
                'slug'           => 'launchpad-web',
                'category_track' => 'dev',
                'price'          => '$299',
                'billing_unit'   => 'project',
                'delivery_time'  => '7–10 Days',
                'badge_text'     => 'STARTER BUILD',
                'is_popular'     => 0,
                'summary'        => 'Ideal for startups & service brands needing a high-converting business website or modern landing funnel.',
                'features'       => "Up to 7 Custom Responsive Pages\nHTML5/CSS3, JS, Tailwind, Bootstrap or WordPress\nMobile-First UI/UX & 95+ Speed Optimization\nLead Capture Form + WhatsApp Chat Integration\nTechnical On-Page SEO & Schema Setup\n7–10 Days Turnaround + Full Ownership",
                'cta_label'      => 'Select Launchpad Web',
                'sort_order'     => 1,
                'is_active'      => 1,
            ],
            [
                'title'          => 'Growth Web App & Store',
                'slug'           => 'growth-web-app-store',
                'category_track' => 'dev',
                'price'          => '$699',
                'billing_unit'   => 'project',
                'delivery_time'  => '2–3 Weeks',
                'badge_text'     => 'MOST POPULAR',
                'is_popular'     => 1,
                'summary'        => 'Full-stack custom web application, client portal, or high-speed e-commerce store with admin dashboard.',
                'features'       => "Custom Full-Stack Build (PHP 8.4, Node.js, or Next.js)\nNormalized MySQL Database & Role Admin Panel\nE-Commerce Cart, Payment Gateways & Invoicing\nOfficial WhatsApp Cloud API / Email Notifications\nComplete Custom UI/UX + Technical SEO Architecture\nLinux VPS / Cloud Server Deployment + 30 Days Support",
                'cta_label'      => 'Select Growth Web App',
                'sort_order'     => 2,
                'is_active'      => 1,
            ],
            [
                'title'          => 'SaaS & Flutter Mobile Suite',
                'slug'           => 'saas-flutter-mobile-suite',
                'category_track' => 'dev',
                'price'          => '$1,499+',
                'billing_unit'   => 'suite',
                'delivery_time'  => '3–5 Weeks',
                'badge_text'     => 'ENTERPRISE SUITE',
                'is_popular'     => 0,
                'summary'        => 'Complete multi-tenant SaaS platform or enterprise web backend paired with iOS & Android Flutter apps.',
                'features'       => "Cross-Platform Flutter & Dart App (iOS & Android)\nScalable Multi-Role Web Backend & REST APIs\nReal-Time GPS Maps, Push Alerts & Live Chat\nComplete Brand Identity & Figma Design System\nApp Store, Google Play & Cloud Server Launch\n100% Unlocked Source Code & Priority SLA",
                'cta_label'      => 'Select SaaS & App Suite',
                'sort_order'     => 3,
                'is_active'      => 1,
            ],

            // TRACK 2: Branding, Logo & Website Redesign
            [
                'title'          => 'Logo & Identity Starter',
                'slug'           => 'logo-identity-starter',
                'category_track' => 'design',
                'price'          => '$149',
                'billing_unit'   => 'pack',
                'delivery_time'  => '4–5 Days',
                'badge_text'     => 'IDENTITY PACK',
                'is_popular'     => 0,
                'summary'        => 'Distinctive custom logo and core visual identity for new brands and ambitious startups.',
                'features'       => "3 Custom Master Logo Concepts (3D & Flat Vector)\nLight Mode, Dark Mode & Transparent Variations\nCustom Favicon Set & App Icon Exports\nBrand Color Palette & Typography Pairing Guide\nFull Vector Source Files (SVG, PNG, PDF, AI)\n4–5 Days Delivery + Unlimited Refinements",
                'cta_label'      => 'Select Logo Starter',
                'sort_order'     => 4,
                'is_active'      => 1,
            ],
            [
                'title'          => 'Complete Brand & Social Kit',
                'slug'           => 'complete-brand-social-kit',
                'category_track' => 'design',
                'price'          => '$349',
                'billing_unit'   => 'pack',
                'delivery_time'  => '7–10 Days',
                'badge_text'     => 'BEST FOR BRANDS',
                'is_popular'     => 1,
                'summary'        => 'Full visual identity system plus scroll-stopping social media and paid ad creative templates.',
                'features'       => "Everything in Logo & Identity Starter\nComprehensive 16-Page Brand Guidelines Book\n15 Custom Social Media Post & Story Templates\nHigh-CTR Paid Ad Banner Set (Meta, IG & Google)\nBusiness Card, Letterhead & Pitch Deck Cover\n100% Commercial Ownership & Editable Files",
                'cta_label'      => 'Select Complete Brand Kit',
                'sort_order'     => 5,
                'is_active'      => 1,
            ],
            [
                'title'          => 'Full Website Redesign & CRO',
                'slug'           => 'full-website-redesign-cro',
                'category_track' => 'design',
                'price'          => '$549',
                'billing_unit'   => 'project',
                'delivery_time'  => '10–14 Days',
                'badge_text'     => 'CONVERSION LIFT',
                'is_popular'     => 0,
                'summary'        => 'Complete UI/UX overhaul and modern frontend rebuild of your existing website to double conversions.',
                'features'       => "Deep UX, Speed & Conversion Rate Audit\nCustom High-Craft UI Redesign (Light & Dark Mode)\nClean Code Implementation (Tailwind / Next.js / PHP)\nCore Web Vitals Speed Optimization (95+ Score)\nSEO URL Preservation & Schema Upgrade\nZero Downtime Migration to Live Server",
                'cta_label'      => 'Select Website Redesign',
                'sort_order'     => 6,
                'is_active'      => 1,
            ],

            // TRACK 3: SEO & Social Media Marketing / Ads
            [
                'title'          => 'SEO Foundation & Rank Boost',
                'slug'           => 'seo-foundation-rank-boost',
                'category_track' => 'growth',
                'price'          => '$249',
                'billing_unit'   => 'month',
                'delivery_time'  => 'Monthly Sprint',
                'badge_text'     => 'ORGANIC GROWTH',
                'is_popular'     => 0,
                'summary'        => 'Technical and on-page SEO engineering to climb Google rankings and capture high-intent organic traffic.',
                'features'       => "Complete Technical SEO & Indexing Fix\n25 High-Intent Commercial Keywords Targeted\nOn-Page Meta, Internal Linking & JSON-LD Schema\nGoogle Business Profile & Local SEO Optimization\nPage Speed & Core Web Vitals Tuning\nTransparent Monthly Ranking & Traffic Dashboard",
                'cta_label'      => 'Start SEO Growth',
                'sort_order'     => 7,
                'is_active'      => 1,
            ],
            [
                'title'          => 'Social Media & Paid Ads Engine',
                'slug'           => 'social-media-paid-ads-engine',
                'category_track' => 'growth',
                'price'          => '$399',
                'billing_unit'   => 'month',
                'delivery_time'  => 'Active Campaigns',
                'badge_text'     => 'HIGHEST ROI',
                'is_popular'     => 1,
                'summary'        => 'Done-for-you social media marketing and high-ROAS Meta, Instagram, and Google ad campaigns.',
                'features'       => "Meta (Facebook & Instagram) + Google Ads Setup\nCustom Scroll-Stopping Ad Graphic & Copy Creation\nPixel, Conversion API (CAPI) & Retargeting Funnels\n12 Branded Social Media Posts & Reels/Carousels\nA/B Audience Testing & Daily Bid Optimization\nBi-Weekly ROAS & Cost-Per-Lead Attribution Report",
                'cta_label'      => 'Launch Paid Ads Engine',
                'sort_order'     => 8,
                'is_active'      => 1,
            ],
            [
                'title'          => 'Omnichannel Scale (SEO + Ads)',
                'slug'           => 'omnichannel-scale-seo-ads',
                'category_track' => 'growth',
                'price'          => '$749',
                'billing_unit'   => 'month',
                'delivery_time'  => 'Full-Funnel SLA',
                'badge_text'     => 'FULL FUNNEL',
                'is_popular'     => 0,
                'summary'        => 'Full-funnel organic SEO + multi-platform paid ads + ongoing landing page conversion optimization.',
                'features'       => "Full Technical & Content SEO (50+ Keywords)\nMulti-Channel Ads (Meta, Google Search & TikTok)\nDedicated Custom Landing Page Design & CRO\nFull Social Media Management & Visual Production\nCompetitor Spy & Funnel Attribution Tracking\nPriority Growth Strategist & Weekly Reviews",
                'cta_label'      => 'Select Omnichannel Scale',
                'sort_order'     => 9,
                'is_active'      => 1,
            ],
        ];

        foreach ($seeds as $s) {
            try {
                db()->insert('service_packages', $s);
            } catch (\Throwable $e) {
            }
        }
    }

    /**
     * Normalize a DB row so both legacy and view/admin keys are always populated.
     */
    private static function normalizeRow(array $row): array
    {
        $track = (string)($row['track'] ?? $row['category_track'] ?? 'dev');
        $price = (string)($row['price_label'] ?? $row['price'] ?? '$299');
        $rawUnit = trim((string)($row['billing_cycle'] ?? $row['billing_unit'] ?? 'project'));
        $cleanUnit = ltrim($rawUnit, '/');
        if ($cleanUnit === '+') {
            $cleanUnit = 'suite';
            if (!str_ends_with($price, '+')) {
                $price .= '+';
            }
        }
        if ($cleanUnit === '') {
            $cleanUnit = 'project';
        }

        $isPop = !empty($row['is_popular']);
        $badge = trim((string)($row['tier_badge'] ?? $row['badge_text'] ?? ''));
        if ($badge === '') {
            $badge = $isPop ? 'MOST POPULAR' : ($track === 'growth' ? 'GROWTH PLAN' : ($track === 'design' ? 'CREATIVE PACK' : 'FIXED MILESTONE'));
        }

        $delivery = trim((string)($row['delivery_time'] ?? ''));
        if ($delivery === '') {
            $delivery = match ($track) {
                'growth' => 'Monthly Sprint',
                'design' => '5–10 Days',
                default  => '7–14 Days',
            };
        }

        $summary = (string)($row['subtitle'] ?? $row['summary'] ?? '');
        $cta = (string)($row['cta_text'] ?? $row['cta_label'] ?? 'Select Package');

        $row['track']          = $track;
        $row['category_track'] = $track;
        $row['price_label']    = $price;
        $row['price']          = $price;
        $row['billing_cycle']  = $cleanUnit;
        $row['billing_unit']   = $cleanUnit;
        $row['tier_badge']     = $badge;
        $row['badge_text']     = $badge;
        $row['delivery_time']  = $delivery;
        $row['subtitle']       = $summary;
        $row['summary']        = $summary;
        $row['cta_text']       = $cta;
        $row['cta_label']      = $cta;

        return $row;
    }

    /**
     * Map controller/form input array to actual DB columns.
     */
    private static function mapInputToDb(array $data): array
    {
        $mapped = [];

        if (isset($data['title'])) {
            $mapped['title'] = (string)$data['title'];
        }
        if (isset($data['slug'])) {
            $slug = trim((string)$data['slug']);
            if ($slug === '' && !empty($mapped['title'])) {
                $slug = strtolower(trim((string)preg_replace('/[^a-zA-Z0-9]+/', '-', $mapped['title']), '-'));
            }
            $mapped['slug'] = $slug ?: 'package-' . time();
        }
        if (isset($data['track']) || isset($data['category_track'])) {
            $mapped['category_track'] = (string)($data['track'] ?? $data['category_track'] ?? 'dev');
        }
        if (isset($data['price_label']) || isset($data['price'])) {
            $mapped['price'] = (string)($data['price_label'] ?? $data['price'] ?? '$299');
        }
        if (isset($data['billing_cycle']) || isset($data['billing_unit'])) {
            $mapped['billing_unit'] = ltrim(trim((string)($data['billing_cycle'] ?? $data['billing_unit'] ?? 'project')), '/');
        }
        if (isset($data['delivery_time'])) {
            $mapped['delivery_time'] = (string)$data['delivery_time'];
        }
        if (isset($data['tier_badge']) || isset($data['badge_text'])) {
            $mapped['badge_text'] = (string)($data['tier_badge'] ?? $data['badge_text'] ?? '');
        }
        if (isset($data['subtitle']) || isset($data['summary'])) {
            $mapped['summary'] = (string)($data['subtitle'] ?? $data['summary'] ?? '');
        }
        if (isset($data['features'])) {
            $mapped['features'] = (string)$data['features'];
        }
        if (isset($data['cta_text']) || isset($data['cta_label'])) {
            $mapped['cta_label'] = (string)($data['cta_text'] ?? $data['cta_label'] ?? 'Select Package');
        }
        if (isset($data['is_popular'])) {
            $mapped['is_popular'] = (int)$data['is_popular'];
        }
        if (isset($data['is_active'])) {
            $mapped['is_active'] = (int)$data['is_active'];
        }
        if (isset($data['sort_order'])) {
            $mapped['sort_order'] = (int)$data['sort_order'];
        }
        if (isset($data['created_at'])) {
            $mapped['created_at'] = $data['created_at'];
        }
        if (isset($data['updated_at'])) {
            $mapped['updated_at'] = $data['updated_at'];
        }

        return $mapped;
    }

    public static function allActive(): array
    {
        self::ensureTableExists();
        $rows = db()->fetchAll("SELECT * FROM `service_packages` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` ASC");
        return array_map([self::class, 'normalizeRow'], $rows);
    }

    public static function allActiveGroupedByTrack(): array
    {
        $all = self::allActive();
        $grouped = ['dev' => [], 'design' => [], 'growth' => []];
        foreach ($all as $pkg) {
            $t = $pkg['track'] ?? 'dev';
            if (!isset($grouped[$t])) {
                $grouped[$t] = [];
            }
            $grouped[$t][] = $pkg;
        }
        return $grouped;
    }

    public static function all(): array
    {
        self::ensureTableExists();
        $rows = db()->fetchAll("SELECT * FROM `service_packages` ORDER BY `category_track` ASC, `sort_order` ASC, `id` ASC");
        return array_map([self::class, 'normalizeRow'], $rows);
    }

    public static function findById(int $id): ?array
    {
        self::ensureTableExists();
        $row = db()->fetchOne("SELECT * FROM `service_packages` WHERE `id` = :id LIMIT 1", ['id' => $id]);
        return $row ? self::normalizeRow($row) : null;
    }

    public static function create(array $data): int
    {
        self::ensureTableExists();
        $mapped = self::mapInputToDb($data);
        $mapped['created_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('service_packages', $mapped);
    }

    public static function update(int $id, array $data): int
    {
        self::ensureTableExists();
        $mapped = self::mapInputToDb($data);
        $mapped['updated_at'] = date('Y-m-d H:i:s');
        return db()->update('service_packages', $mapped, '`id` = :id', ['id' => $id]);
    }

    public static function toggleActive(int $id): bool
    {
        $pkg = self::findById($id);
        if (!$pkg) {
            return false;
        }
        $new = ((int)$pkg['is_active'] === 1) ? 0 : 1;
        self::update($id, ['is_active' => $new]);
        return (bool)$new;
    }

    public static function togglePopular(int $id): bool
    {
        $pkg = self::findById($id);
        if (!$pkg) {
            return false;
        }
        $new = ((int)$pkg['is_popular'] === 1) ? 0 : 1;
        self::update($id, ['is_popular' => $new]);
        return (bool)$new;
    }

    public static function delete(int $id): int
    {
        self::ensureTableExists();
        return db()->delete('service_packages', '`id` = :id', ['id' => $id]);
    }

    public static function parseFeatures(string $raw): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $raw) ?: [];
        return array_values(array_filter(array_map('trim', $lines), static fn($l) => $l !== ''));
    }
}
