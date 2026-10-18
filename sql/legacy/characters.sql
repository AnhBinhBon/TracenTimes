-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 14, 2026 lúc 04:24 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `umamusume pretty derby`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `characters`
--

CREATE TABLE `characters` (
  `ID` int(11) NOT NULL,
  `Tên nhân vật` varchar(256) NOT NULL,
  `Ảnh nhân vật` text NOT NULL,
  `birthday` varchar(100) DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `weight` varchar(50) DEFAULT NULL,
  `three_sizes` varchar(100) DEFAULT NULL,
  `voice_actor` varchar(255) DEFAULT NULL,
  `profile` text DEFAULT NULL,
  `personality` text DEFAULT NULL,
  `source_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `characters`
--

INSERT INTO `characters` (`ID`, `Tên nhân vật`, `Ảnh nhân vật`, `birthday`, `height`, `weight`, `three_sizes`, `voice_actor`, `profile`, `personality`, `source_url`) VALUES
(1, 'Gold Ship', 'media/img/characters/gold_ship.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Tokai Teio', 'media/img/characters/tokai_teio.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Anges Tachyon', 'media/img/characters/anges_tachyon.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Special Week', 'media/img/characters/special_week.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
