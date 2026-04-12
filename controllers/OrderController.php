<?php

class OrderController extends BaseController
{
    public function checkout()
    {
        require_login();
        $userId = (int) current_user()['id'];
        $cartModel = new CartModel();
        $items = $cartModel->get($userId);
        if (empty($items)) {
            $_SESSION['error'] = 'Giỏ hàng đang trống.';
            redirect('cart/index');
        }

        $this->render('order/checkout', [
            'title' => 'Thanh toán & giao hàng',
            'view' => 'order/checkout',
            'items' => $items,
            'user' => current_user(),
        ]);
    }

    public function place()
    {
        require_login();
        if ($this->requestMethod() !== 'POST') {
            redirect('order/checkout');
        }

        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        if ($phone === '' || $address === '') {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ số điện thoại và địa chỉ giao hàng.';
            redirect('order/checkout');
        }

        $userId = (int) current_user()['id'];
        $cartModel = new CartModel();
        $items = $cartModel->get($userId);
        if (empty($items)) {
            $_SESSION['error'] = 'Giỏ hàng đang trống.';
            redirect('cart/index');
        }

        try {
            $orderModel = new OrderModel();
            $orderId = $orderModel->createFromCart($userId, $items, [
                'phone' => $phone,
                'address' => $address,
            ]);
            if ($orderId) {
                $cartModel->clear($userId);
                $_SESSION['success'] = 'Đặt hàng thành công. Mã đơn #' . $orderId;
                redirect('order/detail&id=' . $orderId);
            }
            $_SESSION['error'] = 'Không đủ hàng trong kho cho một hoặc nhiều sản phẩm. Vui lòng kiểm tra lại giỏ hàng.';
            redirect('cart/index');
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể tạo đơn hàng. Vui lòng thử lại.';
            redirect('order/checkout');
        }
    }

    public function detail()
    {
        require_login();
        $id = (int) ($_GET['id'] ?? 0);
        $userId = (int) current_user()['id'];
        $orderModel = new OrderModel();
        $order = $orderModel->findForUser($id, $userId);
        if (!$order) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng.';
            redirect('order/my');
        }

        $this->render('order/detail', [
            'title' => 'Chi tiết đơn hàng #' . $id,
            'view' => 'order/detail',
            'order' => $order,
            'items' => $orderModel->getOrderItems($id),
            'shipping' => $orderModel->getShipping($id),
        ]);
    }

    public function myOrders()
    {
        require_login();
        $orders = (new OrderModel())->getByUser((int) current_user()['id']);
        $this->render('order/my', [
            'title' => 'Đơn hàng của tôi',
            'view' => 'order/my',
            'orders' => $orders,
        ]);
    }
}
