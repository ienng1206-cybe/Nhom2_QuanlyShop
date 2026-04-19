<div class="col-lg-8">
    <?php
    $detailImg = product_image_url($product['image'] ?? '');
    ?>
    <?php if ($detailImg !== ''): ?>
        <div class="mb-4 rounded overflow-hidden shadow" style="max-height:320px;">
            <img class="w-100 h-100 object-fit-cover" src="<?= htmlspecialchars($detailImg) ?>" alt="" style="max-height:320px;object-fit:cover;">
        </div>
    <?php endif; ?>
    <h3 class="fw-bold"><?= htmlspecialchars($product['name']) ?></h3>
    <p class="text-muted">Danh mục: <?= htmlspecialchars($product['category_name'] ?? 'Chưa phân loại') ?></p>
    <p><?= nl2br(htmlspecialchars($product['description'] ?? '')) ?></p>
    <p class="fw-bold">Giá: <?= number_format((float) $product['price']) ?> VND</p>

    <?php if (current_user()): ?>
        <form method="post" action="<?= BASE_URL ?>?action=cart/add" class="row g-2 mb-4">
            <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
            <div class="col-md-3">
                <input type="number" class="form-control" name="qty" value="1" min="1" max="<?= max(1, (int) $product['stock']) ?>" <?= (int) $product['stock'] < 1 ? 'disabled' : '' ?>>
            </div>
            <div class="col-md-4">
                <button class="btn btn-success" type="submit" <?= (int) $product['stock'] < 1 ? 'disabled' : '' ?>>Thêm vào giỏ</button>
            </div>
        </form>
        <?php if ((int) $product['stock'] < 1): ?>
            <p class="text-danger small mb-4">Sản phẩm hiện hết hàng.</p>
        <?php endif; ?>
    <?php else: ?>
        <p class="mb-4"><a href="<?= BASE_URL ?>?action=auth/login">Đăng nhập</a> để thêm sản phẩm vào giỏ hàng.</p>
    <?php endif; ?>

    <h5>Đánh giá</h5>
    <?php if (current_user()): ?>
        <form method="post" action="<?= BASE_URL ?>?action=review/store" class="mb-3">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <div class="row g-2">
                <div class="col-md-2">
                    <select class="form-select" name="rating">
                        <option value="5">5 sao</option>
                        <option value="4">4 sao</option>
                        <option value="3">3 sao</option>
                        <option value="2">2 sao</option>
                        <option value="1">1 sao</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <input class="form-control" name="comment" placeholder="Viết nhận xét..." required>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100">Gửi đánh giá</button>
                </div>
            </div>
        </form>
    <?php endif; ?>

    <?php foreach ($reviews as $review): ?>
        <div class="review-item border p-2 rounded mb-2">
            <strong><?= htmlspecialchars($review['user_name']) ?></strong> - <?= (int) $review['rating'] ?>/5
            <div><?= htmlspecialchars($review['comment']) ?></div>
        </div>
    <?php endforeach; ?>
</div>
