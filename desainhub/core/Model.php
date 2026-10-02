<?php
/**
 * Base Model — wrapper query PDO yang aman (prepared statement)
 */

class Model
{
    protected PDO $db;
    protected string $table = '';

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all(string $orderBy = 'id DESC'): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy}");
        return $stmt->fetchAll();
    }

    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findBy(string $field, $value): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$field} = :value LIMIT 1");
        $stmt->execute(['value' => $value]);
        return $stmt->fetch();
    }

    public function where(string $field, $value, string $orderBy = 'id DESC'): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$field} = :value ORDER BY {$orderBy}");
        $stmt->execute(['value' => $value]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $set = implode(', ', array_map(fn($col) => "{$col} = :{$col}", array_keys($data)));
        $data['id'] = $id;
        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$set} WHERE id = :id");
        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
