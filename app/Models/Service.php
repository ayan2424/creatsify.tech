<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Creatsify Digital Agency - Services Model
 * Includes self-healing seeder for the 8 core agency service pillars.
 */
class Service
{
    public static function ensureAgencyServicesSeeded(): void
    {
        static $checked = false;
        if ($checked) {
            return;
        }
        $checked = true;

        $agencyPillars = [
            [
                'title'             => 'Custom Web Development',
                'slug'              => 'custom-web-development',
                'icon'              => 'code',
                'short_description' => 'High-velocity web applications, custom admin dashboards, and API architectures built in PHP 8.4, Node.js, Next.js, Tailwind CSS, Bootstrap, jQuery, and MySQL.',
                'full_description'  => 'We engineer custom web applications, enterprise portals, and blazing-fast business websites using modern stacks—PHP 8.4 MVC, Node.js, Next.js, HTML5/CSS3, JavaScript ES6, Tailwind CSS, Bootstrap, jQuery, and MySQL. Every build is responsive, SEO-ready, and delivered with 100% clean source code.',
                'deliverables'      => 'Full-Stack Web Apps (PHP / Node.js / Next.js), Responsive Frontend (Tailwind / Bootstrap / JS), Custom Admin & CRM Dashboards, Normalized MySQL Database & REST APIs',
                'sort_order'        => 1,
                'is_active'         => 1,
            ],
            [
                'title'             => 'Mobile App Development (Flutter & Dart)',
                'slug'              => 'mobile-app-development',
                'icon'              => 'smartphone',
                'short_description' => 'Native-speed iOS and Android mobile applications engineered with Flutter and Dart, complete with real-time GPS, push alerts, and cloud API backends.',
                'full_description'  => 'Launch sleek, 60fps mobile applications for both Apple iOS and Google Android from a unified Flutter & Dart codebase. We build on-demand delivery apps, e-commerce apps, booking platforms, and enterprise companion apps with real-time backend synchronization.',
                'deliverables'      => 'iOS & Android Apps in Flutter & Dart, Real-Time Maps & Push Notifications, Secure REST API & Backend Integration, App Store & Google Play Publishing Support',
                'sort_order'        => 2,
                'is_active'         => 1,
            ],
            [
                'title'             => 'Website Redesign & UI/UX Modernization',
                'slug'              => 'website-redesign-modernization',
                'icon'              => 'layout',
                'short_description' => 'Transform outdated, slow, low-converting websites into modern, mobile-first digital experiences engineered to pass Core Web Vitals and double lead conversion.',
                'full_description'  => 'Is your current website slow, dated, or failing to convert visitors into paying customers? We conduct a full UX/CRO teardown and rebuild your site with modern typography, intuitive user flows, sub-second page speeds, and clear conversion funnels.',
                'deliverables'      => 'Complete UI/UX Audit & Wireframe Redesign, Mobile-First Responsive Overhaul, Core Web Vitals & Speed Optimization (95+ Score), Conversion Rate Optimization (CRO) Funnels',
                'sort_order'        => 3,
                'is_active'         => 1,
            ],
            [
                'title'             => 'SaaS Products & Custom Portals',
                'slug'              => 'saas-product-engineering',
                'icon'              => 'layers',
                'short_description' => 'End-to-end SaaS product architecture featuring multi-tenant databases, role-based access control, subscription billing, and automated customer onboarding.',
                'full_description'  => 'Turn your software vision into a commercial SaaS product. We architect multi-tenant web platforms with role-specific portals, Stripe/Paymob billing workflows, automated webhooks, live analytics, and cloud VPS deployment.',
                'deliverables'      => 'Multi-Tenant SaaS Architecture, Role-Based Portals & Permissions, Subscription & Payment Gateway Integration, Cloud VPS / aaPanel Production Deployment',
                'sort_order'        => 4,
                'is_active'         => 1,
            ],
            [
                'title'             => 'Search Engine Optimization (SEO)',
                'slug'              => 'search-engine-optimization-seo',
                'icon'              => 'search',
                'short_description' => 'Data-driven Technical SEO, On-Page semantic architecture, high-intent keyword targeting, and authority building that rank your business at the top of Google.',
                'full_description'  => 'Stop losing high-intent customers to competitors on Google. Our SEO specialists execute deep technical audits, schema markup integration, site-speed fixes, keyword-driven content silos, and local/global search optimization to drive compounding organic traffic.',
                'deliverables'      => 'Technical SEO & Core Web Vitals Audit, High-Intent Keyword & Competitor Strategy, On-Page Optimization & JSON-LD Schema, Monthly Ranking & Organic Pipeline Reports',
                'sort_order'        => 5,
                'is_active'         => 1,
            ],
            [
                'title'             => 'Social Media Marketing & Paid Ads',
                'slug'              => 'social-media-marketing-ads',
                'icon'              => 'trending-up',
                'short_description' => 'High-ROAS paid ad campaigns across Meta (Facebook & Instagram), Google Ads, and TikTok paired with strategic social media management and conversion tracking.',
                'full_description'  => 'Scale your customer acquisition predictably. We design scroll-stopping ad creatives, configure pixel/CAPI conversion tracking, and manage targeted Meta, Instagram, Google, and TikTok ad campaigns focused strictly on lowering CPA and maximizing ROAS.',
                'deliverables'      => 'Meta (FB/IG), Google & TikTok Ad Campaigns, Scroll-Stopping Ad Creatives & Copywriting, Pixel / Conversion API & Retargeting Funnels, Social Media Content Calendar & Growth',
                'sort_order'        => 6,
                'is_active'         => 1,
            ],
            [
                'title'             => 'Branding, Logo & Graphic Design',
                'slug'              => 'branding-logo-graphic-design',
                'icon'              => 'pen-tool',
                'short_description' => 'Memorable custom logo design, complete brand identity books, Figma UI/UX systems, social media creative kits, and high-impact marketing collateral.',
                'full_description'  => 'Great brands command instant trust and premium pricing. Our creative studio crafts distinctive vector logos, color & typography systems, brand guidelines, pitch decks, packaging, and social media visual kits that make your business unforgettable.',
                'deliverables'      => 'Custom Master Logo & Vector Source Files, Complete Brand Identity & Style Guide, Social Media & Ad Creative Templates, UI/UX Design Systems & Pitch Decks',
                'sort_order'        => 7,
                'is_active'         => 1,
            ],
            [
                'title'             => 'E-Commerce & Custom Store Systems',
                'slug'              => 'ecommerce-online-stores',
                'icon'              => 'shopping-bag',
                'short_description' => 'High-converting online stores built on custom PHP/Next.js engines, WooCommerce, or Shopify—complete with inventory, POS, and WhatsApp order alerts.',
                'full_description'  => 'Whether you need a custom zero-bloat store engine, a tailored WooCommerce storefront, or a multi-branch restaurant/retail POS system, we build fast catalog experiences with frictionless checkout and automated WhatsApp buyer notifications.',
                'deliverables'      => 'Custom E-Commerce or WooCommerce/Shopify Build, Fast Mobile Cart & Multi-Gateway Checkout, Inventory, Order & POS Management, Automated WhatsApp Cloud API Order Alerts',
                'sort_order'        => 8,
                'is_active'         => 1,
            ],
        ];

        try {
            foreach ($agencyPillars as $pillar) {
                $existing = db()->fetchOne("SELECT `id` FROM `services` WHERE `slug` = :slug LIMIT 1", ['slug' => $pillar['slug']]);
                if ($existing) {
                    db()->update('services', $pillar, '`id` = :id', ['id' => $existing['id']]);
                } else {
                    $pillar['created_at'] = date('Y-m-d H:i:s');
                    db()->insert('services', $pillar);
                }
            }
            // Deactivate legacy narrow courier-only duplicate service entries if any
            $validSlugs = array_map(static fn($p) => "'" . $p['slug'] . "'", $agencyPillars);
            $inClause = implode(',', $validSlugs);
            db()->query("UPDATE `services` SET `is_active` = 0 WHERE `slug` NOT IN ({$inClause})");
        } catch (\Throwable $e) {
        }
    }

    public static function allActive(): array
    {
        self::ensureAgencyServicesSeeded();
        return db()->fetchAll("SELECT * FROM `services` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
    }

    public static function all(): array
    {
        self::ensureAgencyServicesSeeded();
        return db()->fetchAll("SELECT * FROM `services` ORDER BY `is_active` DESC, `sort_order` ASC");
    }

    public static function findById(int $id): ?array
    {
        self::ensureAgencyServicesSeeded();
        return db()->fetchOne("SELECT * FROM `services` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function toggleActive(int $id): bool
    {
        $service = self::findById($id);
        if (!$service) {
            return false;
        }
        $new = $service['is_active'] ? 0 : 1;
        self::update($id, ['is_active' => $new]);
        return (bool)$new;
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('services', $data);
    }

    public static function update(int $id, array $data): int
    {
        return db()->update('services', $data, '`id` = :id', ['id' => $id]);
    }

    public static function delete(int $id): int
    {
        return db()->delete('services', '`id` = :id', ['id' => $id]);
    }
}
