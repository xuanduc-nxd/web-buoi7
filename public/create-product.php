<?php
require __DIR__ . '/../includes/bootstrap.php';
// TODO B7-E1: gọi requireAdmin() tại đây, bảo vệ cả GET và POST.
$pageTitle = 'Thêm sản phẩm';
$errors = [];
$data = ['name' => '', 'price' => '', 'stock' => '', 'category_id' => ''];
$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach (array_keys($data) as $key) $data[$key] = trim((string) ($_POST[$key] ?? ''));
    if ($data['name'] === '') $errors['name'] = 'Vui lòng nhập tên sản phẩm.';
    elseif (!preg_match('/^.{1,150}$/us', $data['name'])) $errors['name'] = 'Tên sản phẩm tối đa 150 ký tự.';
    if ($data['price'] === '' || !ctype_digit($data['price']) || (int) $data['price'] <= 0 || strlen(ltrim($data['price'], '0')) > 12) $errors['price'] = 'Giá phải là số nguyên dương, tối đa 12 chữ số.';
    if ($data['stock'] === '' || !ctype_digit($data['stock']) || strlen(ltrim($data['stock'], '0')) > 10 || (float) $data['stock'] > 4294967295) $errors['stock'] = 'Tồn kho phải là số nguyên từ 0 đến 4294967295.';
    $categoryId = positive_id($data['category_id']);
    if (!$categoryId) {
        $errors['category_id'] = 'Vui lòng chọn danh mục hợp lệ.';
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM categories WHERE id = :id');
        $stmt->execute(['id' => $categoryId]);
        if (!(int) $stmt->fetchColumn()) $errors['category_id'] = 'Danh mục không tồn tại.';
    }
    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO products (name, price, stock, category_id) VALUES (:name, :price, :stock, :category_id)');
        $stmt->execute(['name' => $data['name'], 'price' => (int) $data['price'], 'stock' => (int) $data['stock'], 'category_id' => $categoryId]);
        redirect('products.php?created=1');
    }
}
require __DIR__ . '/../includes/header.php';
?>
<div class="page-head"><h1>Thêm sản phẩm</h1><a class="btn btn-outline" href="products.php">Quay lại</a></div>
<section class="card form-card">
<form method="post">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
 <div class="field"><label for="name">Tên sản phẩm</label><input id="name" name="name" value="<?= e($data['name']) ?>" maxlength="150" required><p class="field-error"><?= e($errors['name'] ?? '') ?></p></div>
 <div class="field"><label for="price">Giá (đ)</label><input id="price" name="price" inputmode="numeric" value="<?= e($data['price']) ?>" required><p class="field-error"><?= e($errors['price'] ?? '') ?></p></div>
 <div class="field"><label for="stock">Tồn kho</label><input id="stock" name="stock" inputmode="numeric" value="<?= e($data['stock']) ?>" required><p class="field-error"><?= e($errors['stock'] ?? '') ?></p></div>
 <div class="field"><label for="category_id">Danh mục</label><select id="category_id" name="category_id" required><option value="">-- Chọn danh mục --</option>
 <?php foreach ($categories as $category): ?><option value="<?= (int)$category['id'] ?>" <?= (string)$category['id'] === $data['category_id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option><?php endforeach; ?>
 </select><p class="field-error"><?= e($errors['category_id'] ?? '') ?></p></div>
 <div class="form-actions"><button class="btn" type="submit">Lưu sản phẩm</button><a href="products.php">Hủy</a></div>
</form></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
