<?php
require __DIR__ . '/../includes/bootstrap.php';
requireAdmin();
$id = positive_id($_GET['id'] ?? null);
if (!$id) page_not_found('ID sản phẩm không hợp lệ.');
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();
if (!$product) page_not_found('Sản phẩm không tồn tại.');
$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$pageTitle = 'Sửa sản phẩm';
$errors = [];
$data = ['name' => (string)$product['name'], 'price' => (string)$product['price'], 'stock' => (string)$product['stock'], 'category_id' => (string)$product['category_id']];
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
        $categoryStmt = $pdo->prepare('SELECT COUNT(*) FROM categories WHERE id = :id');
        $categoryStmt->execute(['id' => $categoryId]);
        if (!(int) $categoryStmt->fetchColumn()) $errors['category_id'] = 'Danh mục không tồn tại.';
    }
    if (!$errors) {
        $update = $pdo->prepare('UPDATE products SET name = :name, price = :price, stock = :stock, category_id = :category_id WHERE id = :id');
        $update->execute(['name' => $data['name'], 'price' => (int)$data['price'], 'stock' => (int)$data['stock'], 'category_id' => $categoryId, 'id' => $id]);
        redirect('products.php?updated=1');
    }
}
require __DIR__ . '/../includes/header.php';
?>
<div class="page-head"><h1>Sửa sản phẩm #<?= $id ?></h1><a class="btn btn-outline" href="products.php">Quay lại</a></div>
<section class="card form-card"><form method="post" action="edit-product.php?id=<?= $id ?>">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
 <div class="field"><label for="name">Tên sản phẩm</label><input id="name" name="name" value="<?= e($data['name']) ?>" maxlength="150" required><p class="field-error"><?= e($errors['name'] ?? '') ?></p></div>
 <div class="field"><label for="price">Giá (đ)</label><input id="price" name="price" value="<?= e($data['price']) ?>" inputmode="numeric" required><p class="field-error"><?= e($errors['price'] ?? '') ?></p></div>
 <div class="field"><label for="stock">Tồn kho</label><input id="stock" name="stock" value="<?= e($data['stock']) ?>" inputmode="numeric" required><p class="field-error"><?= e($errors['stock'] ?? '') ?></p></div>
 <div class="field"><label for="category_id">Danh mục</label><select id="category_id" name="category_id" required>
 <?php foreach ($categories as $category): ?><option value="<?= (int)$category['id'] ?>" <?= (string)$category['id'] === $data['category_id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option><?php endforeach; ?>
 </select><p class="field-error"><?= e($errors['category_id'] ?? '') ?></p></div>
 <div class="form-actions"><button class="btn" type="submit">Cập nhật</button><a href="products.php">Hủy</a></div>
</form></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
