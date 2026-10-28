<?php
require __DIR__ . '/../includes/bootstrap.php';
requireAdmin();
$id = positive_id($_GET['id'] ?? null);
if (!$id) page_not_found('ID danh mục không hợp lệ.');
$stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
$stmt->execute(['id' => $id]);
$category = $stmt->fetch();
if (!$category) page_not_found('Danh mục không tồn tại.');
$pageTitle = 'Sửa danh mục';
$name = (string)$category['name']; $slug = (string)$category['slug']; $errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string) ($_POST['name'] ?? ''));
    $slug = trim((string) ($_POST['slug'] ?? ''));
    if ($name === '') $errors['name'] = 'Vui lòng nhập tên danh mục.';
    elseif (!preg_match('/^.{1,100}$/us', $name)) $errors['name'] = 'Tên danh mục tối đa 100 ký tự.';
    if (strlen($slug) > 120 || !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) $errors['slug'] = 'Slug tối đa 120 ký tự, chỉ gồm a-z, 0-9 và dấu gạch ngang.';
    if (!$errors) {
        try {
            $update = $pdo->prepare('UPDATE categories SET name = :name, slug = :slug WHERE id = :id');
            $update->execute(['name' => $name, 'slug' => $slug, 'id' => $id]);
            redirect('categories.php?updated=1');
        } catch (PDOException $exception) {
            if (is_duplicate_key($exception)) $errors['slug'] = 'Slug đã tồn tại.';
            else throw $exception;
        }
    }
}
require __DIR__ . '/../includes/header.php';
?>
<div class="page-head"><h1>Sửa danh mục #<?= $id ?></h1><a class="btn btn-outline" href="categories.php">Quay lại</a></div>
<section class="card form-card"><form method="post" action="edit-category.php?id=<?= $id ?>">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
 <div class="field"><label for="name">Tên danh mục</label><input id="name" name="name" value="<?= e($name) ?>" maxlength="100" required><p class="field-error"><?= e($errors['name'] ?? '') ?></p></div>
 <div class="field"><label for="slug">Slug</label><input id="slug" name="slug" value="<?= e($slug) ?>" maxlength="120" required><p class="field-error"><?= e($errors['slug'] ?? '') ?></p></div>
 <button class="btn" type="submit">Cập nhật danh mục</button>
</form></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
