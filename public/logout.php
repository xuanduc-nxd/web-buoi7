<?php
require __DIR__ . '/../includes/bootstrap.php';

use App\Controllers\AuthController;
use App\Repositories\UserRepository;

(new AuthController(new UserRepository($pdo)))->logout();
