-- =======================================================
-- Creatsify Studio - Seed Data
-- =======================================================

-- 1. Roles
INSERT INTO `roles` (`name`, `slug`, `description`, `permissions`) VALUES
('Super Admin', 'super_admin', 'Full control over all platform modules, settings, users, and code injectors.', '["*"]'),
('Admin', 'admin', 'Can manage projects, services, blog, chat desk, inboxes, and view analytics.', '["manage_content","manage_inquiries","manage_chat","manage_comms","view_analytics"]'),
('Editor', 'editor', 'Can author blog posts and edit portfolio projects.', '["manage_content","view_inquiries"]')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 2. Default Super Admin User (Muhammad Ayan Shamim)
-- Password is: admin123
INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `status`) VALUES
('Muhammad Ayan Shamim', 'ayan@creatsify.tech', '$2y$10$Bh26gkLWZ/l6un4H54UzRudQ4a84..iXTqPSN2IKuSpPcD4WnAIJC', 'super_admin', '+923000000000', 'active')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 3. Platform Settings
INSERT INTO `settings` (`key`, `value`, `group_name`) VALUES
('site_title', 'Creatsify — Full-stack web, mobile and AI development studio', 'general'),
('site_tagline', 'We build software for courier companies, online stores, learning platforms, startups & enterprises.', 'general'),
('site_email', 'ayan@creatsify.tech', 'general'),
('site_phone', '+92 300 0000000', 'general'),
('site_location', 'Karachi, Pakistan', 'general'),
('site_logo', '/assets/brand/creatsify-wordmark.png', 'appearance'),
('site_logo_light', '/assets/brand/creatsify-wordmark-light.png', 'appearance'),
('site_icon', '/assets/brand/favicon.png', 'appearance'),
('primary_accent', '#8b7bff', 'appearance'),
('dark_mode_default', '1', 'appearance'),
('founder_name', 'Muhammad Ayan Shamim', 'founder'),
('founder_title', 'Founder & Lead Architect', 'founder'),
('founder_bio', 'Ayan is the founder of Creatsify. He studies AI in the ACCP program at Aptech, Karachi, and builds and sells real software: web platforms, mobile apps, desktop tools and WordPress stores.', 'founder'),
('founder_github', 'https://github.com/ayan2424', 'founder'),
('founder_linkedin', 'https://linkedin.com/in/muhammadayanshamim', 'founder'),
('meta_keywords', 'Creatsify, Software Studio, Karachi, Pakistan, Courier SaaS, Cheetah, PHP MVC, Laravel, Flutter, Web Development', 'seo'),
('meta_description', 'Creatsify is a high-performance software studio in Karachi building full-stack web, mobile, and AI solutions.', 'seo'),
('smtp_host', 'smtp.mailtrap.io', 'mail'),
('smtp_port', '2525', 'mail'),
('smtp_user', '', 'mail'),
('smtp_pass', '', 'mail'),
('smtp_encryption', 'tls', 'mail'),
('smtp_from_email', 'ayan@creatsify.tech', 'mail'),
('smtp_from_name', 'Creatsify Studio', 'mail'),
('wa_provider', 'meta', 'whatsapp'),
('wa_phone_number_id', '', 'whatsapp'),
('wa_access_token', '', 'whatsapp'),
('wa_admin_notification_phone', '923000000000', 'whatsapp'),
('wa_auto_reply_enabled', '1', 'whatsapp'),
('wa_verify_token', 'creatsify_wa_webhook_token_secure', 'whatsapp'),
('sms_gateway', 'twilio', 'sms'),
('sms_twilio_sid', '', 'sms'),
('sms_twilio_token', '', 'sms'),
('sms_twilio_from', '', 'sms'),
('sms_admin_phone', '923000000000', 'sms'),
('custom_header_code', '', 'custom_code'),
('custom_footer_code', '', 'custom_code'),
('custom_css', '', 'custom_code')
ON DUPLICATE KEY UPDATE `value`=VALUES(`value`);

