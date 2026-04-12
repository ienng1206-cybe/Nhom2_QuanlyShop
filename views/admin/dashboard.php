<div class="col-12 admin-page">
    <div class="admin-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="admin-hero-label mb-1">Bảng điều khiển</p>
                <h2 class="admin-hero-title mb-0">Quản trị cửa hàng</h2>
                <p class="admin-hero-lead mb-0 mt-2">Thêm danh mục, sản phẩm và theo dõi đơn hàng.</p>
            </div>
            <a class="btn btn-light admin-hero-btn" href="<?= BASE_URL ?>">← Về trang bán hàng</a>
        </div>
    </div>

    <div class="row g-3 mb-4" id="admin-stats">
        <div class="col-6 col-xl">
            <div class="admin-stat-card admin-stat-card--users">
                <span class="admin-stat-value"><?= count($users) ?></span>
                <span class="admin-stat-label">Người dùng</span>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="admin-stat-card admin-stat-card--categories">
                <span class="admin-stat-value"><?= count($categories) ?></span>
                <span class="admin-stat-label">Danh mục</span>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="admin-stat-card admin-stat-card--products">
                <span class="admin-stat-value"><?= count($products) ?></span>
                <span class="admin-stat-label">Sản phẩm</span>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="admin-stat-card admin-stat-card--orders">
                <span class="admin-stat-value"><?= count($orders) ?></span>
                <span class="admin-stat-label">Đơn hàng</span>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="admin-stat-card admin-stat-card--reviews">
                <span class="admin-stat-value"><?= count($reviews) ?></span>
                <span class="admin-stat-label">Đánh giá</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Danh mục</h3>
                    <p class="admin-card-desc">Thêm nhóm sản phẩm mới</p>
                </div>
                <form class="admin-form" method="post" action="<?= BASE_URL ?>?action=admin/categories">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục</label>
                        <input class="form-control" type="text" name="name" placeholder="Ví dụ: Giày chạy bộ" required maxlength="100">
                    </div>
                    <button class="btn btn-primary" type="submit">Thêm danh mục</button>
                </form>
                <?php if (!empty($categories)): ?>
                    <hr class="my-4 admin-divider">
                    <p class="small text-muted fw-semibold text-uppercase mb-2">Danh sách hiện có</p>
                    <ul class="list-group list-group-flush admin-mini-list">
                        <?php foreach ($categories as $c): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-bottom">
                                <span><?= htmlspecialchars($c['name']) ?></span>
                                <a class="btn btn-sm btn-outline-danger" href="<?= BASE_URL ?>?action=admin/delete&type=category&id=<?= (int) $c['id'] ?>" onclick="return confirm('Xóa danh mục này?');">Xóa</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Sản phẩm</h3>
                    <p class="admin-card-desc">Thêm sản phẩm vào danh mục</p>
                </div>
                <form class="admin-form" method="post" action="<?= BASE_URL ?>?action=admin/products">
                    <div class="mb-3">
                        <label class="form-label">Danh mục</label>
                        <select class="form-select" name="category_id" required>
                            <option value="">— Chọn danh mục —</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= (int) $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm</label>
                        <input class="form-control" type="text" name="name" required maxlength="150" placeholder="Tên hiển thị">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Giá (đ)</label>
                            <input class="form-control" type="number" name="price" step="0.01" min="0" placeholder="0">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Tồn kho</label>
                            <input class="form-control" type="number" name="stock" min="0" placeholder="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link ảnh <span class="text-muted fw-normal">(tùy chọn)</span></label>
                        <input class="form-control" type="text" name="image" placeholder="https://... hoặc đường dẫn file upload">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Mô tả ngắn"></textarea>
                    </div>
                    <button class="btn btn-success" type="submit">Thêm sản phẩm</button>
                </form>
            </div>
        </div>
    </div>

    <div class="admin-card mt-4">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Đơn hàng</h3>
            <p class="admin-card-desc">Cập nhật trạng thái xử lý đơn</p>
        </div>
        <?php if (empty($orders)): ?>
            <p class="text-muted mb-0">Chưa có đơn hàng nào.</p>
        <?php else: ?>
            <div class="app-table-wrap admin-table-scroll">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Khách (ID)</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th style="min-width:220px;">Cập nhật</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $o): ?>
                            <?php $rawSt = $o['status']; ?>
                            <tr>
                                <td class="fw-semibold"><?= (int) $o['id'] ?></td>
                                <td><?= (int) $o['user_id'] ?></td>
                                <td><?= number_format((float) $o['total_amount']) ?> đ</td>
                                <td>
                                    <?php $badgeClass = preg_replace('/[^a-z]/', '', (string) $rawSt); ?>
                                    <span class="badge admin-badge-status admin-badge-status--<?= htmlspecialchars($badgeClass) ?>"><?= htmlspecialchars(order_status_label($rawSt)) ?></span>
                                </td>
                                <td>
                                    <form method="post" action="<?= BASE_URL ?>?action=admin/order-status" class="d-flex flex-wrap gap-2 align-items-center">
                                        <input type="hidden" name="id" value="<?= (int) $o['id'] ?>">
                                        <select name="status" class="form-select form-select-sm" style="min-width:9rem;">
                                            <option value="pending" <?= $rawSt === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                                            <option value="processing" <?= $rawSt === 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                                            <option value="completed" <?= $rawSt === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                                            <option value="cancelled" <?= $rawSt === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                        </select>
                                        <button class="btn btn-sm btn-primary" type="submit">Lưu</button>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-danger" href="<?= BASE_URL ?>?action=admin/delete&type=order&id=<?= (int) $o['id'] ?>" onclick="return confirm('Xóa đơn hàng này?');">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($products)): ?>
        <div class="admin-card mt-4">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Sản phẩm trong kho</h3>
                <p class="admin-card-desc">Danh sách nhanh — xóa khi cần</p>
            </div>
            <div class="app-table-wrap admin-table-scroll">
                <table class="table table-sm mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Giá</th>
                            <th>Tồn</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td><?= (int) $p['id'] ?></td>
                                <td><?= htmlspecialchars($p['name']) ?></td>
                                <td><?= number_format((float) $p['price']) ?> đ</td>
                                <td><?= (int) $p['stock'] ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-danger" href="<?= BASE_URL ?>?action=admin/delete&type=product&id=<?= (int) $p['id'] ?>" onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
