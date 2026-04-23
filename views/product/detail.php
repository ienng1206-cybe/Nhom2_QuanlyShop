<div class="col-lg-8">
    <?php
    $detailImg = product_image_url($product['image'] ?? '');
    ?>
    <?php if ($detailImg !== ''): ?>
        <div class="mb-3 rounded overflow-hidden" style="box-shadow: 0 8px 24px rgba(15, 23, 42, 0.1); max-height: 200px;">
            <img class="w-100 h-100" src="<?= htmlspecialchars($detailImg) ?>" alt="" style="max-height: 200px; object-fit: cover; display: block;">
        </div>
    <?php endif; ?>
    
    <h2 class="fw-bold mb-2" style="font-size: 1.8rem; color: #0f172a;"><?= htmlspecialchars($product['name']) ?></h2>
    <p class="text-muted mb-3"><span class="badge bg-light text-dark" style="padding: 0.4rem 0.8rem;">📁 <?= htmlspecialchars($product['category_name'] ?? 'Chưa phân loại') ?></span></p>
    
    <?php if ($product['description']): ?>
        <p class="text-muted mb-3" style="line-height: 1.6; font-size: 0.95rem;"><?= nl2br(htmlspecialchars($product['description'] ?? '')) ?></p>
    <?php endif; ?>
    
    <div class="mb-4 p-3 rounded" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-left: 4px solid #2563eb;">
        <p class="text-muted small mb-1" style="text-transform: uppercase; font-weight: 600; letter-spacing: 0.03em;">Giá bán</p>
        <p class="fw-bold mb-0" style="font-size: 1.8rem; color: #2563eb;">
            <?= number_format((float) $product['price']) ?><span style="font-size: 1rem; margin-left: 0.5rem; color: #64748b;">VND</span>
        </p>
    </div>

    <?php if (current_user()): ?>
        <form method="post" action="<?= BASE_URL ?>?action=cart/add" class="mb-4">
            <div class="d-flex gap-2 align-items-end">
                <div>
                    <label class="form-label small fw-600 mb-2">Số lượng</label>
                    <input type="number" class="form-control" name="qty" value="1" min="1" max="<?= max(1, (int) $product['stock']) ?>" style="width: 80px; padding: 0.5rem 0.75rem;" <?= (int) $product['stock'] < 1 ? 'disabled' : '' ?>>
                </div>
                <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                <button class="btn btn-success" type="submit" style="padding: 0.5rem 2rem; font-weight: 600;" <?= (int) $product['stock'] < 1 ? 'disabled' : '' ?>>🛒 Thêm vào giỏ</button>
            </div>
        </form>
        <?php if ((int) $product['stock'] < 1): ?>
            <div class="alert alert-danger mb-4" style="border-radius: 10px; border: none; padding: 0.75rem 1rem;">
                <small><strong>⚠️ Sản phẩm hiện hết hàng</strong></small>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info mb-4" style="border-radius: 10px; border: none; padding: 0.75rem 1rem;">
            <small><a href="<?= BASE_URL ?>?action=auth/login" class="fw-bold text-decoration-none">Đăng nhập</a> để thêm sản phẩm vào giỏ hàng</small>
        </div>
    <?php endif; ?>

    <hr class="my-4" style="border-color: #e2e8f0;">

    <h5 class="fw-bold mb-2" style="font-size: 1.1rem; color: #0f172a;">⭐ Đánh giá sản phẩm</h5>
    <p class="text-muted small mb-3" style="line-height: 1.5;">
        Đánh giá chỉ được gửi sau khi đơn hàng đã giao. Vào chi tiết đơn hàng để đánh giá các sản phẩm đã mua.
    </p>

    <?php if (empty($reviews)): ?>
        <p class="text-muted text-center py-3" style="font-size: 0.9rem;">Chưa có đánh giá nào</p>
    <?php else: ?>
        <div style="max-height: 300px; overflow-y: auto;">
            <?php foreach ($reviews as $review): ?>
                <div class="mb-2" style="padding: 0.75rem; background: #f8fafc; border-left: 3px solid #2563eb; border-radius: 6px;">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <strong style="color: #0f172a; font-size: 0.95rem;"><?= htmlspecialchars($review['user_name']) ?></strong>
                        <span class="badge bg-warning text-dark" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">⭐ <?= (int) $review['rating'] ?>/5</span>
                    </div>
                    <p class="text-muted small mb-0" style="line-height: 1.5;"><?= htmlspecialchars($review['comment']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
