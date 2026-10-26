-- =======================================================
-- Creatsify Studio - High-Performance Database Schema
-- Version: 1.0.0
-- Compatible with MySQL 5.7+, MariaDB 10.3+, SQLite 3
-- =======================================================

CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255) NULL,
    `permissions` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) NOT NULL DEFAULT 'admin',
    `avatar` VARCHAR(255) NULL,
    `phone` VARCHAR(50) NULL,
    `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `action` VARCHAR(100) NOT NULL,
    `entity_type` VARCHAR(100) NULL,
    `entity_id` INT NULL,
    `details` TEXT NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` VARCHAR(255) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_user` (`user_id`),
    INDEX `idx_audit_action` (`action`),
    INDEX `idx_audit_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` LONGTEXT NULL,
    `group_name` VARCHAR(50) NOT NULL DEFAULT 'general',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_settings_group` (`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sections` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `section_key` VARCHAR(50) NOT NULL UNIQUE,
    `title` VARCHAR(255) NULL,
    `subtitle` VARCHAR(255) NULL,
    `content` LONGTEXT NULL,
    `metadata` JSON NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_sections_key` (`section_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL UNIQUE,
    `category` VARCHAR(50) NOT NULL DEFAULT 'saas',
    `client` VARCHAR(150) NULL,
    `featured` TINYINT(1) DEFAULT 0,
    `summary` VARCHAR(350) NULL,
    `description` LONGTEXT NULL,
    `image_url` VARCHAR(255) NULL,
    `live_url` VARCHAR(255) NULL,
    `gumroad_url` VARCHAR(255) NULL,
    `price` VARCHAR(50) NULL DEFAULT '$99',
    `tech_stack` VARCHAR(255) NULL,
    `status` VARCHAR(50) DEFAULT 'Shipped',
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_projects_category` (`category`),
    INDEX `idx_projects_featured` (`featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `services` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL UNIQUE,
    `icon` VARCHAR(100) NULL,
    `short_description` VARCHAR(350) NULL,
    `full_description` LONGTEXT NULL,
    `deliverables` TEXT NULL,
    `sort_order` INT DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `member_portfolios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `member_id` INT NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL,
    `category` VARCHAR(100) DEFAULT 'Full-Stack Development',
    `client` VARCHAR(150) NULL,
    `role_in_project` VARCHAR(150) NULL,
    `summary` VARCHAR(400) NULL,
    `description` LONGTEXT NULL,
    `image_url` VARCHAR(255) NULL,
    `project_url` VARCHAR(255) NULL,
    `tech_stack` VARCHAR(255) NULL,
    `completion_date` VARCHAR(50) NULL,
    `is_featured` TINYINT(1) DEFAULT 0,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_mp_member` (`member_id`),
    INDEX `idx_mp_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `posts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `excerpt` VARCHAR(400) NULL,
    `content` LONGTEXT NULL,
    `cover_image` VARCHAR(255) NULL,
    `category` VARCHAR(100) DEFAULT 'Engineering',
    `author_id` INT NULL,
    `is_published` TINYINT(1) DEFAULT 1,
    `views_count` INT DEFAULT 0,
    `published_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_posts_slug` (`slug`),
    INDEX `idx_posts_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `content` LONGTEXT NULL,
    `meta_title` VARCHAR(255) NULL,
    `meta_description` VARCHAR(350) NULL,
    `is_published` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `inquiries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(120) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(50) NULL,
    `project_type` VARCHAR(100) NULL,
    `budget` VARCHAR(50) NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('new', 'in_discussion', 'converted', 'closed') DEFAULT 'new',
    `ip_address` VARCHAR(45) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_inquiries_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chat_sessions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `session_token` VARCHAR(64) NOT NULL UNIQUE,
    `visitor_name` VARCHAR(120) DEFAULT 'Guest Visitor',
    `visitor_email` VARCHAR(150) NULL,
    `visitor_phone` VARCHAR(50) NULL,
    `ip_address` VARCHAR(45) NULL,
    `status` ENUM('active', 'handoff', 'closed') DEFAULT 'active',
    `is_human_handoff` TINYINT(1) DEFAULT 0,
    `last_active_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_chat_token` (`session_token`),
    INDEX `idx_chat_status` (`status`),
    INDEX `idx_chat_handoff` (`is_human_handoff`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chat_messages` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `session_id` INT NOT NULL,
    `sender_type` ENUM('visitor', 'bot', 'admin') NOT NULL,
    `sender_name` VARCHAR(100) NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_chat_session_id` (`session_id`),
    INDEX `idx_chat_msg_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `whatsapp_messages` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `direction` ENUM('inbound', 'outbound') NOT NULL,
    `from_number` VARCHAR(50) NOT NULL,
    `to_number` VARCHAR(50) NOT NULL,
    `message` TEXT NOT NULL,
    `message_id` VARCHAR(150) NULL,
    `status` VARCHAR(50) DEFAULT 'sent',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_wa_from` (`from_number`),
    INDEX `idx_wa_to` (`to_number`),
    INDEX `idx_wa_msg_id` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sms_messages` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `direction` ENUM('inbound', 'outbound') NOT NULL,
    `phone_number` VARCHAR(50) NOT NULL,
    `message` TEXT NOT NULL,
    `status` VARCHAR(50) DEFAULT 'sent',
    `gateway_response` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_sms_phone` (`phone_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `notifications` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(50) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `link` VARCHAR(255) NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_notif_unread` (`is_read`),
    INDEX `idx_notif_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `analytics_visits` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `ip_address` VARCHAR(45) NULL,
    `path` VARCHAR(255) NOT NULL,
    `referrer` VARCHAR(255) NULL,
    `user_agent` VARCHAR(255) NULL,
    `device_type` VARCHAR(50) DEFAULT 'desktop',
    `country` VARCHAR(10) DEFAULT 'PK',
    `visited_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_analytics_path` (`path`),
    INDEX `idx_analytics_visited` (`visited_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `media` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `filename` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NOT NULL,
    `mime_type` VARCHAR(100) NOT NULL,
    `file_size` BIGINT NOT NULL,
    `path` VARCHAR(255) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_media_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================================
-- Agency & Software Portfolio CRM Suite Tables
-- =======================================================

CREATE TABLE IF NOT EXISTS `crm_clients` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `company` VARCHAR(150) NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(50) NULL,
    `whatsapp` VARCHAR(50) NULL,
    `stage` ENUM('lead', 'discovery', 'proposal_sent', 'contract_signed', 'active_client', 'retained', 'churned') DEFAULT 'lead',
    `deal_value` DECIMAL(12,2) DEFAULT 0.00,
    `currency` VARCHAR(10) DEFAULT 'USD',
    `source` VARCHAR(100) DEFAULT 'Website Inquiry',
    `inquiry_id` INT NULL,
    `assigned_to` INT NULL,
    `city` VARCHAR(100) NULL,
    `country` VARCHAR(100) NULL,
    `website` VARCHAR(255) NULL,
    `tags` VARCHAR(255) NULL,
    `notes_summary` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_client_stage` (`stage`),
    INDEX `idx_client_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `crm_tickets` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ticket_number` VARCHAR(30) NOT NULL UNIQUE,
    `client_id` INT NULL,
    `client_name` VARCHAR(150) NOT NULL,
    `client_email` VARCHAR(150) NOT NULL,
    `client_phone` VARCHAR(50) NULL,
    `subject` VARCHAR(255) NOT NULL,
    `category` VARCHAR(60) NOT NULL DEFAULT 'maintenance',
    `priority` ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    `status` ENUM('open', 'in_progress', 'waiting_client', 'resolved', 'closed') DEFAULT 'open',
    `assigned_to` INT NULL,
    `project_id` INT NULL,
    `description` LONGTEXT NOT NULL,
    `due_date` DATE NULL,
    `resolved_at` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_ticket_num` (`ticket_number`),
    INDEX `idx_ticket_status` (`status`),
    INDEX `idx_ticket_priority` (`priority`),
    INDEX `idx_ticket_client` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `crm_ticket_replies` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `ticket_id` INT NOT NULL,
    `user_id` INT NULL,
    `author_name` VARCHAR(100) NOT NULL,
    `author_type` ENUM('staff', 'client', 'system') DEFAULT 'staff',
    `message` LONGTEXT NOT NULL,
    `is_internal_note` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_replies_ticket` (`ticket_id`),
    INDEX `idx_replies_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `crm_notes` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `client_id` INT NULL,
    `inquiry_id` INT NULL,
    `ticket_id` INT NULL,
    `user_id` INT NOT NULL,
    `author_name` VARCHAR(100) NOT NULL,
    `type` ENUM('note', 'call_log', 'meeting', 'deal_terms', 'follow_up') DEFAULT 'note',
    `title` VARCHAR(200) NOT NULL,
    `content` LONGTEXT NOT NULL,
    `follow_up_date` DATE NULL,
    `is_follow_up_done` TINYINT(1) DEFAULT 0,
    `is_pinned` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_notes_client` (`client_id`),
    INDEX `idx_notes_inquiry` (`inquiry_id`),
    INDEX `idx_notes_ticket` (`ticket_id`),
    INDEX `idx_notes_type` (`type`),
    INDEX `idx_notes_followup` (`follow_up_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
