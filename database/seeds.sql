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
('site_location', 'Worldwide, remote', 'general'),
('site_logo', '/assets/brand/creatsify-wordmark.png', 'appearance'),
('site_logo_light', '/assets/brand/creatsify-wordmark-light.png', 'appearance'),
('site_icon', '/assets/brand/favicon.png', 'appearance'),
('primary_accent', '#8b7bff', 'appearance'),
('dark_mode_default', '1', 'appearance'),
('founder_name', 'Muhammad Ayan Shamim', 'founder'),
('founder_title', 'Founder & Lead Architect', 'founder'),
('founder_bio', 'Ayan is the founder of Creatsify. He studies AI in the ACCP program at Aptech, and builds and sells real software: web platforms, mobile apps, desktop tools and WordPress stores.', 'founder'),
('founder_github', 'https://github.com/ayan2424', 'founder'),
('founder_linkedin', 'https://linkedin.com/in/muhammadayanshamim', 'founder'),
('meta_keywords', 'Creatsify, Software Studio, Courier software, Cheetah, Cheezify, PHP, Admin panels, Web Development', 'seo'),
('meta_description', 'Creatsify is a software studio building ready-made courier and store software, plus custom websites, admin panels and apps for clients worldwide.', 'seo'),
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
('hero', 'We build software', 'for courier companies, online stores, local brands, growing businesses', 'Creatsify is a software studio. We sell ready-made courier and online store software, and build custom websites, admin panels and WordPress stores.', '{"chips": ["7 role portals in Cheetah", "Direct software licensing", "3 products for sale"], "rotating_words": ["couriers", "online stores", "local brands", "startups"]}', 1, 1),
('marquee_tech', 'Tech Stack', 'Built with industry-leading modern tooling', 'PHP, Laravel, MySQL, Python, React, Next.js, Flutter, Electron, WordPress, WooCommerce, Docker, Redis', '{}', 1, 2),
('stats', 'By the Numbers', 'Proven track record of engineering delivery', '{"stat_1": {"value": 10, "suffix": "+", "label": "Products shipped"}, "stat_2": {"value": 3, "suffix": "", "label": "Cheetah editions"}, "stat_3": {"value": 7, "suffix": "", "label": "Role portals in Cheetah"}, "stat_4": {"value": 78, "suffix": "", "label": "Database tables in Cheetah"}}', '{}', 1, 3),
('cheetah_showcase', 'Cheetah: courier and warehouse software', 'Our flagship product', 'Cheetah comes in two editions, each with its own code. Enterprise covers couriers and warehouses with 7 role portals, and Lite is for courier-only businesses.', '{"tracker": {"code": "CH-000007", "from": "Origin hub", "to": "Customer", "steps": ["Shipment created", "Linehaul dispatched", "Out for delivery", "Delivered ePOD signed"]}}', 1, 4),
('about_founder', 'Built by Muhammad Ayan Shamim', 'Founder', 'Ayan is the founder of Creatsify. He studies AI in the ACCP program at Aptech, and builds and sells real software: courier and store systems, custom web apps and WordPress stores.', '{"badge": "CREATSIFY • STUDIO • BUILD • SHIP • GROW •"}', 1, 5),
('marquee_cta', 'Let\'s build', 'Let\'s launch • Let\'s grow', 'Ready to scale your next digital breakthrough? We partner with founders and enterprise teams globally.', '{}', 1, 6),
('contact', 'Tell us what you want to build', 'Get in touch directly with our leadership team', 'Have a project in mind, need custom software, or want to deploy one of our pre-built platforms? Send a message and we will respond within 24 hours.', '{}', 1, 7)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- 5. Portfolio Projects (10 Items)
INSERT INTO `projects` (`title`, `slug`, `category`, `client`, `featured`, `summary`, `description`, `image_url`, `live_url`, `tech_stack`, `status`, `sort_order`) VALUES
('Cheetah Enterprise', 'cheetah-enterprise', 'saas', NULL, 1, 'Courier, warehouse and multi-branch logistics platform with 7 role portals, store sync and the Flutter driver and picker app.', 'The full Cheetah system for courier and warehouse businesses: branch management, rider assignment, merchant portals, cash-on-delivery reconciliation, live tracking, proof of delivery and a Flutter app for drivers and pickers. Comes with full source code and white-label rights.', NULL, 'https://cheetah.ayan24.me', 'PHP, MySQL, JavaScript, Flutter', 'For sale', 1),
('Cheetah Lite', 'cheetah-lite', 'saas', NULL, 1, 'A lighter, courier-only edition. No warehouse module and no picker role.', 'The smaller Cheetah edition for businesses that only run courier deliveries. Shipment booking, rider assignment, tracking and cash-on-delivery, without the warehouse module. Comes with full source code and white-label rights.', NULL, 'https://cheetah-lite.ayan24.me', 'PHP, MySQL, JavaScript', 'For sale', 2),
('Cheezify', 'cheezify', 'stores', NULL, 1, 'Online cheese store with its own admin panel, built for a client, with cold-chain delivery zones and SEO-ready pages.', 'A complete online store and admin panel written in pure PHP with a hand-built MVC structure and no framework. Product catalog, cart, delivery zones and order management. Available as ready-made software.', NULL, 'https://cheezify.ayan24.me', 'PHP, MySQL, JavaScript', 'For sale', 3),
('Sawi Store', 'sawi-store', 'stores', NULL, 0, 'WordPress and WooCommerce online store built with Elementor.', 'An e-commerce website built on WordPress with Elementor and WooCommerce.', NULL, NULL, 'WordPress, Elementor, WooCommerce', 'Delivered', 4)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `category`=VALUES(`category`), `featured`=VALUES(`featured`), `summary`=VALUES(`summary`), `description`=VALUES(`description`), `live_url`=VALUES(`live_url`), `tech_stack`=VALUES(`tech_stack`), `status`=VALUES(`status`), `sort_order`=VALUES(`sort_order`);

