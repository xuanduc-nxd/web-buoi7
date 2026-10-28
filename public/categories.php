<?php
// Entry PHP /categories.php: KHÔNG viết query, KHÔNG render HTML.
require __DIR__ . '/../includes/bootstrap.php';

use App\Controllers\CategoryController;
use App\Repositories\CategoryRepository;

if (!class_exists(CategoryRepository::class)) {
    http_response_code(503);
    exit('Chưa có Composer autoload. Tại thư mục gốc, chạy: composer dump-autoload');
}

$controller = new CategoryController(new CategoryRepository($pdo));
$controller->index();
