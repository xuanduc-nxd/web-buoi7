<?php
require __DIR__ . '/../includes/bootstrap.php';
// TODO B7-E1: gọi requireAdmin() tại đây, bảo vệ cả GET và POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Chỉ hỗ trợ POST.');
}
verify_csrf();
$id = positive_id($_POST['id'] ?? null);
if (!$id) page_not_found('ID danh mục không hợp lệ.');
$stmt = $pdo->prepare('SELECT COUNT(*) FROM categories WHERE id = :id');
$stmt->execute(['id' => $id]);
if (!(int)$stmt->fetchColumn()) page_not_found('Danh mục không tồn tại.');
$stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE category_id = :id');
$stmt->execute(['id' => $id]);
if ((int)$stmt->fetchColumn() > 0) redirect('categories.php?error=in_use');
try {
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id = :id');
    $stmt->execute(['id' => $id]);
} catch (PDOException $exception) {
    if ((int)($exception->errorInfo[1] ?? 0) === 1451) redirect('categories.php?error=in_use');
    throw $exception;
}
redirect('categories.php?deleted=1');
