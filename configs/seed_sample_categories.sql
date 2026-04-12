-- Thêm nhanh 2 danh mục mẫu (chạy một lần trong phpMyAdmin nếu bảng categories đang trống).
-- Nếu đã có cột `code` và trùng mã, bỏ qua hoặc đổi mã trong câu lệnh.

USE nhom2_quanlyshop;

INSERT INTO categories (name, code) VALUES ('Giày chạy bộ', 'RUN');
INSERT INTO categories (name, code) VALUES ('Giày thể thao đa năng', 'SPORT');

-- Nếu bảng chưa có cột code, dùng hai dòng sau thay cho trên:
-- INSERT INTO categories (name) VALUES ('Giày chạy bộ');
-- INSERT INTO categories (name) VALUES ('Giày thể thao đa năng');
