<?php

class CategoryModel extends BaseModel
{
    protected $table = 'categories';

    private static ?bool $hasCodeColumn = null;

    private function hasCodeColumn(): bool
    {
        if (self::$hasCodeColumn === null) {
            $stmt = $this->pdo->query("SHOW COLUMNS FROM `categories` LIKE 'code'");
            self::$hasCodeColumn = (bool) $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return self::$hasCodeColumn;
    }

    public function create(string $name, ?string $code = null): bool
    {
        $code = $code !== null && trim($code) !== '' ? trim($code) : null;

        try {
            if ($this->hasCodeColumn()) {
                $stmt = $this->pdo->prepare('INSERT INTO categories(name, code, created_at) VALUES (:name, :code, NOW())');

                return $stmt->execute(['name' => $name, 'code' => $code]);
            }

            $stmt = $this->pdo->prepare('INSERT INTO categories(name, created_at) VALUES (:name, NOW())');

            return $stmt->execute(['name' => $name]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateById(int $id, string $name, ?string $code = null): bool
    {
        $id = (int) $id;
        $name = trim($name);
        $code = $code !== null && trim($code) !== '' ? trim($code) : null;

        if ($id <= 0 || $name === '') {
            return false;
        }

        try {
            if ($this->hasCodeColumn()) {
                $stmt = $this->pdo->prepare('UPDATE categories SET name = :name, code = :code WHERE id = :id');
                return $stmt->execute(['name' => $name, 'code' => $code, 'id' => $id]);
            }

            $stmt = $this->pdo->prepare('UPDATE categories SET name = :name WHERE id = :id');
            return $stmt->execute(['name' => $name, 'id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
