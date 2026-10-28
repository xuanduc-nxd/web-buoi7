<?php
require __DIR__ . '/../includes/bootstrap.php';
$pageTitle = 'Trang chủ';
require __DIR__ . '/../includes/header.php';
?>
<section class="hero">
  <h1>VKUShop — PHP + MySQL</h1>
  <p>Buổi 7: Đăng ký, đăng nhập, Session và phân quyền Admin/User.</p>
</section>
<div class="tiles">
  <section class="card tile"><h2>Sản phẩm</h2><p>Danh sách Product và chức năng CRUD đã học ở Buổi 4.</p><a class="btn" href="products.php">Xem sản phẩm</a></section>
  <section class="card tile"><h2>Danh mục</h2><p>Quản lý Category và quan hệ khóa ngoại với Product.</p><a class="btn" href="categories.php">Xem danh mục</a></section>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
