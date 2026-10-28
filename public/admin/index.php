<?php
require __DIR__ . '/../../includes/bootstrap.php';
requireAdmin('../login.php');
$navPrefix = '../';
$pageTitle = 'Khu vực quản trị';
require __DIR__ . '/../../includes/header.php';
?>
<h1>Khu vực quản trị</h1>
<section class="card">
 <p>Chỉ tài khoản <strong>Admin</strong> được truy cập URL này.</p>
 <a href="../products.php" class="btn">Quản lý Product</a>
 <a href="../categories.php" class="btn">Quản lý Category</a>
</section>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
