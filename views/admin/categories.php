<div class="col-12 admin-page">
    <div class="admin-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="admin-hero-label mb-1">Danh mục</p>
                <h2 class="admin-hero-title mb-0">Quản lý danh mục</h2>
                <p class="admin-hero-lead mb-0 mt-2">Tạo nhóm sản phẩm để phân loại trong cửa hàng.</p>
            </div>
            <a class="btn btn-outline-light admin-hero-btn" href="<?= BASE_URL ?>?action=admin/dashboard">← Về tổng quan</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Thêm danh mục</h3>
                  
                </div>
                <form class="admin-form" method="post" action="<?= BASE_URL ?>?action=admin/categories">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục</label>
                        <input class="form-control" type="text" name="name" placeholder="Ví dụ: Giày chạy bộ" required maxlength="100">
                    </div>
                 
                    <button class="btn btn-primary" type="submit">Thêm danh mục</button>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Danh sách danh mục</h3>
                    <p class="admin-card-desc">Xóa khi không còn dùng</p>
                </div>

                <?php if (empty($categories)): ?>
                    <p class="text-muted mb-0">Chưa có danh mục nào.</p>
                <?php else: ?>
                    <div class="app-table-wrap admin-table-scroll">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th style="width:90px;">ID</th>
                                    <th>Tên</th>
                                    <th style="width:220px;">Mã</th>
                                    <th style="width:140px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $c): ?>
                                    <tr>
                                        <td class="fw-semibold"><?= (int) $c['id'] ?></td>
                                        <td><?= htmlspecialchars((string) ($c['name'] ?? '')) ?></td>
                                        <td class="text-secondary"><?= htmlspecialchars((string) ($c['code'] ?? '')) ?></td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-danger"
                                                href="<?= BASE_URL ?>?action=admin/delete&type=category&id=<?= (int) $c['id'] ?>"
                                                onclick="return confirm('Xóa danh mục này?');">Xóa</a>
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
