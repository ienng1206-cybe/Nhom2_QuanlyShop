<div class="col-md-6">
    <form method="post" action="<?= BASE_URL ?>?action=auth/login">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button class="btn btn-primary">Đăng nhập</button>
    </form>
</div>
