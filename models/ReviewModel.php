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

    public function create($userId, $productId, $rating, $comment)
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO reviews(user_id, product_id, rating, comment, created_at)
            VALUES (:user_id, :product_id, :rating, :comment, NOW())'
        );
        return $stmt->execute([
            'user_id' => $userId,
            'product_id' => $productId,
            'rating' => $rating,
            'comment' => $comment,
        ]);
    }
}
