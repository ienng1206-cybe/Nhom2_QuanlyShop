<div class="col-md-8">
    <h3><?= htmlspecialchars($product['name']) ?></h3>
    <p class="text-muted">Danh mục: <?= htmlspecialchars($product['category_name'] ?? 'Chưa phân loại') ?></p>
    <p><?= nl2br(htmlspecialchars($product['description'] ?? '')) ?></p>
    <p class="fw-bold">Giá: <?= number_format((float) $product['price']) ?> VND</p>

    <form method="post" action="<?= BASE_URL ?>?action=cart/add" class="row g-2 mb-4">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <div class="col-md-3">
            <input type="number" class="form-control" name="qty" value="1" min="1">
        </div>
        <div class="col-md-4">
            <button class="btn btn-success">Thêm vào giỏ</button>
        </div>
    </form>

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
        <div class="border p-2 rounded mb-2">
            <strong><?= htmlspecialchars($review['user_name']) ?></strong> - <?= (int) $review['rating'] ?>/5
            <div><?= htmlspecialchars($review['comment']) ?></div>
        </div>
    <?php endforeach; ?>
</div>
