<div class="col-12">
    <div class="hero-banner mb-4">
        <div class="hero-inner">
            <h2>Giày thể thao — phong cách &amp; bền bỉ</h2>
            <p>Hệ thống shop uy tín, giao diện mới dễ xem — khám phá bộ sưu tập và đặt hàng nhanh chóng.</p>
            <a class="btn btn-light" href="<?= BASE_URL ?>?action=product/index">Xem tất cả sản phẩm</a>
        </div>
    </div>
</div>

<?php foreach ($products as $product): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <?php
            $homeImg = product_image_url($product['image'] ?? '');
            ?>
            <?php if ($homeImg !== ''): ?>
                <img class="card-img-top object-fit-cover" src="<?= htmlspecialchars($homeImg) ?>" alt="" style="height:160px;">
            <?php else: ?>
                <div class="product-thumb">Ảnh sản phẩm</div>
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                <p class="mb-1 text-muted small"><?= htmlspecialchars($product['category_name'] ?? 'Chưa phân loại') ?></p>
                <p class="card-text text-muted small flex-grow-1"><?= htmlspecialchars(substr($product['description'] ?? '', 0, 120)) ?><?= strlen($product['description'] ?? '') > 120 ? '…' : '' ?></p>
                <p class="fw-bold text-primary mb-2"><?= number_format((float) $product['price']) ?> đ</p>
                <div class="d-flex flex-wrap gap-2 mt-auto align-items-center">
                    <a class="btn btn-outline-primary btn-sm" href="<?= BASE_URL ?>?action=product/detail&id=<?= $product['id'] ?>">Chi tiết</a>
                </div>
                <?php require PATH_VIEW . 'partials/cart_quick_add.php'; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
