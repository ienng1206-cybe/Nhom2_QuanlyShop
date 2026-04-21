<div class="col-12 admin-page">
    <div class="admin-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="admin-hero-label mb-1">Người dùng</p>
                <h2 class="admin-hero-title mb-0">Quản lý người dùng</h2>
                <p class="admin-hero-lead mb-0 mt-2">Danh sách tài khoản trong hệ thống.</p>
            </div>
            <a class="btn btn-outline-light admin-hero-btn" href="<?= BASE_URL ?>?action=admin/dashboard">← Về tổng quan</a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Danh sách người dùng</h3>
            <p class="admin-card-desc">Xóa khi cần (cẩn thận với tài khoản admin)</p>
        </div>

        <?php if (empty($users)): ?>
            <p class="text-muted mb-0">Chưa có người dùng nào.</p>
        <?php else: ?>
            <div class="app-table-wrap admin-table-scroll">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width:90px;">ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th style="width:140px;">Vai trò</th>
                            <th style="width:140px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="fw-semibold"><?= (int) ($u['id'] ?? 0) ?></td>
                                <td><?= htmlspecialchars((string) ($u['name'] ?? '')) ?></td>
                                <td class="text-secondary"><?= htmlspecialchars((string) ($u['email'] ?? '')) ?></td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis"><?= htmlspecialchars((string) ($u['role'] ?? '')) ?></span>
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-danger"
                                        href="<?= BASE_URL ?>?action=admin/delete&type=user&id=<?= (int) ($u['id'] ?? 0) ?>"
                                        onclick="return confirm('Xóa người dùng này?');">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

