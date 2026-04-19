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

    public function create(array $data): bool
    {
        $categoryId = (int) ($data['category_id'] ?? 0);
        $name = trim((string) ($data['name'] ?? ''));
        $description = (string) ($data['description'] ?? '');
        $price = (float) ($data['price'] ?? 0);
        $stock = (int) ($data['stock'] ?? 0);
        $image = trim((string) ($data['image'] ?? ''));
        $imageVal = $image === '' ? null : $image;

        if ($this->productsHasImageColumn()) {
            $stmt = $this->pdo->prepare(
                'INSERT INTO products(category_id, name, description, price, stock, image, created_at)
                 VALUES (:category_id, :name, :description, :price, :stock, :image, NOW())'
            );

            return $stmt->execute([
                'category_id' => $categoryId,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'image' => $imageVal,
            ]);
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO products(category_id, name, description, price, stock, created_at)
             VALUES (:category_id, :name, :description, :price, :stock, NOW())'
        );

        return $stmt->execute([
            'category_id' => $categoryId,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
        ]);
    }

    public function updateById(int $id, array $data): bool
    {
        $id = (int) $id;
        $categoryId = (int) ($data['category_id'] ?? 0);
        $name = trim((string) ($data['name'] ?? ''));
        $description = (string) ($data['description'] ?? '');
        $price = (float) ($data['price'] ?? 0);
        $stock = (int) ($data['stock'] ?? 0);
        $image = trim((string) ($data['image'] ?? ''));
        $imageVal = $image === '' ? null : $image;

        if ($id <= 0 || $categoryId <= 0 || $name === '') {
            return false;
        }

        if ($this->productsHasImageColumn()) {
            $stmt = $this->pdo->prepare(
                'UPDATE products
                 SET category_id = :category_id, name = :name, description = :description, price = :price, stock = :stock, image = :image
                 WHERE id = :id'
            );

            return $stmt->execute([
                'id' => $id,
                'category_id' => $categoryId,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'image' => $imageVal,
            ]);
        }

        $stmt = $this->pdo->prepare(
            'UPDATE products
             SET category_id = :category_id, name = :name, description = :description, price = :price, stock = :stock
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'category_id' => $categoryId,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
        ]);
    }
}
