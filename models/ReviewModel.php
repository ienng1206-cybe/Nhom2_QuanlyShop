<?php

class ReviewModel extends BaseModel
{
    protected $table = 'reviews';

    public function getByProduct($productId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.*, u.name AS user_name
            FROM reviews r
            JOIN users u ON u.id = r.user_id
            WHERE r.product_id = :product_id
            ORDER BY r.id DESC'
        );
        $stmt->execute(['product_id' => $productId]);
        return $stmt->fetchAll();
    }

    public function getAllWithDetails()
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.*, u.name AS user_name, p.name AS product_name, p.id AS product_id
            FROM reviews r
            JOIN users u ON u.id = r.user_id
            JOIN products p ON p.id = r.product_id
            ORDER BY r.created_at DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($userId, $productId, $rating, $comment, $image = null)
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO reviews(user_id, product_id, rating, comment, image, created_at)
            VALUES (:user_id, :product_id, :rating, :comment, :image, NOW())'
        );
        return $stmt->execute([
            'user_id' => $userId,
            'product_id' => $productId,
            'rating' => $rating,
            'comment' => $comment,
            'image' => $image,
        ]);
    }
}
