# Mã TODO Buổi 07

| Checkpoint | Đường dẫn | Mã cần làm |
|---|---|---|
| A | `public/session-demo.php` | Quan sát Counter / cookie |
| B | `database/07_users.sql` | Import bảng users |
| C | `UserRepository.php`, `AuthController.php` | B7-R3, B7-C1 → C3 |
| D | `UserRepository.php`, `AuthController.php` | B7-R1, B7-C4 → C5 |
| E | `UserRepository.php`, `AuthController.php`, `includes/auth.php` | B7-R2, C6, C7, A1, A2 |
| E | `public/*product.php`, `public/*category.php`, `views/*` | B7-E1 guard 6 endpoint và B7-E2 ẩn nút |
| F | `TEST_CHECKLIST.md` | Test Guest/User/Admin |

Starter ban đầu giữ CRUD không phân quyền để kiểm tra rủi ro. Sau buổi học, tất cả 6 endpoint phải gọi requireAdmin() ở server; ẩn nút chỉ là UX.
