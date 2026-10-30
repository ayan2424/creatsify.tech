-- Run once on an existing Creatsify database (phpMyAdmin > SQL).
-- Keeps only Cheetah Enterprise, Cheetah Lite, Cheezify and Sawi Store in the projects list
-- and updates the homepage hero, Cheetah and founder text.

INSERT INTO `sections` (`section_key`, `title`, `subtitle`, `content`, `metadata`, `is_active`, `sort_order`) VALUES
('hero', 'We build software', 'for courier companies, online stores, local brands, growing businesses', 'Creatsify is a software studio. We sell ready-made courier and online store software, and build custom websites, admin panels and WordPress stores.', '{"chips": ["7 role portals in Cheetah", "Direct software licensing", "3 products for sale"], "rotating_words": ["couriers", "online stores", "local brands", "startups"]}', 1, 1),
('cheetah_showcase', 'Cheetah: courier and warehouse software', 'Our flagship product', 'Cheetah comes in two editions, each with its own code. Enterprise covers couriers and warehouses with 7 role portals, and Lite is for courier-only businesses.', '{"tracker": {"code": "CH-000007", "from": "Origin hub", "to": "Customer", "steps": ["Shipment created", "Linehaul dispatched", "Out for delivery", "Delivered ePOD signed"]}}', 1, 4),
('about_founder', 'Built by Muhammad Ayan Shamim', 'Founder', 'Ayan is the founder of Creatsify. He studies AI in the ACCP program at Aptech, and builds and sells real software: courier and store systems, custom web apps and WordPress stores.', '{"badge": "CREATSIFY • STUDIO • BUILD • SHIP • GROW •"}', 1, 5)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `subtitle`=VALUES(`subtitle`), `content`=VALUES(`content`), `metadata`=VALUES(`metadata`);

DELETE FROM `projects` WHERE `slug` NOT IN ('cheetah-enterprise','cheetah-lite','cheezify','sawi-store');

INSERT INTO `projects` (`title`, `slug`, `category`, `client`, `featured`, `summary`, `description`, `image_url`, `live_url`, `tech_stack`, `status`, `sort_order`) VALUES
('Cheetah Enterprise', 'cheetah-enterprise', 'saas', NULL, 1, 'Courier, warehouse and multi-branch logistics platform with 7 role portals, store sync and the Flutter driver and picker app.', 'The full Cheetah system for courier and warehouse businesses: branch management, rider assignment, merchant portals, cash-on-delivery reconciliation, live tracking, proof of delivery and a Flutter app for drivers and pickers. Comes with full source code and white-label rights.', NULL, 'https://cheetah.ayan24.me', 'PHP, MySQL, JavaScript, Flutter', 'For sale', 1),
('Cheetah Lite', 'cheetah-lite', 'saas', NULL, 1, 'A lighter, courier-only edition. No warehouse module and no picker role.', 'The smaller Cheetah edition for businesses that only run courier deliveries. Shipment booking, rider assignment, tracking and cash-on-delivery, without the warehouse module. Comes with full source code and white-label rights.', NULL, 'https://cheetah-lite.ayan24.me', 'PHP, MySQL, JavaScript', 'For sale', 2),
('Cheezify', 'cheezify', 'stores', NULL, 1, 'Online cheese store with its own admin panel, built for a client, with cold-chain delivery zones and SEO-ready pages.', 'A complete online store and admin panel written in pure PHP with a hand-built MVC structure and no framework. Product catalog, cart, delivery zones and order management. Available as ready-made software.', NULL, 'https://cheezify.ayan24.me', 'PHP, MySQL, JavaScript', 'For sale', 3),
('Sawi Store', 'sawi-store', 'stores', NULL, 0, 'WordPress and WooCommerce online store built with Elementor.', 'An e-commerce website built on WordPress with Elementor and WooCommerce.', NULL, NULL, 'WordPress, Elementor, WooCommerce', 'Delivered', 4)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `category`=VALUES(`category`), `featured`=VALUES(`featured`), `summary`=VALUES(`summary`), `description`=VALUES(`description`), `live_url`=VALUES(`live_url`), `tech_stack`=VALUES(`tech_stack`), `status`=VALUES(`status`), `sort_order`=VALUES(`sort_order`);

-- Remove location mentions from site settings and the founder profile
INSERT INTO `settings` (`key`, `value`, `group_name`) VALUES
('site_location', 'Worldwide, remote', 'general'),
('meta_description', 'Creatsify is a software studio building ready-made courier and store software, plus custom websites, admin panels and apps for clients worldwide.', 'seo'),
('meta_keywords', 'Creatsify, Software Studio, Courier software, Cheetah, Cheezify, PHP, Admin panels, Web Development', 'seo'),
('founder_bio', 'Ayan is the founder of Creatsify. He studies AI in the ACCP program at Aptech, and builds and sells real software: web platforms, mobile apps, desktop tools and WordPress stores.', 'founder')
ON DUPLICATE KEY UPDATE `value`=VALUES(`value`);
UPDATE `team_members` SET `bio` = REPLACE(`bio`, 'Aptech Karachi', 'Aptech') WHERE `bio` LIKE '%Karachi%';

