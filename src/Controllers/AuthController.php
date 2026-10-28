<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;

final class AuthController
{
    public function __construct(private UserRepository $users) {}

    public function register(): void
    {
        $pageTitle = 'Đăng ký';
        $name = $email = '';
        $errors = [];
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            \verify_csrf();
            $name = is_string($_POST['name'] ?? null) ? trim($_POST['name']) : '';
            $email = is_string($_POST['email'] ?? null) ? strtolower(trim($_POST['email'])) : '';
            $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
            $confirm = is_string($_POST['confirm_password'] ?? null) ? $_POST['confirm_password'] : '';
            if (preg_match('/^.{2,100}$/u', $name) !== 1) {
                $errors['name'] = 'Họ tên phải có từ 2 đến 100 ký tự.';
            }
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false || strlen($email) > 190) {
                $errors['email'] = 'Email không hợp lệ hoặc quá 190 byte.';
            }
            if (preg_match('/^.{8,}$/us', $password) !== 1 || strlen($password) > 72) {
                $errors['password'] = 'Mật khẩu cần ít nhất 8 ký tự và tối đa 72 byte.';
            }
            if ($confirm !== $password) {
                $errors['confirm_password'] = 'Mật khẩu nhập lại không khớp.';
            }

            if ($errors === []) {
                try {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $this->users->create($name, $email, $hash);
                    \redirect('login.php?registered=1');
                } catch (\PDOException $exception) {
                    if (\is_duplicate_key($exception)) {
                        $errors['email'] = 'Email này đã được đăng ký.';
                    } else {
                        throw $exception;
                    }
                }
            }
        }
        require dirname(__DIR__, 2) . '/views/auth/register.php';
    }

    public function login(): void
    {
        $pageTitle = 'Đăng nhập';
        $email = $error = '';
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            \verify_csrf();
            $email = is_string($_POST['email'] ?? null) ? strtolower(trim($_POST['email'])) : '';
            $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
            $user = $this->users->findByEmail($email);
            if ($user === null || !password_verify($password, $user['password_hash'])) {
                $error = 'Email hoặc mật khẩu không đúng.';
            } else {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['role'] = $user['role'];
                unset($_SESSION['csrf_token']);
                \redirect('profile.php');
            }
        }
        require dirname(__DIR__, 2) . '/views/auth/login.php';
    }

    public function profile(): void
    {
        \requireLogin();
        $user = $this->users->findById((int) $_SESSION['user_id']);
        if ($user === null) {
            unset($_SESSION['user_id'], $_SESSION['role']);
            \redirect('login.php');
        }
        $pageTitle = 'Hồ sơ tài khoản';
        require dirname(__DIR__, 2) . '/views/auth/profile.php';
    }

    public function logout(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            exit('Chỉ chấp nhận POST.');
        }
        \verify_csrf();

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 3600,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]);
        }
        session_destroy();
        \redirect('login.php?logged_out=1');
    }
}
