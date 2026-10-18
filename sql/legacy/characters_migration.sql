-- ================================================================
-- characters_migration.sql
-- Mục đích: Thêm cột `Chi tiết` vào bảng characters
--           và chèn dữ liệu mẫu đủ để test phân trang (12 bản ghi)
-- Hướng dẫn: Chạy file này trong phpMyAdmin > SQL tab
-- ================================================================

USE `umamusume pretty derby`;

-- ── BƯỚC 1: Thêm cột `Chi tiết` vào bảng characters ──────────────
-- Nếu cột đã tồn tại, câu lệnh này sẽ báo lỗi (có thể bỏ qua)
ALTER TABLE `characters`
    ADD COLUMN `Chi tiết` TEXT DEFAULT NULL AFTER `Ảnh nhân vật`;

-- ── BƯỚC 2: Xóa dữ liệu cũ để tránh trùng lặp (tùy chọn) ────────
-- TRUNCATE TABLE `characters`;
-- AUTO_INCREMENT reset:
-- ALTER TABLE `characters` AUTO_INCREMENT = 1;

-- ── BƯỚC 3: Cập nhật chi tiết cho 4 nhân vật đã có ───────────────
UPDATE `characters` SET `Chi tiết` =
    'Gold Ship là một trong những nhân vật nổi tiếng và được yêu thích nhất trong Umamusume: Pretty Derby. Cô sở hữu tính cách hoang dã, tự do và đôi khi khó đoán, nhưng ẩn sâu trong đó là trái tim nồng nhiệt và lòng dũng cảm phi thường. Gold Ship luôn chạy theo cách của riêng mình, bất kể mọi quy tắc hay kỳ vọng của người khác. Đây chính là điều khiến cô trở thành một huyền thoại trên đường đua.'
WHERE `Tên nhân vật` = 'Gold Ship';

UPDATE `characters` SET `Chi tiết` =
    'Tokai Teio là hiện thân của ý chí kiên cường và tinh thần không bao giờ bỏ cuộc. Dù trải qua nhiều chấn thương nghiêm trọng, cô luôn đứng dậy và tiếp tục chiến đấu. Phong cách chạy đua của Teio đầy kịch tính và cảm xúc — cô luôn bùng cháy nhất ở đoạn cuối đường thẳng, tạo nên những khoảnh khắc không thể quên trong lịch sử đua ngựa.'
WHERE `Tên nhân vật` = 'Tokai Teio';

UPDATE `characters` SET `Chi tiết` =
    'Agnes Tachyon là thiên tài bí ẩn của Học viện Tracen. Với trí tuệ vượt trội và niềm đam mê bất tận với khoa học, cô thường xuyên bỏ qua các quy tắc và tiến hành các thí nghiệm theo cách riêng của mình. Mặc dù khó đoán, nhưng Agnes Tachyon sở hữu tốc độ và bản năng đua ngựa thuộc hàng hiếm có trong lịch sử Học viện.'
WHERE `Tên nhân vật` = 'Anges Tachyon';

UPDATE `characters` SET `Chi tiết` =
    'Special Week là cô gái đến từ vùng nông thôn Hokkaido với ước mơ trở thành "Uma Musume số 1 Nhật Bản". Tính cách vui vẻ, chân thành và nỗ lực không ngừng của cô đã chinh phục trái tim của mọi người. Special Week đại diện cho tinh thần lạc quan và sự cố gắng bền bỉ — biểu tượng của những ai dám mơ lớn.'
WHERE `Tên nhân vật` = 'Special Week';

-- ── BƯỚC 4: Chèn thêm 8 nhân vật mới để đủ 12 bản ghi ───────────
INSERT INTO `characters` (`Tên nhân vật`, `Ảnh nhân vật`, `Chi tiết`, `birthday`, `height`, `weight`, `three_sizes`, `voice_actor`, `profile`, `personality`, `source_url`) VALUES

('Silence Suzuka',
 'media/img/characters/silence_suzuka.png',
 'Silence Suzuka là hình mẫu lý tưởng và nguồn cảm hứng của Special Week. Cô chạy đua với phong thái lặng lẽ nhưng đầy uy lực, như một làn gió lướt qua đường đua. Sự im lặng của Suzuka không phải là yếu đuối — đó là sức mạnh nội tâm được nén chặt, chờ đến khoảnh khắc bùng nổ trên đường thẳng cuối cùng.',
 'September 27', '161cm', 'Unknown', 'Unknown', 'Marika Kouno',
 'A graceful and quiet runner who became a legend at Tracen Academy.',
 'Silent, elegant, deeply passionate about running.', 'https://umamusume.fandom.com/wiki/Silence_Suzuka'),

('Mejiro McQueen',
 'media/img/characters/mejiro_mcqueen.png',
 'Mejiro McQueen xuất thân từ dòng họ quý tộc Mejiro danh tiếng và mang trong mình niềm tự hào về di sản gia đình. Với tác phong kiêu hãnh và chuẩn mực, cô luôn đặt ra tiêu chuẻn cao nhất cho bản thân. Tuy nhiên, ẩn sau vẻ ngoài lạnh lùng ấy là một người bạn chân thành và trái tim đầy ấm áp.',
 'December 6', '164cm', 'Unknown', 'Unknown', 'Hinata Sato',
 'An aristocratic Uma Musume from the prestigious Mejiro lineage.',
 'Proud, elegant, secretly warm-hearted.', 'https://umamusume.fandom.com/wiki/Mejiro_McQueen'),

