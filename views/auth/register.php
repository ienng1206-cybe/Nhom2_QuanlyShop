<div class="col-12 col-lg-6 mx-auto">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h2 class="h4 mb-3">Đăng ký</h2>
            <p class="text-muted mb-4">Tạo tài khoản để mua hàng nhanh hơn.</p>

            <form method="post" action="<?= BASE_URL ?>?action=auth/register" class="vstack gap-3">
                <div>
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="name" class="form-control" required maxlength="80" autocomplete="name" placeholder="Nguyễn Văn A">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required autocomplete="email" placeholder="name@example.com">
                </div>
                <div>
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required minlength="4" autocomplete="new-password" placeholder="••••••••">
                    <div class="form-text text-muted">Tối thiểu 4 ký tự.</div>
                </div>
                <button type="submit" class="btn btn-success w-100">Tạo tài khoản</button>
            </form>

            <hr class="my-4">
            <p class="mb-0 text-muted">
                Đã có tài khoản?
                <a href="<?= BASE_URL ?>?action=auth/login">Đăng nhập</a>
            </p>
        </div>
    </div>
</div>

