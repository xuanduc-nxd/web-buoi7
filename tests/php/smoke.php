<?php
/**
 * Chạy: composer dump-autoload && php tests/php/smoke.php
 * FakePDO để kiểm tra luồng OOP + render/escape, KHÔNG thay kiểm thử MySQL CRUD.
 */
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$autoload = $root . '/vendor/autoload.php';
if (!is_file($autoload)) {
    fwrite(STDERR, "[FAIL] Chưa tạo vendor/autoload.php. Chạy composer dump-autoload trước.\n");
    exit(2);
}
require $autoload;
require $root . '/includes/functions.php';

use App\Controllers\ProductController;
use App\Controllers\CategoryController;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;

class FakeRows extends PDOStatement
{
    public function __construct(private array $rows) {}
    public function fetchAll(int $mode = PDO::FETCH_DEFAULT, mixed ...$args): array
    {
        return $this->rows;
    }
}
class FakePDO extends PDO
{
    public array $seen = [];
    public function __construct() {}
    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PDOStatement|false
    {
        $this->seen[] = $query;
        if (stripos($query, 'FROM products') !== false) {
            return new FakeRows([[ 'id' => 1, 'name' => '<script>alert(1)</script>', 'price' => '150000', 'stock' => 5, 'category_name' => 'Áo VKU' ]]);
        }
        if (stripos($query, 'FROM categories') !== false) {
            return new FakeRows([[ 'id' => 2, 'name' => '<b>Học tập</b>', 'slug' => 'hoc-tap' ]]);
        }
        throw new RuntimeException('SQL không được mong đợi: ' . $query);
    }
}
function check(bool $condition, string $description): void
{
    if (!$condition) {
        fwrite(STDERR, "[FAIL] $description\n");
        exit(1);
    }
    echo "[PASS] $description\n";
}

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require $root . '/includes/auth.php';
$_SESSION['user_id'] = 1; $_SESSION['role'] = 'admin';
$pdo = new FakePDO();
$productRepo = new ProductRepository($pdo);
$products = $productRepo->all();
check(count($products) === 1, 'ProductRepository::all() trả về dữ liệu');
check(stripos($pdo->seen[0], 'JOIN categories') !== false, 'Product JOIN giữ cột category_name');
$categoryRepo = new CategoryRepository($pdo);
check(count($categoryRepo->all()) === 1, 'CategoryRepository::all() trả về dữ liệu');

ob_start();
(new ProductController($productRepo))->index();
$productHtml = ob_get_clean();
check(str_contains($productHtml, '&lt;script&gt;alert(1)&lt;/script&gt;'), 'View Product escape XSS');
check(!str_contains($productHtml, '<script>alert(1)</script>'), 'View Product không in script raw');
check(str_contains($productHtml, 'Áo VKU'), 'View Product có tên Category qua JOIN');
check(str_contains($productHtml, 'edit-product.php?id=1') && str_contains($productHtml, 'delete-product.php'), 'Link Edit/Delete Product còn tồn tại');
check(str_contains($productHtml, 'assets/css/style.css'), 'Asset CSS giữ đường dẫn');

ob_start();
(new CategoryController($categoryRepo))->index();
$categoryHtml = ob_get_clean();
check(str_contains($categoryHtml, '&lt;b&gt;Học tập&lt;/b&gt;'), 'View Category escape XSS');
check(str_contains($categoryHtml, 'edit-category.php?id=2') && str_contains($categoryHtml, 'delete-category.php'), 'Link Edit/Delete Category còn tồn tại');
check(positive_id('123a') === 0 && positive_id('-1') === 0 && positive_id('7') === 7, 'ID validator không ép sai kiểu');
echo "ALL SMOKE TESTS PASS (không thay kiểm thử CRUD với MySQL).\n";
