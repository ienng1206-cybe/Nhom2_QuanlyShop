<div class="col-12">
    <?php if (empty($items)): ?>
        <p class="empty-hint mb-0">Giỏ hàng chưa có sản phẩm. <a href="<?= BASE_URL ?>?action=product/index">Xem sản phẩm</a></p>
    <?php else: ?>
        <div class="app-table-wrap mb-3">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($items as $item): ?>
                    <?php $line = $item['price'] * $item['qty']; $total += $line; ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= number_format((float) $item['price']) ?></td>
                        <td><?= (int) $item['qty'] ?></td>
                        <td><?= number_format((float) $line) ?></td>
                        <td>
                            <a class="btn btn-sm btn-danger" href="<?= BASE_URL ?>?action=cart/remove&id=<?= $item['id'] ?>">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <p class="fw-bold fs-5">Tổng tiền: <?= number_format((float) $total) ?> đ</p>
        <a class="btn btn-success" href="<?= BASE_URL ?>?action=order/checkout">Đặt hàng / Thanh toán</a>
    <?php endif; ?>
</div>
