<div class="col-12 admin-page">
    <div class="admin-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="admin-hero-label mb-1">Sản phẩm</p>
                <h2 class="admin-hero-title mb-0">Quản lý sản phẩm</h2>
                <p class="admin-hero-lead mb-0 mt-2">Thêm mới, chỉnh sửa thông tin và quản lý tồn kho.</p>
            </div>
            <a class="btn btn-outline-light admin-hero-btn" href="<?= BASE_URL ?>?action=admin/dashboard">← Về tổng quan</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Thêm sản phẩm</h3>
                    <p class="admin-card-desc">Chọn danh mục và nhập thông tin cơ bản</p>
                </div>
                <form class="admin-form" method="post" action="<?= BASE_URL ?>?action=admin/products" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Danh mục</label>
                        <select class="form-select" name="category_id" required>
                            <option value="">— Chọn danh mục —</option>
                            <?php foreach (($categories ?? []) as $c): ?>
                                <option value="<?= (int) $c['id'] ?>"><?= htmlspecialchars((string) ($c['name'] ?? '')) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($categories)): ?>
                            <div class="form-text text-warning">Chưa có danh mục. Hãy tạo danh mục trước.</div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm</label>
                        <input class="form-control" type="text" name="name" required maxlength="150" placeholder="Tên hiển thị">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Giá (đ)</label>
                            <input class="form-control" type="number" name="price" step="0.01" min="0" placeholder="0">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Tồn kho</label>
                            <input class="form-control" type="number" name="stock" min="0" placeholder="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link ảnh <span class="text-muted fw-normal"></span></label>
                        <input class="form-control" type="text" name="image" placeholder="https://... ">
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tải ảnh sản phẩm</label>
                        <input class="form-control" type="file" name="image_file" accept="image/png,image/jpeg,image/webp,image/gif">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Mô tả ngắn"></textarea>
                    </div>
                    <button class="btn btn-success" type="submit">Thêm sản phẩm</button>
                </form>
            </div>

            <?php if (!empty($editingProduct)): ?>
                <div class="admin-card mt-4">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">Sửa sản phẩm #<?= (int) $editingProduct['id'] ?></h3>
                        <p class="admin-card-desc">Cập nhật và lưu thay đổi</p>
                    </div>
                    <form class="admin-form" method="post" action="<?= BASE_URL ?>?action=admin/product-update" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= (int) $editingProduct['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label">Danh mục</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">— Chọn danh mục —</option>
                                <?php foreach (($categories ?? []) as $c): ?>
                                    <option value="<?= (int) $c['id'] ?>" <?= (int) ($editingProduct['category_id'] ?? 0) === (int) $c['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars((string) ($c['name'] ?? '')) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm</label>
                            <input class="form-control" type="text" name="name" maxlength="150" required value="<?= htmlspecialchars((string) ($editingProduct['name'] ?? '')) ?>">
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-sm-6">
                                <label class="form-label">Giá (đ)</label>
                                <input class="form-control" type="number" name="price" step="0.01" min="0" value="<?= htmlspecialchars((string) ($editingProduct['price'] ?? 0)) ?>">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Tồn kho</label>
                                <input class="form-control" type="number" name="stock" min="0" value="<?= htmlspecialchars((string) ($editingProduct['stock'] ?? 0)) ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link ảnh</label>
                            <input class="form-control" type="text" name="image" value="<?= htmlspecialchars((string) ($editingProduct['image'] ?? '')) ?>">
                            <div class="form-text">Có thể nhập link mới hoặc tải ảnh mới từ máy. Giá trị hiện tại: <code><?= htmlspecialchars((string) ($editingProduct['image'] ?? '')) ?></code></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tải ảnh mới</label>
                            <input class="form-control" type="file" name="image_file" accept="image/png,image/jpeg,image/webp,image/gif">
                            <?php $editImg = product_image_url((string) ($editingProduct['image'] ?? '')); ?>
                            <?php if ($editImg !== ''): ?>
                                <img src="<?= htmlspecialchars($editImg) ?>" alt="" class="admin-product-thumb mt-2">
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars((string) ($editingProduct['description'] ?? '')) ?></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Lưu thay đổi</button>
                            <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>?action=admin/products">Bỏ chọn</a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-7">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Danh sách sản phẩm</h3>
                    <p class="admin-card-desc">Sửa nhanh hoặc xóa khi cần</p>
                </div>

                <?php if (empty($products)): ?>
                    <p class="text-muted mb-0">Chưa có sản phẩm nào.</p>
                <?php else: ?>
                    <div class="app-table-wrap admin-table-scroll">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th style="width:90px;">ID</th>
                                    <th style="width:84px;">Ảnh</th>
                                    <th>Tên</th>
                                    <th style="width:140px;">Giá</th>
                                    <th style="width:110px;">Tồn</th>
                                    <th style="width:210px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $p): ?>
                                    <tr>
                                        <td class="fw-semibold"><?= (int) $p['id'] ?></td>
                                        <td>
                                            <?php $thumb = product_image_url((string) ($p['image'] ?? '')); ?>
                                            <?php if ($thumb !== ''): ?>
                                                <img src="<?= htmlspecialchars($thumb) ?>" alt="" class="admin-product-thumb">
                                            <?php else: ?>
                                                <span class="text-secondary small">Chưa có ảnh</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars((string) ($p['name'] ?? '')) ?></td>
                                        <td><?= number_format((float) ($p['price'] ?? 0)) ?> đ</td>
                                        <td><?= (int) ($p['stock'] ?? 0) ?></td>
                                        <td class="text-end d-flex justify-content-end gap-2 flex-wrap">
                                            <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>?action=admin/products&edit_product_id=<?= (int) $p['id'] ?>">Sửa</a>
                                            <a class="btn btn-sm btn-outline-danger" href="<?= BASE_URL ?>?action=admin/delete&type=product&id=<?= (int) $p['id'] ?>" onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
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
