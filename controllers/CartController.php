<?php

class CartController extends BaseController
{
    public function index()
    {
        require_login();
        $userId = (int) current_user()['id'];
        $cartModel = new CartModel();
        $items = $cartModel->get($userId);

        $this->render('cart/index', [
            'title' => 'Giỏ hàng',
            'view' => 'cart/index',
            'items' => $items,
        ]);
    }

    public function add()
    {
        require_login();
        $id = (int) ($_POST['product_id'] ?? 0);
        $qty = (int) ($_POST['qty'] ?? 1);

        $productModel = new ProductModel();
        $product = $productModel->find($id);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại.';
            redirect('product/index');
        }

        $result = (new CartModel())->add((int) current_user()['id'], $product, $qty);
        if ($result['ok']) {
            $_SESSION['success'] = 'Đã thêm sản phẩm vào giỏ hàng.';
            redirect('cart/index');
        }

        $_SESSION['error'] = $result['message'];
        redirect('product/detail&id=' . $id);
    }

    public function update()
    {
        require_login();
        if ($this->requestMethod() !== 'POST') {
            redirect('cart/index');
        }

        $pid = (int) ($_POST['product_id'] ?? 0);
        $qty = (int) ($_POST['qty'] ?? 1);

        $result = (new CartModel())->updateQty((int) current_user()['id'], $pid, $qty);
        if (!$result['ok']) {
            $_SESSION['error'] = $result['message'];
        }

        redirect('cart/index');
    }

    public function remove()
    {
        require_login();
        $id = (int) ($_GET['id'] ?? 0);
        (new CartModel())->remove((int) current_user()['id'], $id);
        $_SESSION['success'] = 'Đã xóa sản phẩm khỏi giỏ hàng.';
        redirect('cart/index');
    }

    /** Xóa toàn bộ sản phẩm trong giỏ (giữ lại bản ghi carts) */
    public function clear()
    {
        require_login();
        (new CartModel())->clear((int) current_user()['id']);
        $_SESSION['success'] = 'Đã xóa toàn bộ giỏ hàng.';
        redirect('cart/index');
    }
}
