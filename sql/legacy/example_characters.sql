-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 16, 2026 lúc 04:17 AM
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
  `source_url` varchar(500) DEFAULT NULL,
  `min_speed` float DEFAULT 0.2,
  `max_speed` float DEFAULT 1.0,
  `speed_change_ms` int(11) DEFAULT 5000,
  `injury_rate` float DEFAULT 0.05,
  `event_rate` float DEFAULT 5.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `characters`
--

INSERT INTO `characters` (`ID`, `Tên nhân vật`, `Ảnh nhân vật`, `birthday`, `height`, `weight`, `three_sizes`, `voice_actor`, `profile`, `personality`, `source_url`, `min_speed`, `max_speed`, `speed_change_ms`, `injury_rate`, `event_rate`) VALUES
(1, 'Gold Ship', 'media/img/characters/gold_ship.png', 'March 6', '170cm', 'Cannot declare', 'B88 W55 H88', 'Hitomi Ueda', 'A highly eccentric and unpredictable Umamusume who does whatever she pleases. Despite her beautiful and elegant appearance, her behavior is completely bizarre, earning her a unique reputation at Tracen Academy. She possesses a mysterious physical strength and immense stamina.', 'She is free-spirited, mischievous, and often acts on strange whims. She enjoys teasing others and rarely takes things seriously off the track, but her raw racing potential is undeniable when she actually feels like running.', 'https://umamusume.fandom.com/wiki/Gold_Ship', 0.5, 2.0, 5000, 0.05, 20.0),
(2, 'Tokai Teio', 'media/img/characters/tokai_teio.png', 'April 20', '150cm', 'No changes', 'B77 W53 H79', 'Machico', 'A cheerful, energetic Umamusume who idolizes the student council president, Symboli Rudolf. She possesses exceptional agility and a unique, springy running style known as the "Teio Step". Her ultimate goal is to become an undefeated Triple Crown Umamusume just like her idol.', 'Extremely confident, competitive, and optimistic. She can be a bit proud and cheeky, but her determination to overcome injuries and setbacks proves her incredible mental resilience. She hates losing more than anything.', 'https://umamusume.fandom.com/wiki/Tokai_Teio', 0.8, 1.8, 3000, 0.15, 10.0),
(3, 'Agnes Tachyon', 'media/img/characters/anges_tachyon.png', 'April 13', '159cm', 'Refused measurement', 'B83 W55 H81', 'Sumire Uesaka', 'Equal parts researcher and runner, Agnes Tachyon pursues the limits of speed with the power of science. She will stop at nothing to quench her pure thirst for knowledge, performing all manner of experiments on living subjects—unauthorized, dangerous experiments, at that.', 'Intelligent, educated, and intensely curious. She is obsessed with her research and finding the outer limits of speed, often treating her Trainer as a "guinea pig" for her glowing, questionable energy drinks. She can be highly blunt and tunnel-visioned.', 'https://umamusume.fandom.com/wiki/Agnes_Tachyon', 0.3, 1.5, 1500, 0.02, 15.0),
(4, 'Special Week', 'media/img/characters/special_week.png', 'May 2', '158cm', 'Minor fluctuations', 'B87 W57 H81', 'Azumi Waki', 'A pure, hardworking Umamusume who moved from Hokkaido to Tokyo to attend Tracen Academy. She promised her two mothers (her birth mother and her human adopted mother) that she would become the best Umamusume in Japan.', 'Earnest, innocent, and extremely diligent. She has a massive appetite (especially for carrots) and can be a bit naive or clumsy at times, but her sheer willpower and competitive spirit on the turf are unmatched.', 'https://umamusume.fandom.com/wiki/Special_Week', 0.4, 1.6, 4000, 0.03, 5.0);

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
