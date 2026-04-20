<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');


define('BASE_URL', 'http://localhost/D%e1%bb%b1%20%c3%a1n%201/Nhom2_QuanlyShop/');

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
define('DB_PASSWORD', '');
define('DB_NAME', 'nhom2_quanlyshop');
define('DB_CHARSET', 'utf8mb4');

define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
