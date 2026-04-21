-- Fix lỗi thêm danh mục: xóa UNIQUE constraint để cho phép nhiều code = NULL

USE nhom2_quanlyshop;

-- Xóa constraint cũ
ALTER TABLE categories DROP INDEX uq_categories_code;

-- Thêm constraint mới: CHỈ enforce UNIQUE cho các code không NULL
-- Cách này cho phép nhiều hàng có code = NULL
ALTER TABLE categories 
ADD CONSTRAINT uq_categories_code_unique 
UNIQUE KEY uq_categories_code (code);
