-- Thêm cột mã danh mục (chạy nếu bảng categories chưa có cột code)
ALTER TABLE categories
ADD COLUMN code VARCHAR(40) NULL DEFAULT NULL AFTER name;

ALTER TABLE categories
ADD UNIQUE KEY uq_categories_code (code);
