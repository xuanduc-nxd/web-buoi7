<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\CategoryRepository;

final class CategoryController
{
    public function __construct(private CategoryRepository $categories) {}

    public function index(): void
    {
        $categories = $this->categories->all();
        require dirname(__DIR__, 2) . '/views/categories/index.php';
    }
}
