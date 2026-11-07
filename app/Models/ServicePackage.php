<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Creatsify Digital Agency - Service Packages & Pricing Tiers Model
 * Self-healing schema and initial 3-track agency packages seeder.
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
            'dev'    => 'Web, Mobile App & SaaS Dev',
            'design' => 'Branding, Logo & Website Redesign',
            'growth' => 'SEO & Social Media Ads',
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
                'billing_unit'   => '/project',
                'badge_text'     => '',
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
                'billing_unit'   => '/project',
                'badge_text'     => 'MOST POPULAR',
                'is_popular'     => 1,
                'summary'        => 'Full-stack custom web application, client portal, or high-speed e-commerce store with admin dashboard.',
                'features'       => "Custom Full-Stack Build (PHP 8.4, Node.js, or Next.js)\nNormalized MySQL Database & Role Admin Panel\nE-Commerce Cart, Payment Gateways & Invoicing\nOfficial WhatsApp Cloud API / Email Notifications\nComplete Custom UI/UX + Technical SEO Architecture\nLinux VPS / aaPanel Deployment + 30 Days Support",
                'cta_label'      => 'Select Growth Web App',
                'sort_order'     => 2,
                'is_active'      => 1,
            ],
            [
                'title'          => 'SaaS & Flutter Mobile Suite',
                'slug'           => 'saas-flutter-mobile-suite',
                'category_track' => 'dev',
                'price'          => '$1,499',
                'billing_unit'   => '+',
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
                'billing_unit'   => '/pack',
                'badge_text'     => '',
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
                'billing_unit'   => '/pack',
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
                'billing_unit'   => '/project',
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
                'billing_unit'   => '/month',
                'badge_text'     => '',
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
                'billing_unit'   => '/month',
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
                'billing_unit'   => '/month',
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

    public static function allActive(): array
    {
        self::ensureTableExists();
        return db()->fetchAll("SELECT * FROM `service_packages` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` ASC");
    }

    public static function allActiveGroupedByTrack(): array
    {
        $all = self::allActive();
        $grouped = ['dev' => [], 'design' => [], 'growth' => []];
        foreach ($all as $pkg) {
            $t = $pkg['category_track'] ?? 'dev';
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
        return db()->fetchAll("SELECT * FROM `service_packages` ORDER BY `category_track` ASC, `sort_order` ASC, `id` ASC");
    }

    public static function findById(int $id): ?array
    {
        self::ensureTableExists();
        return db()->fetchOne("SELECT * FROM `service_packages` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function create(array $data): int
    {
        self::ensureTableExists();
        $data['created_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('service_packages', $data);
    }

    public static function update(int $id, array $data): int
    {
        self::ensureTableExists();
        $data['updated_at'] = date('Y-m-d H:i:s');
        return db()->update('service_packages', $data, '`id` = :id', ['id' => $id]);
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
