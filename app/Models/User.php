<?php

namespace App\Models;

/**
 * Creatsify Studio - User Model (Cheezify Architecture)
 */
class User
{
    public static function findById(int $id): ?array
    {
        return db()->fetchOne("SELECT * FROM `users` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    public static function findByEmail(string $email): ?array
    {
        return db()->fetchOne("SELECT * FROM `users` WHERE `email` = :email LIMIT 1", ['email' => $email]);
    }

    public static function findByRole(string $role): ?array
    {
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
        return db()->delete('users', '`id` = :id', ['id' => $id]);
    }

    public static function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }
}
