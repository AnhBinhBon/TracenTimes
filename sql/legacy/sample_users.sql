-- ============================================================
--  sample_users.sql — 3 tài khoản mẫu (password: 123456)
--  Chạy file này trong phpMyAdmin trên database: umamusume pretty derby
--  Hash được sinh bằng password_hash('123456', PASSWORD_BCRYPT)
-- ============================================================

USE `umamusume pretty derby`;

INSERT INTO `users` (`username`, `password`, `role`) VALUES
('user_demo1',     '$2y$10$lOdk0rdPcdBmULIrSYclQeY.V8Fbw57VBbUpKOOiPSZQtLgbpDY6u', 'user'),
('user_demo2',     '$2y$10$bf500jW.NZuguWoJ.qUyweov06ZAH3lE8qcJjzJd/zjRSrqqPtyU6', 'user'),
('user_moderator', '$2y$10$NbYNyDqPgOEBa6UaqzN7tO0e/QnxCOyY5L0NQtxPXD8/27YnmlDGe', 'admin');
