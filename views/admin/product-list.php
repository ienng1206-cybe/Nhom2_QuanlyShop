<div class="col-12 admin-page">
    <div class="admin-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="admin-hero-label mb-1">Sản phẩm</p>
                <h2 class="admin-hero-title mb-0">Danh sách sản phẩm</h2>
                <p class="admin-hero-lead mb-0 mt-2">Quản lý toàn bộ sản phẩm trong cửa hàng</p>
            </div>
            <a class="btn btn-success admin-hero-btn" href="<?= BASE_URL ?>?action=admin/product-add">✚ Thêm sản phẩm mới</a>
        </div>
    </div>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <strong>✓ Thành công!</strong> <?= htmlspecialchars($successMessage) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <strong>✕ Lỗi!</strong> <?= htmlspecialchars($errorMessage) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Tất cả sản phẩm (<?= count($products ?? []) ?>)</h3>
                    <p class="admin-card-desc">Sửa, xóa hoặc xem chi tiết</p>
                </div>

                <?php if (empty($products)): ?>
                    <div class="text-center py-5 text-muted">
                        <div class="fs-1 mb-3">📦</div>
                        <p class="mb-2">Chưa có sản phẩm nào</p>
                        <a class="btn btn-success" href="<?= BASE_URL ?>?action=admin/product-add">Thêm sản phẩm đầu tiên</a>
                    </div>
                <?php else: ?>
                    <div class="app-table-wrap admin-table-scroll">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th style="width:80px;">ID</th>
                                    <th>Tên sản phẩm</th>
                                    <th style="width:100px;">Danh mục</th>
                                    <th style="width:120px;">Giá</th>
                                    <th style="width:100px;">Tồn kho</th>
                                    <th style="width:200px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $p): ?>
                                    <tr>
                                        <td class="fw-semibold text-primary">#<?= (int) $p['id'] ?></td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars((string) ($p['name'] ?? '')) ?></div>
                                            <?php if (!empty($p['image'])): ?>
                                                <small class="text-muted d-block mt-1">
                                                    🖼️ Có hình ảnh
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                $categoryId = (int) ($p['category_id'] ?? 0);
                                                $categoryName = '';
                                                foreach (($categories ?? []) as $c) {
                                                    if ((int) $c['id'] === $categoryId) {
                                                        $categoryName = $c['name'];
                                                        break;
                                                    }
                                                }
                                            ?>
                                            <span class="badge bg-info text-dark"><?= htmlspecialchars($categoryName ?: 'N/A') ?></span>
                                        </td>
                                        <td>
                                            <strong><?= number_format((float) ($p['price'] ?? 0), 0, ',', '.') ?></strong>đ
                                        </td>
                                        <td>
                                            <?php $stock = (int) ($p['stock'] ?? 0); ?>
                                            <span class="badge <?= $stock > 10 ? 'bg-success' : ($stock > 0 ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                                <?= $stock ?> cái
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>?action=admin/product-edit&id=<?= (int) $p['id'] ?>">
                                                ✎ Sửa
                                            </a>
                                            <a class="btn btn-sm btn-outline-danger" href="<?= BASE_URL ?>?action=admin/delete&type=product&id=<?= (int) $p['id'] ?>" onclick="return confirm('Xóa sản phẩm này khỏi hệ thống?');">
                                                🗑️ Xóa
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
