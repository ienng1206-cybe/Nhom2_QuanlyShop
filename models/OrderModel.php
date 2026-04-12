<?php

class OrderModel extends BaseModel
{
    protected $table = 'orders';

    /**
     * @param list<array{id:int,qty:int,price:mixed}> $cartItems
     * @param array{phone?:string,address?:string}    $shipping
     */
    public function createFromCart($userId, $cartItems, array $shipping = [])
    {
        if (empty($cartItems)) {
            return null;
        }

        $this->pdo->beginTransaction();
        try {
            $stmtLock = $this->pdo->prepare('SELECT id, stock FROM products WHERE id = :id');
            foreach ($cartItems as $item) {
                $pid = (int) $item['id'];
                $need = (int) $item['qty'];
                $stmtLock->execute(['id' => $pid]);
                $row = $stmtLock->fetch();
                if (!$row || (int) $row['stock'] < $need) {
                    $this->pdo->rollBack();

                    return null;
                }
            }

            $total = 0;
            foreach ($cartItems as $item) {
                $total += (float) $item['price'] * (int) $item['qty'];
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

            $stmtStock = $this->pdo->prepare('UPDATE products SET stock = stock - :q WHERE id = :id AND stock >= :q2');

            foreach ($cartItems as $item) {
                $pid = (int) $item['id'];
                $qty = (int) $item['qty'];
                $stmtItem->execute([
                    'order_id' => $orderId,
                    'product_id' => $pid,
                    'price' => $item['price'],
                    'quantity' => $qty,
                ]);
                $stmtStock->execute(['q' => $qty, 'id' => $pid, 'q2' => $qty]);
                if ($stmtStock->rowCount() === 0) {
                    $this->pdo->rollBack();

                    return null;
                }
            }

            if (!empty($shipping['phone']) || !empty($shipping['address'])) {
                $stmtShip = $this->pdo->prepare(
                    'INSERT INTO shipping (order_id, address, phone, status) VALUES (:order_id, :address, :phone, :status)'
                );
                $stmtShip->execute([
                    'order_id' => $orderId,
                    'address' => trim($shipping['address'] ?? ''),
                    'phone' => trim($shipping['phone'] ?? ''),
                    'status' => 'pending',
                ]);
            }

            $this->pdo->commit();

            return $orderId;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function findForUser(int $orderId, int $userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE id = :id AND user_id = :uid LIMIT 1');
        $stmt->execute(['id' => $orderId, 'uid' => $userId]);

        return $stmt->fetch();
    }

    public function getOrderItems(int $orderId): array
    {
        $img = $this->productsHasImageColumn() ? ', p.image' : '';
        $stmt = $this->pdo->prepare(
            "SELECT oi.*, p.name{$img}
             FROM order_items oi
             JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = :oid
             ORDER BY oi.id ASC"
        );
        $stmt->execute(['oid' => $orderId]);

        return $stmt->fetchAll();
    }

    public function getShipping(int $orderId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM shipping WHERE order_id = :oid ORDER BY id DESC LIMIT 1');
        $stmt->execute(['oid' => $orderId]);
        $row = $stmt->fetch();

        return $row ?: null;
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
