<div class="col-12">
    <div class="search-panel mb-4">
        <form class="row g-2 align-items-center" method="get" action="<?= BASE_URL ?>">
            <input type="hidden" name="action" value="product/index">
            <div class="col-md-4">
                <input class="form-control" type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Tìm kiếm sản phẩm...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">Tìm kiếm</button>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="price_range" onchange="this.form.submit()">
                    <option value="" <?= empty($priceRange) ? 'selected' : '' ?>>Lọc theo giá</option>
                    <option value="under_1" <?= ($priceRange === 'under_1') ? 'selected' : '' ?>>Dưới 1 triệu</option>
                    <option value="under_3" <?= ($priceRange === 'under_3') ? 'selected' : '' ?>>Dưới 3 triệu</option>
                    <option value="under_5" <?= ($priceRange === 'under_5') ? 'selected' : '' ?>>Dưới 5 triệu</option>
                    <option value="over_5" <?= ($priceRange === 'over_5') ? 'selected' : '' ?>>Trên 5 triệu</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="sort" onchange="this.form.submit()">
                    <option value="" <?= empty($sort) ? 'selected' : '' ?>>Sắp xếp sản phẩm</option>
                    <option value="price_asc" <?= ($sort === 'price_asc') ? 'selected' : '' ?>>Giá thấp đến cao</option>
                    <option value="price_desc" <?= ($sort === 'price_desc') ? 'selected' : '' ?>>Giá cao đến thấp</option>
                </select>
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
