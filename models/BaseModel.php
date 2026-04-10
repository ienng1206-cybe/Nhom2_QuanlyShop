<?php

class BaseModel
{
    protected $table = '';
    protected $pdo;

    public function __construct()
    {
        $this->pdo = db_connect();
    }

    public function all($orderBy = 'id DESC')
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY {$orderBy}");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
