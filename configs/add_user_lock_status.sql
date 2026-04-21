-- Thêm cột is_locked vào bảng users để hỗ trợ khóa tài khoản
ALTER TABLE users ADD COLUMN is_locked TINYINT DEFAULT 0 AFTER role;
