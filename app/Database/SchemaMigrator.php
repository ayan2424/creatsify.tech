<?php

namespace App\Database;

/**
 * Creatsify Studio - Self-Healing Schema Migrator
 * Automatically detects and applies missing tables, columns, and default seeds
 * on live server deployments (aaPanel / cPanel / Docker / Local XAMPP) without requiring terminal commands.
 */
class SchemaMigrator
{
    private static bool $migrated = false;

    public static function ensureAll(): void
    {
        if (self::$migrated) {
            return;
        }
        self::$migrated = true;

        try {
            $db = db();

            // 1. CRM Tables
            $db->query("CREATE TABLE IF NOT EXISTS `crm_clients` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(150) NOT NULL,
                `email` VARCHAR(150) NULL,
                `phone` VARCHAR(50) NULL,
                `company` VARCHAR(150) NULL,
                `stage` VARCHAR(50) NOT NULL DEFAULT 'lead',
                `deal_value` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                `currency` VARCHAR(10) NOT NULL DEFAULT 'USD',
                `source` VARCHAR(100) NULL DEFAULT 'Website Inquiry',
                `notes` TEXT NULL,
                `tags` VARCHAR(255) NULL,
                `assigned_to` INT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_crm_stage` (`stage`),
                INDEX `idx_crm_email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            $db->query("CREATE TABLE IF NOT EXISTS `crm_tickets` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `ticket_num` VARCHAR(50) NOT NULL UNIQUE,
                `client_id` INT NULL,
                `subject` VARCHAR(255) NOT NULL,
                `description` TEXT NOT NULL,
                `priority` VARCHAR(20) NOT NULL DEFAULT 'medium',
                `status` VARCHAR(30) NOT NULL DEFAULT 'open',
                `category` VARCHAR(50) NOT NULL DEFAULT 'general',
                `assigned_to` INT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_crm_ticket_client` (`client_id`),
                INDEX `idx_crm_ticket_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            $db->query("CREATE TABLE IF NOT EXISTS `crm_ticket_replies` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `ticket_id` INT NOT NULL,
                `user_id` INT NULL,
                `sender_type` VARCHAR(20) NOT NULL DEFAULT 'admin',
                `sender_name` VARCHAR(100) NOT NULL,
                `message` TEXT NOT NULL,
                `is_internal` TINYINT(1) NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX `idx_reply_ticket` (`ticket_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            $db->query("CREATE TABLE IF NOT EXISTS `crm_notes` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `client_id` INT NOT NULL,
                `user_id` INT NULL,
                `author_name` VARCHAR(100) NOT NULL DEFAULT 'Staff',
                `content` TEXT NOT NULL,
                `is_pinned` TINYINT(1) NOT NULL DEFAULT 0,
                `needs_followup` TINYINT(1) NOT NULL DEFAULT 0,
                `followup_date` DATE NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_crm_notes_client` (`client_id`),
                INDEX `idx_crm_notes_followup` (`needs_followup`, `followup_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            // 2. Agency Projects Tables
            $db->query("CREATE TABLE IF NOT EXISTS `agency_projects` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `project_code` VARCHAR(40) NOT NULL UNIQUE,
                `client_id` INT NULL,
                `title` VARCHAR(200) NOT NULL,
                `project_type` VARCHAR(100) NOT NULL DEFAULT 'Custom Development',
                `summary` TEXT NULL,
                `scope_notes` LONGTEXT NULL,
                `status` VARCHAR(30) NOT NULL DEFAULT 'planning',
                `priority` VARCHAR(20) NOT NULL DEFAULT 'medium',
                `deal_value` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                `currency` VARCHAR(10) NOT NULL DEFAULT 'USD',
                `start_date` DATE NULL,
                `target_date` DATE NULL,
                `delivered_at` DATETIME NULL,
                `created_by` INT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_agency_projects_client` (`client_id`),
                INDEX `idx_agency_projects_status` (`status`),
                INDEX `idx_agency_projects_target` (`target_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            $db->query("CREATE TABLE IF NOT EXISTS `agency_project_members` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `agency_project_id` INT NOT NULL,
                `team_member_id` INT NOT NULL,
                `delivery_role` VARCHAR(120) NOT NULL,
                `allocation_percent` TINYINT UNSIGNED NOT NULL DEFAULT 100,
                `assigned_at` DATE NULL,
                `unassigned_at` DATE NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY `uq_agency_project_member` (`agency_project_id`, `team_member_id`),
                INDEX `idx_agency_member_lookup` (`team_member_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            $db->query("CREATE TABLE IF NOT EXISTS `agency_project_milestones` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `agency_project_id` INT NOT NULL,
                `title` VARCHAR(180) NOT NULL,
                `description` TEXT NULL,
                `target_date` DATE NULL,
                `status` VARCHAR(30) NOT NULL DEFAULT 'pending',
                `sort_order` INT NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX `idx_agency_milestones_project` (`agency_project_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            $db->query("CREATE TABLE IF NOT EXISTS `agency_project_tasks` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `agency_project_id` INT NOT NULL,
                `assigned_member_id` INT NULL,
                `title` VARCHAR(200) NOT NULL,
                `description` TEXT NULL,
                `status` VARCHAR(30) NOT NULL DEFAULT 'todo',
                `priority` VARCHAR(20) NOT NULL DEFAULT 'medium',
                `due_date` DATE NULL,
                `sort_order` INT NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_agency_tasks_project` (`agency_project_id`),
                INDEX `idx_agency_tasks_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            $db->query("CREATE TABLE IF NOT EXISTS `agency_project_notes` (
                `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
                `agency_project_id` INT NOT NULL,
                `user_id` INT NULL,
                `author_name` VARCHAR(150) NOT NULL,
                `body` LONGTEXT NOT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX `idx_agency_notes_project` (`agency_project_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            // 3. Client Reviews & Service Packages
            $db->query("CREATE TABLE IF NOT EXISTS `client_reviews` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `client_name` VARCHAR(120) NOT NULL,
                `client_email` VARCHAR(150) NULL,
                `client_role` VARCHAR(120) NULL,
                `company_name` VARCHAR(150) NULL,
                `service_category` VARCHAR(100) NOT NULL DEFAULT 'Web Development',
                `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5,
                `review_title` VARCHAR(200) NOT NULL,
                `review_text` TEXT NOT NULL,
                `outcome_metric` VARCHAR(120) NULL,
                `client_avatar` VARCHAR(255) NULL,
                `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'approved',
                `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
                `sort_order` INT NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_reviews_status` (`status`),
                INDEX `idx_reviews_featured` (`is_featured`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            $db->query("CREATE TABLE IF NOT EXISTS `service_packages` (
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
                `features` TEXT NULL,
                `features_json` LONGTEXT NULL,
                `not_included_json` LONGTEXT NULL,
                `cta_label` VARCHAR(80) NOT NULL DEFAULT 'Select Package',
                `cta_text` VARCHAR(80) NOT NULL DEFAULT 'Get Started Now',
                `cta_url` VARCHAR(255) NOT NULL DEFAULT '/contact',
                `status` VARCHAR(20) NOT NULL DEFAULT 'active',
                `is_active` TINYINT(1) NOT NULL DEFAULT 1,
                `sort_order` INT NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_pkg_category` (`category_track`),
                INDEX `idx_pkg_status` (`status`),
                INDEX `idx_pkg_active` (`is_active`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            // 4. Ensure Columns in Existing Tables
            try {
                $db->query("SELECT `is_active` FROM `service_packages` LIMIT 1");
            } catch (\Throwable $e) {
                try {
                    $db->query("ALTER TABLE `service_packages` 
                        ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `sort_order`,
                        ADD COLUMN `features` TEXT NULL AFTER `summary`,
                        ADD COLUMN `cta_label` VARCHAR(80) NOT NULL DEFAULT 'Select Package' AFTER `features`");
                    $db->query("UPDATE `service_packages` SET `is_active` = (CASE WHEN `status` = 'inactive' THEN 0 ELSE 1 END)");
                    $db->query("UPDATE `service_packages` SET `features` = `features_json` WHERE `features` IS NULL AND `features_json` IS NOT NULL");
                    $db->query("UPDATE `service_packages` SET `cta_label` = `cta_text` WHERE `cta_label` IS NULL AND `cta_text` IS NOT NULL");
                } catch (\Throwable $e2) {
                    try {
                        $db->query("ALTER TABLE `service_packages` ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1");
                    } catch (\Throwable $e3) {}
                }
            }

            try {
                $db->query("SELECT `gumroad_url`, `price` FROM `projects` LIMIT 1");
            } catch (\Throwable $e) {
                try {
                    $db->query("ALTER TABLE `projects` 
                        ADD COLUMN `gumroad_url` VARCHAR(255) NULL AFTER `live_url`, 
                        ADD COLUMN `price` VARCHAR(50) NULL DEFAULT '$99' AFTER `gumroad_url`");
                } catch (\Throwable $e2) {}
            }

            // 5. Ensure Super Admin Account with Guaranteed Password 'admin123'
            $hash = password_hash('admin123', PASSWORD_BCRYPT);
            try {
                $db->query("INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `status`, `created_at`) 
                    VALUES ('Creatsify Admin', 'admin@creatsify.tech', '{$hash}', 'super_admin', '+923000000000', 'active', NOW())
                    ON DUPLICATE KEY UPDATE `password` = '{$hash}', `role` = 'super_admin', `status` = 'active'");
            } catch (\Throwable $e) {}

            try {
                $db->query("INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `status`, `created_at`) 
                    VALUES ('Muhammad Ayan Shamim', 'ayan@creatsify.tech', '{$hash}', 'super_admin', '+923000000000', 'active', NOW())
                    ON DUPLICATE KEY UPDATE `password` = '{$hash}', `role` = 'super_admin', `status` = 'active'");
            } catch (\Throwable $e) {}

        } catch (\Throwable $outer) {
            // Never break runtime execution if DB user lacks certain permissions
        }
    }
}
