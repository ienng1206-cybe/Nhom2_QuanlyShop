<div class="col-12 col-lg-6 mx-auto">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h2 class="h4 mb-3">Đăng nhập</h2>
            <p class="text-muted mb-4">Đăng nhập để mua hàng và theo dõi đơn hàng.</p>

            <form method="post" action="<?= BASE_URL ?>?action=auth/login" class="vstack gap-3">
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required autocomplete="email" placeholder="name@example.com">
                </div>
                <div>
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required autocomplete="current-password" placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
            </form>

            <hr class="my-4">
            <p class="mb-0 text-muted">
                Chưa có tài khoản?
                <a href="<?= BASE_URL ?>?action=auth/register">Đăng ký</a>
            </p>
        </div>
    </div>
</div>

