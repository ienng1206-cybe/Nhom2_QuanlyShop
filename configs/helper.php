<?php

if (!function_exists('debug')) {
    function debug($data)
    {
        echo '<pre>';
        print_r($data);
        die;
    }
}

if (!function_exists('upload_file')) {
    function upload_file($folder, $file)
    {
        $targetFile = $folder . '/' . time() . '-' . $file["name"];

        if (move_uploaded_file($file["tmp_name"], PATH_ASSETS_UPLOADS . $targetFile)) {
            return $targetFile;
        }

        throw new Exception('Upload file không thành công!');
    }
}

if (!function_exists('redirect')) {
    function redirect($action = '/')
    {
        header('Location: ' . BASE_URL . '?action=' . urlencode($action));
        exit;
    }
}

if (!function_exists('current_user')) {
    function current_user()
    {
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('is_admin')) {
    function is_admin()
    {
        return !empty($_SESSION['user']) && ($_SESSION['user']['role'] ?? 'client') === 'admin';
    }
}

if (!function_exists('require_login')) {
    function require_login()
    {
        if (!current_user()) {
            redirect('auth/login');
        }
    }
}

if (!function_exists('require_admin')) {
    function require_admin()
    {
        if (!is_admin()) {
            redirect('auth/login');
        }
    }
}