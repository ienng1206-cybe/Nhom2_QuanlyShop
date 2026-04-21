<?php

class OrderController extends BaseController
{
    public function checkout()
    {
        require_login();
        $userId = (int) current_user()['id'];
        $cartModel = new CartModel();
        $productId = (int) ($_GET['product_id'] ?? 0);
        if ($productId > 0) {
            try {
                $item = $cartModel->getItem($userId, $productId);
            } catch (Throwable $e) {
                $_SESSION['error'] = 'Không thể lấy sản phẩm từ giỏ (thiếu bảng carts/cart_items hoặc lỗi CSDL).';
                redirect('cart/index');
            }
            if (!$item) {
                $_SESSION['error'] = 'Sản phẩm không có trong giỏ hàng.';
                redirect('cart/index');
            }
            $items = [$item];
        } else {
            try {
                $items = $cartModel->get($userId);
            } catch (Throwable $e) {
                $_SESSION['error'] = 'Không thể mở thanh toán (thiếu bảng carts/cart_items hoặc lỗi CSDL).';
                redirect('cart/index');
            }
            if (empty($items)) {
                $_SESSION['error'] = 'Giỏ hàng đang trống.';
                redirect('cart/index');
            }
        }

        $this->render('order/checkout', [
            'title' => 'Thanh toán & giao hàng',
            'view' => 'order/checkout',
            'items' => $items,
            'user' => current_user(),
            'product_id' => $productId,
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
        $productId = (int) ($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            try {
                $item = $cartModel->getItem($userId, $productId);
            } catch (Throwable $e) {
                $_SESSION['error'] = 'Không thể tạo đơn (lỗi giỏ hàng/CSDL).';
                redirect('cart/index');
            }
            $items = $item ? [$item] : [];
        } else {
            try {
                $items = $cartModel->get($userId);
            } catch (Throwable $e) {
                $_SESSION['error'] = 'Không thể tạo đơn (lỗi giỏ hàng/CSDL).';
                redirect('cart/index');
            }
        }
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
                if ($productId > 0) {
                    $cartModel->remove($userId, $productId);
                } else {
                    $cartModel->clear($userId);
                }
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
            if ($productId > 0) {
                redirect('order/checkout&product_id=' . $productId);
            }
            redirect('order/checkout');
        }
    }

    public function detail()
    {
        require_login();
        $id = (int) ($_GET['id'] ?? 0);
        $userId = (int) current_user()['id'];
        $orderModel = new OrderModel();
        try {
            $order = $orderModel->findForUser($id, $userId);
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể tải đơn hàng (thiếu bảng orders/order_items hoặc lỗi CSDL).';
            redirect('order/my');
        }
        if (!$order) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng.';
            redirect('order/my');
        }

        try {
            $items = $orderModel->getOrderItems($id);
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể tải chi tiết sản phẩm trong đơn (lỗi CSDL).';
            redirect('order/my');
        }
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
            $userId = (int) current_user()['id'];
            $orderModel = new OrderModel();
            $ok = $orderModel->cancelForUser($id, $userId);
            if ($ok) {
                // Khóa tài khoản nếu hủy quá nhiều.
                $maxCancels = 3;
                $cancelCount = $orderModel->countCancelledByUser($userId);
                if ($cancelCount >= $maxCancels) {
                    (new UserModel())->setLockStatus($userId, true);
                    unset($_SESSION['user']);
                    $_SESSION['error'] = 'Tài khoản đã bị khóa do hủy đơn quá nhiều lần. Vui lòng liên hệ quản trị viên.';
                    redirect('auth/login');
                }

                $_SESSION['success'] = 'Đã hủy đơn hàng #' . $id . '.';
            } else {
                $_SESSION['error'] = 'Không thể hủy đơn (chỉ hủy được đơn đang chờ xử lý, trong vòng 5 phút sau khi đặt).';
            }
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể hủy đơn hàng lúc này.';
        }

        redirect('order/my');
    }

    public function myOrders()
    {
        require_login();
        try {
            $orders = (new OrderModel())->getByUser((int) current_user()['id']);
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể tải danh sách đơn hàng (thiếu bảng orders hoặc lỗi CSDL).';
            $orders = [];
        }
        $this->render('order/my', [
            'title' => 'Đơn hàng của tôi',
            'view' => 'order/my',
            'orders' => $orders,
        ]);
    }
}
