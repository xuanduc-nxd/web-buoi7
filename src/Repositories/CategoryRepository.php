<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class CategoryRepository
{
    public function __construct(private PDO $pdo) {}

    public function all(): array
    {
        return $this->pdo->query(
            'SELECT id, name, slug FROM categories ORDER BY id DESC'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    // CHALLENGE, không bắt buộc: findById(int $id): ?array với prepare()/fetch().
}
