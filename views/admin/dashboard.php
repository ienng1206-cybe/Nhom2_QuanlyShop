<div class="col-12">
    <h4 class="section-title">Quản lý danh mục</h4>
    <form class="row g-2 mb-3" method="post" action="<?= BASE_URL ?>?action=admin/categories">
        <div class="col-md-4"><input class="form-control" name="name" placeholder="Tên danh mục" required></div>
        <div class="col-md-2"><button class="btn btn-primary w-100">Thêm</button></div>
    </form>

    <h4 class="section-title">Quản lý sản phẩm</h4>
    <form class="row g-2 mb-3" method="post" action="<?= BASE_URL ?>?action=admin/products">
        <div class="col-md-2"><input class="form-control" name="category_id" type="number" placeholder="Category ID" required></div>
        <div class="col-md-2"><input class="form-control" name="name" placeholder="Tên sản phẩm" required></div>
        <div class="col-md-2"><input class="form-control" name="price" type="number" step="0.01" placeholder="Giá"></div>
        <div class="col-md-2"><input class="form-control" name="stock" type="number" placeholder="Tồn kho"></div>
        <div class="col-md-2"><input class="form-control" name="image" placeholder="Link ảnh"></div>
        <div class="col-md-2"><button class="btn btn-success w-100">Thêm SP</button></div>
        <div class="col-12"><textarea class="form-control" name="description" placeholder="Mô tả"></textarea></div>
    </form>

    <h4 class="section-title">Quản lý đơn hàng</h4>
    <div class="app-table-wrap mb-4">
    <table class="table table-bordered mb-0">
        <thead><tr><th>ID</th><th>User ID</th><th>Tổng</th><th>Trạng thái</th><th>Cập nhật</th><th>Xóa</th></tr></thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?= $o['id'] ?></td>
                    <td><?= $o['user_id'] ?></td>
                    <td><?= number_format((float) $o['total_amount']) ?></td>
                    <td><?= htmlspecialchars($o['status']) ?></td>
                    <td>
                        <form method="post" action="<?= BASE_URL ?>?action=admin/order-status" class="d-flex gap-1">
                            <input type="hidden" name="id" value="<?= $o['id'] ?>">
                            <select name="status" class="form-select form-select-sm">
                                <option value="pending">pending</option>
                                <option value="processing">processing</option>
                                <option value="completed">completed</option>
                                <option value="cancelled">cancelled</option>
                            </select>
                            <button class="btn btn-sm btn-primary">Lưu</button>
                        </form>
                    </td>
                    <td><a class="btn btn-sm btn-danger" href="<?= BASE_URL ?>?action=admin/delete&type=order&id=<?= $o['id'] ?>">Xóa</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>

    <h4 class="section-title">Thống kê nhanh</h4>
    <ul class="stats-grid">
        <li><strong><?= count($users) ?></strong> Người dùng</li>
        <li><strong><?= count($categories) ?></strong> Danh mục</li>
        <li><strong><?= count($products) ?></strong> Sản phẩm</li>
        <li><strong><?= count($orders) ?></strong> Đơn hàng</li>
        <li><strong><?= count($reviews) ?></strong> Đánh giá</li>
    </ul>
</div>
