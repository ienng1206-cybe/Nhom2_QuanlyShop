<?php

class CategoryModel extends BaseModel
{
    protected $table = 'categories';

    public function create($name)
    {
        $stmt = $this->pdo->prepare('INSERT INTO categories(name, created_at) VALUES (:name, NOW())');
        return $stmt->execute(['name' => $name]);
    }
}
