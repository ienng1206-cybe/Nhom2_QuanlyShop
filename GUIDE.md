# Hệ Thống Shop Bán Giày - Hướng Dẫn Hoàn Chỉnh

## 📋 Các Tính Năng Chính

### 1. **Giỏ Hàng (Shopping Cart)**
- ✅ Thêm sản phẩm vào giỏ
- ✅ Cập nhật số lượng sản phẩm
- ✅ Xóa sản phẩm khỏi giỏ
- ✅ Xóa toàn bộ giỏ hàng
- ✅ Hiển thị tổng tiền giỏ hàng
- ✅ Kiểm tra số lượng tồn kho

**Routes:**
- `cart/index` - Xem giỏ hàng
- `cart/add` - Thêm sản phẩm
- `cart/update` - Cập nhật số lượng
- `cart/remove` - Xóa 1 sản phẩm
- `cart/clear` - Xóa toàn bộ giỏ

**Controllers:** `CartController.php`
**Models:** `CartModel.php`
**Views:** `views/cart/index.php`

---

### 2. **Đặt Hàng & Thanh Toán**
- ✅ Xem tóm tắt đơn hàng
- ✅ Nhập thông tin giao hàng (số điện thoại, địa chỉ)
- ✅ Chọn phương thức thanh toán
  - Thanh toán khi nhận hàng (COD)
  - Chuyển khoản ngân hàng
  - Thẻ tín dụng/Ghi nợ
- ✅ Tạo đơn hàng từ giỏ hàng
- ✅ Tự động trừ hàng trong kho
- ✅ Tạo bản ghi thanh toán

**Routes:**
- `order/checkout` - Trang thanh toán
- `order/place` - Xác nhận đặt hàng
- `order/detail` - Chi tiết đơn hàng
- `order/my` - Danh sách đơn hàng của tôi

**Controllers:** `OrderController.php`
**Models:** `OrderModel.php`, `PaymentModel.php`
**Views:** 
- `views/order/checkout.php`
- `views/order/detail.php`
- `views/order/my.php`

---

### 3. **Quản Lý Thanh Toán**
- ✅ Tạo bản ghi thanh toán khi đặt hàng
- ✅ Quản lý trạng thái thanh toán (pending, completed, failed, cancelled)
- ✅ Ghi lại thời gian thanh toán
- ✅ Hỗ trợ multiple payment methods
- ✅ Hiển thị thông tin thanh toán trong chi tiết đơn hàng

**Database:** `payments` table
**Model:** `PaymentModel.php`
**Helper Functions:**
- `payment_status_label()` - Nhãn trạng thái
- `payment_method_label()` - Nhãn phương thức

---

### 4. **Giao Hàng**
- ✅ Lưu thông tin giao hàng (địa chỉ, số điện thoại)
- ✅ Quản lý trạng thái giao (pending, shipping, delivered)
- ✅ Hiển thị thông tin giao trong chi tiết đơn

**Database:** `shipping` table
**Model Methods:** 
- `OrderModel::getShipping($orderId)`

---

### 5. **Tài Khoản Cá Nhân (User Profile)**
- ✅ Xem thông tin tài khoản
- ✅ Xem giỏ hàng hiện tại
- ✅ Hiển thị thống kê đơn hàng
- ✅ Xem danh sách đơn hàng gần đây
- ✅ Liên kết nhanh tới các chức năng

**Routes:**
- `user/profile` - Trang tài khoản cá nhân

**Controllers:** `UserController.php`
**Views:** `views/user/profile.php`

---

### 6. **Đánh Giá Sản Phẩm (Reviews)**
- ✅ Gửi đánh giá (1-5 sao)
- ✅ Viết nhận xét về sản phẩm
- ✅ Hiển thị danh sách đánh giá
- ✅ Hiển thị tên người dùng và rating

**Routes:**
- `review/store` - Gửi đánh giá

**Controllers:** `ReviewController.php`
**Models:** `ReviewModel.php`

---

## 🗄️ Cơ Sở Dữ Liệu

### Các Bảng Chính:
```
users          - Thông tin người dùng
admins         - Quản trị viên
products       - Sản phẩm
categories     - Danh mục
carts          - Giỏ hàng
cart_items     - Chi tiết giỏ hàng
orders         - Đơn hàng
order_items    - Chi tiết đơn hàng
payments       - Thanh toán
shipping       - Giao hàng
reviews        - Đánh giá sản phẩm
wishlist       - Sản phẩm yêu thích
product_images - Hình ảnh sản phẩm
```

---

## 🔧 Cài Đặt & Sử Dụng

### 1. Database Setup
```sql
-- In phpMyAdmin/HeidiSQL
-- Import file: configs/schema.sql
```

### 2. Environment Config
File: `configs/env.php`
```php
define('DB_HOST', 'localhost');      // MySQL host
define('DB_PORT', '3306');           // MySQL port
define('DB_USERNAME', 'root');       // MySQL user
define('DB_PASSWORD', '');           // MySQL password (trống cho Laragon)
define('DB_NAME', 'nhom2_quanlyshop'); // Database name
```

### 3. Tài khoản mặc định
```
Email: admin@gmail.com
Password: 123456
Role: Admin
```

---

## 🚀 Luồng Hoạt Động

### Luồng Mua Hàng:
```
1. Người dùng xem sản phẩm (product/index)
2. Xem chi tiết sản phẩm (product/detail)
3. Thêm vào giỏ hàng (cart/add)
4. Xem giỏ hàng (cart/index)
5. Thanh toán (order/checkout)
   - Nhập thông tin giao hàng
   - Chọn phương thức thanh toán
6. Xác nhận đặt hàng (order/place)
   - Tạo đơn hàng
   - Tạo bản ghi thanh toán
   - Tạo bản ghi giao hàng
   - Trừ hàng trong kho
7. Xem chi tiết đơn (order/detail)
8. Quản lý đơn hàng (order/my)
```

