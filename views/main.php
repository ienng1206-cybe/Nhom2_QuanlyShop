<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?? 'Shop MVC' ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/app.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>

<body class="app-body">

    <nav class="navbar navbar-expand-lg navbar-light app-navbar shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>">Shop Giày Thể Thao</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Mở menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>?action=product/index">Sản phẩm</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=cart/index">Giỏ hàng
                            <?php
                            if (current_user()) {
                                try {
                                    $cartQty = (new CartModel())->countItems((int) current_user()['id']);
                                    if ($cartQty > 0) {
                                        echo ' <span class="badge text-bg-primary rounded-pill">' . (int) $cartQty . '</span>';
                                    }
                                } catch (Throwable $e) {
                                    // Tránh vỡ cả trang khi CSDL/sao chép bảng giỏ chưa đúng
                                }
                            }
                            ?>
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>?action=order/my">Đơn hàng</a></li>
                    <?php if (is_admin()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>?action=admin/dashboard">Quản trị</a></li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <?php if (current_user()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>?action=user/profile">Tài khoản</a></li>
                        <li class="nav-item"><span class="nav-link navbar-text mb-0 py-lg-2">Xin chào, <?= htmlspecialchars(current_user()['name']) ?></span></li>
                        <li class="nav-item"><a class="nav-link text-danger" href="<?= BASE_URL ?>?action=auth/logout">Đăng xuất</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>?action=auth/login">Đăng nhập</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>?action=auth/register">Đăng ký</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="app-main">
        <div class="container py-4">
            <?php if (empty($hide_page_title)): ?>
                <header class="page-header mb-4">
                    <h1 class="page-title mb-1"><?= htmlspecialchars($title ?? 'Shop MVC') ?></h1>
                </header>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="row g-3">
                <?php
                if (isset($view)) {
                    require_once PATH_VIEW . $view . '.php';
                }
                ?>
            </div>
        </div>
    </main>

    <footer class="app-footer border-top mt-auto">
        <div class="container py-3 text-center text-muted small">
            Shop Giày Thể Thao
        </div>
    </footer>

</body>

</html>