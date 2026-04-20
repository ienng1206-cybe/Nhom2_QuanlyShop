<?php

class ProductModel extends BaseModel
{
    protected $table = 'products';

    public function allWithCategory($keyword = '', $sort = '', $priceRange = '')
    {
        $sql = 'SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id';
        $params = [];
        $conditions = [];

        if ($keyword !== '') {
            $conditions[] = '(p.name LIKE :keyword OR p.description LIKE :keyword)';
            $params['keyword'] = "%{$keyword}%";
        }

        if ($priceRange === 'under_1') {
            $conditions[] = 'p.price < 1000000';
        } elseif ($priceRange === 'under_3') {
            $conditions[] = 'p.price >= 1000000 AND p.price < 3000000';
        } elseif ($priceRange === 'under_5') {
            $conditions[] = 'p.price >= 3000000 AND p.price < 5000000';
        } elseif ($priceRange === 'over_5') {
            $conditions[] = 'p.price >= 5000000';
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $orderBy = 'p.id DESC';
        if ($sort === 'price_asc') {
            $orderBy = 'p.price ASC';
        } elseif ($sort === 'price_desc') {
            $orderBy = 'p.price DESC';
        }

        $sql .= ' ORDER BY ' . $orderBy;
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
}
