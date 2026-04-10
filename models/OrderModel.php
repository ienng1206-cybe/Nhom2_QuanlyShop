<?php

class OrderModel extends BaseModel
{
    protected $table = 'orders';

    public function createFromCart($userId, $cartItems)
    {
        if (empty($cartItems)) {
            return null;
        }

        $this->pdo->beginTransaction();
        try {
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item['price'] * $item['qty'];
            }

            $stmtOrder = $this->pdo->prepare(
                'INSERT INTO orders(user_id, total_amount, status, created_at)
                VALUES (:user_id, :total, "pending", NOW())'
            );
            $stmtOrder->execute([
                'user_id' => $userId,
                'total' => $total,
            ]);
            $orderId = (int) $this->pdo->lastInsertId();

            $stmtItem = $this->pdo->prepare(
                'INSERT INTO order_items(order_id, product_id, price, quantity)
                VALUES (:order_id, :product_id, :price, :quantity)'
            );

            foreach ($cartItems as $item) {
                $stmtItem->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                ]);
            }

            $this->pdo->commit();
            return $orderId;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function getByUser($userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->pdo->prepare('UPDATE orders SET status = :status WHERE id = :id');
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
}
