<div class="col-md-8 col-lg-5 mx-auto">
    <div class="auth-card">
        <form method="post" action="<?= BASE_URL ?>?action=auth/register">
            <div class="mb-3">
                <label class="form-label">Họ tên</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Đăng ký</button>
        </form>
        <hr class="my-3">
        <p class="text-center text-muted small mb-0">
            Đã có tài khoản? <a href="<?= BASE_URL ?>?action=auth/login" class="fw-semibold text-decoration-none">Đăng nhập</a>
        </p>
    </div>
</div>
