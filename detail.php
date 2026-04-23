<h5 class="fw-bold mb-2" style="font-size: 1.1rem; color: #0f172a;">⭐ Đánh giá sản phẩm</h5>
<p class="text-muted small mb-3" style="line-height: 1.5;">
    Đánh giá chỉ được gửi sau khi đơn hàng đã giao. Vào chi tiết đơn hàng để đánh giá các sản phẩm đã mua.
</p>

<?php if (empty($reviews)): ?>
    <p class="text-muted text-center py-3" style="font-size: 0.9rem;">Chưa có đánh giá nào</p>
<?php else: ?>
    <div style="max-height: 400px; overflow-y: auto;">
        <?php foreach ($reviews as $review): ?>
            <div class="mb-3" style="padding: 1rem; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong style="color: #0f172a; font-size: 0.95rem;">
                        👤 <?= htmlspecialchars($review['user_name']) ?>
                    </strong>

                    <span class="badge bg-warning text-dark" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;">
                        ⭐ <?= (int)$review['rating'] ?>/5
                    </span>
                </div>

                <!-- Comment -->
                <p class="text-muted small mb-2" style="line-height: 1.6;">
                    <?= nl2br(htmlspecialchars($review['comment'])) ?>
                </p>

                <!-- Image review -->
                <?php if (!empty($review['image'])): ?>
                    <div class="mt-2">
                        <img 
                            src="/DA1/Nhom2_QuanlyShop/uploads/reviews/<?= htmlspecialchars($review['image']) ?>"
                            alt="Ảnh đánh giá"
                            style="
                                width: 100%;
                                max-width: 220px;
                                height: auto;
                                border-radius: 10px;
                                border: 1px solid #e2e8f0;
                                box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                                object-fit: cover;
                            ">
                    </div>
                <?php endif; ?>

                <!-- Date -->
                <?php if (!empty($review['created_at'])): ?>
                    <small class="text-muted d-block mt-2">
                        🕒 <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?>
                    </small>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
