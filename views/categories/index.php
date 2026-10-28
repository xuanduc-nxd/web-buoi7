<?php
// VIEW: không query PDO. Dữ liệu $categories được Controller cung cấp.
$pageTitle = 'Danh mục';
$canManage = isAdmin();
require dirname(__DIR__, 2) . '/includes/header.php';
?>
<div class="page-head">
 <div><h1>Danh mục sản phẩm</h1><p class="muted">Quản lý các Category của VKUShop.</p></div>
 <?php if ($canManage): ?>
 <a class="btn" href="create-category.php">+ Thêm danh mục</a>
 <?php endif; ?>
</div>
<?php if (($_GET['error'] ?? '') === 'in_use'): ?><div class="notice error">Không thể xóa danh mục đang có sản phẩm.</div><?php endif; ?>
<?php if (isset($_GET['created']) || isset($_GET['updated']) || isset($_GET['deleted'])): ?><div class="notice success">Đã cập nhật danh mục.</div><?php endif; ?>
<div class="card table-scroll">
 <table class="data-table">
  <thead><tr><th>ID</th><th>Tên danh mục</th><th>Slug</th><?php if ($canManage): ?><th>Thao tác</th><?php endif; ?></tr></thead>
  <tbody>
  <?php foreach ($categories as $category): ?>
  <tr>
   <td><?= (int)$category['id'] ?></td>
   <td><?= e($category['name']) ?></td>
   <td><code><?= e($category['slug']) ?></code></td>
   <?php if ($canManage): ?>
   <td><div class="actions">
       <a class="btn btn-sm btn-outline" href="edit-category.php?id=<?= (int)$category['id'] ?>">Sửa</a>
       <form action="delete-category.php" method="post" onsubmit="return confirm('Xóa danh mục này?')">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
       <input type="hidden" name="id" value="<?= (int)$category['id'] ?>">
        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
       </form>
   </div></td>
   <?php endif; ?>
  </tr>
  <?php endforeach; ?>
  <?php if (!$categories): ?><tr><td colspan="<?= $canManage ? 4 : 3 ?>" class="muted">Chưa có danh mục.</td></tr><?php endif; ?>
  </tbody>
 </table>
</div>
<?php require dirname(__DIR__, 2) . '/includes/footer.php'; ?>
