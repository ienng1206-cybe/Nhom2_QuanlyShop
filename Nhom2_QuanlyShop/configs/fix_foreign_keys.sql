-- Fix lỗi xóa dữ liệu: thêm ON DELETE CASCADE vào tất cả Foreign Key

USE nhom2_quanlyshop;

-- 1. Xóa constraint cũ ở order_items (nếu có)
SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE order_items 
  DROP FOREIGN KEY order_items_ibfk_1;
ALTER TABLE order_items 
  ADD CONSTRAINT order_items_ibfk_1 
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE;

ALTER TABLE order_items 
  DROP FOREIGN KEY order_items_ibfk_2;
ALTER TABLE order_items 
  ADD CONSTRAINT order_items_ibfk_2 
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE;

-- 2. Xóa constraint cũ ở shipping
ALTER TABLE shipping 
  DROP FOREIGN KEY shipping_ibfk_1;
ALTER TABLE shipping 
  ADD CONSTRAINT shipping_ibfk_1 
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE;

-- 3. Xóa constraint cũ ở payments
ALTER TABLE payments 
  DROP FOREIGN KEY payments_ibfk_1;
ALTER TABLE payments 
  ADD CONSTRAINT payments_ibfk_1 
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE;

-- 4. Xóa constraint cũ ở cart_items
ALTER TABLE cart_items 
  DROP FOREIGN KEY cart_items_ibfk_1;
ALTER TABLE cart_items 
  ADD CONSTRAINT cart_items_ibfk_1 
  FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE;

ALTER TABLE cart_items 
  DROP FOREIGN KEY cart_items_ibfk_2;
ALTER TABLE cart_items 
  ADD CONSTRAINT cart_items_ibfk_2 
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE;

-- 5. Xóa constraint cũ ở carts
ALTER TABLE carts 
  DROP FOREIGN KEY carts_ibfk_1;
ALTER TABLE carts 
  ADD CONSTRAINT carts_ibfk_1 
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- 6. Xóa constraint cũ ở products
ALTER TABLE products 
  DROP FOREIGN KEY products_ibfk_1;
ALTER TABLE products 
  ADD CONSTRAINT products_ibfk_1 
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE;

-- 7. Xóa constraint cũ ở reviews
ALTER TABLE reviews 
  DROP FOREIGN KEY reviews_ibfk_1;
ALTER TABLE reviews 
  ADD CONSTRAINT reviews_ibfk_1 
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE reviews 
  DROP FOREIGN KEY reviews_ibfk_2;
ALTER TABLE reviews 
  ADD CONSTRAINT reviews_ibfk_2 
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE;

-- 8. Orders giữ nguyên (user_id đã có ON DELETE CASCADE)
ALTER TABLE orders 
  DROP FOREIGN KEY orders_ibfk_1;
ALTER TABLE orders 
  ADD CONSTRAINT orders_ibfk_1 
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- 9. User Sessions (fix constraint để xóa user)
ALTER TABLE user_sessions 
  DROP FOREIGN KEY user_sessions_ibfk_1;
ALTER TABLE user_sessions 
  ADD CONSTRAINT user_sessions_ibfk_1 
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- 10. Wishlist (fix constraint)
ALTER TABLE wishlist 
  DROP FOREIGN KEY wishlist_ibfk_1;
ALTER TABLE wishlist 
  ADD CONSTRAINT wishlist_ibfk_1 
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE wishlist 
  DROP FOREIGN KEY wishlist_ibfk_2;
ALTER TABLE wishlist 
  ADD CONSTRAINT wishlist_ibfk_2 
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE;

-- 11. Admins (fix constraint để xóa user)
ALTER TABLE admins 
  DROP FOREIGN KEY admins_ibfk_1;
ALTER TABLE admins 
  ADD CONSTRAINT admins_ibfk_1 
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS=1;
