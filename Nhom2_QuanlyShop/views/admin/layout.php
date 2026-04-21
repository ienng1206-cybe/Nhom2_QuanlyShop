<!DOCTYPE html>
<html lang="vi" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($title ?? 'Quản trị') ?> · Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/admin.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>

<body class="admin-body">
    <?php $currentAction = $_GET['action'] ?? 'admin/dashboard'; ?>
    <header class="admin-topbar">
        <div class="admin-topbar-inner">
            <a class="admin-brand" href="<?= BASE_URL ?>?action=admin/dashboard">
                <span class="admin-brand-mark">A</span>
                <span class="admin-brand-text">Quản trị</span>
            </a>
            <nav class="admin-topnav d-none d-md-flex align-items-center gap-1">
                <a class="admin-nav-link <?= $currentAction === 'admin/dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>?action=admin/dashboard">Tổng quan</a>
                <a class="admin-nav-link <?= $currentAction === 'admin/orders' ? 'active' : '' ?>" href="<?= BASE_URL ?>?action=admin/orders">Đơn hàng</a>
                <a class="admin-nav-link <?= $currentAction === 'admin/products' ? 'active' : '' ?>" href="<?= BASE_URL ?>?action=admin/products">Sản phẩm</a>
            </nav>
            <div class="admin-topbar-actions d-flex align-items-center gap-2 flex-wrap">
                <a class="btn btn-outline-secondary btn-sm d-md-none" href="<?= BASE_URL ?>?action=admin/dashboard">Tổng quan</a>
                <a class="btn btn-outline-light btn-sm" href="<?= BASE_URL ?>" target="_blank" rel="noopener">Mở cửa hàng ↗</a>
                <?php if (current_user()): ?>
                    <span class="admin-user text-secondary small d-none d-sm-inline"><?= htmlspecialchars(current_user()['name']) ?></span>
                    <a class="btn btn-danger btn-sm" href="<?= BASE_URL ?>?action=auth/logout">Đăng xuất</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="admin-layout d-flex">
        <aside class="admin-sidebar d-none d-lg-flex flex-column">
            <div class="admin-sidebar-label">Menu</div>
            <a class="admin-side-link <?= $currentAction === 'admin/dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>?action=admin/dashboard">
                <span class="admin-side-icon">⌂</span> Tổng quan
            </a>
            <a class="admin-side-link <?= $currentAction === 'admin/categories' ? 'active' : '' ?>" href="<?= BASE_URL ?>?action=admin/categories">
                <span class="admin-side-icon">▦</span> Danh mục
            </a>
            <a class="admin-side-link <?= $currentAction === 'admin/products' ? 'active' : '' ?>" href="<?= BASE_URL ?>?action=admin/products">
                <span class="admin-side-icon">◼</span> Sản phẩm
            </a>
            <a class="admin-side-link <?= $currentAction === 'admin/orders' ? 'active' : '' ?>" href="<?= BASE_URL ?>?action=admin/orders">
                <span class="admin-side-icon">≡</span> Đơn hàng
            </a>
            <a class="admin-side-link <?= $currentAction === 'admin/users' ? 'active' : '' ?>" href="<?= BASE_URL ?>?action=admin/users">
                <span class="admin-side-icon">☺</span> Người dùng
            </a>
            <a class="admin-side-link <?= $currentAction === 'admin/reviews' ? 'active' : '' ?>" href="<?= BASE_URL ?>?action=admin/reviews">
                <span class="admin-side-icon">★</span> Đánh giá
            </a>
            <div class="admin-sidebar-label mt-4">Liên kết</div>
            <a class="admin-side-link" href="<?= BASE_URL ?>" target="_blank" rel="noopener">
                <span class="admin-side-icon">↗</span> Website khách
            </a>
        </aside>
        <main class="admin-main flex-grow-1">
            <div class="admin-main-inner container-fluid py-4 px-3 px-lg-4">
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger admin-alert"><?= $_SESSION['error'];
                    unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success admin-alert"><?= $_SESSION['success'];
                    unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <?php require $adminTemplatePath; ?>
            </div>
        </main>
    </div>
</body>

</html>
