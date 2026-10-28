<?php

declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url, true, 303);
    exit;
}

function asset_path(string $relativePath): string
{
    return 'assets/' . ltrim($relativePath, '/');
}

function positive_id(mixed $value): int
{
    // Không chuyển "12abc" thành 12.
    $id = filter_var($value, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);
    return $id === false || $id === null ? 0 : $id;
}

function page_not_found(string $message = 'Không tìm thấy dữ liệu.'): never
{
    http_response_code(404);
    $pageTitle = 'Không tìm thấy';
    require __DIR__ . '/header.php';
    echo '<div class="notice error">' . e($message) . '</div>';
    echo '<a class="btn" href="index.php">Về trang chủ</a>';
    require __DIR__ . '/footer.php';
    exit;
}

function is_duplicate_key(PDOException $exception): bool
{
    // MySQL 1062: duplicate entry; không nhầm FK violation (1451/1452).
    return (int) ($exception->errorInfo[1] ?? 0) === 1062;
}
