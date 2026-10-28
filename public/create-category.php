<?php
require __DIR__ . '/../includes/bootstrap.php';
requireAdmin();
$pageTitle = 'Thêm danh mục';
$name = $slug = '';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string) ($_POST['name'] ?? ''));
    $slug = trim((string) ($_POST['slug'] ?? ''));
    if ($name === '') $errors['name'] = 'Vui lòng nhập tên danh mục.';
    elseif (!preg_match('/^.{1,100}$/us', $name)) $errors['name'] = 'Tên danh mục tối đa 100 ký tự.';
    if (strlen($slug) > 120 || !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) $errors['slug'] = 'Slug tối đa 120 ký tự, chỉ gồm a-z, 0-9 và dấu gạch ngang.';
    if (!$errors) {
        try {
            $stmt = $pdo->prepare('INSERT INTO categories (name, slug) VALUES (:name, :slug)');
            $stmt->execute(['name' => $name, 'slug' => $slug]);
            redirect('categories.php?created=1');
        } catch (PDOException $exception) {
            if (is_duplicate_key($exception)) $errors['slug'] = 'Slug đã tồn tại.';
            else throw $exception;
        }
    }
}
require __DIR__ . '/../includes/header.php';
?>
<div class="page-head"><h1>Thêm danh mục</h1><a class="btn btn-outline" href="categories.php">Quay lại</a></div>
<section class="card form-card"><form method="post">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
 <div class="field"><label for="name">Tên danh mục</label><input id="name" name="name" value="<?= e($name) ?>" maxlength="100" required><p class="field-error"><?= e($errors['name'] ?? '') ?></p></div>
 <div class="field"><label for="slug">Slug (không dấu)</label><input id="slug" name="slug" value="<?= e($slug) ?>" maxlength="120" required><p class="help">Ví dụ: phu-kien</p><p class="field-error"><?= e($errors['slug'] ?? '') ?></p></div>
 <button class="btn" type="submit">Lưu danh mục</button>
</form></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
