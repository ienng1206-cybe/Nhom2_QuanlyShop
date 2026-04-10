<?php

class CartController extends BaseController
{
    public function index()
    {
        require_login();
        $cartModel = new CartModel();
        $items = $cartModel->get();

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
        if ($product) {
            (new CartModel())->add($product, $qty);
            $_SESSION['success'] = 'Đã thêm sản phẩm vào giỏ hàng.';
        }

        redirect('cart/index');
    }

    public function remove()
    {
        require_login();
        $id = (int) ($_GET['id'] ?? 0);
        (new CartModel())->remove($id);
        redirect('cart/index');
    }
}
