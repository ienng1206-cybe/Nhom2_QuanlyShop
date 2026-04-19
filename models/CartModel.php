<?php

class CartModel extends BaseModel
{
    protected $table = 'carts';

    private function ensureCartId(int $userId): int
    {
        $stmt = $this->pdo->prepare('SELECT id FROM carts WHERE user_id = :u LIMIT 1');
        $stmt->execute(['u' => $userId]);
        $row = $stmt->fetch();
        if ($row) {
            return (int) $row['id'];
        }

        try {
            $ins = $this->pdo->prepare('INSERT INTO carts (user_id) VALUES (:u)');
            $ins->execute(['u' => $userId]);

            return (int) $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // Hai request cùng lúc có thể trùng INSERT (sau khi thêm UNIQUE user_id)
            if (strpos($e->getMessage(), 'Duplicate') !== false || (string) $e->getCode() === '23000') {
                $stmt->execute(['u' => $userId]);
                $row = $stmt->fetch();
                if ($row) {
                    return (int) $row['id'];
                }
            }
            throw $e;
        }
    }

    /** @return list<array{id:int,qty:int,name:string,price:string,stock:int,image?:string}> */
    public function get(int $userId): array
    {
        $cid = $this->ensureCartId($userId);
        $img = $this->productsHasImageColumn() ? ', p.image' : '';
        $sql = "SELECT ci.product_id AS id, ci.quantity AS qty, p.name, p.price{$img}, p.stock
                FROM cart_items ci
                INNER JOIN products p ON p.id = ci.product_id
                WHERE ci.cart_id = :cid
                ORDER BY ci.id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['cid' => $cid]);

        return $stmt->fetchAll();
    }

    public function countItems(int $userId): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COALESCE(SUM(ci.quantity), 0) FROM cart_items ci
             INNER JOIN carts c ON c.id = ci.cart_id
             WHERE c.user_id = :u'
        );
        $stmt->execute(['u' => $userId]);

        return (int) $stmt->fetchColumn();
    }

    /** @return array{ok:bool,message:string} */
    public function add(int $userId, array $product, int $qty): array
    {
        $qty = max(1, $qty);
        $stock = (int) ($product['stock'] ?? 0);
        $pid = (int) $product['id'];
        $cid = $this->ensureCartId($userId);

        $stmt = $this->pdo->prepare('SELECT id, quantity FROM cart_items WHERE cart_id = :c AND product_id = :p LIMIT 1');
        $stmt->execute(['c' => $cid, 'p' => $pid]);
        $existing = $stmt->fetch();

        $currentInCart = $existing ? (int) $existing['quantity'] : 0;
        if ($currentInCart + $qty > $stock) {
            return ['ok' => false, 'message' => 'Trong kho chỉ còn ' . $stock . ' sản phẩm.'];
        }

        if ($existing) {
            $newQ = $currentInCart + $qty;
            $this->pdo->prepare('UPDATE cart_items SET quantity = :q WHERE id = :id')->execute(['q' => $newQ, 'id' => $existing['id']]);
        } else {
            $this->pdo->prepare('INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (:c,:p,:q)')
                ->execute(['c' => $cid, 'p' => $pid, 'q' => $qty]);
        }

        return ['ok' => true, 'message' => ''];
    }

    /** @return array{ok:bool,message:string} */
    public function updateQty(int $userId, int $productId, int $qty): array
    {
        if ($qty < 1) {
            $this->remove($userId, $productId);

            return ['ok' => true, 'message' => ''];
        }

        $stmt = $this->pdo->prepare('SELECT id FROM carts WHERE user_id = :u LIMIT 1');
        $stmt->execute(['u' => $userId]);
        $cart = $stmt->fetch();
        if (!$cart) {
            return ['ok' => false, 'message' => 'Giỏ hàng trống.'];
        }
        $cid = (int) $cart['id'];

        $productModel = new ProductModel();
        $p = $productModel->find($productId);
        if (!$p) {
            return ['ok' => false, 'message' => 'Sản phẩm không tồn tại.'];
        }
        if ($qty > (int) $p['stock']) {
            return ['ok' => false, 'message' => 'Tối đa ' . (int) $p['stock'] . ' sản phẩm trong kho.'];
        }

        $stmt = $this->pdo->prepare('SELECT id FROM cart_items WHERE cart_id = :c AND product_id = :p LIMIT 1');
        $stmt->execute(['c' => $cid, 'p' => $productId]);
        $row = $stmt->fetch();
        if (!$row) {
            return ['ok' => false, 'message' => 'Sản phẩm không có trong giỏ.'];
        }
        $this->pdo->prepare('UPDATE cart_items SET quantity = :q WHERE id = :id')->execute(['q' => $qty, 'id' => $row['id']]);

        return ['ok' => true, 'message' => ''];
    }

    public function remove(int $userId, int $productId): void
    {
        $stmt = $this->pdo->prepare('SELECT id FROM carts WHERE user_id = :u LIMIT 1');
        $stmt->execute(['u' => $userId]);
        $cart = $stmt->fetch();
        if (!$cart) {
            return;
        }
        $this->pdo->prepare('DELETE FROM cart_items WHERE cart_id = :c AND product_id = :p')
            ->execute(['c' => (int) $cart['id'], 'p' => $productId]);
    }

    public function clear(int $userId): void
    {
        $stmt = $this->pdo->prepare('SELECT id FROM carts WHERE user_id = :u LIMIT 1');
        $stmt->execute(['u' => $userId]);
        $cart = $stmt->fetch();
        if (!$cart) {
            return;
        }
        $this->pdo->prepare('DELETE FROM cart_items WHERE cart_id = :c')->execute(['c' => (int) $cart['id']]);
    }
}
