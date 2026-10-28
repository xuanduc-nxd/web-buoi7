<?php
// Cấu hình chung; thông tin riêng của máy nằm trong database.local.php (không commit).
$localConfig = __DIR__ . '/database.local.php';
if (is_file($localConfig)) {
    return require $localConfig;
}

return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => getenv('DB_PORT') ?: '3306',
    'database' => getenv('DB_NAME') ?: 'vku_shop',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : '',
];