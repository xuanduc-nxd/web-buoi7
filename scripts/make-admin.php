<?php
// Dành cho GV trên máy local. Không dùng endpoint web hoặc mật khẩu mẫu.
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}
$email = strtolower(trim((string) ($argv[1] ?? '')));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Cú pháp: php scripts/make-admin.php email-da-dang-ky@domain.test\n");
}
require __DIR__ . '/../includes/bootstrap.php';
$stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE email = :email");
$stmt->execute(['email' => $email]);
echo $stmt->rowCount() > 0 ? "Đã cấp quyền Admin. Đăng xuất và đăng nhập lại để cập nhật Session.\n" : "Không tìm thấy User hoặc User đã là Admin.\n";