-- 4. Dynamic Sections (Homepage Content Engine)
INSERT INTO `sections` (`section_key`, `title`, `subtitle`, `content`, `metadata`, `is_active`, `sort_order`) VALUES
('hero', 'We build software', 'for courier companies, online stores, local brands, growing businesses', 'Creatsify is a software studio in Karachi. We sell ready-made courier and online store software, and build custom websites, admin panels and WordPress stores.', '{"chips": ["7 role portals in Cheetah", "Direct software licensing", "3 products for sale"], "rotating_words": ["courier companies", "online stores", "local brands", "growing businesses"]}', 1, 1),
('marquee_tech', 'Tech Stack', 'Built with industry-leading modern tooling', 'PHP, Laravel, MySQL, Python, React, Next.js, Flutter, Electron, WordPress, WooCommerce, Docker, Redis', '{}', 1, 2),
('stats', 'By the Numbers', 'Proven track record of engineering delivery', '{"stat_1": {"value": 10, "suffix": "+", "label": "Products shipped"}, "stat_2": {"value": 3, "suffix": "", "label": "Cheetah editions"}, "stat_3": {"value": 7, "suffix": "", "label": "Role portals in Cheetah"}, "stat_4": {"value": 78, "suffix": "", "label": "Database tables in Cheetah"}}', '{}', 1, 3),
('cheetah_showcase', 'Cheetah: courier and warehouse software', 'Our flagship product', 'Cheetah comes in two editions, each with its own code. Enterprise covers couriers and warehouses with 7 role portals, and Lite is for courier-only businesses.', '{"tracker": {"code": "PK-000007", "from": "Karachi", "to": "Lahore", "steps": ["Shipment created", "Linehaul dispatched", "Out for delivery", "Delivered ePOD signed"]}}', 1, 4),
('about_founder', 'Built by Muhammad Ayan Shamim', 'Founder', 'Ayan is the founder of Creatsify. He studies AI in the ACCP program at Aptech, Karachi, and builds and sells real software: courier and store systems, custom web apps and WordPress stores.', '{"badge": "CREATSIFY • KARACHI • BUILD • SHIP • GROW •"}', 1, 5),
('marquee_cta', 'Let\'s build', 'Let\'s launch • Let\'s grow', 'Ready to scale your next digital breakthrough? We partner with founders and enterprise teams globally.', '{}', 1, 6),
('contact', 'Tell us what you want to build', 'Get in touch directly with our leadership team', 'Have a project in mind, need custom software, or want to deploy one of our pre-built platforms? Send a message and we will respond within 24 hours.', '{}', 1, 7)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- 5. Portfolio Projects (10 Items)
INSERT INTO `projects` (`title`, `slug`, `category`, `client`, `featured`, `summary`, `description`, `image_url`, `live_url`, `tech_stack`, `status`, `sort_order`) VALUES
('Cheetah Enterprise', 'cheetah-enterprise', 'saas', NULL, 1, 'Courier, warehouse and multi-branch logistics platform with 7 role portals, store sync and the Flutter driver and picker app.', 'The full Cheetah system for courier and warehouse businesses: branch management, rider assignment, merchant portals, cash-on-delivery reconciliation, live tracking, proof of delivery and a Flutter app for drivers and pickers. Comes with full source code and white-label rights.', NULL, 'https://cheetah.ayan24.me', 'PHP, MySQL, JavaScript, Flutter', 'For sale', 1),
('Cheetah Lite', 'cheetah-lite', 'saas', NULL, 1, 'A lighter, courier-only edition. No warehouse module and no picker role.', 'The smaller Cheetah edition for businesses that only run courier deliveries. Shipment booking, rider assignment, tracking and cash-on-delivery, without the warehouse module. Comes with full source code and white-label rights.', NULL, 'https://cheetah-lite.ayan24.me', 'PHP, MySQL, JavaScript', 'For sale', 2),
('Cheezify', 'cheezify', 'stores', NULL, 1, 'Online cheese store with its own admin panel, built for a client in Karachi, with cold-chain delivery zones and SEO-ready pages.', 'A complete online store and admin panel written in pure PHP with a hand-built MVC structure and no framework. Product catalog, cart, delivery zones and order management. Available as ready-made software.', NULL, 'https://cheezify.ayan24.me', 'PHP, MySQL, JavaScript', 'For sale', 3),
('Sawi Store', 'sawi-store', 'stores', NULL, 0, 'WordPress and WooCommerce online store built with Elementor.', 'An e-commerce website built on WordPress with Elementor and WooCommerce.', NULL, NULL, 'WordPress, Elementor, WooCommerce', 'Delivered', 4)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `category`=VALUES(`category`), `featured`=VALUES(`featured`), `summary`=VALUES(`summary`), `description`=VALUES(`description`), `live_url`=VALUES(`live_url`), `tech_stack`=VALUES(`tech_stack`), `status`=VALUES(`status`), `sort_order`=VALUES(`sort_order`);

