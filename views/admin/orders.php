<div class="col-12 admin-page">
    <div class="admin-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="admin-hero-label mb-1">Đơn hàng</p>
                <h2 class="admin-hero-title mb-0">Quản lý đơn hàng</h2>
                <p class="admin-hero-lead mb-0 mt-2">Cập nhật trạng thái xử lý và theo dõi tổng tiền.</p>
            </div>
            <a class="btn btn-outline-light admin-hero-btn" href="<?= BASE_URL ?>?action=admin/dashboard">← Về tổng quan</a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Danh sách đơn hàng</h3>
            <p class="admin-card-desc">Đổi trạng thái để theo dõi tiến độ</p>
        </div>

        <?php if (empty($orders)): ?>
            <p class="text-muted mb-0">Chưa có đơn hàng nào.</p>
        <?php else: ?>
            <div class="app-table-wrap admin-table-scroll">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width:90px;">#</th>
                            <th style="width:220px;">Khách</th>
                            <th style="min-width:260px;">Giao hàng</th>
                            <th style="width:170px;">Tổng tiền</th>
                            <th style="width:160px;">Trạng thái</th>
                            <th style="min-width:220px;">Cập nhật</th>
                            <th style="width:140px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $o): ?>
                            <?php $rawSt = (string) ($o['status'] ?? ''); ?>
                            <tr>
                                <td class="fw-semibold"><?= (int) ($o['id'] ?? 0) ?></td>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars((string) ($o['user_name'] ?? '')) ?></div>
                                    <div class="text-secondary small">
                                        #<?= (int) ($o['user_id'] ?? 0) ?>
                                        <?php if (!empty($o['user_email'])): ?>
                                            · <?= htmlspecialchars((string) $o['user_email']) ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php $shipPhone = trim((string) ($o['ship_phone'] ?? '')); ?>
                                    <?php $shipAddr = trim((string) ($o['ship_address'] ?? '')); ?>
                                    <?php if ($shipPhone !== '' || $shipAddr !== ''): ?>
                                        <div class="fw-semibold"><?= htmlspecialchars($shipPhone) ?></div>
                                        <div class="text-secondary small"><?= htmlspecialchars($shipAddr) ?></div>
                                    <?php else: ?>
                                        <span class="text-secondary small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format(order_total_amount($o)) ?> đ</td>
                                <td>
                                    <?php $badgeClass = preg_replace('/[^a-z]/', '', $rawSt); ?>
                                    <span class="badge admin-badge-status admin-badge-status--<?= htmlspecialchars($badgeClass) ?>">
                                        <?= htmlspecialchars(order_status_label($rawSt)) ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="post" action="<?= BASE_URL ?>?action=admin/order-status" class="d-flex flex-wrap gap-2 align-items-center">
                                        <input type="hidden" name="id" value="<?= (int) ($o['id'] ?? 0) ?>">
                                        <select name="status" class="form-select form-select-sm" style="min-width:9rem;">
                                            <option value="pending" <?= $rawSt === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                                            <option value="processing" <?= $rawSt === 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                                            <option value="completed" <?= $rawSt === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                                            <option value="cancelled" <?= $rawSt === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                        </select>
                                        <button class="btn btn-sm btn-primary" type="submit">Lưu</button>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-danger"
                                        href="<?= BASE_URL ?>?action=admin/delete&type=order&id=<?= (int) ($o['id'] ?? 0) ?>"
                                        onclick="return confirm('Xóa đơn hàng này?');">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
