<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?? 'Shop MVC' ?></title>

    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">Shop MVC</a>
            <div class="navbar-nav">
                <a class="nav-link" href="<?= BASE_URL ?>?action=product/index">Sản phẩm</a>
                <a class="nav-link" href="<?= BASE_URL ?>?action=cart/index">Giỏ hàng</a>
                <a class="nav-link" href="<?= BASE_URL ?>?action=order/my">Đơn hàng</a>
                <?php if (is_admin()): ?>
                    <a class="nav-link" href="<?= BASE_URL ?>?action=admin/dashboard">Quản trị</a>
                <?php endif; ?>
            </div>
            <div class="navbar-nav ms-auto">
                <?php if (current_user()): ?>
                    <span class="nav-link">Xin chào, <?= htmlspecialchars(current_user()['name']) ?></span>
                    <a class="nav-link text-danger" href="<?= BASE_URL ?>?action=auth/logout">Đăng xuất</a>
                <?php else: ?>
                    <a class="nav-link" href="<?= BASE_URL ?>?action=auth/login">Đăng nhập</a>
                    <a class="nav-link" href="<?= BASE_URL ?>?action=auth/register">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-3 mb-3"><?= $title ?? 'Shop MVC' ?></h1>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <div class="row">
            <?php
            if (isset($view)) {
                require_once PATH_VIEW . $view . '.php';
            }
            ?>
        </div>
    </div>

</body>

</html>