<?php
/**
 * Trang debug để kiểm tra dữ liệu order
 * Truy cập: localhost/New%20folder/Nhom2_QuanlyShop/debug_order.php?order_id=4
 */

require 'configs/env.php';
require 'configs/database.php';

$orderId = (int) ($_GET['order_id'] ?? 0);

if ($orderId <= 0) {
    echo "Vui lòng cung cấp order_id";
    exit;
}

$pdo = db_connect();

// Kiểm tra order
echo "<h2>Order #$orderId</h2>";
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
echo "<pre>";
echo "Order data:\n";
var_dump($order);
echo "</pre>";

// Kiểm tra order_items
echo "<h2>Order Items</h2>";
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$orderId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
echo "Order items:\n";
var_dump($items);
echo "</pre>";

if (empty($items)) {
    echo "<h3 style='color:red;'>⚠️ Không có order_items cho order này!</h3>";
    echo "<p>Có thể order này được tạo mà không lưu sản phẩm, hoặc dữ liệu bị xóa.</p>";
}

// Tính tổng tiền từ order_items
if (!empty($items)) {
    $total = 0;
    foreach ($items as $item) {
        $total += (float) $item['price'] * (int) $item['quantity'];
    }
    echo "<h3>Tổng tiền tính từ order_items: " . number_format($total) . " đ</h3>";
}
?>
