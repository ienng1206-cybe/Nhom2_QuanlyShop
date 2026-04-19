<?php

class AuthController extends BaseController
{
    public function login()
    {
        if ($this->requestMethod() === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $userModel = new UserModel();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Kiểm tra tài khoản có bị khóa không
                if (!empty($user['is_locked'])) {
                    $_SESSION['error'] = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.';
                    redirect('auth/login');
                }

                $_SESSION['user'] = $user;
                redirect(is_admin() ? 'admin/dashboard' : '/');
            }

            $_SESSION['error'] = 'Email hoặc mật khẩu không đúng.';
            redirect('auth/login');
        }

        $this->render('auth/login', [
            'title' => 'Đăng nhập',
            'view' => 'auth/login',
        ]);
    }

    public function register()
    {
        if ($this->requestMethod() === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($name === '' || $email === '' || $password === '') {
                $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin.';
                redirect('auth/register');
            }

            $userModel = new UserModel();
            if ($userModel->findByEmail($email)) {
                $_SESSION['error'] = 'Email đã tồn tại.';
                redirect('auth/register');
            }

            $userModel->create($name, $email, $password, 'client');
            $_SESSION['success'] = 'Đăng ký thành công. Vui lòng đăng nhập.';
            redirect('auth/login');
        }

        $this->render('auth/register', [
            'title' => 'Đăng ký',
            'view' => 'auth/register',
        ]);
    }

    public function logout()
    {
        unset($_SESSION['user']);
        redirect('/');
    }
}
