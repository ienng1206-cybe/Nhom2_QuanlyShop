<div class="col-12 admin-page">
    <div class="admin-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <p class="admin-hero-label mb-1">Quản trị cửa hàng</p>
                <h2 class="admin-hero-title mb-0">Quản lý tài khoản</h2>
                <p class="admin-hero-lead mb-0 mt-2">Thay đổi vai trò, khóa tài khoản người dùng.</p>
            </div>
            <a class="btn btn-light admin-hero-btn" href="<?= BASE_URL ?>?action=admin/dashboard">← Bảng điều khiển</a>
        </div>
    </div>

    <?php if (empty($users)): ?>
        <div class="alert alert-info" role="alert">
            Chưa có người dùng nào trong hệ thống.
        </div>
    <?php else: ?>
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Danh sách người dùng</h3>
                <p class="admin-card-desc">Quản lý vai trò và trạng thái tài khoản</p>
            </div>
            <div class="app-table-wrap admin-table-scroll">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th style="min-width: 300px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="fw-semibold"><?= (int) $u['id'] ?></td>
                                <td><?= htmlspecialchars($u['name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                                <td>
                                    <span class="badge <?= (($u['role'] ?? 'client') === 'admin' ? 'bg-danger' : 'bg-secondary') ?>">
                                        <?= (($u['role'] ?? 'client') === 'admin' ? 'Admin' : 'Khách hàng') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= (($u['is_locked'] ?? 0) ? 'bg-warning' : 'bg-success') ?>">
                                        <?= (($u['is_locked'] ?? 0) ? 'Bị khóa' : 'Hoạt động') ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <form method="post" action="<?= BASE_URL ?>?action=admin/update-user" class="d-inline">
                                            <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                                            <input type="hidden" name="action" value="update_role">
                                            <select name="role" class="form-select form-select-sm d-inline-block" style="width: auto;">
                                                <option value="client" <?= (($u['role'] ?? 'client') === 'client' ? 'selected' : '') ?>>Khách hàng</option>
                                                <option value="admin" <?= (($u['role'] ?? 'client') === 'admin' ? 'selected' : '') ?>>Admin</option>
                                            </select>
                                            <button class="btn btn-sm btn-primary" type="submit">Cập nhật</button>
                                        </form>
                                        <form method="post" action="<?= BASE_URL ?>?action=admin/update-user" class="d-inline">
                                            <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                                            <input type="hidden" name="action" value="toggle_lock">
                                            <button class="btn btn-sm <?= (($u['is_locked'] ?? 0) ? 'btn-warning' : 'btn-outline-warning') ?>" type="submit">
                                                <?= (($u['is_locked'] ?? 0) ? 'Mở khóa' : 'Khóa') ?>
                                            </button>
                                        </form>
                                        <a class="btn btn-sm btn-outline-danger" href="<?= BASE_URL ?>?action=admin/delete&type=user&id=<?= (int) $u['id'] ?>" onclick="return confirm('Xóa người dùng này?');">
                                            Xóa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
