<div class="col-12 admin-page">
    <div class="admin-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="admin-hero-label mb-1">Đơn hàng #<?= (int) ($order['id'] ?? 0) ?></p>
                <h2 class="admin-hero-title mb-0">Chi tiết đơn hàng</h2>
                <p class="admin-hero-lead mb-0 mt-2">Thông tin người đặt, giao hàng và danh sách sản phẩm trong đơn.</p>
            </div>
            <a class="btn btn-outline-light admin-hero-btn" href="<?= BASE_URL ?>?action=admin/orders">← Quay lại danh sách</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="admin-card">
                <h3 class="admin-card-title mb-3">Thông tin người đặt</h3>
                <div class="small text-secondary mb-1">Tài khoản</div>
                <div class="fw-semibold"><?= htmlspecialchars((string) ($order['user_name'] ?? '')) ?></div>
                <div class="text-secondary mb-3"><?= htmlspecialchars((string) ($order['user_email'] ?? '')) ?></div>

                <div class="small text-secondary mb-1">Người nhận</div>
                <div class="fw-semibold"><?= htmlspecialchars((string) ($order['ship_recipient_name'] ?? $order['user_name'] ?? '')) ?></div>
                <div class="text-secondary"><?= htmlspecialchars((string) ($order['ship_recipient_email'] ?? $order['user_email'] ?? '')) ?></div>
                <div class="mt-2"><?= htmlspecialchars((string) ($order['ship_phone'] ?? '')) ?></div>
                <div class="text-secondary"><?= nl2br(htmlspecialchars((string) ($order['ship_address'] ?? ''))) ?></div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="admin-card">
                <h3 class="admin-card-title mb-3">Sản phẩm trong đơn</h3>
                <div class="app-table-wrap admin-table-scroll">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width:80px;">Ảnh</th>
                                <th>Sản phẩm</th>
                                <th style="width:120px;">SL</th>
                                <th style="width:160px;">Đơn giá</th>
                                <th style="width:170px;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (($items ?? []) as $it): ?>
                                <?php $q = (int) ($it['quantity'] ?? 0); ?>
                                <?php $price = (float) ($it['price'] ?? 0); ?>
                                <tr>
                                    <td>
                                        <?php $img = product_image_url((string) ($it['image'] ?? '')); ?>
                                        <?php if ($img !== ''): ?>
                                            <img src="<?= htmlspecialchars($img) ?>" alt="" class="admin-product-thumb">
                                        <?php else: ?>
                                            <span class="text-secondary small">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars((string) ($it['name'] ?? '')) ?></td>
                                    <td><?= $q ?></td>
                                    <td><?= number_format($price) ?> đ</td>
                                    <td class="fw-semibold"><?= number_format($price * $q) ?> đ</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-3 fw-semibold fs-5">
                    Tổng đơn: <?= number_format(order_total_amount($order)) ?> đ
                </div>
            </div>
        </div>
    </div>
</div>
