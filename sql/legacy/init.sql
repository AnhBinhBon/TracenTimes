-- 1. Create the Database
CREATE DATABASE IF NOT EXISTS umamusume_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE umamusume_db;

-- 2. Drop existing tables to ensure a clean slate if re-running
DROP TABLE IF EXISTS media_gallery;
DROP TABLE IF EXISTS characters;
DROP TABLE IF EXISTS news;

-- 3. Create the 'news' Table
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date VARCHAR(20) NOT NULL,
    label VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL
);

-- 4. Create the 'characters' Table
CREATE TABLE characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image VARCHAR(255) NOT NULL
);

-- 5. Create the 'media_gallery' Table
CREATE TABLE media_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    content VARCHAR(255) NOT NULL
);

-- 6. Insert Mock Data for News
INSERT INTO news (date, label, title) VALUES
('23/04/2026', 'SỰ KIỆN', 'Sự kiện ra mắt nhân vật mới cực kỳ hấp dẫn!'),
('20/04/2026', 'CẬP NHẬT', 'Bản cập nhật mùa xuân với nhiều tính năng mới'),
('15/04/2026', 'THÔNG BÁO', 'Bảo trì hệ thống định kỳ máy chủ');

-- 7. Insert Mock Data for Characters
INSERT INTO characters (name, image) VALUES
('Nhân vật 01', '[Ảnh Avatar: media/img/char_01.png]'),
('Nhân vật 02', '[Ảnh Avatar: media/img/char_02.png]'),
('Nhân vật 03', '[Ảnh Avatar: media/img/char_03.png]'),
('Nhân vật 04', '[Ảnh Avatar: media/img/char_04.png]');

-- 8. Insert Mock Data for Media Gallery
INSERT INTO media_gallery (type, content) VALUES
('Image', '[Thumbnail: media/img/gallery_01.jpg]'),
('Image', '[Thumbnail: media/img/gallery_02.jpg]'),
('Video', '[Video: media/video/trailer_01.mp4]'),
('Image', '[Thumbnail: media/img/gallery_03.jpg]');
