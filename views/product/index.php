<div class="col-12 mb-3">
    <form class="row g-2" method="get" action="<?= BASE_URL ?>">
        <input type="hidden" name="action" value="product/index">
        <div class="col-md-8">
            <input class="form-control" type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Tìm kiếm sản phẩm...">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Tìm kiếm</button>
        </div>
    </form>
</div>

<?php foreach ($products as $product): ?>
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <h5><?= htmlspecialchars($product['name']) ?></h5>
                <p class="text-muted mb-1"><?= htmlspecialchars($product['category_name'] ?? 'Chưa phân loại') ?></p>
                <p class="fw-bold"><?= number_format((float) $product['price']) ?> VND</p>
                <a class="btn btn-outline-primary btn-sm" href="<?= BASE_URL ?>?action=product/detail&id=<?= $product['id'] ?>">Xem chi tiết</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
