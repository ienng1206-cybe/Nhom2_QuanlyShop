<div class="col-12">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4">
        <div>
            <p class="text-muted small mb-1">Mã đơn #<?= (int) $order['id'] ?></p>
            <p class="mb-0"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2"><?= htmlspecialchars(order_status_label($order['status'])) ?></span></p>
        </div>
        <div class="text-md-end">
            <p class="small text-muted mb-0">Đặt lúc</p>
            <p class="mb-0"><?= htmlspecialchars($order['created_at']) ?></p>
        </div>
    </div>

    <?php if ($shipping): ?>
        <div class="search-panel mb-4">
            <h3 class="h6 fw-bold mb-3">Giao hàng</h3>
            <p class="mb-1"><strong>Điện thoại:</strong> <?= htmlspecialchars($shipping['phone']) ?></p>
            <p class="mb-0"><strong>Địa chỉ:</strong> <?= nl2br(htmlspecialchars($shipping['address'])) ?></p>
            <?php if (!empty($shipping['status'])): ?>
                <p class="small text-muted mt-2 mb-0">Trạng thái giao: <?= htmlspecialchars($shipping['status']) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($payment)): ?>
        <div class="search-panel mb-4">
            <h3 class="h6 fw-bold mb-3">Thanh toán</h3>
            <p class="mb-1"><strong>Phương thức:</strong> <?= htmlspecialchars(payment_method_label($payment['method'])) ?></p>
            <p class="mb-0"><strong>Trạng thái:</strong> <span class="badge bg-<?= $payment['status'] === 'completed' ? 'success' : 'warning' ?>-subtle text-<?= $payment['status'] === 'completed' ? 'success' : 'warning' ?>"><?= htmlspecialchars(payment_status_label($payment['status'])) ?></span></p>
            <?php if (!empty($payment['paid_at'])): ?>
                <p class="small text-muted mt-2 mb-0">Thanh toán lúc: <?= htmlspecialchars($payment['paid_at']) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <h3 class="h6 fw-bold section-title" style="margin-top:0;">Sản phẩm</h3>
    <div class="app-table-wrap mb-4">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th style="width:100px;">SL</th>
                    <th style="width:120px;">Đơn giá</th>
                    <th style="width:120px;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $row): ?>
                    <?php $sub = (float) $row['price'] * (int) $row['quantity']; ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= (int) $row['quantity'] ?></td>
                        <td><?= number_format((float) $row['price']) ?> đ</td>
                        <td class="fw-semibold"><?= number_format($sub) ?> đ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <p class="fw-bold fs-4 text-end">Tổng thanh toán: <?= number_format(order_total_amount($order)) ?> đ</p>

    <a class="btn btn-outline-primary" href="<?= BASE_URL ?>?action=order/my">← Danh sách đơn hàng</a>
</div>
