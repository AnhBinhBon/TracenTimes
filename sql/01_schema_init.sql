-- ====================================================================
--  01_schema_init.sql — Khởi tạo CSDL TracenTimes theo chuẩn 3 lớp
--  Database: `tracen_times`
-- ====================================================================

CREATE DATABASE IF NOT EXISTS `tracen_times`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `tracen_times`;

-- 1. Bảng Users (Quản lý tài khoản & Phân quyền Admin / User)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Bảng Characters (Chuyên lưu trữ Thông tin cá nhân & Hồ sơ nhân vật)
CREATE TABLE IF NOT EXISTS `characters` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `japanese_name` VARCHAR(255) DEFAULT NULL,
    `nickname` VARCHAR(255) DEFAULT NULL,
    `avatar` VARCHAR(500) NOT NULL,
    `banner_image` VARCHAR(500) DEFAULT NULL,
    `birthday` VARCHAR(100) DEFAULT NULL,
    `height` VARCHAR(50) DEFAULT NULL,
    `weight` VARCHAR(50) DEFAULT NULL,
    `three_sizes` VARCHAR(100) DEFAULT NULL,
    `voice_actor` VARCHAR(255) DEFAULT NULL,
    `class_grade` VARCHAR(100) DEFAULT NULL,
    `profile` TEXT DEFAULT NULL,
    `personality` TEXT DEFAULT NULL,
    `detail` LONGTEXT DEFAULT NULL,
    `source_url` VARCHAR(500) DEFAULT NULL,
    `status` ENUM('active', 'hidden') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Bảng Character Stats (Tách riêng Chỉ số Đua, Tốc độ & Chấn thương)
CREATE TABLE IF NOT EXISTS `character_stats` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `character_id` INT NOT NULL UNIQUE,
    `min_speed` FLOAT NOT NULL DEFAULT 0.2,
    `max_speed` FLOAT NOT NULL DEFAULT 1.0,
    `speed_change_ms` INT NOT NULL DEFAULT 3000,
    `injury_rate` FLOAT NOT NULL DEFAULT 0.05,
    `event_rate` FLOAT NOT NULL DEFAULT 5.0,
    `base_speed` INT NOT NULL DEFAULT 100,
    `stamina` INT NOT NULL DEFAULT 100,
    `power` INT NOT NULL DEFAULT 100,
    `guts` INT NOT NULL DEFAULT 100,
    `wit` INT NOT NULL DEFAULT 100,
    `running_style` ENUM('Runner', 'Leader', 'Betweener', 'Chaser') NOT NULL DEFAULT 'Leader',
    `turf_aptitude` VARCHAR(10) NOT NULL DEFAULT 'A',
    `dirt_aptitude` VARCHAR(10) NOT NULL DEFAULT 'G',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_stats_character` FOREIGN KEY (`character_id`)
        REFERENCES `characters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Bảng News (Cổng thông tin tin tức, Sự kiện & Phần thưởng)
CREATE TABLE IF NOT EXISTS `news` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(500) NOT NULL,
    `category` VARCHAR(100) NOT NULL DEFAULT 'Event',
    `thumbnail` VARCHAR(500) DEFAULT NULL,
    `summary` TEXT DEFAULT NULL,
    `content` LONGTEXT DEFAULT NULL,
    `race_period_rewards` TEXT DEFAULT NULL,
    `important_information` TEXT DEFAULT NULL,
    `author_id` INT DEFAULT NULL,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `published_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_news_author` FOREIGN KEY (`author_id`)
        REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Bảng Gallery (Thư viện hình ảnh và biểu diễn)
CREATE TABLE IF NOT EXISTS `gallery` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `image` VARCHAR(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
