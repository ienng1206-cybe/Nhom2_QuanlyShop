<?php

/**
 * Một kết nối PDO dùng chung cho mỗi request (singleton).
 */
function db_connect(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_NAME,
        'utf8mb4'
    );

    try {
        $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
    } catch (PDOException $e) {
        $raw = $e->getMessage();
        $msg = htmlspecialchars($raw, ENT_QUOTES, 'UTF-8');

        $hint = ' Kiểm tra file configs/env.php (host, port, user, mật khẩu, tên CSDL).';

        if (stripos($raw, 'could not find driver') !== false || stripos($raw, 'driver not found') !== false) {
            $hint = ' Bật extension pdo_mysql trong php.ini (Laragon: Menu → PHP → php.ini → bỏ dấu ; trước extension=pdo_mysql).';
        } elseif (stripos($raw, '1049') !== false || stripos($raw, 'Unknown database') !== false) {
            $hint = ' CSDL <strong>' . htmlspecialchars(DB_NAME, ENT_QUOTES, 'UTF-8') . '</strong> chưa có. Mở phpMyAdmin/HeidiSQL → Import file <code>configs/schema.sql</code>.';
        } elseif (stripos($raw, '2002') !== false || stripos($raw, 'refused') !== false || stripos($raw, 'timed out') !== false) {
            $hint = ' MySQL chưa chạy hoặc sai port. Bật MySQL (Laragon: Start All). Nếu dùng port khác (ví dụ 3307), sửa DB_PORT trong env.php.';
        } elseif (stripos($raw, '1045') !== false || stripos($raw, 'Access denied') !== false) {
            $hint = ' Sai tên đăng nhập hoặc mật khẩu MySQL. Sửa DB_USERNAME và DB_PASSWORD trong env.php.';
        }

        die(
            '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Lỗi CSDL</title></head><body style="font-family:sans-serif;max-width:640px;margin:2rem auto;padding:0 1rem;">'
            . '<h1>Kết nối cơ sở dữ liệu thất bại</h1>'
            . '<p><strong>Chi tiết:</strong> ' . $msg . '</p>'
            . '<p>' . $hint . '</p>'
            . '</body></html>'
        );
    }

    return $pdo;
}