-- 6. Services
INSERT INTO `services` (`title`, `slug`, `icon`, `short_description`, `full_description`, `deliverables`, `sort_order`, `is_active`) VALUES
('Websites and admin panels', 'websites-admin-panels', 'server', 'Business websites, dashboards and management systems in PHP and MySQL.', 'Business websites, dashboards and management systems built in PHP and MySQL around how your team works. Fast, tidy and easy to host.', 'Responsive website, Admin panel, Contact and enquiry forms, SEO basics, Deployment help', 1, 1),
('WordPress and online stores', 'wordpress-online-stores', 'shopping-bag', 'Elementor and WooCommerce stores with product pages, checkout and SEO basics.', 'Online stores and business sites built on WordPress with Elementor and WooCommerce, ready for you to manage yourself.', 'Product pages, Cart and checkout, SEO basics, Handover and training', 2, 1),
('Courier and warehouse systems', 'courier-warehouse-systems', 'truck', 'Start from Cheetah Enterprise or Lite and adapt it to your business.', 'Start from Cheetah Enterprise or Cheetah Lite and we adapt it to your branches, riders and merchants.', 'Branch and rider management, Shipment tracking, Cash on delivery, Proof of delivery, Setup help', 3, 1),
('WhatsApp and SMS alerts', 'whatsapp-sms-alerts', 'smartphone', 'Order and shipment updates sent through the WhatsApp Cloud API and SMS.', 'Order and shipment updates sent to your customers through the WhatsApp Cloud API and SMS.', 'WhatsApp Cloud API setup, SMS alerts, Webhooks, Message templates', 4, 1)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `icon`=VALUES(`icon`), `short_description`=VALUES(`short_description`), `full_description`=VALUES(`full_description`), `deliverables`=VALUES(`deliverables`), `sort_order`=VALUES(`sort_order`), `is_active`=1;

