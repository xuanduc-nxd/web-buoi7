<?php require dirname(__DIR__, 2) . '/includes/header.php'; ?>
<div class="page-head"><h1>Đăng ký tài khoản</h1></div>
<?php if (isset($errors['general'])): ?><div class="notice error"><?= e($errors['general']) ?></div><?php endif; ?>
<section class="card form-card"><form method="post" action="register.php">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="field"><label for="name">Họ tên</label><input id="name" name="name" required maxlength="100" value="<?= e($name) ?>"><p class="field-error"><?= e($errors['name'] ?? '') ?></p></div>
<div class="field"><label for="email">Email</label><input id="email" name="email" type="email" required maxlength="190" value="<?= e($email) ?>"><p class="field-error"><?= e($errors['email'] ?? '') ?></p></div>
<div class="field"><label for="password">Mật khẩu (tối thiểu 8 ký tự, tối đa 72 byte)</label><input id="password" name="password" type="password" required autocomplete="new-password"><p class="field-error"><?= e($errors['password'] ?? '') ?></p></div>
<div class="field"><label for="confirm_password">Nhập lại mật khẩu</label><input id="confirm_password" name="confirm_password" type="password" required autocomplete="new-password"><p class="field-error"><?= e($errors['confirm_password'] ?? '') ?></p></div>
<button class="btn" type="submit">Đăng ký</button> <a href="login.php">Đã có tài khoản?</a>
</form></section>
<?php require dirname(__DIR__, 2) . '/includes/footer.php'; ?>
