<div class="col-12 admin-page">
    <div class="admin-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="admin-hero-label mb-1">Sản phẩm</p>
                <h2 class="admin-hero-title mb-0"><?= !empty($editingProduct) ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới' ?></h2>
                <p class="admin-hero-lead mb-0 mt-2"><?= !empty($editingProduct) ? 'Cập nhật thông tin sản phẩm' : 'Tạo một sản phẩm mới và thêm vào danh sách' ?></p>
            </div>
            <a class="btn btn-outline-light admin-hero-btn" href="<?= BASE_URL ?>?action=admin/products">← Danh sách sản phẩm</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Thông tin sản phẩm</h3>
                    <p class="admin-card-desc">Nhập đầy đủ thông tin cơ bản</p>
                </div>
                <form class="admin-form" method="post" action="<?= BASE_URL ?>?action=<?= !empty($editingProduct) ? 'admin/product-update' : 'admin/product-create' ?>">
                    <?php if (!empty($editingProduct)): ?>
                        <input type="hidden" name="id" value="<?= (int) $editingProduct['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label text-muted small">ID Sản phẩm</label>
                            <div class="form-control-plaintext">#<?= (int) $editingProduct['id'] ?></div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg" name="category_id" required>
                            <option value="">— Chọn danh mục —</option>
                            <?php foreach (($categories ?? []) as $c): ?>
                                <option value="<?= (int) $c['id'] ?>" <?= !empty($editingProduct) && (int) ($editingProduct['category_id'] ?? 0) === (int) $c['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars((string) ($c['name'] ?? '')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($categories)): ?>
                            <div class="form-text text-warning">⚠️ Chưa có danh mục. <a href="<?= BASE_URL ?>?action=admin/categories">Tạo danh mục trước</a></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input class="form-control form-control-lg" type="text" name="name" placeholder="Ví dụ: Giày Nike Air Max" required maxlength="150" value="<?= htmlspecialchars((string) ((!empty($editingProduct) ? $editingProduct['name'] : '') ?? '')) ?>">
                        <div class="form-text text-secondary">Tên hiển thị trên cửa hàng</div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Giá bán <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <input class="form-control" type="number" name="price" step="1000" min="0" placeholder="0" required value="<?= htmlspecialchars((string) ((!empty($editingProduct) ? $editingProduct['price'] : '') ?? '0')) ?>">
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tồn kho <span class="text-danger">*</span></label>
                            <input class="form-control form-control-lg" type="number" name="stock" min="0" placeholder="0" required value="<?= htmlspecialchars((string) ((!empty($editingProduct) ? $editingProduct['stock'] : '') ?? '0')) ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link ảnh <span class="text-muted fw-normal">(tùy chọn)</span></label>
                        <input class="form-control form-control-lg" type="text" name="image" placeholder="https://example.com/image.jpg" value="<?= htmlspecialchars((string) ((!empty($editingProduct) ? $editingProduct['image'] : '') ?? '')) ?>">
                        <div class="form-text text-secondary">Đường dẫn URL ảnh sản phẩm</div>
                        <?php if (!empty($editingProduct) && !empty($editingProduct['image'])): ?>
                            <div class="mt-2">
                                <img src="<?= htmlspecialchars($editingProduct['image']) ?>" alt="Product" style="max-width: 200px; max-height: 200px; border-radius: 8px;" class="img-thumbnail">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Mô tả sản phẩm <span class="text-muted fw-normal">(tùy chọn)</span></label>
                        <textarea class="form-control" name="description" rows="5" placeholder="Nhập mô tả chi tiết về sản phẩm..."><?= htmlspecialchars((string) ((!empty($editingProduct) ? $editingProduct['description'] : '') ?? '')) ?></textarea>
                        <div class="form-text text-secondary">Mô tả chi tiết giúp khách hàng hiểu rõ hơn về sản phẩm</div>
                    </div>

                    <div class="d-flex gap-3 flex-wrap">
                        <button class="btn btn-success btn-lg" type="submit">
                            <?= !empty($editingProduct) ? '💾 Lưu thay đổi' : '✚ Thêm sản phẩm' ?>
                        </button>
                        <a class="btn btn-outline-secondary btn-lg" href="<?= BASE_URL ?>?action=admin/products">Bỏ qua</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card bg-secondary-subtle border-secondary">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Hướng dẫn</h3>
                </div>
                <div class="small text-secondary">
                    <div class="mb-3">
                        <strong class="d-block mb-1">📋 Danh mục</strong>
                        <span>Chọn nhóm sản phẩm để phân loại</span>
                    </div>
                    <div class="mb-3">
                        <strong class="d-block mb-1">💰 Giá bán</strong>
                        <span>Nhập giá bán cho khách hàng</span>
                    </div>
                    <div class="mb-3">
                        <strong class="d-block mb-1">📦 Tồn kho</strong>
                        <span>Số lượng sản phẩm có sẵn</span>
                    </div>
                    <div class="mb-3">
                        <strong class="d-block mb-1">🖼️ Hình ảnh</strong>
                        <span>Đường dẫn URL hình ảnh sản phẩm</span>
                    </div>
                    <div>
                        <strong class="d-block mb-1">📝 Mô tả</strong>
                        <span>Mô tả chi tiết sản phẩm cho khách hàng</span>
                    </div>
                </div>
            </div>

            <div class="admin-card mt-4">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Thông tin</h3>
                </div>
                <div class="small text-secondary">
                    <?php if (!empty($editingProduct)): ?>
                        <div class="mb-2"><strong>ID:</strong> #<?= (int) $editingProduct['id'] ?></div>
                        <div class="mb-2"><strong>Tạo:</strong> <?= !empty($editingProduct['created_at']) ? date('d/m/Y H:i', strtotime($editingProduct['created_at'])) : 'N/A' ?></div>
                        <div><strong>Cập nhật:</strong> <?= !empty($editingProduct['updated_at']) ? date('d/m/Y H:i', strtotime($editingProduct['updated_at'])) : 'N/A' ?></div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Điền đầy đủ thông tin sản phẩm ở bên trái.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
