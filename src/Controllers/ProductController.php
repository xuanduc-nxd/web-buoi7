<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ProductRepository;

final class ProductController
{
    public function __construct(private ProductRepository $products) {}

    public function index(): void
    {
        $products = $this->products->all();
        // View nhận biến $products và chỉ render HTML; không query DB.
        require dirname(__DIR__, 2) . '/views/products/index.php';
    }
}
