-- ====================================================================
--  00_full_database_init.sql — Khởi tạo Toàn bộ Cơ sở Dữ liệu TracenTimes
--  Database: `tracen_times`
--  Bao gồm:
--    1. Tạo Database & Thiết lập UTF-8 Unicode
--    2. Cấu trúc toàn bộ bảng theo chuẩn Kiến trúc 3 Lớp (Users, Characters, Stats, News, Gallery)
--    3. Ràng buộc toàn vẹn dữ liệu (Foreign Keys: ON DELETE CASCADE / SET NULL)
--    4. Dữ liệu hạt giống hoàn chỉnh (Seed Data):
--       - Tài khoản Admin (admin/admin123) & Trainer (trainer/trainer123)
--       - 15 Umamusume với đầy đủ Hồ sơ cá nhân (Tiếng Anh + Tiếng Nhật)
--       - 15 Bộ chỉ số đua cân bằng chặt chẽ theo quy luật "High Risk - High Reward"
--       - 4 Bản tin Scout & Sự kiện cập nhật chuẩn theo Umamusu Wiki
--       - 5 Khung ảnh trình chiếu Media Gallery
-- ====================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

-- ── 1. KHỞI TẠO CƠ SỞ DỮ LIỆU ──────────────────────────────────────────
CREATE DATABASE IF NOT EXISTS `tracen_times`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `tracen_times`;

-- ── 2. CẤU TRÚC CÁC BẢNG (SCHEMA DEFINITION) ───────────────────────────

-- 2.1. Bảng Users (Tài khoản & Phân quyền)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2.2. Bảng Characters (Hồ sơ & Tiểu sử nhân vật)
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

-- 2.3. Bảng Character Stats (Chỉ số Đua, Tốc độ & Chấn thương - Quan hệ 1-1)
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

-- 2.4. Bảng News (Cổng thông tin tin tức, Sự kiện & Phần thưởng)
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

