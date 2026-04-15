<div class="col-12">
    <div class="row g-4">
        <!-- Thông tin cá nhân -->
        <div class="col-lg-4">
            <div class="search-panel">
                <h3 class="h6 fw-bold mb-3">Thông tin tài khoản</h3>
                <p class="mb-2"><strong>Tên:</strong> <?= htmlspecialchars($user['name'] ?? 'N/A') ?></p>
                <p class="mb-2"><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? 'N/A') ?></p>
                <p class="mb-0"><strong>Vai trò:</strong> 
                    <?php if ($user['role'] === 'admin'): ?>
                        <span class="badge bg-danger">Quản trị viên</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Khách hàng</span>
                    <?php endif; ?>
                </p>
                <hr>
                <a href="<?= BASE_URL ?>?action=auth/logout" class="btn btn-outline-danger btn-sm w-100">Đăng xuất</a>
            </div>
        </div>

        <!-- Giỏ hàng hiện tại -->
        <div class="col-lg-4">
            <div class="search-panel">
                <h3 class="h6 fw-bold mb-3">Giỏ hàng</h3>
                <?php if ($cartCount > 0): ?>
                    <p class="mb-2"><strong>Số sản phẩm:</strong> <?= $cartCount ?></p>
                    <p class="mb-3"><strong>Tổng tiền:</strong> <span class="text-success fw-bold"><?= number_format($cartTotal) ?> đ</span></p>
                    <div class="d-flex gap-2 flex-column">
                        <a href="<?= BASE_URL ?>?action=cart/index" class="btn btn-success btn-sm">Xem giỏ hàng</a>
                        <a href="<?= BASE_URL ?>?action=order/checkout" class="btn btn-primary btn-sm">Thanh toán ngay</a>
                    </div>
                <?php else: ?>
                    <p class="text-muted small mb-0">Giỏ hàng trống. <a href="<?= BASE_URL ?>?action=product/index">Mua sắm ngay</a></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Thống kê đơn hàng -->
        <div class="col-lg-4">
            <div class="search-panel">
                <h3 class="h6 fw-bold mb-3">Đơn hàng</h3>
                <p class="mb-2"><strong>Tổng đơn:</strong> <?= count($orders) ?></p>
                <?php
                $pending = count(array_filter($orders, fn($o) => $o['status'] === 'pending'));
                $processing = count(array_filter($orders, fn($o) => $o['status'] === 'processing'));
                $completed = count(array_filter($orders, fn($o) => $o['status'] === 'completed'));
                ?>
                <p class="mb-2 small">
                    <span class="badge bg-warning">Chờ xử lý: <?= $pending ?></span>
                    <span class="badge bg-info">Đang xử lý: <?= $processing ?></span>
                </p>
                <p class="mb-3 small">
                    <span class="badge bg-success">Hoàn thành: <?= $completed ?></span>
                </p>
                <a href="<?= BASE_URL ?>?action=order/my" class="btn btn-primary btn-sm w-100">Xem tất cả đơn hàng</a>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- Đơn hàng gần đây -->
        <div class="col-12">
            <div class="search-panel">
                <h3 class="h6 fw-bold mb-3">Đơn hàng gần đây</h3>
                <?php if (count($orders) > 0): ?>
                    <div class="app-table-wrap">
                        <table class="table table-striped table-sm mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                    <th style="width:80px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                                    <tr>
                                        <td>#<?= (int) $order['id'] ?></td>
                                        <td><?= number_format(order_total_amount($order)) ?> đ</td>
                                        <td>
                                            <span class="badge rounded-pill bg-light text-dark border"><?= htmlspecialchars(order_status_label($order['status'])) ?></span>
                                        </td>
                                        <td class="small"><?= htmlspecialchars($order['created_at']) ?></td>
                                        <td>
                                            <a href="<?= BASE_URL ?>?action=order/detail&id=<?= (int) $order['id'] ?>" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (count($orders) > 5): ?>
                        <div class="mt-3 text-center">
                            <a href="<?= BASE_URL ?>?action=order/my" class="btn btn-outline-secondary btn-sm">Xem tất cả đơn hàng</a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-muted small mb-0">Bạn chưa có đơn hàng nào. <a href="<?= BASE_URL ?>?action=product/index">Mua sắm ngay</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
