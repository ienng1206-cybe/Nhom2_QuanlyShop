<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-4">
                <h1 class="mb-2">Đánh giá sản phẩm</h1>
                <p class="text-muted">Xem tất cả các đánh giá từ khách hàng</p>
            </div>

            <?php if (empty($reviews)): ?>
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle me-2"></i>Chưa có đánh giá nào. Hãy mua sản phẩm và chia sẻ trải nghiệm của bạn!
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($reviews as $review): ?>
                        <div class="col-12">
                            <div class="card review-card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="card-title mb-1">
                                                <a href="<?= BASE_URL ?>?action=product/detail&id=<?= (int) ($review['product_id'] ?? 0) ?>" class="text-decoration-none">
                                                    <?= htmlspecialchars($review['product_name'] ?? 'Sản phẩm') ?>
                                                </a>
                                            </h5>
                                            <p class="text-muted small mb-0">
                                                Bởi <strong><?= htmlspecialchars($review['user_name'] ?? 'Người dùng') ?></strong>
                                                <?php if (!empty($review['created_at'])): ?>
                                                    · <span><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <div>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-star-fill me-1"></i><?= (int) ($review['rating'] ?? 5) ?>/5
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-text">
                                        <p class="mb-0"><?= nl2br(htmlspecialchars($review['comment'] ?? '')) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .review-card {
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .review-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
