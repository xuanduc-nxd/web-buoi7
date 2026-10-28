<?php require dirname(__DIR__, 2) . '/includes/header.php'; ?>
<div class="page-head"><h1>Hồ sơ của tôi</h1></div>
<div class="card form-card">
<p><strong>Họ tên:</strong> <?= e($user['name']) ?></p>
<p><strong>Email:</strong> <?= e($user['email']) ?></p>
<p><strong>Vai trò:</strong> <?= e($user['role']) ?></p>
<?php if (isAdmin()): ?><a class="btn" href="admin/index.php">Đi tới Admin</a><?php endif; ?>
</div>
<?php require dirname(__DIR__, 2) . '/includes/footer.php'; ?>