-- Services: replace the old list with the four services shown on the homepage
DELETE FROM `services` WHERE `slug` NOT IN ('websites-admin-panels','wordpress-online-stores','courier-warehouse-systems','whatsapp-sms-alerts');
INSERT INTO `services` (`title`, `slug`, `icon`, `short_description`, `full_description`, `deliverables`, `sort_order`, `is_active`) VALUES
('Websites and admin panels', 'websites-admin-panels', 'server', 'Business websites, dashboards and management systems in PHP and MySQL.', 'Business websites, dashboards and management systems built in PHP and MySQL around how your team works. Fast, tidy and easy to host.', 'Responsive website, Admin panel, Contact and enquiry forms, SEO basics, Deployment help', 1, 1),
('WordPress and online stores', 'wordpress-online-stores', 'shopping-bag', 'Elementor and WooCommerce stores with product pages, checkout and SEO basics.', 'Online stores and business sites built on WordPress with Elementor and WooCommerce, ready for you to manage yourself.', 'Product pages, Cart and checkout, SEO basics, Handover and training', 2, 1),
('Courier and warehouse systems', 'courier-warehouse-systems', 'truck', 'Start from Cheetah Enterprise or Lite and adapt it to your business.', 'Start from Cheetah Enterprise or Cheetah Lite and we adapt it to your branches, riders and merchants.', 'Branch and rider management, Shipment tracking, Cash on delivery, Proof of delivery, Setup help', 3, 1),
('WhatsApp and SMS alerts', 'whatsapp-sms-alerts', 'smartphone', 'Order and shipment updates sent through the WhatsApp Cloud API and SMS.', 'Order and shipment updates sent to your customers through the WhatsApp Cloud API and SMS.', 'WhatsApp Cloud API setup, SMS alerts, Webhooks, Message templates', 4, 1)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `icon`=VALUES(`icon`), `short_description`=VALUES(`short_description`), `full_description`=VALUES(`full_description`), `deliverables`=VALUES(`deliverables`), `sort_order`=VALUES(`sort_order`), `is_active`=1;

-- Team page: keep only the founder; the founder's portfolio lists the real projects
UPDATE `team_members` SET `is_active` = 0 WHERE `id` <> 1;
DELETE FROM `member_portfolios` WHERE `member_id` <> 1 OR `slug` NOT IN ('cheetah-enterprise','cheetah-lite','cheezify','sawi-store');
INSERT INTO `member_portfolios` (`id`, `member_id`, `title`, `slug`, `category`, `client`, `role_in_project`, `summary`, `description`, `image_url`, `project_url`, `tech_stack`, `completion_date`, `is_featured`, `sort_order`) VALUES
(1, 1, 'Cheetah Enterprise', 'cheetah-enterprise', 'Courier software', NULL, 'Founder and lead developer', 'Courier, warehouse and multi-branch logistics platform with 7 role portals.', 'Designed and built end to end: branch management, rider assignment, merchant portals, live tracking and proof of delivery.', NULL, 'https://cheetah.ayan24.me', 'PHP, MySQL, JavaScript, Flutter', NULL, 1, 1),
(2, 1, 'Cheetah Lite', 'cheetah-lite', 'Courier software', NULL, 'Founder and lead developer', 'A lighter, courier-only edition of Cheetah.', 'The smaller Cheetah edition for businesses that only run courier deliveries.', NULL, 'https://cheetah-lite.ayan24.me', 'PHP, MySQL, JavaScript', NULL, 1, 2),
(3, 1, 'Cheezify', 'cheezify', 'Online store', NULL, 'Founder and lead developer', 'Online cheese store with its own admin panel.', 'A complete online store and admin panel in plain PHP with a hand-built MVC structure.', NULL, 'https://cheezify.ayan24.me', 'PHP, MySQL, JavaScript', NULL, 1, 3),
(4, 1, 'Sawi Store', 'sawi-store', 'Online store', NULL, 'Designer and developer', 'WordPress and WooCommerce online store built with Elementor.', 'An e-commerce website built on WordPress with Elementor and WooCommerce.', NULL, NULL, 'WordPress, Elementor, WooCommerce', NULL, 0, 4)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `category`=VALUES(`category`), `role_in_project`=VALUES(`role_in_project`), `summary`=VALUES(`summary`), `description`=VALUES(`description`), `project_url`=VALUES(`project_url`), `tech_stack`=VALUES(`tech_stack`), `is_featured`=VALUES(`is_featured`);

-- Blog: no made-up view counters
UPDATE `posts` SET `views_count` = 0;
