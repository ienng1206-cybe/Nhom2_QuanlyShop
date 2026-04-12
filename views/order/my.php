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
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= (int) $order['id'] ?></td>
                        <td><?= number_format((float) $order['total_amount']) ?> đ</td>
                        <td>
                            <span class="badge rounded-pill bg-light text-dark border"><?= htmlspecialchars(order_status_label($order['status'])) ?></span>
                        </td>
                        <td class="small"><?= htmlspecialchars($order['created_at']) ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>?action=order/detail&id=<?= (int) $order['id'] ?>">Chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>