('Rice Shower',
 'media/img/characters/rice_shower.png',
 'Rice Shower mang danh hiệu "Kẻ phá hoại huyền thoại" nhưng thực chất cô chỉ là một cô gái rụt rè, nhút nhát và cực kỳ tốt bụng. Vì liên tục phá vỡ các kỷ lục của những ngôi sao khác, cô bị hiểu lầm và cô đơn. Hành trình của Rice Shower là câu chuyện về việc tìm thấy sự chấp nhận và tình bạn đích thực.',
 'December 6', '154cm', 'Unknown', 'Unknown', 'Maki Kawase',
 'A shy girl who earned the title of "legend breaker" despite her gentle nature.',
 'Timid, kind-hearted, deeply misunderstood.', 'https://umamusume.fandom.com/wiki/Rice_Shower'),

('Vodka',
 'media/img/characters/vodka.png',
 'Vodka là cô gái năng động với tính cách bồng bột và thẳng thắn. Cô luôn cạnh tranh gay gắt với Daiwa Scarlet nhưng hai người thực ra là đôi bạn thân không thể tách rời. Phong cách đua của Vodka mang đậm cá tính — táo bạo, quyết đoán và không bao giờ nhàm chán.',
 'August 17', '160cm', 'Unknown', 'Unknown', 'Yuka Otsubo',
 'A spirited runner with a fierce rivalry and deep friendship with Daiwa Scarlet.',
 'Energetic, blunt, fiercely competitive.', 'https://umamusume.fandom.com/wiki/Vodka'),

('Daiwa Scarlet',
 'media/img/characters/daiwa_scarlet.png',
 'Daiwa Scarlet là cô nàng tsundere kiêu ngạo nhưng thực ra rất quan tâm đến mọi người xung quanh. Cô không bao giờ thừa nhận tình bạn với Vodka nhưng luôn ở bên khi cần thiết. Trên đường đua, Scarlet chạy với sự tự tin tuyệt đối và kỹ thuật hoàn hảo được luyện tập kỹ lưỡng.',
 'April 29', '162cm', 'Unknown', 'Unknown', 'Rina Honizumi',
 'A tsundere ace runner who secretly cares deeply for her rival.',
 'Tsundere, proud, secretly compassionate.', 'https://umamusume.fandom.com/wiki/Daiwa_Scarlet'),

('El Condor Pasa',
 'media/img/characters/el_condor_pasa.png',
 'El Condor Pasa là cô gái đến từ Argentina với ước mơ chinh phục đường đua thế giới và đặc biệt là France. Với năng lượng vô tận, nụ cười rạng rỡ và tiếng hát luôn trên môi, cô mang đến bầu không khí vui tươi cho toàn bộ Học viện Tracen. Ước mơ quốc tế của El Condor Pasa đã truyền cảm hứng cho vô số thế hệ.',
 'May 16', '158cm', 'Unknown', 'Unknown', 'Machico',
 'An international Uma Musume dreaming of conquering the world stage.',
 'Cheerful, energetic, internationally ambitious.', 'https://umamusume.fandom.com/wiki/El_Condor_Pasa'),

('Symboli Rudolf',
 'media/img/characters/symboli_rudolf.png',
 'Symboli Rudolf là Hội trưởng Hội Học sinh quyền uy và được kính trọng nhất Học viện Tracen. Với tầm nhìn xa và lý tưởng cao cả về tương lai của Uma Musume, cô lãnh đạo bằng sự khôn ngoan và uy tín. Rudolf là nhân vật đại diện cho tầm cao của tiêu chuẩn và trách nhiệm lãnh đạo.',
 'March 13', '165cm', 'Unknown', 'Unknown', 'Yuki Nakashima',
 'The esteemed Student Council President and an undefeated champion.',
 'Composed, visionary, deeply responsible.', 'https://umamusume.fandom.com/wiki/Symboli_Rudolf'),

('Oguri Cap',
 'media/img/characters/oguri_cap.png',
 'Oguri Cap là nhân vật nổi tiếng với tình yêu ẩm thực vô song và năng lực đua ngựa đáng kinh ngạc. Cô luôn tìm kiếm món ăn ngon ở mọi nơi và không bao giờ từ chối bất kỳ thức ăn nào. Tuy nhiên, trên đường đua, Oguri Cap biến thành một đối thủ không thể xem thường với sức bền phi thường.',
 'March 27', '161cm', 'Unknown', 'Unknown', 'Nozomi Yamamoto',
 'A legendary runner with an equally legendary appetite for food.',
 'Carefree, food-obsessed, surprisingly powerful.', 'https://umamusume.fandom.com/wiki/Oguri_Cap');

-- ── BƯỚC 5: Thêm AUTO_INCREMENT cho tiện quản lý (nếu chưa có) ───
-- ALTER TABLE `characters` MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

-- ── Xác nhận kết quả ──────────────────────────────────────────────
SELECT `ID`, `Tên nhân vật`, LEFT(`Chi tiết`, 50) AS `Chi tiết (tóm tắt)` FROM `characters` ORDER BY `ID`;
