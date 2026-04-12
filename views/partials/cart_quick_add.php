<?php
/** @var array $product */
$stock = (int) ($product['stock'] ?? 0);
?>
<div class="mt-2">
    <?php if (current_user()): ?>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <form method="post" action="<?= BASE_URL ?>?action=cart/add" class="d-inline">
                <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                <input type="hidden" name="qty" value="1">
                <button type="submit" class="btn btn-success btn-sm"<?= $stock < 1 ? ' disabled' : '' ?>>Thêm vào giỏ</button>
            </form>
            <?php if ($stock < 1): ?>
                <span class="small text-danger">Hết hàng</span>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p class="small text-muted mb-0"><a href="<?= BASE_URL ?>?action=auth/login">Đăng nhập</a> để thêm vào giỏ</p>
    <?php endif; ?>
</div>
