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

if (!function_exists('product_image_url')) {
    function product_image_url(?string $image): string
    {
        if ($image === null || $image === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $image)) {
            return $image;
        }

        return BASE_ASSETS_UPLOADS . ltrim($image, '/');
    }
}

if (!function_exists('category_option_label')) {
    /** Nhãn trong select danh mục: [mã hoặc #id] + tên */
    function category_option_label(array $c): string
    {
        $name = (string) ($c['name'] ?? '');
        $id = (int) ($c['id'] ?? 0);
        $code = isset($c['code']) ? trim((string) $c['code']) : '';
        $prefix = $code !== '' ? '[' . $code . ']' : '[#' . $id . ']';

        return $prefix . ' ' . $name;
    }
}

if (!function_exists('order_total_amount')) {
    /**
     * Tổng tiền đơn hàng (một số CSDL dùng cột `total` thay cho `total_amount`).
     */
    function order_total_amount(array $order): float
    {
        if (isset($order['total_amount'])) {
            return (float) $order['total_amount'];
        }
        if (isset($order['total'])) {
            return (float) $order['total'];
        }

        return 0.0;
    }
}

if (!function_exists('order_status_label')) {
    function order_status_label(string $status): string
    {
        return match ($status) {
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            default => $status,
        };
    }
}

if (!function_exists('payment_status_label')) {
    function payment_status_label(string $status): string
    {
        return match ($status) {
            'pending' => 'Chờ thanh toán',
            'completed' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
            'cancelled' => 'Đã hủy',
            default => $status,
        };
    }
}

if (!function_exists('payment_method_label')) {
    function payment_method_label(string $method): string
    {
        return match ($method) {
            'COD' => 'Thanh toán khi nhận hàng',
            'Banking' => 'Chuyển khoản ngân hàng',
            'Card' => 'Thẻ tín dụng/Ghi nợ',
            default => $method,
        };
    }
}