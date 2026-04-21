<?php

class AdminController extends BaseController
{
    /** Layout riêng views/admin/layout.php — không dùng main.php (cửa hàng). */
    private function renderAdminPage(string $viewName, array $data): void
    {
        extract($data);
        $adminTemplatePath = PATH_VIEW . $viewName . '.php';
        require PATH_VIEW . 'admin/layout.php';
    }

    /** Tránh vỡ trang admin khi CSDL thiếu bảng. */
    private function adminAll(string $modelClass): array
    {
        try {
            return (new $modelClass())->all();
        } catch (Throwable $e) {
            return [];
        }
    }

    public function dashboard()
    {
        require_admin();

        $reviews = [];
        $admin_reviews_missing = false;
        try {
            $reviews = (new ReviewModel())->all();
        } catch (Throwable $e) {
            $admin_reviews_missing = true;
        }

        $this->renderAdminPage('admin/dashboard', [
            'title' => 'Bảng điều khiển',
            'users' => $this->adminAll(UserModel::class),
            'categories' => $this->adminAll(CategoryModel::class),
            'products' => $this->adminAll(ProductModel::class),
            'orders' => $this->adminAll(OrderModel::class),
            'reviews' => $reviews,
            'admin_reviews_missing' => $admin_reviews_missing,
        ]);
    }

    public function categories()
    {
        require_admin();

        if ($this->requestMethod() === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $code = trim($_POST['code'] ?? '');

            if ($name === '') {
                $_SESSION['error'] = 'Nhập tên danh mục.';
            } else {
                $ok = (new CategoryModel())->create($name, $code !== '' ? $code : null);
                if ($ok) {
                    $_SESSION['success'] = 'Đã thêm danh mục «' . $name . '».';
                } else {
                    $_SESSION['error'] = 'Không thêm được danh mục. Có thể trùng mã (code) hoặc lỗi CSDL.';
                }
            }

            redirect('admin/categories');
        }

        $this->renderAdminPage('admin/categories', [
            'title' => 'Danh mục',
            'categories' => $this->adminAll(CategoryModel::class),
        ]);
    }

    public function products()
    {
        require_admin();

        if ($this->requestMethod() === 'POST') {
            $data = [
                'category_id' => (int) ($_POST['category_id'] ?? 0),
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => (float) ($_POST['price'] ?? 0),
                'stock' => (int) ($_POST['stock'] ?? 0),
                'image' => trim($_POST['image'] ?? ''),
            ];

            if ($data['name'] === '' || $data['category_id'] <= 0) {
                $_SESSION['error'] = 'Vui lòng chọn danh mục và nhập tên sản phẩm.';
            } else {
                try {
                    $ok = (new ProductModel())->create($data);
                    $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Đã thêm sản phẩm thành công.' : 'Không thể thêm sản phẩm.';
                } catch (Throwable $e) {
                    $_SESSION['error'] = 'Lỗi khi thêm sản phẩm. Kiểm tra danh mục có tồn tại và cấu trúc bảng products.';
                }
            }

            redirect('admin/products');
        }

        $editProductId = (int) ($_GET['edit_product_id'] ?? 0);
        $editingProduct = null;
        if ($editProductId > 0) {
            $editingProduct = (new ProductModel())->find($editProductId);
        }

        $this->renderAdminPage('admin/products', [
            'title' => 'Sản phẩm',
            'categories' => $this->adminAll(CategoryModel::class),
            'products' => $this->adminAll(ProductModel::class),
            'editingProduct' => $editingProduct,
        ]);
    }

    public function updateProduct()
    {
        require_admin();
        if ($this->requestMethod() !== 'POST') {
            redirect('admin/products');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $data = [
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (float) ($_POST['price'] ?? 0),
            'stock' => (int) ($_POST['stock'] ?? 0),
            'image' => trim($_POST['image'] ?? ''),
        ];

        try {
            $ok = (new ProductModel())->updateById($id, $data);
            $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Đã cập nhật sản phẩm #' . $id . '.' : 'Không thể cập nhật sản phẩm.';
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Lỗi khi cập nhật sản phẩm.';
        }

        redirect('admin/products');
    }

    public function orders()
    {
        require_admin();

        $orders = [];
        try {
            $orders = (new OrderModel())->allForAdmin();
        } catch (Throwable $e) {
            $orders = [];
        }

        $this->renderAdminPage('admin/orders', [
            'title' => 'Đơn hàng',
            'orders' => $orders,
        ]);
    }

    public function updateOrderStatus()
    {
        require_admin();

        $id = (int) ($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? 'pending';
        if ($id > 0) {
            $ok = (new OrderModel())->updateStatus($id, $status);
            $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Đã cập nhật trạng thái đơn hàng #' . $id . '.' : 'Không cập nhật được trạng thái.';
        }

        redirect('admin/orders');
    }

    public function orderDetail()
    {
        require_admin();

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            redirect('admin/orders');
        }

        $order = null;
        $orderItems = [];
        $shipping = null;

        try {
            $order = (new OrderModel())->find($id);
            if ($order) {
                $orderItems = (new OrderModel())->getOrderItems($id);
                $shipping = (new OrderModel())->getShipping($id);
            }
        } catch (Throwable $e) {
            $order = null;
        }

        if (!$order) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng.';
            redirect('admin/orders');
        }

        // Get user info
        $user = (new UserModel())->find($order['user_id'] ?? 0);

        $this->renderAdminPage('admin/order-detail', [
            'title' => 'Chi tiết đơn hàng #' . $id,
            'order' => $order,
            'orderItems' => $orderItems,
            'shipping' => $shipping,
            'user' => $user,
        ]);
    }

    public function users()
    {
        require_admin();

        $this->renderAdminPage('admin/users', [
            'title' => 'Người dùng',
            'users' => $this->adminAll(UserModel::class),
        ]);
    }

    public function reviews()
    {
        require_admin();

        $reviews = [];
        $admin_reviews_missing = false;
        try {
            $reviews = (new ReviewModel())->all();
        } catch (Throwable $e) {
            $admin_reviews_missing = true;
        }

        $this->renderAdminPage('admin/reviews', [
            'title' => 'Đánh giá',
            'reviews' => $reviews,
            'admin_reviews_missing' => $admin_reviews_missing,
        ]);
    }

    public function delete()
    {
        require_admin();

        $type = $_GET['type'] ?? '';
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            redirect('admin/dashboard');
        }

        try {
            match ($type) {
                'user' => (new UserModel())->delete($id),
                'category' => (new CategoryModel())->delete($id),
                'product' => (new ProductModel())->delete($id),
                'order' => (new OrderModel())->delete($id),
                'review' => (new ReviewModel())->delete($id),
                default => null,
            };

            if ($type === 'order') {
                $_SESSION['success'] = 'Đã xóa đơn hàng #' . $id . '.';
            } elseif (in_array($type, ['user', 'category', 'product', 'review'], true)) {
                $_SESSION['success'] = 'Đã xóa bản ghi.';
            }
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không xóa được: ' . $e->getMessage();
        }

        $back = match ($type) {
            'user' => 'admin/users',
            'category' => 'admin/categories',
            'product' => 'admin/products',
            'order' => 'admin/orders',
            'review' => 'admin/reviews',
            default => 'admin/dashboard',
        };
        redirect($back);
    }
}

