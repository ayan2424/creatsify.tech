<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Creatsify Studio - PDO Database Layer
 */
class Database
{
    private ?PDO $pdo = null;
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getPdo(): PDO
    {
        if ($this->pdo === null) {
            $this->connect();
        }
        return $this->pdo;
    }

    private function connect(): void
    {
        $driver = $this->config['driver'] ?? 'mysql';

        if ($driver === 'sqlite') {
            $dbPath = $this->config['database'] ?? (App::getInstance()->getBasePath() . '/storage/database.sqlite');
            $dsn = "sqlite:" . $dbPath;
            $user = null;
            $pass = null;
        } else {
            $host = $this->config['host'] ?? '127.0.0.1';
            $port = $this->config['port'] ?? 3306;
            $dbname = $this->config['database'] ?? 'creatsify_db';
            $charset = $this->config['charset'] ?? 'utf8mb4';
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
            $user = $this->config['username'] ?? 'root';
            $pass = $this->config['password'] ?? '';
        }

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new \RuntimeException("Database Connection Failed: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetchOne(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params)->fetch();
        return $result ?: null;
    }

    public function fetchValue(string $sql, array $params = []): mixed
    {
        return $this->query($sql, $params)->fetchColumn();
    }

    public function insert(string $table, array $data): int|string
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            throw new \InvalidArgumentException("Invalid table name: {$table}");
        }
        $keys = array_keys($data);
        $fields = implode(', ', array_map(fn($k) => "`$k`", $keys));
        $placeholders = implode(', ', array_map(fn($k) => ":$k", $keys));

        $sql = "INSERT INTO `{$table}` ({$fields}) VALUES ({$placeholders})";
        $this->query($sql, $data);
        return $this->getPdo()->lastInsertId();
    }

    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            throw new \InvalidArgumentException("Invalid table name: {$table}");
        }
        $cleanWhere = trim($where);
        if ($cleanWhere === '') {
            throw new \InvalidArgumentException("UPDATE query rejected: WHERE clause cannot be empty.");
        }
        $setClauses = [];
        $params = [];
        foreach ($data as $key => $val) {
            $setClauses[] = "`$key` = :set_$key";
            $params["set_$key"] = $val;
        }
        $setString = implode(', ', $setClauses);
        $sql = "UPDATE `{$table}` SET {$setString} WHERE {$cleanWhere}";
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute(array_merge($params, $whereParams));
        return $stmt->rowCount();
    }

    public function delete(string $table, string $where, array $whereParams = []): int
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            throw new \InvalidArgumentException("Invalid table name: {$table}");
        }
        $cleanWhere = trim($where);
        if ($cleanWhere === '') {
            throw new \InvalidArgumentException("DELETE query rejected: WHERE clause cannot be empty.");
        }
        $sql = "DELETE FROM `{$table}` WHERE {$cleanWhere}";
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute($whereParams);
        return $stmt->rowCount();
    }

    public function beginTransaction(): bool
    {
        return $this->getPdo()->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->getPdo()->commit();
    }

    public function rollback(): bool
    {
        return $this->getPdo()->rollBack();
    }
}
