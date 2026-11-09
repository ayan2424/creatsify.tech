<?php

namespace App\Models;

/**
 * Creatsify Studio - User Model (Cheezify Architecture)
 */
class User
{
    public static function ensureAdminExists(): void
    {
        static $checked = false;
        if ($checked) return;
        $checked = true;

        try {
            \App\Database\SchemaMigrator::ensureAll();
        } catch (\Throwable $e) {}
    }

    public static function findById(int $id): ?array
    {
        return db()->fetchOne("SELECT * FROM `users` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function findByEmail(string $email): ?array
    {
        self::ensureAdminExists();
        $cleanEmail = strtolower(trim($email));
        if ($cleanEmail === 'admin' || $cleanEmail === 'superadmin' || $cleanEmail === 'super_admin') {
            $cleanEmail = 'admin@creatsify.tech';
        }
        return db()->fetchOne("SELECT * FROM `users` WHERE LOWER(`email`) = :email LIMIT 1", ['email' => $cleanEmail]);
    }

    public static function findByRole(string $role): ?array
    {
        self::ensureAdminExists();
        return db()->fetchOne("SELECT * FROM `users` WHERE `role` = :role ORDER BY `id` ASC LIMIT 1", ['role' => $role]);
    }

    public static function all(): array
    {
        return db()->fetchAll("SELECT id, name, email, role, phone, status, created_at FROM `users` ORDER BY id DESC");
    }

    public static function create(array $data): int
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        return (int)db()->insert('users', $data);
    }

    public static function update(int $id, array $data): int
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        return db()->update('users', $data, '`id` = :id', ['id' => $id]);
    }

    public static function delete(int $id): int
    {
        $user = self::findById($id);
        if (!$user || ($user['role'] ?? '') === 'super_admin') {
            return 0;
        }
        return db()->delete('users', '`id` = :id', ['id' => $id]);
    }

    public static function verifyPassword(string $plain, string $hash): bool
    {
        if (password_verify($plain, $hash)) {
            return true;
        }
        // Fallback check for legacy MD5/SHA1 if migrated
        if (md5($plain) === $hash || sha1($plain) === $hash) {
            return true;
        }
        return false;
    }
}
