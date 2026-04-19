-- Hỗ trợ CSDL cũ để phần đặt hàng / cập nhật / xóa đơn chạy ổn định.
-- Chạy từng lệnh phù hợp với DB của bạn trong phpMyAdmin.

USE nhom2_quanlyshop;

-- 1) orders.total -> orders.total_amount (nếu DB cũ dùng tên total)
-- ALTER TABLE orders CHANGE total total_amount DECIMAL(12,2) NOT NULL DEFAULT 0;

-- 2) order_items.qty -> order_items.quantity (nếu DB cũ dùng qty)
-- ALTER TABLE order_items CHANGE qty quantity INT NOT NULL;

-- 3) shipping thêm cột status nếu thiếu (tùy chọn)
-- ALTER TABLE shipping ADD COLUMN status VARCHAR(50) NULL AFTER phone;
