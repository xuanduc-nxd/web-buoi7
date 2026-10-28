# Buổi 07 — Ma trận test trước khi chốt

| Case | Guest | User | Admin |
|---|---|---|---|
| GET /products.php, /categories.php | Xem | Xem | Xem |
| GET /profile.php | 303 → Login | 200 | 200 |
| GET /admin/index.php | 303 → Login | 403 | 200 |
| GET Create/Edit Product/Category | 303 → Login | 403 | 200 |
| POST Create/Edit/Delete (kèm CSRF) | 303 → Login | 403 | Được chạy + validation |

- [ ] `/session-demo.php` F5 tăng counter; cửa sổ ẩn danh độc lập.
- [ ] Register hợp lệ lưu **hash**, role `user`.
- [ ] Register gửi POST role=admin vẫn ra user.
- [ ] Register email trùng, tên/Email sai, mật khẩu ngắn/không khớp có lỗi.
- [ ] Login đúng tạo Session và thay Session ID; sai không đăng nhập.
- [ ] Profile chỉ xem khi đã đăng nhập.
- [ ] User truy cập URL admin và POST trực tiếp delete đều 403.
- [ ] Admin CRUD Product/Category vẫn đúng, JOIN giữ tên danh mục.
- [ ] Logout POST xóa session và cookie; GET logout 405.
- [ ] POST sai hoặc thiếu CSRF bị từ chối. Token là helper chuẩn bị trước cho Buổi 12.
- [ ] Bật tài khoản Admin bằng CLI, không gắn mật khẩu mặc định trong code.
- [ ] Composer PSR-4 chạy trên máy học; php -l PASS; kiểm tra DB MySQL/MariaDB thật.

**Lưu ý:** Đây là source lab, chưa phải hệ thống xác thực đã harden production.
