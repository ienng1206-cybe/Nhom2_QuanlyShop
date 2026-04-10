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
        <button class="btn btn-success w-100">Đăng ký</button>
    </form>
    </div>
</div>
