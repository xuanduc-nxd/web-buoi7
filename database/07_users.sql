-- MIGRATION BƯỚC 7: chỉ thêm users, KHÔNG DROP và KHÔNG sửa Product/Category.
-- Chạy SAU database/database.sql bằng phpMyAdmin hoặc CLI.
CREATE DATABASE IF NOT EXISTS vkushop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vkushop;
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB;
