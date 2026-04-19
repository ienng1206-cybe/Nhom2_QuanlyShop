<?php
$total = 0;
foreach ($items as $i) {
    $total += (float) $i['price'] * (int) $i['qty'];
}
?>
<div class="col-lg-7">
    <div class="auth-card" style="max-width:none;">
        <h2 class="h5 fw-bold mb-3">Thông tin giao hàng</h2>
        <form method="post" action="<?= BASE_URL ?>?action=order/place">
            <div class="mb-3">
                <label class="form-label">Họ tên</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>" readonly disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="phone" required placeholder="VD: 0901234567" maxlength="20">
            </div>
            <div class="mb-3">
                <label class="form-label">Địa chỉ nhận hàng <span class="text-danger">*</span></label>
                <textarea class="form-control" name="address" rows="3" required placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành"></textarea>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="submit" class="btn btn-success">Xác nhận đặt hàng</button>
                <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>?action=cart/index">Quay lại giỏ hàng</a>
            </div>
        </form>
    </div>
</div>
<div class="col-lg-5">
    <div class="search-panel">
        <h3 class="h6 fw-bold mb-3">Đơn hàng (<?= count($items) ?> sản phẩm)</h3>
        <ul class="list-unstyled mb-0">
            <?php foreach ($items as $item): ?>
                <?php $line = (float) $item['price'] * (int) $item['qty']; ?>
                <li class="d-flex justify-content-between gap-2 py-2 border-bottom">
                    <span class="small"><?= htmlspecialchars($item['name']) ?> × <?= (int) $item['qty'] ?></span>
                    <span class="small text-nowrap fw-semibold"><?= number_format($line) ?> đ</span>
                </li>
            <?php endforeach; ?>
        </ul>
        <p class="fw-bold fs-5 mt-3 mb-0 pt-2 border-top">Tổng: <?= number_format($total) ?> đ</p>
        <p class="small text-muted mt-2 mb-0">Thanh toán khi nhận hàng (COD). Trạng thái đơn sẽ được cập nhật trong mục Đơn hàng.</p>
    </div>
</div>