-- 2.5. Bảng Gallery (Thư viện hình ảnh sự kiện)
CREATE TABLE IF NOT EXISTS `gallery` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `image` VARCHAR(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── 3. NẠP DỮ LIỆU HẠT GIỐNG (SEED DATA) ──────────────────────────────

-- 3.1. USERS (Mật khẩu mặc định: admin123 / trainer123 mã hóa Bcrypt)
INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$7AK1D1BxDrJ8LRr81DHwQe6gaZsmj1WCydpiL4UQeXSfDhbr6IQ2y', 'admin'),
(2, 'trainer', '$2y$10$7AK1D1BxDrJ8LRr81DHwQe6gaZsmj1WCydpiL4UQeXSfDhbr6IQ2y', 'user')
ON DUPLICATE KEY UPDATE `password` = VALUES(`password`), `role` = VALUES(`role`);

-- 3.2. CHARACTERS (15 HỒ SƠ CHI TIẾT)
INSERT INTO `characters` (`id`, `name`, `japanese_name`, `nickname`, `avatar`, `banner_image`, `birthday`, `height`, `weight`, `three_sizes`, `voice_actor`, `class_grade`, `profile`, `personality`, `detail`, `source_url`, `status`) VALUES
(1, 'Special Week', 'スペシャルウィーク', 'Ngôi sao Hokkaido', 'media/img/characters/special_week.png', NULL, 'May 2', '158cm', 'Dao động nhỏ', 'B87 W57 H81', 'Azumi Waki', 'Trung học phổ thông', 
 'Cô gái thuần khiết, chăm chỉ chuyển từ vùng quê Hokkaido lên Tokyo để nhập học tại Học viện Tracen. Cô đã hứa với hai người mẹ của mình rằng sẽ trở thành Uma Musume số 1 Nhật Bản.',
 'Chân thành, ngây thơ và cực kỳ kiên trì. Đôi lúc vụng về và có niềm đam mê bất tận với cà rốt, nhưng ý chí thi đấu trên đường đua của cô là không gì sánh bằng.',
 'Special Week là hình tượng tiêu biểu cho sự nỗ lực không ngừng nghỉ. Sinh ra tại vùng nông thôn hẻo lánh, cô vượt qua muôn vàn khó khăn để bước chân vào giảng đường Tracen danh giá. Từng bước chạy của Special Week mang theo ước mơ của cả một vùng đất.',
 'https://umamusume.fandom.com/wiki/Special_Week', 'active'),

(2, 'Tokai Teio', 'トウカイテイオー', 'Kỳ tích Teio Step', 'media/img/characters/tokai_teio.png', NULL, 'April 20', '150cm', 'Không thay đổi', 'B77 W53 H79', 'Machico', 'Trung học cơ sở',
 'Một Uma Musume năng động, hoạt bát, thần tượng tuyệt đối Hội trưởng Symboli Rudolf. Cô sở hữu bước chạy dẻo dai đặc trưng mang tên Teio Step và ước mơ giành cú ăn ba Triple Crown bất bại.',
 'Cực kỳ tự tin, lạc quan và ghét thua cuộc hơn bất cứ thứ gì. Tinh thần kiên định vượt qua những chấn thương nặng nề của cô là tấm gương lớn cho toàn thể học viên.',
 'Dù nhiều lần đối mặt với chấn thương đe dọa sự nghiệp, Teio chưa bao giờ từ bỏ. Mỗi lần trở lại đường đua, những bước nhảy lò xo uyển chuyển của cô lại làm nổ tung cầu trường và chạm đến trái tim của hàng triệu khán giả.',
 'https://umamusume.fandom.com/wiki/Tokai_Teio', 'active'),

(3, 'Gold Ship', 'ゴールドシップ', 'Bất kham & Hoang dã', 'media/img/characters/gold_ship.png', NULL, 'March 6', '170cm', 'Không thể đo lường', 'B88 W55 H88', 'Hitomi Ueda', 'Trung học phổ thông',
 'Một Uma Musume vô cùng lập dị và khó lường, luôn hành động theo ý thích. Trái ngược với vẻ ngoài quý phái, hành vi của cô cực kỳ kỳ quặc nhưng lại ẩn chứa sức mạnh thể chất phi thường và sức bền vô hạn.',
 'Tự do, thích trêu chọc người khác và hiếm khi nghiêm túc ngoài đường đua. Tuy nhiên, khi cô thực sự muốn chạy, tiềm năng bứt tốc của cô là một huyền thoại không ai cản nổi.',
 'Gold Ship là biểu tượng của sự tự do tuyệt đối. Cô không bao giờ để bất kỳ quy chuẩn nào trói buộc mình. Từ những pha bứt phá từ vị trí chót bảng lên dẫn đầu, Golshi đã khắc tên mình vào lịch sử giải đấu.',
 'https://umamusume.fandom.com/wiki/Gold_Ship', 'active'),

(4, 'Agnes Tachyon', 'アグネスタキオン', 'Nhà nghiên cứu tốc độ', 'media/img/characters/anges_tachyon.png', NULL, 'April 13', '159cm', 'Từ chối cân', 'B83 W55 H81', 'Sumire Uesaka', 'Trung học phổ thông',
 'Nhà khoa học thiên tài đuổi theo giới hạn của tốc độ bằng sức mạnh tri thức. Cô không ngần ngại tiến hành các thí nghiệm khác thường trên chính cơ thể mình và huấn luyện viên.',
 'Thông minh, sắc sảo và luôn đắm chìm trong nghiên cứu. Đôi khi ăn nói thẳng thắn đến mức gây sốc, nhưng niềm đam mê khoa học và năng khiếu đua ngựa của cô là không thể bàn cãi.',
 'Agnes Tachyon tìm kiếm bản chất của vận tốc tột cùng. Các công thức độc quyền và thức uống phát sáng bí ẩn của cô thường mang lại những đợt tăng tốc vượt bậc nhưng cũng đi kèm với rủi ro chấn thương cần được kiểm soát kỹ lưỡng.',
 'https://umamusume.fandom.com/wiki/Agnes_Tachyon', 'active'),

(5, 'Silence Suzuka', 'サイレンススズカ', 'Làn gió tĩnh lặng', 'media/img/characters/silence_suzuka.png', NULL, 'September 27', '161cm', 'Chuẩn mực', 'B70 W53 H79', 'Marika Kouno', 'Trung học phổ thông',
 'Hình mẫu lý tưởng và nguồn cảm hứng bất tận của Special Week. Cô chạy đua với phong thái lặng lẽ nhưng tốc độ tiên phong khủng khiếp như một cơn lốc xoáy lướt qua mặt cỏ.',
 'Trầm tĩnh, tao nhã và dành trọn tình yêu cho việc chạy tự do ở phía trước hàng ngũ đối thủ.',
 'Silence Suzuka chỉ cảm thấy hạnh phúc khi nhìn thấy cảnh sắc phía trước không bị ai che khuất. Khả năng dẫn đầu từ đầu đến cuối chặng đua (Escape/Runner) của cô đạt đến độ hoàn mỹ.',
 'https://umamusume.fandom.com/wiki/Silence_Suzuka', 'active'),

(6, 'Mejiro McQueen', 'メジロマックイーン', 'Niềm kiêu hãnh gia tộc Mejiro', 'media/img/characters/mejiro_mcqueen.png', NULL, 'December 6', '164cm', 'Bí mật thiếu nữ', 'B71 W54 H76', 'Hinata Sato', 'Trung học phổ thông',
 'Hậu duệ danh gia vọng tộc Mejiro trứ danh. Với phong thái quý tộc chuẩn mực, cô luôn đặt ra tiêu chuẩn khắt khe nhất cho bản thân để bảo vệ danh dự gia đình trên các đường đua cự ly dài.',
 'Kiêu hãnh, tao nhã nhưng bí mật rất hảo ngọt và luôn lo lắng về cân nặng của mình.',
 'Được mệnh danh là Nữ hoàng cự ly dài (Long Distance), Mejiro McQueen có thể lực dồi dào, khả năng điều tiết nhịp độ hoàn hảo và trái tim ấm áp luôn hướng về đồng đội.',
 'https://umamusume.fandom.com/wiki/Mejiro_McQueen', 'active'),

(7, 'Rice Shower', 'ライスシャワー', 'Bông hồng đen nhút nhát', 'media/img/characters/rice_shower.png', NULL, 'December 6', '154cm', 'Nhẹ nhàng', 'B75 W51 H76', 'Maki Kawase', 'Trung học cơ sở',
 'Dù bị mệnh danh là Kẻ phá bĩnh kỷ lục, Rice Shower thực chất là cô bé rụt rè, mong muốn đem lại niềm vui cho mọi người. Cô luôn nỗ lực vượt qua định kiến để tỏa sáng.',
 'Nhút nhát, hiền lành nhưng sở hữu ý chí quật cường kiên định khi bước lên đường piste.',
 'Rice Shower chứng minh rằng ngay cả những cô gái nhỏ bé nhất cũng có thể tạo nên kỳ tích bằng sự khổ luyện và lòng dũng cảm phi thường.',
 'https://umamusume.fandom.com/wiki/Rice_Shower', 'active'),

(8, 'Vodka', 'ウオッカ', 'Ngọn lửa nổi loạn', 'media/img/characters/vodka.png', NULL, 'August 17', '160cm', 'Khỏe khoắn', 'B76 W55 H78', 'Yuka Otsubo', 'Trung học phổ thông',
 'Cô gái cá tính mạnh mẽ yêu thích phong cách ngầu và xe mô tô. Cô và Daiwa Scarlet vừa là kỳ phùng địch thủ không đội trời chung, vừa là cặp đôi bạn thân thiết nhất.',
 'Thẳng thắn, sôi nổi, luôn khao khát khẳng định bản thân bằng những cú tăng tốc bốc lửa.',
 'Phong cách đua của Vodka tràn đầy nội lực và tính ganh đua quyết liệt, sẵn sàng bung hết sức ở đoạn cua cuối cùng.',
 'https://umamusume.fandom.com/wiki/Vodka', 'active'),

(9, 'Daiwa Scarlet', 'ダイワスカーレット', 'Ngôi sao hoàn hảo', 'media/img/characters/daiwa_scarlet.png', NULL, 'April 29', '162cm', 'Cân đối', 'B90 W56 H82', 'Rina Honizumi', 'Trung học phổ thông',
 'Cô nàng tài sắc vẹn toàn luôn muốn trở thành số 1 trong mọi lĩnh vực. Tính cách hơi tsundere nhưng luôn hết lòng quan tâm và theo dõi từng bước đi của đối thủ Vodka.',
 'Kiêu kỳ, cầu toàn nhưng vô cùng nỗ lực và giàu lòng trắc ẩn.',
 'Daiwa Scarlet kết hợp hoàn hảo giữa thể lực dẻo dai và tư duy chiến thuật sắc sảo, luôn chiếm thế thượng phong ở cự ly trung bình.',
 'https://umamusume.fandom.com/wiki/Daiwa_Scarlet', 'active'),

(10, 'Symboli Rudolf', 'シンボリルドルフ', 'Hoàng đế bất bại', 'media/img/characters/symboli_rudolf.png', NULL, 'March 13', '165cm', 'Lý tưởng', 'B86 W59 H85', 'Azusa Tadokoro', 'Trung học phổ thông',
 'Hội trưởng Hội học sinh Học viện Tracen, người nắm giữ danh hiệu Hoàng đế bất bại. Với tầm nhìn vĩ đại, cô luôn nỗ lực vì tương lai tươi sáng của toàn thể Uma Musume.',
 'Uy nghiêm, thông thái, mẫu mực nhưng thỉnh thoảng rất thích chơi chữ (oyaji gag) khiến mọi người bất ngờ.',
 'Tượng đài bất khả chiến bại của trường Tracen, tấm gương sáng ngời về đạo đức và thực lực vô song.',
 'https://umamusume.fandom.com/wiki/Symboli_Rudolf', 'active'),

(11, 'Zenno Rob Roy', 'ゼンノロブロイ', 'Hero of Great Talent (ロブロイ)', 'https://umamusu.wiki/w/thumb.php?f=Zenno_Rob_Roy_(Main).png&width=360', 'https://umamusu.wiki/w/thumb.php?f=Game_Banner_130134_EN.png&width=704', 'March 27', '140cm', 'Không thay đổi (No change)', 'B89 / W56 / H78 (Cỡ giày: 21.0cm)', 'Haruka Terui (照井春佳)', 'Junior Division (Khối sơ trung)',
 'Một thủ thư chăm chỉ tại Học viện Tracen, người luôn khao khát được viết nên câu chuyện huyền thoại của riêng mình qua từng sải bước trên đường đua xanh.',
 'Dịu dàng, nhút nhát nhưng sở hữu ý chí quật cường. Cô là bạn cùng phòng của Rice Shower tại Ký túc xá Miho. Thích đọc tiểu thuyết thần thoại và có khả năng định vị phương hướng xuất chúng.',
 'Zenno Rob Roy là biểu tượng của tinh thần "anh hùng thầm lặng". Trong lịch sử, cô là chiến mã từng giành cú ăn ba mùa thu lịch sử gồm Tenno Sho (Thu), Japan Cup và Arima Kinen năm 2004.',
 'https://umamusu.wiki/Zenno_Rob_Roy', 'active'),

(12, 'Mayano Top Gun', 'マヤノトップガン', 'Free Spirit (Thần Ưng Nổi Loạn)', 'https://umamusu.wiki/w/thumb.php?f=Mayano_Top_Gun_(Main).png&width=360', 'https://umamusu.wiki/w/thumb.php?f=Game_Banner_130135_EN.png&width=704', 'March 24', '143cm', 'Không đổi (Lý tưởng)', 'B75 / W51 / H73 (Cỡ giày: 20.5cm)', 'Mio Hoshitani (星谷美緒)', 'Junior Division (Khối sơ trung)',
 'Cực kỳ tinh nghịch và tràn đầy năng lượng! Mayano có cha là phi công lái máy bay tiêm kích, vì vậy cô luôn nói bằng tiếng thuật ngữ không quân "Copy that! ☆".',
 'Thích tìm kiếm cảm giác hưng phấn tốc độ cao. Bạn cùng phòng của Tokai Teio tại Ký túc xá Ritto. Cực kỳ toàn năng trong mọi chiến thuật chạy từ Trốn (Runner) đến Vọt Đích (Chaser).',
 'Mayano Top Gun sở hữu trực giác thiên bẩm khó ai sánh bằng. Khả năng biến hóa chiến thuật linh hoạt giúp cô chinh phục Kikuka Sho, Arima Kinen và Tenno Sho Xuân.',
 'https://umamusu.wiki/Mayano_Top_Gun', 'active'),

(13, 'Air Groove', 'エアグルーヴ', 'Empress (Nữ Hoàng Khắt Khe)', 'https://umamusu.wiki/w/thumb.php?f=Air_Groove_(Main).png&width=360', 'https://umamusu.wiki/w/thumb.php?f=Game_Banner_130472_JP.png&width=704', 'April 6', '165cm', 'Tuyệt hảo (Amazing form)', 'B90 / W57 / H86 (Cỡ giày: 24.5cm)', 'Ruriko Aoki (青木瑠璃子)', 'Senior Division (Khối cao trung)',
 'Phó hội trưởng Hội Học Sinh Tracen Academy tài năng và nghiêm cẩn. Cô cùng Symboli Rudolf gánh vác việc dẫn lối thế hệ Umamusume kế cận hướng tới đỉnh cao vinh quang.',
 'Cực kỳ nghiêm khắc với sự lười biếng hay yếu đuối, hay mắng Trainer là "Đồ ngốc! (たわけ)". Tuy nhiên, cô lại là người yêu hoa và âm thầm chăm chút vườn hoa học viện mỗi sớm mai.',
 'Kế thừa dòng máu vô địch từ mẹ Dyna Carle, Nữ Hoàng Air Groove đã tạo nên địa chấn lịch sử khi trở thành ngựa cái đầu tiên sau 17 năm vô địch Tenno Sho Mùa Thu danh giá.',
 'https://umamusu.wiki/Air_Groove', 'active'),

(14, 'Narita Brian', 'ナリタブライアン', 'Monster in the Shadows (Quái Vật Bóng Tối)', 'https://umamusu.wiki/w/thumb.php?f=Narita_Brian_(Main).png&width=360', 'https://umamusu.wiki/w/thumb.php?f=Game_Banner_130473_JP.png&width=704', 'May 3', '160cm', 'Không cản trở đường chạy', 'B91 / W58 / H85 (Cỡ giày: 24.5cm)', 'Rika Kinugawa (衣川里佳)', 'Senior Division (Khối cao trung)',
 'Một sói cô độc ít nói nhưng mang sức mạnh áp đảo kinh hoàng. Narita Brian chạy để thỏa mãn cơn khát tốc độ vô tận của bản thân và san bằng mọi đối thủ.',
 'Lạnh lùng, quả quyết và ghét sự ồn ào vô nghĩa. Cô là em gái của Biwa Hayahide và bạn cùng phòng với Tanino Gimlet tại Ký túc xá Ritto.',
 'Được ca ngợi là một trong những nhà vô địch Triple Crown hủy diệt nhất lịch sử với những chiến thắng tạo cách biệt hàng chục chiều dài thân ngựa không tưởng.',
 'https://umamusu.wiki/Narita_Brian', 'active'),

(15, 'Sweep Tosho', 'スイープトウショウ', 'Pouty Witch (Tiểu Phù Thủy Hờn Dỗi)', 'https://umamusu.wiki/w/thumb.php?f=Sweep_Tosho_(Main).png&width=360', 'https://umamusu.wiki/w/thumb.php?f=Game_Banner_130474_JP.png&width=704', 'May 9', '139cm', 'Từ chối cân đo! (Refused measurement)', 'B72 / W53 / H73 (Cỡ giày: 19.0cm)', 'Shiori Sugiura (杉浦しおり)', 'Junior Division (Khối sơ trung)',
 'Cô bé phù thủy nhỏ luôn mang theo cây đũa phép và chiếc mũ chóp nhọn, tự nhận rằng sức mạnh chạy nước rút của mình bắt nguồn từ ma thuật kỳ bí.',
 'Cực kỳ bướng bỉnh, khó chiều và gọi Trainer của mình là "Familiar" (Linh thú phục dịch). Tuy nhiên, khi vào đoạn cua cuối, cú bứt tốc sấm sét của cô sẽ cuốn phăng tất cả.',
 'Nhà vô địch Shuka Sho và Takarazuka Kinen với phong cách "Chaser" (Vọt đích từ cuối đoàn đua) khiến khán giả luôn nín thở đến giây phút chạm vạch.',
 'https://umamusu.wiki/Sweep_Tosho', 'active')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- 3.3. CHARACTER STATS (CHỈ SỐ THI ĐẤU CÂN BẰNG: HIGH RISK - HIGH REWARD)
INSERT INTO `character_stats` (`character_id`, `min_speed`, `max_speed`, `speed_change_ms`, `injury_rate`, `event_rate`, `base_speed`, `stamina`, `power`, `guts`, `wit`, `running_style`, `turf_aptitude`, `dirt_aptitude`) VALUES
(1, 0.45, 1.55, 3000, 0.03, 8.5,  680, 660, 670, 700, 650, 'Leader', 'A', 'G'),    -- Special Week (3% chấn thương, an toàn, ổn định)
(2, 0.58, 1.95, 2300, 0.08, 13.0, 840, 660, 740, 890, 740, 'Leader', 'A', 'G'),    -- Tokai Teio (8% chấn thương gãy chân, Teio Step 1.95)
(3, 0.35, 1.62, 3500, 0.02, 22.0, 650, 880, 860, 750, 500, 'Chaser', 'A', 'G'),    -- Gold Ship (2% an toàn thể lực, thất thường 22% event)
(4, 0.62, 2.15, 2400, 0.11, 15.0, 890, 620, 720, 650, 920, 'Betweener', 'A', 'G'), -- Agnes Tachyon (11% độc dược thử nghiệm, 2.15 tên lửa)
(5, 0.65, 2.05, 1900, 0.09, 14.0, 960, 590, 710, 660, 760, 'Runner', 'A', 'G'),    -- Silence Suzuka (9% chấn thương bi kịch, 2.05 xé gió)
(6, 0.46, 1.50, 3200, 0.02, 7.0,  620, 850, 640, 720, 700, 'Leader', 'A', 'G'),    -- Mejiro McQueen (2% cực an toàn marathon)
(7, 0.44, 1.66, 2900, 0.04, 11.5, 670, 790, 690, 860, 650, 'Betweener', 'A', 'G'), -- Rice Shower (4% tiêu chuẩn, ý chí guts cao)
(8, 0.48, 1.72, 2800, 0.05, 9.5,  740, 630, 850, 700, 610, 'Betweener', 'A', 'F'), -- Vodka (5% tiêu chuẩn, uy lực đường trường)
(9, 0.52, 1.68, 2600, 0.04, 10.5, 760, 710, 730, 690, 740, 'Runner', 'A', 'G'),    -- Daiwa Scarlet (4% tiêu chuẩn, chắc chân top 2)
(10, 0.50, 1.62, 2500, 0.03, 10.0, 720, 750, 740, 730, 860, 'Leader', 'A', 'G'),   -- Symboli Rudolf (3% an toàn, Hoàng đế ổn định)
(11, 0.52, 1.78, 2700, 0.06, 9.0,  750, 730, 760, 710, 830, 'Betweener', 'A', 'G'), -- Zenno Rob Roy (6% tiêu chuẩn, chiến thuật đĩnh đạc)
(12, 0.58, 2.10, 2400, 0.10, 12.0, 850, 860, 760, 790, 750, 'Leader', 'A', 'G'),   -- Mayano Top Gun (10% phi cơ tiêm kích, 2.10 nhào lộn)
(13, 0.55, 1.86, 2500, 0.07, 10.0, 800, 750, 820, 740, 810, 'Leader', 'A', 'G'),   -- Air Groove (7% cảnh báo, nữ hoàng kỷ luật)
(14, 0.68, 2.30, 2200, 0.15, 14.0, 950, 830, 960, 810, 690, 'Leader', 'A', 'G'),   -- Narita Brian (15% rủi ro cao nhất, quái vật 2.30 tốc độ)
(15, 0.52, 2.22, 2700, 0.13, 16.0, 870, 610, 800, 730, 900, 'Chaser', 'A', 'G')    -- Sweep Tosho (13% phù thủy hờn dỗi, bứt tốc cuối 2.22)
ON DUPLICATE KEY UPDATE 
    `min_speed` = VALUES(`min_speed`),
    `max_speed` = VALUES(`max_speed`),
    `speed_change_ms` = VALUES(`speed_change_ms`),
    `injury_rate` = VALUES(`injury_rate`),
    `event_rate` = VALUES(`event_rate`),
    `base_speed` = VALUES(`base_speed`),
    `stamina` = VALUES(`stamina`),
    `power` = VALUES(`power`),
    `guts` = VALUES(`guts`),
    `wit` = VALUES(`wit`);

-- 3.4. NEWS (TIN TỨC GACHA & SCOUTS CẬP NHẬT 19 ITEMS)
INSERT INTO `news` (`id`, `title`, `category`, `thumbnail`, `summary`, `content`, `race_period_rewards`, `important_information`, `author_id`, `is_featured`, `published_at`) VALUES
(1, '[Global Scout] Spotlight Pretty Derby: [Heroic Author] Zenno Rob Roy', 'Event', 'media/img/galleries/media_gallery_1.png', 'Biểu ngữ tuyển trạch Spotlight Pretty Derby chính thức mở trên Global/English Server với tỷ lệ tăng cao cho Zenno Rob Roy.', 'Thông tin từ Umamusume Wiki (umamusu.wiki):
Spotlight Pretty Derby Scout mới đã chính thức bắt đầu trên cụm máy chủ Quốc Tế. Huấn luyện viên có cơ hội chiêu mộ phiên bản ★3 [Heroic Author] Zenno Rob Roy với tỷ lệ xuất hiện tăng lên 0.75%.

Chiến mã này sở hữu kỹ năng kích hoạt bứt tốc tuyệt vời ở cự ly trung bình và khả năng đọc đường đua mẫu mực.', 'Thời gian sự kiện: Đang diễn ra trực tiếp trên Global/EN Server.
Phần thưởng: Tăng tỷ lệ pickup ★3 Zenno Rob Roy (0.75%), nhận thêm Shards khi chiêu mộ thành công.', 'Nguồn dữ liệu đối chiếu: https://umamusu.wiki/Main_Page
Huấn luyện viên nên cân nhắc bảo lưu Carrots cho các sự kiện Anniversary sắp tới nếu đội hình đã đủ lực lượng Betweener cự ly trung bình.', 1, 1, '2026-10-15 12:00:00'),
(2, '[Global Scout] Thẻ Hỗ Trợ SSR: Light Hello & Mayano Top Gun', 'Campaign', 'media/img/galleries/media_gallery_2.png', 'Biểu ngữ Support Card Scout nổi bật với SSR [From the Ground Up] Light Hello (Pal) và SSR [Party Formation] Mayano Top Gun (Speed).', 'Cập nhật từ umamusu.wiki:
Đợt tuyển trạch thẻ hỗ trợ mới đem đến 2 quân bài chiến lược không thể thiếu trong kịch bản huấn luyện:
1. SSR [From the Ground Up] Light Hello (Loại Pal): Tối ưu hóa điểm năng lượng và cung cấp nhiều sự kiện hồi phục hữu ích.
2. SSR [Party Formation] Mayano Top Gun (Loại Speed): Gia tăng chỉ số tốc độ và cung cấp kỹ năng vượt rào bứt phá.', 'Tỷ lệ Pickup: 0.75% cho mỗi thẻ SSR xuất hiện trên biểu ngữ.
Thời gian: Đồng bộ với Spotlight Character Scout trên EN Server.', 'Lưu ý từ Tracen Academy: Thẻ Light Hello được đánh giá là một trong những thẻ hỗ trợ đa dụng hàng đầu cho mọi kịch bản huấn luyện Career.', 1, 1, '2026-10-14 10:00:00'),
(3, '[Japan Server] Pickup Gacha: [Adamant Sovereign] Symboli Rudolf & Air Groove', 'Update', 'media/img/galleries/media_gallery_3.png', 'Máy chủ Nhật Bản ra mắt trang phục thi đấu mới đầy quyền uy của Hội trưởng Symboli Rudolf và Nữ hoàng Air Groove.', 'Thông tin từ Umamusume Wiki JP Scouts:
Phiên bản trang phục mới [Adamant Sovereign] Symboli Rudolf cùng [悠久なるヘカテイア] Air Groove đã chính thức gia nhập đường đua Twinkle Series. Cùng với đó là đợt ra mắt cặp thẻ hỗ trợ Wit đẳng cấp cao: SSR [晩餐] Narita Brian và SSR [冷凜なるビスクドール] Admire Groove.', 'Pickups: ★3 [Adamant Sovereign] Symboli Rudolf (0.75%), ★3 Air Groove (0.75%), SSR Narita Brian (0.75%), SSR Admire Groove (0.75%).', 'Nguồn thông tin: https://umamusu.wiki/Game:List_of_Scouts_(JP)
Hệ thống chỉ số và kỹ năng độc quyền đã được cập nhật vào cơ sở dữ liệu TracenTimes.', 1, 0, '2026-10-13 09:30:00'),
(4, '[Twinkle Collection] Khai Mạc Gacha Tuyển Trạch 8 Chiến Mã Huyền Thoại', 'Race', 'media/img/galleries/media_gallery_4.png', 'Biểu ngữ Twinkle Collection đặc biệt quy tụ Gold City, Sweep Tosho, Satono Crown, Hishi Miracle và Chrono Genesis.', 'Trích xuất từ umamusu.wiki:
Bộ sưu tập Twinkle Collection Gacha mang đến cơ hội sở hữu 8 nhân vật được yêu thích bậc nhất:
- [Boho Flare] Gold City
- [リアライズ・ルーン] Sweep Tosho
- [逍遥、整然たる路を] Ikuno Dictus
- [黒翠千里行] Satono Crown
- [ソナタ・アッラ・メンタ] Sounds of Earth
- [ハッピー・リトル・ノーツ] Hishi Miracle
- [陸離の編纂者] Chrono Genesis
- [Miracle Author] Kiseki', 'Tỷ lệ mỗi nhân vật trong bộ sưu tập: 0.38%.
Thời gian hiệu lực: Kéo dài đến đầu tháng 11.', 'Tham khảo chi tiết kỹ năng và độ tương thích đường đua tại: https://umamusu.wiki/Main_Page', 1, 0, '2026-10-12 15:00:00'),
(5, '[EN] Spotlight Pretty Derby Scout', 'Gacha', 'https://umamusu.wiki/w/thumb.php?f=Game_Banner_130134_EN.png&width=704', 'Tăng tỷ lệ chiêu mộ đặc biệt cho Umamusume Zenno Rob Roy phiên bản [Heroic Author] tại máy chủ Global/English. Kết thúc: October 15, 2026', '<p>Tăng tỷ lệ chiêu mộ đặc biệt cho Umamusume Zenno Rob Roy phiên bản [Heroic Author] tại máy chủ Global/English.</p><h4>Tỷ lệ chiêu mộ tăng cường (Rate-up):</h4><ul><li><strong>[Heroic Author] Zenno Rob Roy</strong>: 0.75% (Playable Character)</li></ul><p><strong>Thời hạn:</strong> Kết thúc vào October 15, 2026</p><p><em>Nguồn thông tin chính thức: <a href=\"https://umamusu.wiki/Main_Page\" target=\"_blank\" rel=\"noopener\">Umamusume English Wiki</a></em></p>', '', '', 1, 0, '2026-10-18 13:25:55'),
(6, '[EN] Spotlight Support Card Scout', 'Gacha', 'https://umamusu.wiki/w/thumb.php?f=Game_Banner_130135_EN.png&width=704', 'Banner thẻ hỗ trợ nổi bật với SSR Friend Light Hello và SSR Speed Mayano Top Gun cho các bài tập huấn luyện cự ly trung bình. Kết thúc: October 15, 2026', '<p>Banner thẻ hỗ trợ nổi bật với SSR Friend Light Hello và SSR Speed Mayano Top Gun cho các bài tập huấn luyện cự ly trung bình.</p><h4>Tỷ lệ chiêu mộ tăng cường (Rate-up):</h4><ul><li><strong>SSR [From the Ground Up] Light Hello</strong>: 0.75% (Pal Support Card)</li><li><strong>SSR [Party Formation] Mayano Top Gun</strong>: 0.75% (Speed Support Card)</li></ul><p><strong>Thời hạn:</strong> Kết thúc vào October 15, 2026</p><p><em>Nguồn thông tin chính thức: <a href=\"https://umamusu.wiki/Main_Page\" target=\"_blank\" rel=\"noopener\">Umamusume English Wiki</a></em></p>', '', '', 1, 0, '2026-10-18 13:25:55'),
(7, '[JP] ピックアップ プリティーダービーガチャ (Pickup Pretty Derby Gacha)', 'Gacha', 'https://umamusu.wiki/w/thumb.php?f=Game_Banner_130472_JP.png&width=704', 'Phiên bản trang phục lễ phục vương giả của Hội trưởng Symboli Rudolf và Phó hội trưởng Air Groove. Kết thúc: October 13, 2026', '<p>Phiên bản trang phục lễ phục vương giả của Hội trưởng Symboli Rudolf và Phó hội trưởng Air Groove.</p><h4>Tỷ lệ chiêu mộ tăng cường (Rate-up):</h4><ul><li><strong>[Adamant Sovereign] Symboli Rudolf</strong>: 0.75% (Playable 3★)</li><li><strong>[悠久なるヘカテイア] Air Groove</strong>: 0.75% (Playable 3★)</li></ul><p><strong>Thời hạn:</strong> Kết thúc vào October 13, 2026</p><p><em>Nguồn thông tin chính thức: <a href=\"https://umamusu.wiki/Main_Page\" target=\"_blank\" rel=\"noopener\">Umamusume English Wiki</a></em></p>', '', '', 1, 0, '2026-10-18 13:25:55'),
(8, '[JP] ピックアップ サポートカードガチャ (Pickup Support Card Gacha)', 'Gacha', 'https://umamusu.wiki/w/thumb.php?f=Game_Banner_130473_JP.png&width=704', 'Cặp đôi thẻ Trí tuệ (Wit) Narita Brian và Admire Groove gia tăng khả năng kích hoạt kỹ năng tối thượng. Kết thúc: October 13, 2026', '<p>Cặp đôi thẻ Trí tuệ (Wit) Narita Brian và Admire Groove gia tăng khả năng kích hoạt kỹ năng tối thượng.</p><h4>Tỷ lệ chiêu mộ tăng cường (Rate-up):</h4><ul><li><strong>SSR [晩餐] Narita Brian (Wit)</strong>: 0.75% (Intelligence Support Card)</li><li><strong>SSR [冷凜なるビスクドール] Admire Groove (Wit)</strong>: 0.75% (Intelligence Support Card)</li></ul><p><strong>Thời hạn:</strong> Kết thúc vào October 13, 2026</p><p><em>Nguồn thông tin chính thức: <a href=\"https://umamusu.wiki/Main_Page\" target=\"_blank\" rel=\"noopener\">Umamusume English Wiki</a></em></p>', '', '', 1, 0, '2026-10-18 13:25:55'),
(9, '[JP] トゥインクルコレクション プリティーダービーガチャ (Twinkle Collection)', 'Gacha', 'https://umamusu.wiki/w/thumb.php?f=Game_Banner_130474_JP.png&width=704', 'Bộ sưu tập Twinkle Collection tập hợp 8 Umamusume tiêu biểu với trang phục thi đấu giới hạn. Kết thúc: November 02, 2026', '<p>Bộ sưu tập Twinkle Collection tập hợp 8 Umamusume tiêu biểu với trang phục thi đấu giới hạn.</p><h4>Tỷ lệ chiêu mộ tăng cường (Rate-up):</h4><ul><li><strong>[Boho Flare] Gold City</strong>: 0.38% (Playable 3★)</li><li><strong>[リアライズ・ルーン] Sweep Tosho</strong>: 0.38% (Playable 3★)</li><li><strong>[逍遥、整然たる路を] Ikuno Dictus</strong>: 0.38% (Playable 3★)</li><li><strong>[黒翠千里行] Satono Crown</strong>: 0.38% (Playable 3★)</li><li><strong>[ソナタ・アッラ・メンタ] Sounds of Earth</strong>: 0.38% (Playable 3★)</li><li><strong>[ハッピー・リトル・ノーツ] Hishi Miracle</strong>: 0.38% (Playable 3★)</li><li><strong>[陸離の編纂者] Chrono Genesis</strong>: 0.38% (Playable 3★)</li><li><strong>[Miracle Author] Kiseki</strong>: 0.38% (Playable 3★)</li></ul><p><strong>Thời hạn:</strong> Kết thúc vào November 02, 2026</p><p><em>Nguồn thông tin chính thức: <a href=\"https://umamusu.wiki/Main_Page\" target=\"_blank\" rel=\"noopener\">Umamusume English Wiki</a></em></p>', '', '', 1, 0, '2026-10-18 13:25:55'),
(10, '[Tournament] Giải Đấu Twinkle Series Mùa Thu: Khởi Tranh Cup Thiên Hoàng (Tenno Sho Autumn)', 'Tournament', 'media/img/galleries/media_gallery_1.png', 'Cuộc chạm trán nảy lửa cự ly trung bình 2000m tại Tokyo Racecourse với sự góp mặt của Mejiro McQueen, Tokai Teio và Narita Brian.', '<p>Không khí tại đấu trường Tokyo Racecourse đang nóng lên từng giờ khi ban tổ chức Twinkle Series chính thức khai mạc chặng đua danh giá nhất mùa thu: <strong>Tenno Sho (Autumn) 2026</strong>.</p>
<h4>Tâm Điểm Của Cuộc Đua</h4>
<p>Năm nay, cuộc đua quy tụ hơn 18 Mã Nương hàng đầu với phong độ đỉnh cao. Sự trở lại của <em>Tokai Teio</em> sau chuỗi ngày hồi phục chấn thương đã tạo nên làn sóng cổ vũ cuồng nhiệt từ khán đài.</p>
<ul>
    <li><strong>Cự ly tranh tài:</strong> 2,000m (Sân cỏ tự nhiên, rẽ trái).</li>
    <li><strong>Điều kiện mặt sân:</strong> Mặt cỏ khô ráo chuẩn quốc tế (Firm Turf).</li>
    <li><strong>Các ứng cử viên hàng đầu:</strong> Tokai Teio, Mejiro McQueen, Silence Suzuka, Narita Brian.</li>
</ul>
<p>Ban trọng tài Tracen Academy cam kết sẽ giám sát chặt chẽ các chỉ số an toàn và rủi ro quá tải thể lực của từng tuyển thủ nhằm bảo đảm tinh thần thể thao cao thượng nhất.</p>', 'Huy chương vàng Tenno Sho, 150.000 Carat, Danh hiệu \"Nữ Hoàng Mùa Thu 2026\"', 'Độc giả và Huấn luyện viên có thể theo dõi trực tiếp qua tính năng Live Racing trên cổng TracenTimes.', 1, 1, '2026-10-18 10:00:00'),
(11, '[Update] Bản Cập Nhật Hệ Thống 3.5: Tối Ưu Hóa Trí Tuệ Nài Ngựa & Chỉ Số Tăng Tốc', 'Update', 'media/img/galleries/media_gallery_2.png', 'Điều chỉnh cân bằng chiến thuật Chạy Dẫn Đầu (Runner) và Rút Đích Thần Tốc (Chaser), nâng cấp đồ họa trường đua mô phỏng.', '<p>Hội đồng Kỹ thuật Học viện Tracen đã chính thức phát hành bản cập nhật số hiệu 3.5.0 cho nền tảng mô phỏng đường đua Derby Cup.</p>
<h4>Chi Tiết Nâng Cấp Kỹ Thuật</h4>
<ul>
    <li><strong>Chiến thuật Dẫn Đầu (Runner):</strong> Tăng tốc độ xuất phát thêm 3.5% trong 400m đầu tiên, tăng nhẹ mức tiêu hao thể lực.</li>
    <li><strong>Chiến thuật Bứt Phá (Chaser):</strong> Tối ưu khả năng tìm khoảng trống khi ôm cua khúc ngoặt cuối (Final Corner).</li>
    <li><strong>Bảo vệ Thể lực:</strong> Cảnh báo nguy cơ chấn thương sẽ nhấp nháy đỏ khi tốc độ vượt quá 72 km/h.</li>
</ul>
<p>Bản cập nhật đã được áp dụng tự động trên toàn bộ máy chủ và không yêu cầu thời gian bảo trì gián đoạn.</p>', 'Gói quà cập nhật: 500 Năng lượng Huấn luyện viên, 30 Vé bốc thăm vật phẩm', 'Vui lòng làm mới trình duyệt (Ctrl + F5) nếu chưa thấy giao diện bục vinh quang mới.', 1, 0, '2026-10-18 11:30:00'),
(12, '[Event] Lễ Hội Văn Hóa Học Viện Tracen: Hội Chợ Ẩm Thực & Màn Biểu Diễn Winning Live', 'Event', 'media/img/galleries/media_gallery_3.png', 'Biko Pegasus cùng Oguri Cap mở màn gian hàng ẩm thực kỷ lục, hội tụ dàn Mã Nương xuất sắc nhất sau chuỗi ngày tập luyện căng thẳng.', '<p>Lễ hội Thường niên Tracen Culture Fest 2026 đã diễn ra tưng bừng với sự tham gia của hàng nghìn người hâm mộ từ khắp mọi miền.</p>
<h4>Những Điểm Nhấn Đáng Nhớ</h4>
<p>Gian hàng ramen khổng lồ do <em>Oguri Cap</em> và <em>Tamamo Cross</em> phụ trách đã lập kỷ lục bán hết 2.000 bát chỉ sau 3 giờ mở cửa. Trong khi đó, nhóm cổ động viên của <em>Gold Ship</em> mang đến những trò chơi giải đố đầy tính hài hước.</p>
<p>Đêm hội khép lại bằng sân khấu Winning Live hoành tráng với ca khúc biểu tượng <strong>\"Make Debut!\"</strong> dưới ánh đèn laser lung linh.</p>', 'Thẻ kỷ niệm Lễ hội Tracen 2026, Bộ ngoại trang Đồng phục Lễ hội độc quyền', 'Sự kiện mở cửa tự do cho tất cả học viên và huấn luyện viên đã đăng ký thẻ định danh.', 1, 1, '2026-10-18 13:15:00'),
(13, '[Character] Tiêu Điểm Mã Nương: Silence Suzuka — Cơn Lốc Bất Diệt Vượt Ranh Giới', 'Character', 'media/img/characters/silence_suzuka.png', 'Phân tích kỹ thuật chạy thoát đàn độc nhất vô nhị, hành trình hồi phục sau chấn thương mắt cá chân huyền thoại và khát vọng vô địch G1.', '<p>Được mệnh danh là <em>\"Cơn gió vô hình trên đường đua\"</em>, <strong>Silence Suzuka</strong> luôn mang lại cảm xúc vỡ òa cho khán giả bằng chiến thuật Dẫn đầu cô độc nhưng áp đảo hoàn toàn.</p>
<h4>Bí Quyết Tạo Nên Phong Cách Đua Huyền Thoại</h4>
<ul>
    <li><strong>Tốc độ xuất phát:</strong> Khả năng bứt phá đạt vận tốc 68 km/h chỉ sau 3 giây mở cổng.</li>
    <li><strong>Tâm lý thi đấu:</strong> Giữ nhịp thở đều đặn và không bị dao động trước sự bám đuổi của đối phương.</li>
    <li><strong>Sự đánh đổi:</strong> Tỷ lệ áp lực lên khớp chân cao đòi hỏi chế độ giãn cơ khắt khe sau mỗi cuộc thi.</li>
</ul>
<p>Suzuka khẳng định mục tiêu lớn nhất trong năm nay là cùng Huấn luyện viên chinh phục giải đấu quốc tế Japan Cup danh giá.</p>', 'Hồ sơ chuyên sâu Silence Suzuka, Mở khóa đoạn hội thoại Huấn luyện viên đặc biệt', 'Đọc thêm chi tiết tại trang Characters Wiki của TracenTimes.', 1, 0, '2026-10-18 14:00:00'),
(14, '[Guide] Cẩm Nang Huấn Luyện: Phương Pháp Phối Hợp Kỹ Năng & Kiểm Soát Rủi Ro', 'Guide', 'media/img/galleries/media_gallery_4.png', 'Phân tích chuyên sâu mối tương quan giữa Tốc độ đỉnh cao và Tỷ lệ kiệt sức, bí quyết bảo toàn thể lực cho các trận Derby đường dài 2400m.', '<p>Một trong những sai lầm phổ biến nhất của các tân Huấn luyện viên là dồn toàn bộ điểm huấn luyện vào Tốc độ (Speed) mà bỏ qua Độ bền (Stamina) và Ý chí (Guts).</p>
<h4>Ba Quy Tắc Vàng Trong Quản Lý Thể Lực</h4>
<ul>
    <li><strong>Quy tắc 1:</strong> Luôn duy trì chỉ số Thể lực tối thiểu bằng 70% cự ly đua quy đổi.</li>
    <li><strong>Quy tắc 2:</strong> Không kích hoạt kỹ năng tăng tốc quá sớm tại khúc cua số 2 để tránh hiện tượng căng cơ sớm.</li>
    <li><strong>Quy tắc 3:</strong> Định kỳ kiểm tra bảng theo dõi y tế sau mỗi 3 chặng đua liên tiếp.</li>
</ul>
<p>Áp dụng đúng phương pháp sẽ giúp giảm thiểu rủi ro chấn thương DNF từ 45% xuống mức an toàn dưới 10%.</p>', 'Sách hướng dẫn Huấn luyện viên cao cấp, Bộ học cụ phân tích đường đua', 'Tài liệu biên soạn bởi Ban Chuyên Môn Tracen Academy.', 1, 0, '2026-10-18 15:20:00'),
(15, '[Lore] Lịch Sử Đua Ngựa: Nguyên Mẫu Đời Thực Của Quái Kiệt Narita Brian', 'Lore', 'media/img/characters/narita_brian.png', 'Kỷ lục Triple Crown huyền thoại năm 1994, phong thái đeo Shadow Roll độc nhất vô nhij và tầm ảnh hưởng sâu sắc đến thế hệ ngựa đua hiện đại.', '<p>Trong lịch sử đua ngựa thuần chủng Nhật Bản, cái tên <strong>Narita Brian (ナリタブライアン)</strong> mãi mãi được khắc ghi như một trong những cỗ máy tốc độ hoàn hảo nhất thế kỷ 20.</p>
<h4>Kỷ Lục Tam Quan Vĩ Đại (1994)</h4>
<p>Tại giải Arima Kinen 1994, Narita Brian đã về đích cách biệt đối thủ đứng thứ nhì tới 3 độ dài thân ngựa, hoàn tất cú hat-trick Triple Crown làm rúng động toàn bộ giới mộ điệu châu Á.</p>
<p>Chiếc băng mũi Shadow Roll màu trắng đặc trưng không chỉ giúp che bóng mặt đất mà còn trở thành biểu tượng quyền lực không thể nhầm lẫn của Brian trong Umamusume Pretty Derby.</p>', 'Huy hiệu vinh danh Huyền Thoại 1994, Tư liệu lịch sử JRA lưu trữ', 'Được trích xuất từ văn khố lưu trữ thể thao quốc gia.', 1, 0, '2026-10-18 16:00:00'),
(16, '[Gacha] [JP Banner] Giới Thiệu Thẻ Hỗ Trợ SSR: Tokai Teio & Symboli Rudolf', 'Gacha', 'media/img/characters/tokai_teio.png', 'Tỷ lệ xuất hiện tăng vọt cho cặp đôi Hoàng gia, cung cấp tuyệt kỹ bứt phá khúc ngoặt cuối cùng cực kỳ đắt giá.', '<p>Kỳ tuyển chọn Gacha mới đã chính thức bắt đầu với tâm điểm là cặp thẻ hỗ trợ cực kỳ giá trị: <em>SSR Tokai Teio [Nụ Cười Vượt Gian Khổ]</em> và <em>SSR Symboli Rudolf [Uy Quyền Chủ Tịch]</em>.</p>
<h4>Kỹ Năng Đột Phá Đáng Chú Ý</h4>
<ul>
    <li><strong>Kỹ năng Teio:</strong> \"Step of Emperor\" — Tăng vọt tốc độ chuyển làn khi bị đối thủ kẹp sườn ở khúc cua cuối.</li>
    <li><strong>Kỹ năng Rudolf:</strong> \"Dominant Command\" — Giảm tiêu hao thể lực cho toàn bộ đồng đội chung cự ly trung bình.</li>
</ul>
<p>Thời hạn tuyển chọn diễn ra trong 14 ngày tới. Huấn luyện viên hãy nhanh tay tận dụng số Carat tích lũy!</p>', 'Bảo hiểm 200 lượt gacha nhận ngay Thẻ Tự Chọn SSR', 'Tỷ lệ SSR cơ bản là 3.0%, tỷ lệ đặc nhiệm chiếm 0.75%.', 1, 0, '2026-10-18 16:45:00'),
(17, '[Community] Tổng Kết Giải Đấu Mô Phỏng Derby Cup Mùa Khai Mạc Tháng 10', 'Community', 'media/img/characters/daiwa_scarlet.png', 'Hơn 1.200 lượt đua được ghi nhận, Vodka và Daiwa Scarlet tiếp tục so kè từng phần trăm giây trong những chặng nước rút nghẹt thở.', '<p>Giải đấu cộng đồng Tracen Times Derby Cup mô phỏng mùa đầu tiên đã khép lại thành công rực rỡ với hàng loạt kỷ lục mới được thiết lập.</p>
<h4>Top Thành Tích Nổi Bật</h4>
<p>Cặp kỳ phùng địch thủ <strong>Daiwa Scarlet</strong> và <strong>Vodka</strong> đã tạo nên trận chung kết nghẹt thở khi cách biệt tại vạch đích chỉ là <em>0.02 giây</em>. Khán đài đã bùng nổ khi hình ảnh phân định bằng camera chiếu chậm (Photo Finish) được công bố.</p>
<p>Ban tổ chức xin gửi lời cảm ơn chân thành đến tất cả Huấn luyện viên đã tham gia thử nghiệm hệ thống.</p>', 'Kỷ niệm chương Huấn luyện viên danh dự, 10.000 Điểm kinh nghiệm hồ sơ', 'Bảng xếp hạng tổng thể có thể xem trực tiếp tại trang Racing.', 1, 0, '2026-10-18 17:30:00'),
(18, '[Medical] Báo Cáo Y Tế Học Viện: Phác Đồ Trị Liệu Thể Lực & Phục Hồi Thần Tốc', 'Medical', 'media/img/galleries/media_gallery_5.png', 'Trung tâm Y tế Tracen công bố hướng dẫn dinh dưỡng và liệu trình spa phục hồi dành cho các Uma có tỷ lệ chấn thương trên 30%.', '<p>Nhằm giảm thiểu tỷ lệ vận động viên phải bỏ cuộc giữa chặng (DNF), Trung tâm Phục hồi Chức năng Học viện Tracen đã công bố bản hướng dẫn y tế mùa giải 2026.</p>
<h4>Các Biện Pháp Phục Hồi Thiết Yếu</h4>
<ul>
    <li><strong>Liệu pháp Ngâm Khoáng Nóng:</strong> Kích thích tuần hoàn máu và thải trừ axit lactic sau 45 phút chạy cường độ cao.</li>
    <li><strong>Thực đơn Bổ Sung Collagen Thảo Mộc:</strong> Tăng cường độ dẻo dai của dây chằng mắt cá chân và khớp gối.</li>
    <li><strong>Theo dõi Điện Não Đồ Trước Giờ Đua:</strong> Kiểm tra mức độ căng thẳng thần kinh để điều chỉnh tâm lý ổn định.</li>
</ul>
<p>Các Huấn luyện viên cần liên hệ trực tiếp với bác sĩ Tracen khi chỉ số rủi ro của Uma vượt mức 25%.</p>', 'Thẻ khám sức khỏe miễn phí toàn diện, Bộ dược liệu thảo mộc dưỡng khớp', 'Được chứng nhận bởi Hội Đồng Y Học Thể Thao Tracen.', 1, 0, '2026-10-18 18:15:00'),
(19, '[Interview] Phỏng Vấn Độc Quyền: Rice Shower & Giọt Nước Mắt Đầy Cảm Xúc', 'Interview', 'media/img/characters/rice_shower.png', 'Vượt qua định kiến \"kẻ ngáng đường lịch sử\", Rice Shower chia sẻ về lòng kiên định và tình cảm chân thành dành cho người hâm mộ.', '<p>Trong buổi trò chuyện ấm cúng cùng phóng viên TracenTimes tại phòng trà học viện, <strong>Rice Shower</strong> đã trải lòng về những tháng ngày đối mặt với sức ép vô hình từ dư luận.</p>
<h4>Tâm Sự Của Bông Hoa Xanh Bé Nhỏ</h4>
<p><em>\"Em từng rất sợ khi mọi người gọi em là kẻ phá hỏng giấc mơ của người khác... Nhưng Huấn luyện viên đã nắm tay em và nói rằng: Mỗi nỗ lực trên đường đua đều xứng đáng được tôn trọng. Em chạy không phải để cản ai, em chạy vì ước mơ của chính mình.\"</em></p>
<p>Lời chia sẻ chân thành của Rice Shower đã chạm đến trái tim của hàng triệu khán giả và trở thành nguồn cảm hứng bất tận cho thế hệ đàn em.</p>', 'Bộ bưu thiếp có chữ ký Rice Shower, Bản ghi âm độc quyền lời cảm ơn Huấn luyện viên', 'Phát sóng độc quyền trên chuyên trang TracenTimes Magazine.', 1, 1, '2026-10-18 19:00:00')
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `content` = VALUES(`content`), `thumbnail` = VALUES(`thumbnail`);


-- 3.5. GALLERY (5 KHUNG HÌNH TRÌNH DIỄN MEDIA)
INSERT INTO `gallery` (`id`, `image`) VALUES
(1, 'media/img/galleries/media_gallery_1.png'),
(2, 'media/img/galleries/media_gallery_2.png'),
(3, 'media/img/galleries/media_gallery_3.png'),
(4, 'media/img/galleries/media_gallery_4.png'),
(5, 'media/img/galleries/media_gallery_5.png')
ON DUPLICATE KEY UPDATE `image` = VALUES(`image`);

SET FOREIGN_KEY_CHECKS = 1;

-- ====================================================================
--  HOÀN TẤT KHỞI TẠO CƠ SỞ DỮ LIỆU TRACENTIMES THÀNH CÔNG!
-- ====================================================================
