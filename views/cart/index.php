<div class="col-12">
    <?php if (empty($items)): ?>
        <p class="empty-hint mb-0">Giỏ hàng chưa có sản phẩm. <a href="<?= BASE_URL ?>?action=product/index">Xem sản phẩm</a></p>
    <?php else: ?>
        <div class="app-table-wrap mb-3">
        <table class="table table-bordered mb-0 align-middle">
            <thead>
                <tr>
                    <th class="d-none d-md-table-cell" style="width:88px;">Ảnh</th>
                    <th>Sản phẩm</th>
                    <th style="width:120px;">Đơn giá</th>
                    <th style="width:140px;">Số lượng</th>
                    <th style="width:120px;">Thành tiền</th>
                    <th style="width:140px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($items as $item): ?>
                    <?php
                    $line = (float) $item['price'] * (int) $item['qty'];
                    $total += $line;
                    $img = product_image_url($item['image'] ?? '');
                    ?>
                    <tr>
                        <td class="d-none d-md-table-cell">
                            <?php if ($img !== ''): ?>
                                <img src="<?= htmlspecialchars($img) ?>" alt="" class="rounded" style="width:72px;height:72px;object-fit:cover;">
                            <?php else: ?>
                                <div class="product-thumb rounded" style="height:72px;font-size:0.65rem;">Ảnh</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($item['name']) ?></strong>
                            <div class="small text-muted">Kho: <?= (int) $item['stock'] ?></div>
                        </td>
                        <td><?= number_format((float) $item['price']) ?> đ</td>
                        <td>
                            <form method="post" action="<?= BASE_URL ?>?action=cart/update" class="d-flex gap-1 align-items-center flex-wrap">
                                <input type="hidden" name="product_id" value="<?= (int) $item['id'] ?>">
                                <input type="number" class="form-control form-control-sm cart-qty-input" name="qty" value="<?= (int) $item['qty'] ?>" min="1" max="<?= (int) $item['stock'] ?>" style="width:4.5rem;">
                            </form>
                        </td>
                        <td class="fw-semibold"><?= number_format($line) ?> đ</td>
                        <td>
                            <div class="d-flex flex-column gap-2">
                                <a class="btn btn-sm btn-outline-success" href="<?= BASE_URL ?>?action=order/checkout&product_id=<?= (int) $item['id'] ?>">Thanh toán</a>
                                <a class="btn btn-sm btn-outline-danger" href="<?= BASE_URL ?>?action=cart/remove&id=<?= (int) $item['id'] ?>" onclick="return confirm('Xóa sản phẩm này khỏi giỏ?');">Xóa</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 flex-wrap">
            <p class="fw-bold fs-5 mb-0">Tổng tiền: <?= number_format((float) $total) ?> đ</p>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-danger" href="<?= BASE_URL ?>?action=cart/clear" onclick="return confirm('Xóa hết sản phẩm trong giỏ?');">Xóa toàn bộ giỏ</a>
                <a class="btn btn-success btn-lg" href="<?= BASE_URL ?>?action=order/checkout">Tiến hành thanh toán</a>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.cart-qty-input').forEach(function (input) {
                    input.addEventListener('change', function () {
                        var form = input.closest('form');
                        if (form) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    <?php endif; ?>
</div>
