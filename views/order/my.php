<div class="col-12">
    <?php if (empty($orders)): ?>
        <p class="empty-hint mb-0">Bạn chưa có đơn hàng nào. <a href="<?= BASE_URL ?>?action=product/index">Mua sắm ngay</a></p>
    <?php else: ?>
        <div class="app-table-wrap">
        <table class="table table-striped table-bordered mb-0 align-middle">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th style="width:220px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= (int) $order['id'] ?></td>
                            <td><?= number_format(order_total_amount($order)) ?> đ</td>
                        <td>
                            <span class="badge rounded-pill bg-light text-dark border"><?= htmlspecialchars(order_status_label($order['status'])) ?></span>
                        </td>
                        <td class="small"><?= htmlspecialchars($order['created_at']) ?></td>
                        <td class="d-flex gap-2 flex-wrap">
                            <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>?action=order/detail&id=<?= (int) $order['id'] ?>">Chi tiết</a>
                            <?php if (($order['status'] ?? '') === 'pending'): ?>
                                <form method="post" action="<?= BASE_URL ?>?action=order/cancel" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?');">
                                    <input type="hidden" name="id" value="<?= (int) $order['id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hủy đơn</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>
