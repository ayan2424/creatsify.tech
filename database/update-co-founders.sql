-- ============================================================================
-- Creatsify Studio - Equal Co-Founders & Brand Content Update
-- Updates the agency from a single founder to 3 Strictly Equal Co-Founders:
-- 1. Muhammad Ayan Shamim (Co-Founder & Lead Full-Stack Architect)
-- 2. Syeda Sukaina Batool (Co-Founder & UI/UX Product Design Lead)
-- 3. Abdul Moiz (Co-Founder & Technical Operations Lead)
-- ============================================================================

-- 1. Update Team Members Table
UPDATE `team_members` SET 
    `name` = 'Muhammad Ayan Shamim',
    `slug` = 'ayan-shamim',
    `role_title` = 'Co-Founder & Lead Full-Stack Architect',
    `bio` = 'Co-founder and lead engineer at Creatsify. Ayan specializes in high-velocity PHP 8 MVC engines, MySQL database architecture, and full-stack software development.',
    `skills` = 'PHP 8, MySQL, Full-Stack Architecture, REST APIs, Flutter',
    `avatar` = '/assets/brand/creatsify-icon.png',
    `is_active` = 1,
    `sort_order` = 1
WHERE `id` = 1;

UPDATE `team_members` SET 
    `name` = 'Syeda Sukaina Batool',
    `slug` = 'syeda-sukaina-batool',
    `role_title` = 'Co-Founder & UI/UX Product Design Lead',
    `bio` = 'Co-founder and head of product design at Creatsify. Sukaina crafts high-contrast design systems, obsidian interfaces, and intuitive user workflows for web platforms and mobile apps.',
    `skills` = 'UI/UX Design, Design Systems, Figma, Product Architecture, Frontend UX',
    `avatar` = '/assets/brand/creatsify-icon.png',
    `is_active` = 1,
    `sort_order` = 2
WHERE `id` = 2;

UPDATE `team_members` SET 
    `name` = 'Abdul Moiz',
    `slug` = 'abdul-moiz',
    `role_title` = 'Co-Founder & Technical Operations Lead',
    `bio` = 'Co-founder and operations lead at Creatsify. Moiz leads systems deployment, server infrastructure, client integrations, and operational delivery across all software products.',
    `skills` = 'System Deployment, Linux / Nginx, aaPanel, Technical Operations, Client Solutions',
    `avatar` = '/assets/brand/creatsify-icon.png',
    `is_active` = 1,
    `sort_order` = 3
WHERE `id` = 3;

-- 2. Update Member Portfolios
UPDATE `member_portfolios` SET
    `title` = 'Cheetah Obsidian UI/UX Design System',
    `slug` = 'cheetah-design-system',
    `category` = 'Product Design',
    `client` = 'Creatsify Studio',
    `role_in_project` = 'Co-Founder & Lead Designer',
    `summary` = 'Crafted a dark obsidian design system with high-contrast accessibility tokens, 3D glass widgets, and responsive UI components.',
    `description` = 'Constructed atomic Figma components, telemetry chart layouts, and interactive mobile driver interfaces.',
    `project_url` = 'https://creatsify.tech',
    `tech_stack` = 'Figma, Design Tokens, CSS Custom Properties'
WHERE `id` = 3;

UPDATE `member_portfolios` SET
    `title` = 'Cheezify E-Commerce Store & Admin UX',
    `slug` = 'cheezify-store-ux',
    `category` = 'E-Commerce Interface',
    `client` = 'Commercial Client',
    `role_in_project` = 'Co-Founder & Product Designer',
    `summary` = 'Designed the high-conversion storefront and simplified merchant inventory dashboard for Cheezify.',
    `description` = 'Streamlined cold-chain checkout and delivery zone selectors with touch-friendly ergonomics.',
    `project_url` = 'https://cheezify.ayan24.me',
    `tech_stack` = 'Figma, Micro-animations, UI Tokens'
WHERE `id` = 4;

UPDATE `member_portfolios` SET
    `title` = 'Cheetah Courier Driver & Picker Mobile App',
    `slug` = 'cheetah-driver-picker-app',
    `category` = 'Mobile Operations',
    `client` = 'Courier Fleet',
    `role_in_project` = 'Co-Founder & Technical Operations Lead',
    `summary` = 'Mobile operations companion for delivery couriers featuring offline signature ePOD and background GPS tracking.',
    `description` = 'Implemented background geofencing, thermal Bluetooth barcode scanner integrations, and route recalculations.',
    `project_url` = 'https://cheetah.ayan24.me',
    `tech_stack` = 'Flutter, Dart, SQLite, Google Maps SDK'
WHERE `id` = 5;

UPDATE `member_portfolios` SET
    `title` = 'High-Availability aaPanel & Nginx Deployment Engine',
    `slug` = 'aapanel-nginx-deployment-engine',
    `category` = 'Infrastructure',
    `client` = 'Creatsify Studio',
    `role_in_project` = 'Co-Founder & Operations Lead',
    `summary` = 'Engineered automated front-controller URL rewrite standards and zero-downtime PHP 8.4 deployment pipelines.',
    `description` = 'Configured production Nginx reverse proxy configurations, SSL certificate renewals, and MySQL performance tuning.',
    `project_url` = 'https://creatsify.tech',
    `tech_stack` = 'Linux, Nginx, aaPanel, MySQL, Bash'
WHERE `id` = 6;

-- 3. Update Settings Table for 3 Co-Founders
INSERT INTO `settings` (`key`, `value`, `group_name`) VALUES
('founder_name', 'Muhammad Ayan Shamim, Syeda Sukaina Batool & Abdul Moiz', 'founder'),
('founder_title', 'Co-Founders & Leadership', 'founder'),
('founder_role', 'Co-Founders & Studio Architecture', 'founder'),
('founder_bio', 'Creatsify is an independent software studio led by three co-founders: Muhammad Ayan Shamim, Syeda Sukaina Batool, and Abdul Moiz. Together, we architect, design, and ship production-grade courier platforms, online store systems, and custom web applications for clients worldwide.', 'founder'),
('founder_github', 'https://github.com/ayan2424/creatsify.tech', 'founder'),
('founder_linkedin', 'https://creatsify.tech/team', 'founder'),
('site_location', 'Worldwide, remote', 'general')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

-- 4. Update Sections Table for About Co-Founders
INSERT INTO `sections` (`section_key`, `title`, `subtitle`, `content`, `metadata`, `is_active`, `sort_order`) VALUES
('about_founder', 'Built by Creatsify Co-Founders', 'Technical Architecture & Product Leadership', 'Creatsify is an independent digital software studio founded by three co-founders: Muhammad Ayan Shamim (Lead Full-Stack Architect), Syeda Sukaina Batool (UI/UX Product Design Lead), and Abdul Moiz (Technical Operations Lead). We design, engineer, and support production-ready software platforms for businesses worldwide.', '{"badge": "CREATSIFY • STUDIO LEADERSHIP • BUILD • SHIP • "}', 1, 5)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `subtitle`=VALUES(`subtitle`), `content`=VALUES(`content`), `metadata`=VALUES(`metadata`);
