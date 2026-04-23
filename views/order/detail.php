<div class="col-12">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4">
        <div>
            <p class="text-muted small mb-1">Mã đơn #<?= (int) $order['id'] ?></p>
            <p class="mb-0"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2"><?= htmlspecialchars(order_status_label($order['status'], $order['created_at'])) ?></span></p>
        </div>
        <div class="text-md-end">
            <p class="small text-muted mb-0">Đặt lúc</p>
            <p class="mb-0"><?= htmlspecialchars($order['created_at']) ?></p>
        </div>
    </div>

    <?php if ($shipping): ?>
        <div class="search-panel mb-4">
            <h3 class="h6 fw-bold mb-3">Giao hàng</h3>
            <p class="mb-1"><strong>Điện thoại:</strong> <?= htmlspecialchars($shipping['phone']) ?></p>
            <p class="mb-0"><strong>Địa chỉ:</strong> <?= nl2br(htmlspecialchars($shipping['address'])) ?></p>
            <?php if (!empty($shipping['status'])): ?>
                <p class="small text-muted mt-2 mb-0">Trạng thái giao: <?= htmlspecialchars($shipping['status']) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <h3 class="h6 fw-bold section-title" style="margin-top:0;">Sản phẩm</h3>
    <div class="app-table-wrap mb-4">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th style="width:100px;">SL</th>
                    <th style="width:120px;">Đơn giá</th>
                    <th style="width:120px;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $row): ?>
                    <?php $sub = (float) $row['price'] * (int) $row['quantity']; ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= (int) $row['quantity'] ?></td>
                        <td><?= number_format((float) $row['price']) ?> đ</td>
                        <td class="fw-semibold"><?= number_format($sub) ?> đ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <p class="fw-bold fs-4 text-end">Tổng thanh toán: <?= number_format(order_total_amount($order)) ?> đ</p>

    <?php
        $hasReviewForms = false;
        $effectiveStatus = order_effective_status($order['status'] ?? '', $order['created_at'] ?? null);
        if ($effectiveStatus === 'delivered') {
            foreach ($items as $item) {
                if (!empty($canReviewProducts[$item['product_id'] ?? 0])) {
                    $hasReviewForms = true;
                    break;
                }
            }
        }
    ?>

    <!-- Phần hiển thị đánh giá từ khách hàng khác -->
    <?php foreach ($items as $item): ?>
        <?php $productId = (int) ($item['product_id'] ?? 0); ?>
        <?php $reviews = $productReviews[$productId] ?? []; ?>
        <?php if (!empty($reviews)): ?>
            <div class="search-panel mb-4">
                <h3 class="h6 fw-bold mb-3">Đánh giá của khách hàng khác - <?= htmlspecialchars($item['name']) ?></h3>
                <div class="row g-3">
                    <?php foreach ($reviews as $review): ?>
                        <div class="col-12">
                            <div class="card review-card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <p class="text-muted small mb-2">
                                                <strong><?= htmlspecialchars($review['user_name'] ?? 'Người dùng') ?></strong>
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
                                        <p class="mb-2"><?= nl2br(htmlspecialchars($review['comment'] ?? '')) ?></p>
                                        <?php if (!empty($review['image'])): ?>
                                            <img src="<?= BASE_URL . $review['image'] ?>" alt="Review image" class="img-fluid rounded mt-2" style="max-width: 300px; max-height: 300px;">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- Phần đánh giá sản phẩm -->
    <div class="search-panel mb-4">
        <h3 class="h6 fw-bold mb-3">Đánh giá sản phẩm</h3>
        
        <p class="text-muted mb-3">Chia sẻ trải nghiệm của bạn về các sản phẩm trong đơn hàng này.</p>

        <?php foreach ($items as $item): ?>
            <?php $productId = (int) ($item['product_id'] ?? 0); ?>
            <?php $canReview = !empty($canReviewProducts[$productId]); ?>
            
            <div class="card p-3 mb-3">
                <h5 class="mb-3"><?= htmlspecialchars($item['name']) ?></h5>
                
                <?php if (!$canReview): ?>
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-check-circle me-2"></i>
                        Bạn đã đánh giá sản phẩm này rồi.
                    </div>
                <?php else: ?>
                    <form method="post" action="<?= BASE_URL ?>?action=review/store" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?= $productId ?>">
                        <div class="mb-3">
                            <label class="form-label">Điểm đánh giá</label>
                            <select name="rating" class="form-select">
                                <?php for ($n = 5; $n >= 1; $n--): ?>
                                    <option value="<?= $n ?>"><?= $n ?> sao</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nhận xét</label>
                            <textarea class="form-control" name="comment" rows="3" required placeholder="Viết nhận xét..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hình ảnh (tùy chọn)</label>
                            <input type="file" class="form-control" name="review_image" accept="image/jpeg,image/png,image/gif" title="Chọn file ảnh (JPG, PNG, GIF - Max 5MB)">
                            <small class="text-muted d-block mt-1">Định dạng: JPG, PNG, GIF | Dung lượng tối đa: 5MB</small>
                        </div>
                        <button class="btn btn-primary" type="submit">Gửi đánh giá</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <a class="btn btn-outline-primary" href="<?= BASE_URL ?>?action=order/my">← Danh sách đơn hàng</a>
</div>