-- 7. Blog Posts
INSERT INTO `posts` (`title`, `slug`, `excerpt`, `content`, `cover_image`, `category`, `author_id`, `is_published`, `views_count`) VALUES
('Why We Built Cheetah Courier Software from Scratch', 'why-we-built-cheetah-courier-software', 'An inside look into the architecture of our flagship logistics platform and why off-the-shelf software failed logistics operators.', '<p>Logistics operators across emerging markets face unique constraints: high cash-on-delivery volumes, unreliable connectivity, and complex linehaul handoffs. In this article, we break down why Cheetah was built with 7 specialized role portals and how its normalized database schema handles over 100,000 daily parcel transitions effortlessly.</p>', '/assets/brand/creatsify-icon.png', 'Engineering', 1, 1, 0),
('Designing High-Converting SaaS & Studio Portfolios', 'designing-high-converting-saas-portfolios', 'How subtle micro-interactions, dark luxury aesthetics, and instantaneous response times turn casual visitors into enterprise leads.', '<p>A modern digital portfolio is not a static brochure—it is a live product showcase. In this breakdown, we explore dark mode ergonomics, performance optimization, and integrating first-party live chat for instant client qualification.</p>', '/assets/brand/creatsify-icon.png', 'Design & UI', 1, 1, 0)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- 8. Agency Team Members & Staff
INSERT INTO `team_members` (`id`, `name`, `slug`, `role_title`, `bio`, `avatar`, `skills`, `github_url`, `linkedin_url`, `portfolio_url`, `email`, `sort_order`, `is_active`) VALUES
(1, 'Muhammad Ayan Shamim', 'ayan-shamim', 'Founder & Lead Full-Stack Architect', 'Founder of Creatsify. ACCP AI student at Aptech. Specializes in building mission-critical courier platforms, multi-tenant SaaS backends, and full-stack software architectures.', '/assets/brand/creatsify-icon.png', 'PHP, Laravel, Python, React, Next.js, MySQL, Redis, Docker', 'https://github.com/ayan2424', 'https://linkedin.com/in/muhammadayanshamim', 'https://creatsify.tech', 'ayan@creatsify.tech', 1, 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 9. Individual Team Member Portfolio Projects
INSERT INTO `member_portfolios` (`id`, `member_id`, `title`, `slug`, `category`, `client`, `role_in_project`, `summary`, `description`, `image_url`, `project_url`, `tech_stack`, `completion_date`, `is_featured`, `sort_order`) VALUES
(1, 1, 'Cheetah Enterprise', 'cheetah-enterprise', 'Courier software', NULL, 'Founder and lead developer', 'Courier, warehouse and multi-branch logistics platform with 7 role portals.', 'Designed and built end to end: branch management, rider assignment, merchant portals, live tracking and proof of delivery.', NULL, 'https://cheetah.ayan24.me', 'PHP, MySQL, JavaScript, Flutter', NULL, 1, 1),
(2, 1, 'Cheetah Lite', 'cheetah-lite', 'Courier software', NULL, 'Founder and lead developer', 'A lighter, courier-only edition of Cheetah.', 'The smaller Cheetah edition for businesses that only run courier deliveries.', NULL, 'https://cheetah-lite.ayan24.me', 'PHP, MySQL, JavaScript', NULL, 1, 2),
(3, 1, 'Cheezify', 'cheezify', 'Online store', NULL, 'Founder and lead developer', 'Online cheese store with its own admin panel.', 'A complete online store and admin panel in plain PHP with a hand-built MVC structure.', NULL, 'https://cheezify.ayan24.me', 'PHP, MySQL, JavaScript', NULL, 1, 3),
(4, 1, 'Sawi Store', 'sawi-store', 'Online store', NULL, 'Designer and developer', 'WordPress and WooCommerce online store built with Elementor.', 'An e-commerce website built on WordPress with Elementor and WooCommerce.', NULL, NULL, 'WordPress, Elementor, WooCommerce', NULL, 0, 4)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `category`=VALUES(`category`), `role_in_project`=VALUES(`role_in_project`), `summary`=VALUES(`summary`), `description`=VALUES(`description`), `project_url`=VALUES(`project_url`), `tech_stack`=VALUES(`tech_stack`), `is_featured`=VALUES(`is_featured`);
