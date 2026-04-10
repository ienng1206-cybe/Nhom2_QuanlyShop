<div class="col-12">
    <?php if (empty($items)): ?>
        <p>Giỏ hàng chưa có sản phẩm.</p>
    <?php else: ?>
        <table class="table table-bordered">
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
        <p class="fw-bold">Tổng tiền: <?= number_format((float) $total) ?> VND</p>
        <a class="btn btn-success" href="<?= BASE_URL ?>?action=order/checkout">Đặt hàng / Thanh toán</a>
    <?php endif; ?>
</div>
