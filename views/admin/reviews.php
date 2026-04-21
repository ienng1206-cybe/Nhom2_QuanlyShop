<div class="col-12 admin-page">
    <?php if (!empty($admin_reviews_missing)): ?>
        <div class="alert alert-warning border-0 shadow-sm mb-4" role="alert">
            Chưa có bảng <code>reviews</code> (hoặc lỗi truy vấn). Trong phpMyAdmin hãy chạy file <code>configs/migrate_reviews_table.sql</code> để tạo bảng.
        </div>
    <?php endif; ?>

    <div class="admin-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="admin-hero-label mb-1">Đánh giá</p>
                <h2 class="admin-hero-title mb-0">Quản lý đánh giá</h2>
                <p class="admin-hero-lead mb-0 mt-2">Xem và xóa đánh giá không phù hợp.</p>
            </div>
            <a class="btn btn-outline-light admin-hero-btn" href="<?= BASE_URL ?>?action=admin/dashboard">← Về tổng quan</a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Danh sách đánh giá</h3>
            <p class="admin-card-desc">Hiển thị dữ liệu theo bảng `reviews`</p>
        </div>

        <?php if (empty($reviews)): ?>
            <p class="text-muted mb-0">Chưa có đánh giá nào.</p>
        <?php else: ?>
            <div class="app-table-wrap admin-table-scroll">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width:90px;">ID</th>
                            <th style="width:120px;">SP</th>
                            <th style="width:120px;">User</th>
                            <th style="width:120px;">Rating</th>
                            <th>Bình luận</th>
                            <th style="width:180px;">Ngày</th>
                            <th style="width:140px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $r): ?>
                            <tr>
                                <td class="fw-semibold"><?= (int) ($r['id'] ?? 0) ?></td>
                                <td>#<?= (int) ($r['product_id'] ?? 0) ?></td>
                                <td>#<?= (int) ($r['user_id'] ?? 0) ?></td>
                                <td><?= (int) ($r['rating'] ?? 0) ?>/5</td>
                                <td><?= htmlspecialchars((string) ($r['comment'] ?? '')) ?></td>
                                <td class="text-secondary"><?= htmlspecialchars((string) ($r['created_at'] ?? '')) ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-danger"
                                        href="<?= BASE_URL ?>?action=admin/delete&type=review&id=<?= (int) ($r['id'] ?? 0) ?>"
                                        onclick="return confirm('Xóa đánh giá này?');">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
