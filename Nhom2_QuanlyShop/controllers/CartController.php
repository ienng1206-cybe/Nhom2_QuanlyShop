<?php

class CartController extends BaseController
{
    public function index(): void
    {
        require_login();

        try {
            $items = (new CartModel())->get((int) current_user()['id']);
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể mở giỏ hàng (thiếu bảng carts/cart_items hoặc lỗi CSDL). Hãy import schema/migrate.';
            $items = [];
        }
        $this->render('cart/index', [
            'title' => 'Giỏ hàng',
            'view' => 'cart/index',
            'items' => $items,
        ]);
    }

    public function add(): void
    {
        require_login();
        if ($this->requestMethod() !== 'POST') {
            redirect('cart/index');
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $qty = (int) ($_POST['qty'] ?? 1);
        if ($productId <= 0) {
            redirect('product/index');
        }

        try {
            $product = (new ProductModel())->find($productId);
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể thêm vào giỏ (lỗi CSDL).';
            redirect('product/index');
        }
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại.';
            redirect('product/index');
        }

        try {
            $res = (new CartModel())->add((int) current_user()['id'], $product, $qty);
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể thêm vào giỏ hàng (thiếu bảng carts/cart_items hoặc lỗi CSDL).';
            redirect('cart/index');
        }
        if (!$res['ok']) {
            $_SESSION['error'] = $res['message'];
        } else {
            $_SESSION['success'] = 'Đã thêm vào giỏ hàng.';
        }

        $back = $_POST['back'] ?? '';
        if (is_string($back) && $back !== '') {
            redirect($back);
        }
        redirect('cart/index');
    }

    public function update(): void
    {
        require_login();
        if ($this->requestMethod() !== 'POST') {
            redirect('cart/index');
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $qty = (int) ($_POST['qty'] ?? 1);
        if ($productId <= 0) {
            redirect('cart/index');
        }

        try {
            $res = (new CartModel())->updateQty((int) current_user()['id'], $productId, $qty);
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể cập nhật giỏ hàng (lỗi CSDL).';
            redirect('cart/index');
        }
        if (!$res['ok']) {
            $_SESSION['error'] = $res['message'];
        }

        redirect('cart/index');
    }

    public function remove(): void
    {
        require_login();

        $productId = (int) ($_GET['id'] ?? 0);
        if ($productId > 0) {
            try {
                (new CartModel())->remove((int) current_user()['id'], $productId);
                $_SESSION['success'] = 'Đã xóa sản phẩm khỏi giỏ.';
            } catch (Throwable $e) {
                $_SESSION['error'] = 'Không thể xóa sản phẩm khỏi giỏ (lỗi CSDL).';
            }
        }

        redirect('cart/index');
    }

    public function clear(): void
    {
        require_login();

        try {
            (new CartModel())->clear((int) current_user()['id']);
            $_SESSION['success'] = 'Đã xóa toàn bộ giỏ hàng.';
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể xóa giỏ hàng (lỗi CSDL).';
        }
        redirect('cart/index');
    }
}

