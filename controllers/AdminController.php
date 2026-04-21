<?php

class AdminController extends BaseController
{
    private function uploadProductImage(array $file): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Tải ảnh lên thất bại.');
        }
        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0 || $size > 4 * 1024 * 1024) {
            throw new RuntimeException('Ảnh phải nhỏ hơn 4MB.');
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $mime = mime_content_type((string) ($file['tmp_name'] ?? ''));
        $ext = strtolower((string) pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
        $mimeOk = is_string($mime) && in_array($mime, $allowedMimes, true);
        $extOk = in_array($ext, $allowedExts, true);
        if (!$mimeOk && !$extOk) {
            throw new RuntimeException('Chỉ hỗ trợ ảnh JPG, PNG, WEBP, GIF.');
        }

        return upload_file('products', $file);
    }

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
                    $uploadedImage = $this->uploadProductImage($_FILES['image_file'] ?? []);
                    if ($uploadedImage !== null) {
                        $data['image'] = $uploadedImage;
                    }
                    $ok = (new ProductModel())->create($data);
                    $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Đã thêm sản phẩm thành công.' : 'Không thể thêm sản phẩm.';
                } catch (Throwable $e) {
                    $_SESSION['error'] = 'Lỗi khi thêm sản phẩm: ' . $e->getMessage();
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
            $uploadedImage = $this->uploadProductImage($_FILES['image_file'] ?? []);
            if ($uploadedImage !== null) {
                $data['image'] = $uploadedImage;
            }
            $ok = (new ProductModel())->updateById($id, $data);
            $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Đã cập nhật sản phẩm #' . $id . '.' : 'Không thể cập nhật sản phẩm.';
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage();
        }

        redirect('admin/products');
    }

    public function orders()
    {
        require_admin();

        $keyword = trim((string) ($_GET['keyword'] ?? ''));
        $orders = [];
        try {
            $orders = (new OrderModel())->allForAdmin($keyword);
        } catch (Throwable $e) {
            $orders = [];
        }

        $this->renderAdminPage('admin/orders', [
            'title' => 'Đơn hàng',
            'orders' => $orders,
            'keyword' => $keyword,
        ]);
    }

    public function updateOrderStatus()
    {
        require_admin();

        $id = (int) ($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? 'pending';
        $keyword = trim((string) ($_POST['keyword'] ?? ''));
        if ($id > 0) {
            $ok = (new OrderModel())->updateStatus($id, $status);
            $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Đã cập nhật trạng thái đơn hàng #' . $id . '.' : 'Không cập nhật được trạng thái.';
        }

        $next = 'admin/orders';
        if ($keyword !== '') {
            $next .= '&keyword=' . urlencode($keyword);
        }
        redirect($next);
    }

    public function orderDetail()
    {
        require_admin();

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'Mã đơn hàng không hợp lệ.';
            redirect('admin/orders');
        }

        $orderModel = new OrderModel();
        $order = $orderModel->findForAdmin($id);
        if (!$order) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng.';
            redirect('admin/orders');
        }

        $this->renderAdminPage('admin/order_detail', [
            'title' => 'Chi tiết đơn #' . $id,
            'order' => $order,
            'items' => $orderModel->getOrderItems($id),
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
            $_SESSION['error'] = 'Không xóa được.';
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

