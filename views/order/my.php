<div class="col-12">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= number_format((float) $order['total_amount']) ?> VND</td>
                    <td><?= htmlspecialchars($order['status']) ?></td>
                    <td><?= htmlspecialchars($order['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
