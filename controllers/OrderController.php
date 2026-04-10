<?php

class OrderController extends BaseController
{
    public function checkout()
    {
        require_login();

        $cartModel = new CartModel();
        $items = $cartModel->get();
        if (empty($items)) {
            $_SESSION['error'] = 'Giỏ hàng đang trống.';
            redirect('cart/index');
        }

        $orderModel = new OrderModel();
        $orderId = $orderModel->createFromCart(current_user()['id'], $items);
        if ($orderId) {
            $cartModel->clear();
            $_SESSION['success'] = 'Đặt hàng thành công.';
        }

        redirect('order/my');
    }

    public function myOrders()
    {
        require_login();
        $orders = (new OrderModel())->getByUser(current_user()['id']);
        $this->render('order/my', [
            'title' => 'Đơn hàng của tôi',
            'view' => 'order/my',
            'orders' => $orders,
        ]);
    }
}
