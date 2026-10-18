-- ============================================================
--  auth.sql — Users Table & Default Admin Account
--  Run this in phpMyAdmin on the database:
--    `umamusume pretty derby`
-- ============================================================

USE `umamusume pretty derby`;

-- 1. Create users table
CREATE TABLE IF NOT EXISTS `users` (
    `id`         INT AUTO_INCREMENT PRIMARY KEY,
    `username`   VARCHAR(50)  NOT NULL UNIQUE,
    `password`   VARCHAR(255) NOT NULL,
    `role`       VARCHAR(20)  DEFAULT 'user',
    `created_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Insert default admin account (password: admin123)
--    Hash generated with: password_hash('admin123', PASSWORD_BCRYPT)
INSERT INTO `users` (`username`, `password`, `role`)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE `role` = 'admin';
