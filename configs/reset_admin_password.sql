-- Đặt lại mật khẩu admin thành: admin123
-- Chạy trong phpMyAdmin (chọn đúng database).

UPDATE users
SET password = '$2y$10$JBd7GPfjSGa8wroIgNG4t.8sHL3D96E8b4HyDvC3799H96BhtmzeW'
WHERE email = 'admin@gmail.com' AND role = 'admin';
