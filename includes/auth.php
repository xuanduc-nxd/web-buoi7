<?php

declare(strict_types=1);

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && (int) $_SESSION['user_id'] > 0;
}

function isAdmin(): bool
{
    // TODO B7-A1: xác định user đã login và role trong Session là admin.

    if(isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'){
        return true;
    }
    return false;
}

function requireLogin(string $loginUrl = 'login.php'): void
{
    if (!isLoggedIn()) redirect($loginUrl);
}

function requireAdmin(string $loginUrl = 'login.php'): void
{
    requireLogin($loginUrl);
    // TODO B7-A2: nếu không phải Admin, trả HTTP 403 và dừng.
    // Mặc định chặn tất cả để không vô tình mở quyền.

    if(!isAdmin()){
        http_response_code(403);
        exit('Chỉ Admin mới được phép truy cập.');
    }
}

// Helper CSRF có sẵn để code mới không sử dụng POST thiếu token.
// Phần giải thích chuyên sâu về CSRF dành cho Buổi 12.
function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void
{
    $value = $_POST['csrf_token'] ?? '';
    if (!is_string($value) || !hash_equals(csrf_token(), $value)) {
        http_response_code(403);
        exit('Yêu cầu không hợp lệ.');
    }
}
