<div class="col-12 admin-page">
    <?php if (!empty($admin_reviews_missing)): ?>
        <div class="alert alert-warning border-0 shadow-sm mb-4" role="alert">
            Chưa có bảng <code>reviews</code> (hoặc lỗi truy vấn). Trong phpMyAdmin hãy chạy file <code>configs/migrate_reviews_table.sql</code> để tạo bảng — phần đánh giá sản phẩm mới hoạt động.
        </div>
    <?php endif; ?>

    <div class="admin-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="admin-hero-label mb-1">Bảng điều khiển</p>
                <h2 class="admin-hero-title mb-0">Quản trị cửa hàng</h2>
                <p class="admin-hero-lead mb-0 mt-2">Chọn từng chức năng ở menu để đi theo đúng luồng quản trị.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-light admin-hero-btn" href="<?= BASE_URL ?>?action=admin/orders">Đi tới đơn hàng</a>
                <a class="btn btn-outline-light admin-hero-btn" href="<?= BASE_URL ?>" target="_blank" rel="noopener">Mở cửa hàng ↗</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4" id="admin-stats">
        <div class="col-6 col-xl">
            <div class="admin-stat-card admin-stat-card--users">
                <span class="admin-stat-value"><?= count($users ?? []) ?></span>
                <span class="admin-stat-label">Người dùng</span>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="admin-stat-card admin-stat-card--categories">
                <span class="admin-stat-value"><?= count($categories ?? []) ?></span>
                <span class="admin-stat-label">Danh mục</span>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="admin-stat-card admin-stat-card--products">
                <span class="admin-stat-value"><?= count($products ?? []) ?></span>
                <span class="admin-stat-label">Sản phẩm</span>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="admin-stat-card admin-stat-card--orders">
                <span class="admin-stat-value"><?= count($orders ?? []) ?></span>
                <span class="admin-stat-label">Đơn hàng</span>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="admin-stat-card admin-stat-card--reviews">
                <span class="admin-stat-value"><?= count($reviews ?? []) ?></span>
                <span class="admin-stat-label">Đánh giá</span>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6 col-xl-4">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Danh mục</h3>
                    <p class="admin-card-desc">Tạo / xóa / xem danh mục</p>
                </div>
                <a class="btn btn-primary w-100" href="<?= BASE_URL ?>?action=admin/categories">Quản lý danh mục →</a>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Sản phẩm</h3>
                    <p class="admin-card-desc">Thêm / sửa / xóa sản phẩm</p>
                </div>
                <a class="btn btn-success w-100" href="<?= BASE_URL ?>?action=admin/products">Quản lý sản phẩm →</a>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Đơn hàng</h3>
                    <p class="admin-card-desc">Theo dõi và cập nhật trạng thái</p>
                </div>
                <a class="btn btn-outline-light w-100" href="<?= BASE_URL ?>?action=admin/orders">Quản lý đơn hàng →</a>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Người dùng</h3>
                    <p class="admin-card-desc">Danh sách tài khoản và vai trò</p>
                </div>
                <a class="btn btn-outline-secondary w-100" href="<?= BASE_URL ?>?action=admin/users">Quản lý người dùng →</a>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Đánh giá</h3>
                    <p class="admin-card-desc">Xem / xóa đánh giá sản phẩm</p>
                </div>
                <a class="btn btn-outline-secondary w-100" href="<?= BASE_URL ?>?action=admin/reviews">Quản lý đánh giá →</a>
            </div>
        </div>
    </div>
</div>

