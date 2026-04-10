<?php

class ProductModel extends BaseModel
{
    protected $table = 'products';

    public function allWithCategory($keyword = '')
    {
        $sql = 'SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id';
        $params = [];

        if ($keyword !== '') {
            $sql .= ' WHERE p.name LIKE :keyword OR p.description LIKE :keyword';
            $params['keyword'] = "%{$keyword}%";
        }

        $sql .= ' ORDER BY p.id DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findDetail($id)
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.*, c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            WHERE p.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO products(category_id, name, description, price, stock, image, created_at)
            VALUES (:category_id, :name, :description, :price, :stock, :image, NOW())'
        );
        return $stmt->execute($data);
    }
}
