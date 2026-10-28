<?php
// Entry PHP /products.php: KHÔNG viết query, KHÔNG render HTML.
require __DIR__ . '/../includes/bootstrap.php';

use App\Controllers\ProductController;
use App\Repositories\ProductRepository;

if (!class_exists(ProductRepository::class)) {
    http_response_code(503);
    exit('Chưa có Composer autoload. Tại thư mục gốc, chạy: composer dump-autoload');
}

$controller = new ProductController(new ProductRepository($pdo));
$controller->index();
