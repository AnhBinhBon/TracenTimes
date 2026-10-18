<?php
// ====================================================================
//  modules/stats/StatsDAO.php — Data Access Layer Chỉ số & Đua ngựa
// ====================================================================

class StatsDAO {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getByCharacterId(int $charId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `character_stats` WHERE `character_id` = ? LIMIT 1");
        $stmt->execute([$charId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getAllWithCharacterInfo(?string $search = null): array {
        $sql = "SELECT s.*, c.name, c.japanese_name, c.avatar, c.status
                FROM `character_stats` s
                JOIN `characters` c ON s.character_id = c.id
                WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (c.name LIKE ? OR c.japanese_name LIKE ?)";
            $term = '%' . $search . '%';
            $params[] = $term;
            $params[] = $term;
        }

        $sql .= " ORDER BY c.id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function createDefault(int $charId): bool {
        // Kiểm tra xem đã có bản ghi chưa
        if ($this->getByCharacterId($charId)) {
            return true;
        }
        $sql = "INSERT INTO `character_stats` 
                (`character_id`, `min_speed`, `max_speed`, `speed_change_ms`, `injury_rate`, `event_rate`, 
                 `base_speed`, `stamina`, `power`, `guts`, `wit`, `running_style`, `turf_aptitude`, `dirt_aptitude`)
                VALUES (?, 0.45, 1.50, 3000, 0.05, 8.0, 450, 450, 450, 450, 450, 'Leader', 'A', 'G')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$charId]);
    }

    public function updateByCharacterId(int $charId, array $data): bool {
        // Nếu chưa có, tự tạo trước
        if (!$this->getByCharacterId($charId)) {
            $this->createDefault($charId);
        }

        $sql = "UPDATE `character_stats` SET 
                `min_speed` = ?, `max_speed` = ?, `speed_change_ms` = ?, 
                `injury_rate` = ?, `event_rate` = ?, `base_speed` = ?, 
                `stamina` = ?, `power` = ?, `guts` = ?, `wit` = ?, 
                `running_style` = ?, `turf_aptitude` = ?, `dirt_aptitude` = ?
                WHERE `character_id` = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['min_speed'],
            $data['max_speed'],
            $data['speed_change_ms'],
            $data['injury_rate'],
            $data['event_rate'],
            $data['base_speed'],
            $data['stamina'],
            $data['power'],
            $data['guts'],
            $data['wit'],
            $data['running_style'],
            $data['turf_aptitude'],
            $data['dirt_aptitude'],
            $charId
        ]);
    }

    public function getSummary(): array {
        $sql = "SELECT 
                    COUNT(*) as total_stats,
                    MIN(min_speed) as lowest_min_speed,
                    MAX(max_speed) as highest_max_speed,
                    AVG(injury_rate) as avg_injury_rate,
                    MAX(injury_rate) as max_injury_rate,
                    MIN(injury_rate) as min_injury_rate,
                    AVG(base_speed) as avg_base_speed
                FROM `character_stats`";
        return $this->db->query($sql)->fetch() ?: [];
    }
}
