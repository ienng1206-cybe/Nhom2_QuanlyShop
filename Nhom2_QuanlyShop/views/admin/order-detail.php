<div class="col-12 admin-page">
    <div class="admin-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="admin-hero-label mb-1">Đơn hàng</p>
                <h2 class="admin-hero-title mb-0">Chi tiết đơn hàng #<?= (int) ($order['id'] ?? 0) ?></h2>
                <p class="admin-hero-lead mb-0 mt-2">Xem thông tin và danh sách sản phẩm trong đơn hàng.</p>
            </div>
            <a class="btn btn-outline-light admin-hero-btn" href="<?= BASE_URL ?>?action=admin/orders">← Danh sách đơn hàng</a>
        </div>
    </div>

    <div class="row gap-4">
        <!-- Thông tin chung -->
        <div class="col-12 col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Thông tin đơn hàng</h3>
                </div>

                <div class="admin-info-group">
                    <div class="admin-info-row">
                        <span class="admin-info-label">Mã đơn:</span>
                        <span class="admin-info-value fw-semibold">#<?= (int) ($order['id'] ?? 0) ?></span>
                    </div>
                    <div class="admin-info-row">
                        <span class="admin-info-label">Ngày đặt:</span>
                        <span class="admin-info-value"><?= !empty($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : '—' ?></span>
                    </div>
                    <div class="admin-info-row">
                        <span class="admin-info-label">Trạng thái:</span>
                        <span class="admin-info-value">
                            <?php $badgeClass = preg_replace('/[^a-z]/', '', (string) ($order['status'] ?? '')); ?>
                            <span class="badge admin-badge-status admin-badge-status--<?= htmlspecialchars($badgeClass) ?>">
                                <?= htmlspecialchars(order_status_label((string) ($order['status'] ?? ''))) ?>
                            </span>
                        </span>
                    </div>
                    <div class="admin-info-row">
                        <span class="admin-info-label">Tổng tiền:</span>
                        <span class="admin-info-value fw-semibold text-success">
                            <?php 
                                $totalAmount = 0;
                                if (!empty($orderItems)) {
                                    // Nếu có order_items, tính từ danh sách sản phẩm
                                    foreach ($orderItems as $item) {
                                        $totalAmount += (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0);
                                    }
                                } else {
                                    // Nếu order_items trống, lấy từ cột total_amount hoặc total
                                    $totalAmount = (float) ($order['total_amount'] ?? $order['total'] ?? 0);
                                }
                                echo number_format($totalAmount);
                            ?> đ
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="col-12 col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Thông tin khách hàng</h3>
                </div>

                <?php if ($user): ?>
                    <div class="admin-info-group">
                        <div class="admin-info-row">
                            <span class="admin-info-label">Tên:</span>
                            <span class="admin-info-value fw-semibold"><?= htmlspecialchars((string) ($user['name'] ?? '')) ?></span>
                        </div>
                        <div class="admin-info-row">
                            <span class="admin-info-label">Email:</span>
                            <span class="admin-info-value"><?= htmlspecialchars((string) ($user['email'] ?? '')) ?></span>
                        </div>
                        <div class="admin-info-row">
                            <span class="admin-info-label">ID:</span>
                            <span class="admin-info-value">#<?= (int) ($user['id'] ?? 0) ?></span>
                        </div>
                        <div class="admin-info-row">
                            <span class="admin-info-label">Vai trò:</span>
                            <span class="admin-info-value">
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                    <?= htmlspecialchars((string) ($user['role'] ?? '')) ?>
                                </span>
                            </span>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Không tìm thấy thông tin khách hàng.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Thông tin giao hàng -->
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Thông tin giao hàng</h3>
                </div>

                <?php if ($shipping): ?>
                    <div class="admin-info-group">
                        <div class="admin-info-row">
                            <span class="admin-info-label">Số điện thoại:</span>
                            <span class="admin-info-value"><?= htmlspecialchars((string) ($shipping['phone'] ?? '—')) ?></span>
                        </div>
                        <div class="admin-info-row">
                            <span class="admin-info-label">Địa chỉ:</span>
                            <span class="admin-info-value"><?= htmlspecialchars((string) ($shipping['address'] ?? '—')) ?></span>
                        </div>
                        <?php if (!empty($shipping['status'])): ?>
                            <div class="admin-info-row">
                                <span class="admin-info-label">Trạng thái giao:</span>
                                <span class="admin-info-value"><?= htmlspecialchars((string) $shipping['status']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Không có thông tin giao hàng.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Danh sách sản phẩm</h3>
                </div>

                <?php if (empty($orderItems)): ?>
                    <div style="padding: 1rem; background-color: rgba(255,193,7,.1); border-left: 3px solid #ffc107; border-radius: 4px;">
                        <p class="mb-0" style="color: #ff9800;">
                            <strong>⚠️ Không có chi tiết sản phẩm</strong><br>
                            Dữ liệu <code>order_items</code> không được lưu, nhưng tổng tiền vẫn được lưu lại (<?= number_format((float) ($order['total_amount'] ?? $order['total'] ?? 0)) ?> đ).
                        </p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th style="width:60px;">STT</th>
                                    <th>Sản phẩm</th>
                                    <th style="width:100px;">Giá</th>
                                    <th style="width:80px;">Số lượng</th>
                                    <th style="width:140px; text-align:right;">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($orderItems as $item): ?>
                                    <tr>
                                        <td class="fw-semibold"><?= $no++ ?></td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars((string) ($item['name'] ?? 'Sản phẩm bị xóa')) ?></div>
                                            <div class="text-secondary small">ID: #<?= (int) ($item['product_id'] ?? 0) ?></div>
                                        </td>
                                        <td><?= number_format((float) ($item['price'] ?? 0)) ?> đ</td>
                                        <td><?= (int) ($item['quantity'] ?? 0) ?></td>
                                        <td style="text-align:right;" class="fw-semibold">
                                            <?= number_format((float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0)) ?> đ
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="table-active">
                                    <td colspan="4" class="text-end fw-semibold">Tổng cộng:</td>
                                    <td style="text-align:right;" class="fw-semibold text-success">
                                        <?php 
                                            $totalAmount = 0;
                                            if (!empty($orderItems)) {
                                                foreach ($orderItems as $item) {
                                                    $totalAmount += (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0);
                                                }
                                            } else {
                                                $totalAmount = (float) ($order['total_amount'] ?? $order['total'] ?? 0);
                                            }
                                            echo number_format($totalAmount);
                                        ?> đ
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.admin-info-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.admin-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.admin-info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.admin-info-label {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.875rem;
    white-space: nowrap;
}

.admin-info-value {
    color: rgba(255, 255, 255, 0.9);
    text-align: right;
}
</style>
