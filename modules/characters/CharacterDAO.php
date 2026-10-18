<?php
// ====================================================================
//  modules/characters/CharacterDAO.php — Data Access Layer Hồ sơ nhân vật
// ====================================================================

class CharacterDAO {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(?int $limit = null, int $offset = 0, ?string $search = null, string $status = 'active'): array {
        $sql = "SELECT * FROM `characters` WHERE 1=1";
        $params = [];

        if ($status !== 'all') {
            $sql .= " AND `status` = ?";
            $params[] = $status;
        }

        if (!empty($search)) {
            $sql .= " AND (`name` LIKE ? OR `japanese_name` LIKE ? OR `nickname` LIKE ?)";
            $term = '%' . $search . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $sql .= " ORDER BY `id` ASC";

        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countAll(?string $search = null, string $status = 'active'): int {
        $sql = "SELECT COUNT(*) FROM `characters` WHERE 1=1";
        $params = [];

        if ($status !== 'all') {
            $sql .= " AND `status` = ?";
            $params[] = $status;
        }

        if (!empty($search)) {
            $sql .= " AND (`name` LIKE ? OR `japanese_name` LIKE ? OR `nickname` LIKE ?)";
            $term = '%' . $search . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `characters` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Lấy đầy đủ thông tin nhân vật kèm các chỉ số đua ngựa (JOIN 2 bảng)
     */
    public function getWithStats(int $id): ?array {
        $sql = "SELECT c.*, 
                       s.id AS stat_id, s.min_speed, s.max_speed, s.speed_change_ms, 
                       s.injury_rate, s.event_rate, s.base_speed, s.stamina, s.power, 
                       s.guts, s.wit, s.running_style, s.turf_aptitude, s.dirt_aptitude, 
                       s.updated_at AS stats_updated_at
                FROM `characters` c
                LEFT JOIN `character_stats` s ON c.id = s.character_id
                WHERE c.id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row && !empty($row['stat_id'])) {
            $row['stats'] = [
                'id'              => $row['stat_id'],
                'min_speed'       => $row['min_speed'],
                'max_speed'       => $row['max_speed'],
                'speed_change_ms' => $row['speed_change_ms'],
                'injury_rate'     => $row['injury_rate'],
                'event_rate'      => $row['event_rate'],
                'base_speed'      => $row['base_speed'],
                'stamina'         => $row['stamina'],
                'power'           => $row['power'],
                'guts'            => $row['guts'],
                'wit'             => $row['wit'],
                'running_style'   => $row['running_style'],
                'turf_aptitude'   => $row['turf_aptitude'],
                'dirt_aptitude'   => $row['dirt_aptitude']
            ];
        }
        return $row ?: null;
    }

    public function getAllWithStats(?string $search = null, ?string $style = null, ?int $limit = null, int $offset = 0): array {
        $sql = "SELECT c.*, 
                       s.id AS stat_id, s.min_speed, s.max_speed, s.speed_change_ms, 
                       s.injury_rate, s.event_rate, s.base_speed, s.stamina, s.power, 
                       s.guts, s.wit, s.running_style, s.turf_aptitude, s.dirt_aptitude
                FROM `characters` c
                LEFT JOIN `character_stats` s ON c.id = s.character_id
                WHERE c.status = 'active'";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (c.name LIKE ? OR c.japanese_name LIKE ? OR c.nickname LIKE ?)";
            $term = '%' . $search . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        if (!empty($style)) {
            $sql .= " AND s.running_style = ?";
            $params[] = $style;
        }

        $sql .= " ORDER BY c.id ASC";

        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $sql = "INSERT INTO `characters` 
                (`name`, `japanese_name`, `nickname`, `avatar`, `image_url`, `banner_image`, `birthday`, 
                 `height`, `weight`, `three_sizes`, `voice_actor`, `class_grade`, `profile`, 
                 `personality`, `detail`, `source_url`, `status`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['name'],
            $data['japanese_name'] ?? null,
            $data['nickname'] ?? null,
            $data['avatar'] ?? null,
            $data['image_url'] ?? null,
            $data['banner_image'] ?? null,
            $data['birthday'] ?? null,
            $data['height'] ?? null,
            $data['weight'] ?? null,
            $data['three_sizes'] ?? null,
            $data['voice_actor'] ?? null,
            $data['class_grade'] ?? null,
            $data['profile'] ?? null,
            $data['personality'] ?? null,
            $data['detail'] ?? null,
            $data['source_url'] ?? null,
            $data['status'] ?? 'active'
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE `characters` SET 
                `name` = ?, `japanese_name` = ?, `nickname` = ?, `avatar` = ?, `image_url` = ?,
                `banner_image` = ?, `birthday` = ?, `height` = ?, `weight` = ?, 
                `three_sizes` = ?, `voice_actor` = ?, `class_grade` = ?, `profile` = ?, 
                `personality` = ?, `detail` = ?, `source_url` = ?, `status` = ?
                WHERE `id` = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['japanese_name'] ?? null,
            $data['nickname'] ?? null,
            $data['avatar'] ?? null,
            $data['image_url'] ?? null,
            $data['banner_image'] ?? null,
            $data['birthday'] ?? null,
            $data['height'] ?? null,
            $data['weight'] ?? null,
            $data['three_sizes'] ?? null,
            $data['voice_actor'] ?? null,
            $data['class_grade'] ?? null,
            $data['profile'] ?? null,
            $data['personality'] ?? null,
            $data['detail'] ?? null,
            $data['source_url'] ?? null,
            $data['status'] ?? 'active',
            $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM `characters` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }
}
