<?php

class UserController extends BaseController
{
    /**
     * Trang tài khoản cá nhân: hiển thị thông tin người dùng, đơn hàng, giỏ hàng
     */
    public function profile()
    {
        require_login();
        $userId = (int) current_user()['id'];
        
        $orderModel = new OrderModel();
        $cartModel = new CartModel();
        
        $orders = $orderModel->getByUser($userId);
        $cartItems = $cartModel->get($userId);
        $cartCount = count($cartItems);
        $cartTotal = 0;
        foreach ($cartItems as $item) {
            $cartTotal += (float) $item['price'] * (int) $item['qty'];
        }

        $this->render('user/profile', [
            'title' => 'Thông tin cá nhân',
            'view' => 'user/profile',
            'user' => current_user(),
            'orders' => $orders,
            'cartItems' => $cartItems,
            'cartCount' => $cartCount,
            'cartTotal' => $cartTotal,
        ]);
    }

    /**
     * Xem lịch sử đơn hàng (redirect to order/my)
     */
    public function orders()
    {
        require_login();
        redirect('order/my');
    }
}
