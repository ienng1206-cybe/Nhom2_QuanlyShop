<?php

class BaseModel
{
    protected $table = '';
    protected $pdo;

    public function __construct()
    {
        $this->pdo = db_connect();
    }

    /** CSDL cũ có thể chưa có cột products.image — tránh lỗi SQL 1054 */
    protected function productsHasImageColumn(): bool
    {
        static $has = null;
        if ($has === null) {
            $stmt = $this->pdo->query("SHOW COLUMNS FROM `products` LIKE 'image'");
            $has = (bool) $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $has;
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
