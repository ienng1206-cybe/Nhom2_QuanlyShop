<div class="col-12">
    <p class="text-muted">Hệ thống Shop theo mô hình MVC + OOP, bám theo use case Client/Admin.</p>
    <a class="btn btn-primary mb-3" href="<?= BASE_URL ?>?action=product/index">Xem tất cả sản phẩm</a>
</div>

<?php foreach ($products as $product): ?>
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                <p class="mb-1 text-muted"><?= htmlspecialchars($product['category_name'] ?? 'Chưa phân loại') ?></p>
                <p class="card-text"><?= htmlspecialchars(substr($product['description'] ?? '', 0, 120)) ?></p>
                <p class="fw-bold"><?= number_format((float) $product['price']) ?> VND</p>
                <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>?action=product/detail&id=<?= $product['id'] ?>">Chi tiết</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
