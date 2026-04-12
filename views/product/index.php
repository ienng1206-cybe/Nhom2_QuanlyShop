<div class="col-12">
    <div class="search-panel mb-4">
        <form class="row g-2 align-items-stretch" method="get" action="<?= BASE_URL ?>">
            <input type="hidden" name="action" value="product/index">
            <div class="col-md-8">
                <input class="form-control" type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Tìm kiếm sản phẩm...">
            </div>
            <div class="col-md-4 col-lg-2">
                <button class="btn btn-primary w-100 h-100" type="submit">Tìm kiếm</button>
            </div>
        </form>
    </div>
</div>

<?php foreach ($products as $product): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <?php
            $listImg = product_image_url($product['image'] ?? '');
            ?>
            <?php if ($listImg !== ''): ?>
                <img class="card-img-top object-fit-cover" src="<?= htmlspecialchars($listImg) ?>" alt="" style="height:160px;">
            <?php else: ?>
                <div class="product-thumb">Ảnh sản phẩm</div>
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                <p class="text-muted mb-1 small"><?= htmlspecialchars($product['category_name'] ?? 'Chưa phân loại') ?></p>
                <p class="fw-bold text-primary mb-2 mt-auto"><?= number_format((float) $product['price']) ?> đ</p>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <a class="btn btn-outline-primary btn-sm" href="<?= BASE_URL ?>?action=product/detail&id=<?= $product['id'] ?>">Xem chi tiết</a>
                </div>
                <?php require PATH_VIEW . 'partials/cart_quick_add.php'; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
