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

    /** Tránh vỡ trang admin khi CSDL thiếu bảng (vd. reviews chưa tạo). */
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

        $editProductId = (int) ($_GET['edit_product_id'] ?? 0);
        $editingProduct = null;
        if ($editProductId > 0) {
            $editingProduct = (new ProductModel())->find($editProductId);
        }

        $this->renderAdminPage('admin/dashboard', [
            'title' => 'Bảng điều khiển',
            'users' => $this->adminAll(UserModel::class),
            'categories' => $this->adminAll(CategoryModel::class),
            'products' => $this->adminAll(ProductModel::class),
            'orders' => $this->adminAll(OrderModel::class),
            'reviews' => $reviews,
            'admin_reviews_missing' => $admin_reviews_missing,
            'editingProduct' => $editingProduct,
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
                    $_SESSION['success'] = 'Đã thêm danh mục «' . $name . '». Bạn có thể chọn danh mục này khi thêm sản phẩm.';
                } else {
                    $_SESSION['error'] = 'Không thêm được danh mục. Có thể trùng mã (code) hoặc lỗi CSDL — đổi mã khác hoặc chạy migrate_categories_code.sql.';
                }
            }
        }
        redirect('admin/dashboard');
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
                    $_SESSION['error'] = 'Lỗi khi thêm sản phẩm. Kiểm tra danh mục có tồn tại và bảng products (import schema.sql hoặc migrate).';
                }
            }
        }
        redirect('admin/dashboard');
    }

    public function updateProduct()
    {
        require_admin();
        if ($this->requestMethod() !== 'POST') {
            redirect('admin/dashboard');
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
            $_SESSION[$ok ? 'success' : 'error'] = $ok
                ? 'Đã cập nhật sản phẩm #' . $id . '.'
                : 'Không thể cập nhật sản phẩm.';
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Lỗi khi cập nhật sản phẩm.';
        }

        redirect('admin/dashboard');
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
        redirect('admin/dashboard');
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
            $pdo = db_connect();
            
            // Xóa các liên kết trước khi xóa dữ liệu chính
            if ($type === 'order') {
                // Xóa order_items liên kết đến đơn hàng này
                $pdo->prepare('DELETE FROM order_items WHERE order_id = :id')->execute(['id' => $id]);
                // Xóa shipping liên kết
                $pdo->prepare('DELETE FROM shipping WHERE order_id = :id')->execute(['id' => $id]);
                // Xóa payments liên kết
                $pdo->prepare('DELETE FROM payments WHERE order_id = :id')->execute(['id' => $id]);
            }
            
            if ($type === 'product') {
                // Xóa order_items liên kết đến sản phẩm này
                $pdo->prepare('DELETE FROM order_items WHERE product_id = :id')->execute(['id' => $id]);
                // Xóa cart_items liên kết đến sản phẩm này
                $pdo->prepare('DELETE FROM cart_items WHERE product_id = :id')->execute(['id' => $id]);
                // Xóa review liên kết
                $pdo->prepare('DELETE FROM reviews WHERE product_id = :id')->execute(['id' => $id]);
                // Xóa wishlist liên kết
                $pdo->prepare('DELETE FROM wishlist WHERE product_id = :id')->execute(['id' => $id]);
            }
            
            // Nếu xóa danh mục, xóa tất cả sản phẩm trong danh mục trước
            if ($type === 'category') {
                $products = (new ProductModel())->all();
                foreach ($products as $p) {
                    if ((int) $p['category_id'] === $id) {
                        $productId = (int) $p['id'];
                        // Xóa các liên kết của sản phẩm
                        $pdo->prepare('DELETE FROM order_items WHERE product_id = :id')->execute(['id' => $productId]);
                        $pdo->prepare('DELETE FROM cart_items WHERE product_id = :id')->execute(['id' => $productId]);
                        $pdo->prepare('DELETE FROM reviews WHERE product_id = :id')->execute(['id' => $productId]);
                        $pdo->prepare('DELETE FROM wishlist WHERE product_id = :id')->execute(['id' => $productId]);
                        // Sau đó xóa sản phẩm
                        (new ProductModel())->delete($productId);
                    }
                }
            }
            
            $success = match ($type) {
                'user' => (new UserModel())->delete($id),
                'category' => (new CategoryModel())->delete($id),
                'product' => (new ProductModel())->delete($id),
                'order' => (new OrderModel())->delete($id),
                'review' => (new ReviewModel())->delete($id),
                default => false,
            };
            
            if ($success) {
                if ($type === 'order') {
                    $_SESSION['success'] = 'Đã xóa đơn hàng #' . $id . ' cùng order_items, shipping, payments.';
                } elseif ($type === 'category') {
                    $_SESSION['success'] = 'Đã xóa danh mục cùng tất cả sản phẩm liên kết.';
                } elseif ($type === 'product') {
                    $_SESSION['success'] = 'Đã xóa sản phẩm cùng dữ liệu liên kết (order_items, giỏ hàng, đánh giá, yêu thích).';
                } elseif (in_array($type, ['user', 'review'], true)) {
                    $_SESSION['success'] = 'Đã xóa bản ghi.';
                }
            } else {
                $_SESSION['error'] = 'Không xóa được. Kiểm tra xem bản ghi có được tham chiếu bởi bản ghi khác không.';
            }
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không xóa được (lỗi CSDL: ' . $e->getMessage() . ').';
        }

        redirect('admin/dashboard');
    }

    public function users()
    {
        require_admin();
        $userModel = new UserModel();
        $users = $userModel->all('id DESC');

        $this->renderAdminPage('admin/users', [
            'title' => 'Quản lý tài khoản',
            'users' => $users,
        ]);
    }

    public function updateUser()
    {
        require_admin();
        if ($this->requestMethod() !== 'POST') {
            redirect('admin/users');
        }

        $userId = (int) ($_POST['user_id'] ?? 0);
        $role = $_POST['role'] ?? 'client';
        $action = $_POST['action'] ?? '';

        if ($userId <= 0) {
            $_SESSION['error'] = 'ID người dùng không hợp lệ.';
            redirect('admin/users');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy người dùng.';
            redirect('admin/users');
        }

        try {
            if ($action === 'update_role') {
                if ($userModel->updateRole($userId, $role)) {
                    $_SESSION['success'] = 'Đã cập nhật vai trò cho người dùng #' . $userId . '.';
                } else {
                    $_SESSION['error'] = 'Không thể cập nhật vai trò.';
                }
            } elseif ($action === 'toggle_lock') {
                if ($userModel->toggleLock($userId)) {
                    $newStatus = ($user['is_locked'] ?? 0) ? 0 : 1;
                    $msg = $newStatus ? 'Đã khóa' : 'Đã mở khóa';
                    $_SESSION['success'] = $msg . ' tài khoản người dùng #' . $userId . '.';
                } else {
                    $_SESSION['error'] = 'Không thể cập nhật trạng thái khóa.';
                }
            }
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        redirect('admin/users');
    }

    public function updateCategory()
    {
        require_admin();
        if ($this->requestMethod() !== 'POST') {
            redirect('admin/dashboard');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $code = trim($_POST['code'] ?? '');

        if ($id <= 0 || $name === '') {
            $_SESSION['error'] = 'Dữ liệu danh mục không hợp lệ.';
        } else {
            try {
                $ok = (new CategoryModel())->updateById($id, $name, $code !== '' ? $code : null);
                $_SESSION[$ok ? 'success' : 'error'] = $ok
                    ? 'Đã cập nhật danh mục #' . $id . '.'
                    : 'Không thể cập nhật danh mục.';
            } catch (Throwable $e) {
                $_SESSION['error'] = 'Lỗi khi cập nhật danh mục.';
            }
        }

        redirect('admin/dashboard');
    }
}
