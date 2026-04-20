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
            $msg = $e->getMessage();
            if (strlen($msg) > 200) {
                $msg = substr($msg, 0, 200) . '…';
            }
            $_SESSION['error'] = 'Không thể tạo đơn hàng. ' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
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

        $items = $orderModel->getOrderItems($id);
        $canReviewProducts = [];
        foreach ($items as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            if ($productId > 0) {
                $canReviewProducts[$productId] = $orderModel->userCanReviewProduct($userId, $productId);
            }
        }

        $this->render('order/detail', [
            'title' => 'Chi tiết đơn hàng #' . $id,
            'view' => 'order/detail',
            'order' => $order,
            'items' => $items,
            'shipping' => $orderModel->getShipping($id),
            'canReviewProducts' => $canReviewProducts,
        ]);
    }

    public function cancel()
    {
        require_login();
        if ($this->requestMethod() !== 'POST') {
            redirect('order/my');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'Mã đơn không hợp lệ.';
            redirect('order/my');
        }

        try {
            $ok = (new OrderModel())->cancelForUser($id, (int) current_user()['id']);
            $_SESSION[$ok ? 'success' : 'error'] = $ok
                ? 'Đã hủy đơn hàng #' . $id . '.'
                : 'Không thể hủy đơn (chỉ hủy được đơn đang chờ xử lý, trong vòng 5 phút sau khi đặt).';
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể hủy đơn hàng lúc này.';
        }

        redirect('order/my');
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
