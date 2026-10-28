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
if (!$id) page_not_found('ID sản phẩm không hợp lệ.');
$stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
$stmt->execute(['id' => $id]);
if ($stmt->rowCount() === 0) page_not_found('Sản phẩm không tồn tại.');
redirect('products.php?deleted=1');
