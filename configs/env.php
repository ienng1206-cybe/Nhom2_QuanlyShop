<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');


if (!defined('BASE_URL')) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
    $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    if ($basePath === '' || $basePath === '.') {
        $basePath = '';
    }
    define('BASE_URL', $scheme . '://' . $host . $basePath . '/');
}

define('PATH_ROOT', __DIR__ . '/../');
define('PATH_VIEW', PATH_ROOT . 'views/');
define('PATH_VIEW_MAIN', PATH_ROOT . 'views/main.php');
define('BASE_ASSETS_UPLOADS', BASE_URL . 'assets/uploads/');
define('PATH_ASSETS_UPLOADS', PATH_ROOT . 'assets/uploads/');
define('PATH_CONTROLLER', PATH_ROOT . 'controllers/');
define('PATH_MODEL', PATH_ROOT . 'models/');

// Laragon: thường root / mật khẩu trống / port 3306. Nếu lỗi kết nối, thử đổi DB_HOST thành '127.0.0.1' hoặc ngược lại.
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'admin');
define('DB_NAME', 'nhom2_quanlyshop');
define('DB_CHARSET', 'utf8mb4');

define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