-- 6. Services
INSERT INTO `services` (`title`, `slug`, `icon`, `short_description`, `full_description`, `deliverables`, `sort_order`, `is_active`) VALUES
('Web Platforms & SaaS', 'web-platforms-saas', 'server', 'Bespoke web applications, subscription portals, and scalable cloud APIs.', 'We architect and build end-to-end web applications with custom PHP MVC or Laravel backends, reactive frontends, robust database schemas, and rock-solid payment processing.', 'High-performance API, Multi-tenant database, Role-based auth, Payment gateways, Unit tests', 1, 1),
('Mobile & Desktop Apps', 'mobile-desktop-apps', 'smartphone', 'Cross-platform native mobile apps and desktop software.', 'Using Flutter and Electron, we build beautiful, high-performance apps that feel completely native on Android, iOS, Windows, macOS, and Linux from a unified codebase.', 'Flutter Android/iOS apps, Desktop installers, Offline caching, Push notifications', 2, 1),
('E-Commerce & Marketplaces', 'ecommerce-marketplaces', 'shopping-bag', 'High-converting online stores, multi-vendor platforms, and ordering systems.', 'From custom WooCommerce stores to multi-vendor delivery marketplaces, we design checkout experiences engineered to maximize conversion rates and average order value.', 'Custom themes, Fast checkout funnels, Inventory syncing, Multi-gateway payments', 3, 1),
('AI & Process Automation', 'ai-process-automation', 'cpu', 'Intelligent bots, automated workflows, and CRM communication systems.', 'We integrate modern LLMs and intelligent rule engines directly into operational workflows, customer chat widgets, WhatsApp Cloud API dispatch, and SMS gateways.', 'WhatsApp Cloud API integration, Live chat widgets, Smart context engines, Custom cron automations', 4, 1)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- 7. Blog Posts
INSERT INTO `posts` (`title`, `slug`, `excerpt`, `content`, `cover_image`, `category`, `author_id`, `is_published`, `views_count`) VALUES
('Why We Built Cheetah Courier Software from Scratch', 'why-we-built-cheetah-courier-software', 'An inside look into the architecture of our flagship logistics platform and why off-the-shelf software failed logistics operators.', '<p>Logistics operators across emerging markets face unique constraints: high cash-on-delivery volumes, unreliable connectivity, and complex linehaul handoffs. In this article, we break down why Cheetah was built with 7 specialized role portals and how its normalized database schema handles over 100,000 daily parcel transitions effortlessly.</p>', '/assets/brand/creatsify-icon.png', 'Engineering', 1, 1, 342),
('Designing High-Converting SaaS & Studio Portfolios', 'designing-high-converting-saas-portfolios', 'How subtle micro-interactions, dark luxury aesthetics, and instantaneous response times turn casual visitors into enterprise leads.', '<p>A modern digital portfolio is not a static brochure—it is a live product showcase. In this breakdown, we explore dark mode ergonomics, performance optimization, and integrating first-party live chat for instant client qualification.</p>', '/assets/brand/creatsify-icon.png', 'Design & UI', 1, 1, 218)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- 8. Agency Team Members & Staff
INSERT INTO `team_members` (`id`, `name`, `slug`, `role_title`, `bio`, `avatar`, `skills`, `github_url`, `linkedin_url`, `portfolio_url`, `email`, `sort_order`, `is_active`) VALUES
(1, 'Muhammad Ayan Shamim', 'ayan-shamim', 'Founder & Lead Full-Stack Architect', 'Founder of Creatsify. ACCP AI student at Aptech Karachi. Specializes in building mission-critical courier platforms, multi-tenant SaaS backends, and full-stack software architectures.', '/assets/brand/creatsify-icon.png', 'PHP, Laravel, Python, React, Next.js, MySQL, Redis, Docker', 'https://github.com/ayan2424', 'https://linkedin.com/in/muhammadayanshamim', 'https://creatsify.tech', 'ayan@creatsify.tech', 1, 1),
(2, 'Bilal Ahmed', 'bilal-ahmed', 'Senior UI/UX & Design Systems Lead', 'Creative design architect with 4+ years of experience crafting high-conversion design systems, SaaS dashboards, and immersive micro-interactions.', '/assets/brand/creatsify-icon.png', 'Figma, UI/UX, Design Systems, Tailwind CSS, Interaction Design, Framer', 'https://github.com', 'https://linkedin.com', 'https://dribbble.com', 'bilal@creatsify.tech', 2, 1),
(3, 'Hamza Malik', 'hamza-malik', 'Mobile Apps & Flutter Specialist', 'Cross-platform mobile developer dedicated to building fluid, 60fps native iOS and Android apps with robust offline sync and clean state architectures.', '/assets/brand/creatsify-icon.png', 'Flutter, Dart, Android, iOS, REST APIs, Firebase, SQLite', 'https://github.com', 'https://linkedin.com', 'https://github.com', 'hamza@creatsify.tech', 3, 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 9. Individual Team Member Portfolio Projects
INSERT INTO `member_portfolios` (`id`, `member_id`, `title`, `slug`, `category`, `client`, `role_in_project`, `summary`, `description`, `image_url`, `project_url`, `tech_stack`, `completion_date`, `is_featured`, `sort_order`) VALUES
(1, 1, 'Cheetah Enterprise Logistics Core', 'cheetah-logistics-core', 'Enterprise Architecture', 'National Freight Network', 'Lead Architect & Backend Engineer', 'Designed the multi-origin dispatch engine, 7 role portals, and automated manifest generator.', 'Led complete system design, normalized 78 database tables, implemented sub-millisecond barcode tracking lookups, and built zero-dependency native chat.', '/assets/images/cheetah-showcase.jpg', 'https://creatsify.tech/#cheetah', 'PHP 8.2, MySQL, Laravel, WebSockets', '2026', 1, 1),
(2, 1, 'Multi-Tenant Cloud Dispatcher', 'multi-tenant-cloud-dispatcher', 'Cloud SaaS', 'Global Delivery Startups', 'Full-Stack Developer', 'Automated tenant subdomain provisioning with isolated database schemas and Stripe billing.', 'Built the automated provisioning pipeline allowing clients to launch branded courier instances in under 60 seconds.', '/assets/images/hero-artwork.jpg', 'https://creatsify.tech', 'PHP, MySQL, Redis, Docker', '2026', 1, 2),
(3, 2, 'Cheetah Obsidian UI/UX Design System', 'cheetah-design-system', 'Product Design', 'Creatsify Studio', 'Lead UI/UX Designer', 'Crafted a dark-mode obsidian design system with high-contrast accessibility tokens and 3D glass widgets.', 'Constructed 250+ atomic Figma components, telemetry chart layouts, and interactive mobile driver interfaces.', '/assets/images/cheetah-showcase.jpg', 'https://creatsify.tech', 'Figma, Design Tokens, CSS Variables', '2026', 1, 1),
(4, 2, 'RestroMaster POS & KDS Interface', 'restromaster-kds-interface', 'Interface Design', 'Hospitality Client', 'UI/UX Designer', 'Designed touch-optimized kitchen display system and contactless QR tabletop menu ordering.', 'Reduced order-to-kitchen latency by designing high-visibility order ticket states and ergonomic touch targets.', '/assets/images/restromaster-preview.jpg', 'https://creatsify.tech', 'Figma, Micro-animations, SVG Systems', '2026', 1, 2),
(5, 3, 'Cheetah Rider & Driver Companion', 'cheetah-rider-companion', 'Mobile Development', 'Courier Fleet', 'Lead Mobile Developer', 'Native Flutter mobile app for delivery couriers featuring offline signature ePOD and background GPS tracking.', 'Implemented background geofencing, thermal Bluetooth barcode scanner integrations, and instantaneous route recalculations.', '/assets/images/cheetah-showcase.jpg', 'https://creatsify.tech', 'Flutter, Dart, SQLite, Google Maps SDK', '2026', 1, 1),
(6, 3, 'Flavor Fleet Multi-Vendor Food App', 'flavor-fleet-mobile', 'Mobile App', 'Food Startup', 'Flutter Engineer', 'Customer-facing food ordering app with live delivery route animations and payment gateways.', 'Created a seamless multi-restaurant browsing experience with category filters, cart persistence, and order tracking.', '/assets/images/restromaster-preview.jpg', 'https://creatsify.tech', 'Flutter, Dart, Firebase, Stripe SDK', '2026', 1, 2)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);
