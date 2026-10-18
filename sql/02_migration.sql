-- ====================================================================
--  02_migration.sql — Script chuyển đổi dữ liệu từ CSDL cũ sang TracenTimes
--  Nguồn: `umamusume pretty derby` -> Đích: `tracen_times`
-- ====================================================================

USE `tracen_times`;

-- 1. Migration Users từ CSDL cũ (nếu tồn tại)
INSERT IGNORE INTO `tracen_times`.`users` (`id`, `username`, `password`, `role`, `created_at`)
SELECT `id`, `username`, `password`, COALESCE(`role`, 'user'), COALESCE(`created_at`, NOW())
FROM `umamusume pretty derby`.`users`
ON DUPLICATE KEY UPDATE `username` = VALUES(`username`);

-- 2. Migration Characters (Tách phần Hồ sơ)
INSERT INTO `tracen_times`.`characters` 
(`id`, `name`, `avatar`, `birthday`, `height`, `weight`, `three_sizes`, `voice_actor`, `profile`, `personality`, `detail`, `source_url`, `status`)
SELECT 
    `ID`, 
    `Tên nhân vật`, 
    `Ảnh nhân vật`, 
    `birthday`, 
    `height`, 
    `weight`, 
    `three_sizes`, 
    `voice_actor`, 
    `profile`, 
    `personality`, 
    COALESCE(`Chi tiết`, `profile`), 
    `source_url`, 
    'active'
FROM `umamusume pretty derby`.`characters`
ON DUPLICATE KEY UPDATE 
    `name` = VALUES(`name`),
    `avatar` = VALUES(`avatar`);

-- 3. Migration Character Stats (Tách phần Chỉ số Đua, Tốc độ, Chấn thương)
INSERT INTO `tracen_times`.`character_stats`
(`character_id`, `min_speed`, `max_speed`, `speed_change_ms`, `injury_rate`, `event_rate`, `base_speed`, `stamina`, `power`, `guts`, `wit`, `running_style`, `turf_aptitude`, `dirt_aptitude`)
SELECT 
    `ID`, 
    COALESCE(`min_speed`, 0.4), 
    COALESCE(`max_speed`, 1.5), 
    COALESCE(`speed_change_ms`, 3000), 
    COALESCE(`injury_rate`, 0.05), 
    COALESCE(`event_rate`, 8.0),
    450, 450, 450, 450, 450,
    'Leader', 'A', 'G'
FROM `umamusume pretty derby`.`characters`
ON DUPLICATE KEY UPDATE 
    `min_speed` = VALUES(`min_speed`),
    `max_speed` = VALUES(`max_speed`),
    `injury_rate` = VALUES(`injury_rate`);

-- 4. Migration News
INSERT INTO `tracen_times`.`news`
(`id`, `title`, `category`, `race_period_rewards`, `important_information`, `published_at`)
SELECT 
    `ID`, 
    `Tin tức`, 
    'Event', 
    `race_period_rewards`, 
    `important_information`, 
    `Thời gian`
FROM `umamusume pretty derby`.`news`
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);
