<?php

class CartModel
{
    public function get()
    {
        return $_SESSION['cart'] ?? [];
    }

    public function add($product, $qty = 1)
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $id = (int) $product['id'];
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = [
                'id' => $id,
                'name' => $product['name'],
                'price' => $product['price'],
                'qty' => 0,
            ];
        }

        $_SESSION['cart'][$id]['qty'] += max(1, (int) $qty);
    }

    public function remove($id)
    {
        unset($_SESSION['cart'][(int) $id]);
    }

    public function clear()
    {
        unset($_SESSION['cart']);
    }
}
