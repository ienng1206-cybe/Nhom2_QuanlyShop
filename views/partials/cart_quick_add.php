<?php
// Partial: thêm nhanh vào giỏ từ danh sách sản phẩm.
// Biến đầu vào: $product (array)
$pid = (int) ($product['id'] ?? 0);
$stock = (int) ($product['stock'] ?? 0);
?>

<?php if ($pid > 0): ?>
    <?php if (current_user()): ?>
        <form method="post" action="<?= BASE_URL ?>?action=cart/add" class="d-flex gap-2 align-items-center flex-wrap mt-2">
            <input type="hidden" name="product_id" value="<?= $pid ?>">
            <input type="hidden" name="qty" value="1">
            <input type="hidden" name="back" value="product/index">
            <button class="btn btn-success btn-sm" type="submit" <?= $stock < 1 ? 'disabled' : '' ?>>
                <?= $stock < 1 ? 'Hết hàng' : 'Thêm vào giỏ' ?>
            </button>
        </form>
    <?php else: ?>
        <div class="mt-2">
            <a class="btn btn-outline-success btn-sm" href="<?= BASE_URL ?>?action=auth/login">Đăng nhập để mua</a>
        </div>
    <?php endif; ?>
<?php endif; ?>

