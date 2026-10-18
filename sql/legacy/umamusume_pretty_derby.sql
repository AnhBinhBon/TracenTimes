-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 31, 2026 lúc 09:24 AM
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
(1, 'Gold Ship', 'media/img/characters/gold_ship.png', 'March 6', '170cm', 'Cannot declare', 'B88 W55 H88', 'Hitomi Ueda', 'A highly eccentric and unpredictable Umamusume who does whatever she pleases. Despite her beautiful and elegant appearance, her behavior is completely bizarre, earning her a unique reputation at Tracen Academy. She possesses a mysterious physical strength and immense stamina.', 'She is free-spirited, mischievous, and often acts on strange whims. She enjoys teasing others and rarely takes things seriously off the track, but her raw racing potential is undeniable when she actually feels like running.', 'https://umamusume.fandom.com/wiki/Gold_Ship'),
(2, 'Tokai Teio', 'media/img/characters/tokai_teio.png', 'April 20', '150cm', 'No changes', 'B77 W53 H79', 'Machico', 'A cheerful, energetic Umamusume who idolizes the student council president, Symboli Rudolf. She possesses exceptional agility and a unique, springy running style known as the \"Teio Step\". Her ultimate goal is to become an undefeated Triple Crown Umamusume just like her idol.', 'Extremely confident, competitive, and optimistic. She can be a bit proud and cheeky, but her determination to overcome injuries and setbacks proves her incredible mental resilience. She hates losing more than anything.', 'https://umamusume.fandom.com/wiki/Tokai_Teio'),
(3, 'Agnes Tachyon', 'media/img/characters/anges_tachyon.png', 'April 13', '159cm', 'Refused measurement', 'B83 W55 H81', 'Sumire Uesaka', 'Equal parts researcher and runner, Agnes Tachyon pursues the limits of speed with the power of science. She will stop at nothing to quench her pure thirst for knowledge, performing all manner of experiments on living subjects—unauthorized, dangerous experiments, at that.', 'Intelligent, educated, and intensely curious. She is obsessed with her research and finding the outer limits of speed, often treating her Trainer as a \"guinea pig\" for her glowing, questionable energy drinks. She can be highly blunt and tunnel-visioned.', 'https://umamusume.fandom.com/wiki/Agnes_Tachyon'),
(4, 'Special Week', 'media/img/characters/special_week.png', 'May 2', '158cm', 'Minor fluctuations', 'B87 W57 H81', 'Azumi Waki', 'A pure, hardworking Umamusume who moved from Hokkaido to Tokyo to attend Tracen Academy. She promised her two mothers (her birth mother and her human adopted mother) that she would become the best Umamusume in Japan.', 'Earnest, innocent, and extremely diligent. She has a massive appetite (especially for carrots) and can be a bit naive or clumsy at times, but her sheer willpower and competitive spirit on the turf are unmatched.', 'https://umamusume.fandom.com/wiki/Special_Week');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gallery`
--

CREATE TABLE `gallery` (
  `ID` int(11) NOT NULL,
  `Ảnh` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `gallery`
--

INSERT INTO `gallery` (`ID`, `Ảnh`) VALUES
(1, 'media/img/galleries/media_gallery_1.png'),
(2, 'media/img/galleries/media_gallery_2.png'),
(3, 'media/img/galleries/media_gallery_3.png'),
(4, 'media/img/galleries/media_gallery_4.png'),
(5, 'media/img/galleries/media_gallery_5.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news`
--

CREATE TABLE `news` (
  `ID` int(11) NOT NULL,
  `Thời gian` datetime NOT NULL,
  `Tin tức` text NOT NULL,
  `race_period_rewards` text DEFAULT NULL COMMENT 'Race / Period / Rewards',
  `important_information` text DEFAULT NULL COMMENT 'Important Information'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `news`
--

INSERT INTO `news` (`ID`, `Thời gian`, `Tin tức`, `race_period_rewards`, `important_information`) VALUES
(1, '2026-05-01 02:29:18', 'Bonus Star Piece rewards in Career', 'Period: May 1 - May 7, 2026.\r\nRewards: Earn up to 2x extra Star Pieces for clearing URA Finals, AOHARU Cup, and Make a New Track scenarios.', 'These extra Star Pieces can be exchanged in the Shop for exclusive items, including Character Shards and Support Card limit-break items. \r\nNote: The bonus only applies to the first 3 Career runs per day.'),
(2, '2026-05-05 02:30:31', 'A Legend Race is coming soon!', 'Race: Legend Race vs. Mejiro McQueen (Kyoto Racecourse, 3200m Turf).\r\nPeriod: May 10 - May 15, 2026.\r\nRewards: Mejiro McQueen Character Shards, Jewels, and exclusive Legend Trophies.', 'Prepare your best Long-Distance runners! The opponent is highly skilled and has immense stamina.\r\nTrainer tip: Ensure your Umamusume has an S-rank in Stamina and equips rare recovery skills (like Circular Maestro) before challenging this race.'),
(3, '2026-05-06 02:32:49', 'Bonus Star Piece rewards in Career', 'Period: May 6 - May 12, 2026.\r\nRewards: Earn extra Star Pieces for clearing Grand Masters and Project L\'Arc scenarios.', 'This is the second phase of the Star Piece campaign. Make sure to complete your daily training runs to maximize your gains.\r\nDon\'t forget to claim your rewards from the Missions tab before the event period ends.'),
(4, '2026-05-06 21:35:10', 'Goldshi Week Celebration now underway!', 'Period: May 6 - May 20, 2026.\r\nRewards: Up to 5640 Jewels from the special Login Bonus, 564,000 Support Points, and an exclusive guaranteed SSR Support Card Ticket from Bingo Missions.', 'GOLSHI WEEK is back and more chaotic than ever!\r\n\"Gold Ship Mode\" is now unlocked in training scenarios. The difficulty of races will increase unpredictably, but the stat gains upon winning are massive.\r\nComplete all limited-time special missions to earn the grand prize!');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(2, 'Pretty_Derby', '$2y$10$Bq2eCRB/7fClFdfjmXlKUurgXrOxtiGedklfXrcnRFps/h53NXay.', 'user', '2026-05-21 03:33:25'),
(3, 'admin', '$2y$10$.Lxomm1PMcV.FhgqkFbV8eWXGnZXJljiSAvodG0Nw7yaEIQc68VnG', 'admin', '2026-05-21 04:04:13'),
(4, 'abc', '$2y$10$wIrUgGLe8tmY3keApRfsaO/u7Chk.hRHtEDqT4b.YKcyTLP4MzGby', 'user', '2026-05-28 03:11:43');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
