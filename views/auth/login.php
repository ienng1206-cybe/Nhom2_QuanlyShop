<div class="col-md-8 col-lg-5 mx-auto">
    <div class="auth-card">
        <form method="post" action="<?= BASE_URL ?>?action=auth/login">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
        </form>
        <hr class="my-3">
        <p class="text-center text-muted small mb-0">
            Chưa có tài khoản? <a href="<?= BASE_URL ?>?action=auth/register" class="fw-semibold text-decoration-none">Đăng ký ngay</a>
        </p>
    </div>
</div>
