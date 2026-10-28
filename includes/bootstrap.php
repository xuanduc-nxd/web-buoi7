<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

// Cần bắt đầu Session trước khi gửi HTML ra trình duyệt.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start([
        'use_strict_mode' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
}
require_once __DIR__ . '/auth.php';

// Composer được dùng để tự nạp App\\Controllers và App\\Repositories.
// Starter vẫn chạy CRUD cũ trước khi học class; muốn chạy Completed/List mới,
// sinh viên phải chạy: composer dump-autoload tại thư mục gốc project.
$autoloadFile = dirname(__DIR__) . '/vendor/autoload.php';
if (is_file($autoloadFile)) {
    require_once $autoloadFile;
}

$dbConfig = require dirname(__DIR__) . '/config/database.php';
try {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $dbConfig['host'], $dbConfig['port'], $dbConfig['database']
    );
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $exception) {
    http_response_code(503);
    exit('Không thể kết nối MySQL. Kiểm tra config/database.php, MySQL và database/database.sql.');
}
