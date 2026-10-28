<?php require dirname(__DIR__, 2) . '/includes/header.php'; ?>
<div class="page-head"><h1>Đăng nhập</h1></div>
<?php if (isset($_GET['registered'])): ?><div class="notice success">Đăng ký thành công. Mời đăng nhập.</div><?php endif; ?>
<?php if (isset($_GET['logged_out'])): ?><div class="notice success">Đã đăng xuất.</div><?php endif; ?>
<?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<section class="card form-card"><form method="post" action="login.php">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="field"><label for="email">Email</label><input id="email" type="email" name="email" required value="<?= e($email) ?>"></div>
<div class="field"><label for="password">Mật khẩu</label><input id="password" type="password" name="password" required autocomplete="current-password"></div>
<button class="btn" type="submit">Đăng nhập</button> <a href="register.php">Tạo tài khoản</a>
</form></section>
<?php require dirname(__DIR__, 2) . '/includes/footer.php'; ?>
