<?php
require __DIR__ . '/../includes/bootstrap.php';
$_SESSION['counter'] = ($_SESSION['counter'] ?? 0) + 1;
$pageTitle = 'Thử nghiệm Session';
require __DIR__ . '/../includes/header.php';
?>
<h1>Session Counter</h1>
<div class="card"><p>Số lượt tải trang trong phiên này: <strong><?= (int) $_SESSION['counter'] ?></strong></p>
<p>F5 để tăng; mở ẩn danh để thử phiên khác.</p></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
