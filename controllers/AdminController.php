<?php

class AdminController extends BaseController
{
    public function dashboard()
    {
        require_admin();

        $this->render('admin/dashboard', [
            'title' => 'Quản trị cửa hàng',
            'view' => 'admin/dashboard',
            'users' => (new UserModel())->all(),
            'categories' => (new CategoryModel())->all(),
            'products' => (new ProductModel())->all(),
            'orders' => (new OrderModel())->all(),
            'reviews' => (new ReviewModel())->all(),
        ]);
    }

    public function categories()
    {
        require_admin();
        if ($this->requestMethod() === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if ($name !== '') {
                (new CategoryModel())->create($name);
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
            if ($data['name'] !== '' && $data['category_id'] > 0) {
                (new ProductModel())->create($data);
            }
        }
        redirect('admin/dashboard');
    }

    public function updateOrderStatus()
    {
        require_admin();
        $id = (int) ($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? 'pending';
        if ($id > 0) {
            (new OrderModel())->updateStatus($id, $status);
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

        match ($type) {
            'user' => (new UserModel())->delete($id),
            'category' => (new CategoryModel())->delete($id),
            'product' => (new ProductModel())->delete($id),
            'order' => (new OrderModel())->delete($id),
            'review' => (new ReviewModel())->delete($id),
            default => null,
        };

        redirect('admin/dashboard');
    }
}
