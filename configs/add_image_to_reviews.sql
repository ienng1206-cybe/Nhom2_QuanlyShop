-- Thêm cột image vào bảng reviews
ALTER TABLE reviews ADD COLUMN image VARCHAR(255) NULL AFTER comment;
