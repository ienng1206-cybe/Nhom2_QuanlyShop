<?php
/**
 * Script chạy migration fix_foreign_keys.sql
 * Chạy từ terminal: php configs/run_fix_foreign_keys.php
 */

require 'database.php';

try {
    $pdo = db_connect();
    
    // Đọc file SQL
    $sqlFile = __DIR__ . '/fix_foreign_keys.sql';
    $sql = file_get_contents($sqlFile);
    
    // Tách các câu lệnh SQL
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    // Chạy từng câu lệnh
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            echo "Executing: " . substr($statement, 0, 80) . "...\n";
            $pdo->exec($statement);
        }
    }
    
    echo "\n✅ Đã sửa tất cả Foreign Key constraints!\n";
    echo "Giờ bạn có thể xóa tài khoản bình thường.\n";
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}