### Luồng Đánh Giá:
```
1. Xem chi tiết sản phẩm (product/detail)
2. Gửi đánh giá (review/store)
3. Danh sách đánh giá cập nhật tức thì
```

---

## 📊 API Routes

### Public Routes:
```
GET  /                      - Trang chủ
GET  /product/index        - Danh sách sản phẩm
GET  /product/detail&id=X  - Chi tiết sản phẩm
POST /auth/login           - Đăng nhập
POST /auth/register        - Đăng ký
GET  /auth/logout          - Đăng xuất
```

### User Routes (require login):
```
GET  /user/profile         - Tài khoản cá nhân
GET  /cart/index           - Xem giỏ hàng
POST /cart/add             - Thêm vào giỏ
POST /cart/update          - Cập nhật giỏ
GET  /cart/remove&id=X     - Xóa khỏi giỏ
GET  /cart/clear           - Xóa toàn bộ giỏ
GET  /order/checkout       - Trang thanh toán
POST /order/place          - Xác nhận đặt hàng
GET  /order/detail&id=X    - Chi tiết đơn
GET  /order/my             - Danh sách đơn
POST /review/store         - Gửi đánh giá
```

### Admin Routes (require admin role):
```
GET  /admin/dashboard      - Bảng điều khiển
GET  /admin/categories     - Quản lý danh mục
GET  /admin/products       - Quản lý sản phẩm
POST /admin/order-status   - Cập nhật trạng thái đơn
POST /admin/delete         - Xóa dữ liệu
```

---

## 🛠️ Helper Functions

### Hiển Thị:
```php
number_format($amount)              // Định dạng số tiền (VND)
htmlspecialchars($text)             // Escape HTML
nl2br($text)                        // Xuống dòng HTML
```

### Sản Phẩm:
```php
product_image_url($image)           // URL ảnh sản phẩm
category_option_label($category)    // Nhãn danh mục
```

### Đơn Hàng:
```php
order_total_amount($order)          // Tổng tiền đơn
order_status_label($status)         // Nhãn trạng thái đơn
```

### Thanh Toán:
```php
payment_status_label($status)       // Nhãn trạng thái thanh toán
payment_method_label($method)       // Nhãn phương thức thanh toán
PaymentModel::getPaymentMethods()   // Danh sách phương thức
```

### Xác Thực:
```php
current_user()                      // Lấy người dùng hiện tại
is_admin()                          // Kiểm tra admin
require_login()                     // Yêu cầu đăng nhập
require_admin()                     // Yêu cầu role admin
```

---

## ⚠️ Lỗi Thường Gặp & Cách Sửa

### Lỗi: "Kết nối cơ sở dữ liệu thất bại"
**Nguyên nhân:** Sai thông tin MySQL
**Giải pháp:** 
1. Kiểm tra Laragon đang chạy
2. Sửa `configs/env.php`
3. Thử đổi `DB_HOST` từ `localhost` sang `127.0.0.1`

### Lỗi: "CSDL không tồn tại"
**Nguyên nhân:** Chưa import schema
**Giải pháp:**
1. Mở phpMyAdmin (http://localhost/phpmyadmin)
2. Import `configs/schema.sql`

### Lỗi: "PDO driver not found"
**Nguyên nhân:** Extension pdo_mysql chưa bật
**Giải pháp:**
1. Laragon → Menu → PHP → php.ini
2. Tìm dòng `;extension=pdo_mysql`
3. Bỏ dấu `;` ở đầu
4. Restart Laragon

### Lỗi: "Giỏ hàng trống"
**Nguyên nhân:** Phải đăng nhập để sử dụng
**Giải pháp:** Đăng nhập hoặc đăng ký tài khoản

---

## 📝 Cấu Trúc Thư Mục

```
index.php                   - Entry point
configs/
  ├── env.php             - Cấu hình môi trường
  ├── database.php        - Kết nối DB
  ├── helper.php          - Helper functions
  └── schema.sql          - Database schema

controllers/
  ├── BaseController.php
  ├── CartController.php
  ├── OrderController.php
  ├── UserController.php
  ├── ReviewController.php
  ├── ProductController.php
  └── AuthController.php

models/
  ├── BaseModel.php
  ├── CartModel.php
  ├── OrderModel.php
  ├── PaymentModel.php
  ├── ReviewModel.php
  ├── ProductModel.php
  └── UserModel.php

views/
  ├── main.php            - Layout chính
  ├── cart/               - Giỏ hàng
  ├── order/              - Đơn hàng
  ├── user/               - Tài khoản
  ├── product/            - Sản phẩm
  ├── auth/               - Đăng nhập/ký
  └── admin/              - Quản trị

routes/
  └── index.php           - Định tuyến

assets/
  ├── css/                - Stylesheet
  └── uploads/            - Lưu trữ ảnh
```

---

## 🎯 Tính Năng Sẽ Được Thêm

- [ ] Payment gateway integration (VNPay, Stripe)
- [ ] Wishlist management
- [ ] Discount coupons
- [ ] Order tracking
- [ ] Email notifications
- [ ] Product recommendations
- [ ] Advanced search & filters
- [ ] Product variants (size, color)

---

**Phiên bản:** 1.0
**Cập nhật lần cuối:** Apr 14, 2026
**Ngôn ngữ:** Vietnamese
