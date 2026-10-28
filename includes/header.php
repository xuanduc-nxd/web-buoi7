<?php
$pageTitle = $pageTitle ?? 'VKUShop';
$navPrefix = $navPrefix ?? '';
?><!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> · VKUShop</title>
    <link rel="stylesheet" href="<?= e($navPrefix . asset_path('css/style.css')) ?>">
</head>
<body>
<header class="site-header">
  <div class="container nav-inner">
    <a class="brand" href="<?= e($navPrefix) ?>index.php"><span class="brand-mark">V</span><span>VKUShop</span></a>
    <nav aria-label="Điều hướng chính">
      <a href="<?= e($navPrefix) ?>index.php">Trang chủ</a>
      <a href="<?= e($navPrefix) ?>products.php">Sản phẩm</a>
      <a href="<?= e($navPrefix) ?>categories.php">Danh mục</a>
      <?php if (isLoggedIn()): ?>
        <a href="<?= e($navPrefix) ?>profile.php">Hồ sơ</a>
        <?php if (isAdmin()): ?><a href="<?= e($navPrefix) ?>admin/index.php">Admin</a><?php endif; ?>
        <form method="post" action="<?= e($navPrefix) ?>logout.php" class="nav-logout">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <button class="btn btn-sm btn-outline" type="submit">Đăng xuất</button>
        </form>
      <?php else: ?>
        <a href="<?= e($navPrefix) ?>login.php">Đăng nhập</a>
        <a href="<?= e($navPrefix) ?>register.php">Đăng ký</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container main-content">
