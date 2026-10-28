<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, name, email, role FROM users WHERE email = :email LIMIT 1');  
        $stmt->execute(['email' => $email]); // truyen tham so vao cau lenh sql
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // lay ket qua tra ve

        return $user === false ? null : $user; // neu khong tim thay user thi tra ve null, nguoc lai tra ve user.
    }

    public function findById(int $id): ?array
    {
        // TODO B7-R2: SELECT id,name,email,role theo id.
        $stmt = $this->pdo->prepare("SELECT id, name, email, role FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user === false ? null : $user;
    }

    public function create(string $name, string $email, string $hash): void
    {
        // TODO B7-R3: INSERT user bằng prepared statement.
        // role CỐ ĐỊNH 'user' tại server; không lấy từ POST.
        
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :password_hash, 'user')");
        $stmt -> execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $hash
        ]); // ham nay dung de insert du lieu vao database, truyen tham so vao cau lenh sql de tranh sql injection
    }
}
