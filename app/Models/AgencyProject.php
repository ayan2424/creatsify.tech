<?php

namespace App\Models;

/**
 * Internal delivery projects for Creatsify's own agency work.
 * These records are deliberately separate from public software products.
 */
class AgencyProject
{
    private static bool $schemaChecked = false;

    public const STATUSES = ['planning', 'active', 'on_hold', 'delivered', 'archived'];
    public const MILESTONE_STATUSES = ['not_started', 'in_progress', 'review', 'complete'];
    public const TASK_STATUSES = ['todo', 'in_progress', 'blocked', 'done'];
    public const PRIORITIES = ['low', 'medium', 'high', 'urgent'];

    public static function ensureSchema(): void
    {
        if (self::$schemaChecked) {
            return;
        }
        TeamMember::ensureTableExists();

        try {
            db()->query("CREATE TABLE IF NOT EXISTS `agency_projects` (
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
        } catch (\Throwable $e) {}

        try {
            db()->query("CREATE TABLE IF NOT EXISTS `agency_project_members` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `agency_project_id` INT NOT NULL,
                `team_member_id` INT NOT NULL,
                `delivery_role` VARCHAR(120) NOT NULL,
                `allocation_percent` TINYINT UNSIGNED NOT NULL DEFAULT 100,
                `assigned_at` DATE NULL,
                `unassigned_at` DATE NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY `uq_agency_project_member` (`agency_project_id`, `team_member_id`),
                INDEX `idx_agency_project_members_team` (`team_member_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } catch (\Throwable $e) {}

        try {
            db()->query("CREATE TABLE IF NOT EXISTS `agency_project_milestones` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `agency_project_id` INT NOT NULL,
                `title` VARCHAR(200) NOT NULL,
                `description` TEXT NULL,
                `status` VARCHAR(30) NOT NULL DEFAULT 'not_started',
                `amount` DECIMAL(12,2) NULL,
                `due_date` DATE NULL,
                `completed_at` DATETIME NULL,
                `sort_order` INT NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_agency_milestones_project` (`agency_project_id`),
                INDEX `idx_agency_milestones_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } catch (\Throwable $e) {}

        try {
            db()->query("CREATE TABLE IF NOT EXISTS `agency_project_tasks` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `agency_project_id` INT NOT NULL,
                `milestone_id` INT NULL,
                `assigned_team_member_id` INT NULL,
                `title` VARCHAR(200) NOT NULL,
                `description` TEXT NULL,
                `status` VARCHAR(30) NOT NULL DEFAULT 'todo',
                `priority` VARCHAR(20) NOT NULL DEFAULT 'medium',
                `due_date` DATE NULL,
                `completed_at` DATETIME NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_agency_tasks_project` (`agency_project_id`),
                INDEX `idx_agency_tasks_member` (`assigned_team_member_id`),
                INDEX `idx_agency_tasks_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } catch (\Throwable $e) {}

        try {
            db()->query("CREATE TABLE IF NOT EXISTS `agency_project_notes` (
                `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
                `agency_project_id` INT NOT NULL,
                `user_id` INT NULL,
                `author_name` VARCHAR(150) NOT NULL,
                `body` LONGTEXT NOT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX `idx_agency_notes_project` (`agency_project_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } catch (\Throwable $e) {}

        self::$schemaChecked = true;
    }

    public static function all(?string $status = null, ?string $search = null): array
    {
        self::ensureSchema();
        $where = [];
        $params = [];

        if ($status !== null && in_array($status, self::STATUSES, true)) {
            $where[] = 'p.status = :status';
            $params['status'] = $status;
        }
        if ($search !== null && $search !== '') {
            $where[] = '(p.title LIKE :search OR p.project_code LIKE :search OR c.name LIKE :search OR c.company LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        return db()->fetchAll("SELECT p.*, c.name AS client_name, c.company AS client_company,
                    (SELECT COUNT(*) FROM agency_project_members pm WHERE pm.agency_project_id = p.id) AS member_count,
                    (SELECT COUNT(*) FROM agency_project_milestones mm WHERE mm.agency_project_id = p.id) AS milestone_count,
                    (SELECT COUNT(*) FROM agency_project_tasks tt WHERE tt.agency_project_id = p.id AND tt.status <> 'done') AS open_task_count
                FROM agency_projects p
                LEFT JOIN crm_clients c ON c.id = p.client_id
                {$whereClause}
                ORDER BY FIELD(p.status, 'active', 'planning', 'on_hold', 'delivered', 'archived'), p.target_date IS NULL, p.target_date ASC, p.id DESC", $params);
    }

    public static function metrics(): array
    {
        self::ensureSchema();
        $rows = db()->fetchAll('SELECT status, COUNT(*) AS count FROM agency_projects GROUP BY status');
        $counts = array_fill_keys(self::STATUSES, 0);
        foreach ($rows as $row) {
            if (isset($counts[$row['status']])) {
                $counts[$row['status']] = (int)$row['count'];
            }
        }

        return [
            'counts' => $counts,
            'open_tasks' => (int)db()->fetchValue("SELECT COUNT(*) FROM agency_project_tasks WHERE status <> 'done'"),
            'overdue_tasks' => (int)db()->fetchValue("SELECT COUNT(*) FROM agency_project_tasks WHERE status <> 'done' AND due_date IS NOT NULL AND due_date < CURDATE()"),
        ];
    }

    public static function findById(int $id): ?array
    {
        self::ensureSchema();
        return db()->fetchOne("SELECT p.*, c.name AS client_name, c.company AS client_company, c.email AS client_email
            FROM agency_projects p
            LEFT JOIN crm_clients c ON c.id = p.client_id
            WHERE p.id = :id LIMIT 1", ['id' => $id]);
    }

    public static function create(array $data): int
    {
        self::ensureSchema();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('agency_projects', $data);
    }

    public static function updateStatus(int $id, string $status): int
    {
        self::ensureSchema();
        $data = ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')];
        $data['delivered_at'] = $status === 'delivered' ? date('Y-m-d H:i:s') : null;
        return db()->update('agency_projects', $data, '`id` = :id', ['id' => $id]);
    }

    public static function members(int $projectId): array
    {
        self::ensureSchema();
        return db()->fetchAll("SELECT pm.*, tm.name, tm.role_title, tm.avatar
            FROM agency_project_members pm
            INNER JOIN team_members tm ON tm.id = pm.team_member_id
            WHERE pm.agency_project_id = :project_id
            ORDER BY pm.created_at ASC", ['project_id' => $projectId]);
    }

    public static function assignMember(int $projectId, int $teamMemberId, string $deliveryRole, int $allocation): int
    {
        self::ensureSchema();
        $existing = db()->fetchOne('SELECT id FROM agency_project_members WHERE agency_project_id = :project_id AND team_member_id = :team_member_id LIMIT 1', [
            'project_id' => $projectId,
            'team_member_id' => $teamMemberId,
        ]);

        $data = [
            'delivery_role' => $deliveryRole,
            'allocation_percent' => max(1, min(100, $allocation)),
            'assigned_at' => date('Y-m-d'),
        ];
        if ($existing) {
            db()->update('agency_project_members', $data, '`id` = :id', ['id' => $existing['id']]);
            return (int)$existing['id'];
        }

        return (int)db()->insert('agency_project_members', array_merge($data, [
            'agency_project_id' => $projectId,
            'team_member_id' => $teamMemberId,
        ]));
    }

    public static function removeMember(int $projectId, int $memberId): int
    {
        self::ensureSchema();
        return db()->delete('agency_project_members', '`id` = :id AND `agency_project_id` = :project_id', [
            'id' => $memberId,
            'project_id' => $projectId,
        ]);
    }

    public static function milestones(int $projectId): array
    {
        self::ensureSchema();
        return db()->fetchAll('SELECT * FROM agency_project_milestones WHERE agency_project_id = :project_id ORDER BY sort_order ASC, due_date IS NULL, due_date ASC, id ASC', ['project_id' => $projectId]);
    }

    public static function createMilestone(array $data): int
    {
        self::ensureSchema();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('agency_project_milestones', $data);
    }

    public static function updateMilestoneStatus(int $projectId, int $milestoneId, string $status): int
    {
        self::ensureSchema();
        $data = ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')];
        if ($status === 'complete') {
            $data['completed_at'] = date('Y-m-d H:i:s');
        }
        return db()->update('agency_project_milestones', $data, '`id` = :id AND `agency_project_id` = :project_id', [
            'id' => $milestoneId,
            'project_id' => $projectId,
        ]);
    }

    public static function tasks(int $projectId): array
    {
        self::ensureSchema();
        return db()->fetchAll("SELECT t.*, m.title AS milestone_title, tm.name AS assignee_name
            FROM agency_project_tasks t
            LEFT JOIN agency_project_milestones m ON m.id = t.milestone_id
            LEFT JOIN team_members tm ON tm.id = t.assigned_team_member_id
            WHERE t.agency_project_id = :project_id
            ORDER BY CASE t.status WHEN 'blocked' THEN 1 WHEN 'in_progress' THEN 2 WHEN 'todo' THEN 3 WHEN 'done' THEN 4 ELSE 5 END, t.due_date IS NULL, t.due_date ASC, t.id DESC", ['project_id' => $projectId]);
    }

    public static function createTask(array $data): int
    {
        self::ensureSchema();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('agency_project_tasks', $data);
    }

    public static function updateTaskStatus(int $projectId, int $taskId, string $status): int
    {
        self::ensureSchema();
        $data = ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')];
        if ($status === 'done') {
            $data['completed_at'] = date('Y-m-d H:i:s');
        }
        return db()->update('agency_project_tasks', $data, '`id` = :id AND `agency_project_id` = :project_id', [
            'id' => $taskId,
            'project_id' => $projectId,
        ]);
    }

    public static function notes(int $projectId): array
    {
        self::ensureSchema();
        return db()->fetchAll('SELECT * FROM agency_project_notes WHERE agency_project_id = :project_id ORDER BY id DESC', ['project_id' => $projectId]);
    }

    public static function addNote(int $projectId, ?int $userId, string $authorName, string $body): int
    {
        self::ensureSchema();
        return (int)db()->insert('agency_project_notes', [
            'agency_project_id' => $projectId,
            'user_id' => $userId,
            'author_name' => $authorName,
            'body' => $body,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
