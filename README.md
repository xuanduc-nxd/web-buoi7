# VKUShop — Buổi 07

Kế thừa Completed Buổi 6. Dự án có luồng Register → Login → Profile → Logout và phân quyền Guest/User/Admin. Chỉ chạy trong môi trường thực hành localhost; **không dùng production**.

## Cài và chạy

1. Dự án dùng database `vkushop`. Import `database/database.sql` **chỉ nếu chưa có**. Import tiếp `database/07_users.sql` để thêm users, không đụng dữ liệu Product/Category.
2. Kiểm tra `php -v`, `composer --version`, MySQL và extension `pdo_mysql`.
3. Ở thư mục chứa composer.json:

   ```bash
   composer dump-autoload
   php -S localhost:8000 -t public
   ```

4. Mở `/session-demo.php` để xem Session, `/register.php` và `/login.php` để thử luồng xác thực. `vendor/` do Composer tạo, không đóng ZIP.

### Cấu hình database trên máy cá nhân

`config/database.php` đọc các biến môi trường `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`. Nếu cần lưu cấu hình riêng trên máy, sao chép `config/database.local.example.php` thành `config/database.local.php` rồi sửa các giá trị trong file mới. File `database.local.php` được Git bỏ qua; không đưa mật khẩu database vào commit. Chọn cùng tên database trong cấu hình và các file SQL trước khi import.

## Checkpoint theo giáo án

- **A — Session:** `public/session-demo.php`: F5 và thử cửa sổ ẩn danh; giải thích cookie ID.
- **B — Database:** `database/07_users.sql`: xem cấu trúc users, email UNIQUE, role mặc định user.
- **C — Register:** `UserRepository.php`: **B7-R3**; `AuthController.php`: **B7-C1 → C3**.
- **D — Login:** `UserRepository.php`: **B7-R1**; `AuthController.php`: **B7-C4 → C5**.
- **E — Profile/Logout/Admin:** **B7-R2**, **B7-C6/C7**, `includes/auth.php` **B7-A1/A2**. Cả 6 endpoint Create/Edit/Delete gọi `requireAdmin()` trước khi xử lý request (**B7-E1**); các nút ghi chỉ hiển thị cho Admin (**B7-E2**).
- **F — Test:** dùng `TEST_CHECKLIST.md` kiểm tra Guest/User/Admin và tình huống sai.

Giảng viên có thể cấp role sau khi SV tự đăng ký tài khoản bằng `php scripts/make-admin.php email@example.test`. Đăng xuất rồi đăng nhập lại sau khi nâng quyền.

`csrf_token()` / `verify_csrf()` đã được cung cấp sẵn như helper an toàn. Buổi 12 mới giải thích CSRF đầy đủ. Không tự xóa các guard/CSRF khi hoàn thiện bài.
