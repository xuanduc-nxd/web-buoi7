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
            // TODO B7-C1: kiểm tra name, email, password, confirm.

            if($name === ''){
                $errors['name'] = 'Tên không được để trống.';
            }
            if($email === ''){
                $errors['email'] = 'Email không được để trống.';
            }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $errors['email'] = 'Email không hợp lệ.';
            } // filter_var() là hàm kiểm tra định dạng email hợp lệ hay không co san cua PHP

            if($password === ''){
                $errors['password'] = 'Mật khẩu không được để trống.';
            }elseif(strlen($password) < 6){
                $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự.';
            }

            if($confirm === ''){
                $errors['confirm_password'] = 'Xác nhận mật khẩu không được để trống.';
            }elseif($confirm !== $password){
                $errors['confirm_password'] = 'Xác nhận mật khẩu không khớp.';
            }


            // TODO B7-C2: password_hash() rồi UserRepository::create().
            // TODO B7-C3: xử lý email trùng (PDOException 1062); redirect tới login.php.
            
            if($error ===[]){
                try{
                    $hash = password_hash($password, PASSWORD_DEFAULT); // ham nay de ma hoa mat khau truoc khi luu vao database, PASSWORD_DEFAULT la co che ma hoa mac dinh cua PHP
                    $this->users->create($name, $email, $hash);
                    header('Location: login.php');
                    exit;
                } catch (\PDOException $e) {
                    if ($e->getCode() === '23000') { // mã lỗi trùng lặp (duplicate entry)
                        $errors['email'] = 'Email đã tồn tại.';
                    } else {
                        throw $e; // ném lại ngoại lệ nếu không phải lỗi trùng lặp
                    }
                }
            }
            
            $errors['general'] = 'Chức năng đăng ký chưa hoàn thiện (TODO B7-C1–C3).';
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
            // TODO B7-C4: findByEmail() và password_verify().

            $user = $this->users->findByEmail($email); // tìm user theo email

            if($user === null || !password_verify($password, $user['password_hash'])){
                $error = 'Email hoặc mật khẩu không đúng.';
            } else {
                // TODO B7-C5: nếu đúng -> session_regenerate_id(true), lưu user_id/role, redirect.
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id']; // lưu user_id vào session
                $_SESSION['role'] = $user['role']; // lưu role vào session
                header('Location: profile.php');
                exit;
            }
            // TODO B7-C5: nếu đúng -> session_regenerate_id(true), lưu user_id/role, redirect.
        }
        require dirname(__DIR__, 2) . '/views/auth/login.php';
    }

    public function profile(): void
    {
        \requireLogin();
        // TODO B7-C6: dùng UserRepository::findById() lấy $user.
        $user = $this->users->findById((int) $_SESSION['user_id']);

        if($user === null){
            unset($_SESSION['user_id'], $_SESSION['role']);
            http_response_code(403);
            exit('Tài khoản không tồn tại.');
        }
        $pageTitle = 'Hồ sơ tài khoản';
        require dirname(__DIR__, 2) . '/views/auth/profile.php';
    }

    // ham logout() de xoa session va cookie session, redirect ve trang login.php
    public function logout(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            exit('Chỉ chấp nhận POST.');
        }
        \verify_csrf();
        // TODO B7-C7: xóa Session, cookie Session; redirect login.php.

        $_SESSION = []; // xóa tất cả dữ liệu trong session

        if(ini_get(;'session.use.cookies')){
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        header('Location: login.php');
        exit;
    }
}
