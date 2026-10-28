-- Buổi 6 dùng lại schema 2 bảng từ Buổi 3–5. KHÔNG đổi DB khi refactor.
-- File này tạo DB và thêm dữ liệu mẫu để chạy độc lập khi chưa có source Buổi 4.
-- CẢNH BÁO: không chạy lại trên DB đã có dữ liệu cần giữ.
CREATE DATABASE IF NOT EXISTS vkushop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vkushop;

CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,
    UNIQUE KEY uq_categories_slug (slug)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    price DECIMAL(12, 0) NOT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT fk_products_categories
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

INSERT INTO categories (name, slug)
SELECT 'Áo & Đồng phục', 'ao-dong-phuc'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug='ao-dong-phuc');
INSERT INTO categories (name, slug)
SELECT 'Phụ kiện', 'phu-kien'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug='phu-kien');
INSERT INTO categories (name, slug)
SELECT 'Học tập', 'hoc-tap'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug='hoc-tap');

INSERT INTO products (category_id, name, price, stock)
SELECT c.id, 'Áo thun VKU', 150000, 25 FROM categories c WHERE c.slug='ao-dong-phuc'
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name='Áo thun VKU');
INSERT INTO products (category_id, name, price, stock)
SELECT c.id, 'Áo khoác VKU', 420000, 12 FROM categories c WHERE c.slug='ao-dong-phuc'
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name='Áo khoác VKU');
INSERT INTO products (category_id, name, price, stock)
SELECT c.id, 'Balo VKU', 350000, 9 FROM categories c WHERE c.slug='phu-kien'
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name='Balo VKU');
INSERT INTO products (category_id, name, price, stock)
SELECT c.id, 'Bình nước VKU', 120000, 0 FROM categories c WHERE c.slug='phu-kien'
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name='Bình nước VKU');
INSERT INTO products (category_id, name, price, stock)
SELECT c.id, 'Sổ tay VKU', 45000, 40 FROM categories c WHERE c.slug='hoc-tap'
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name='Sổ tay VKU');
