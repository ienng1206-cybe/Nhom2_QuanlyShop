-- Thêm cột image vào bảng reviews nếu chưa có
ALTER TABLE reviews ADD COLUMN image VARCHAR(255) NULL DEFAULT NULL AFTER comment;
