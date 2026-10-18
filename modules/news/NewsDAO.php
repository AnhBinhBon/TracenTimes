<?php
// ====================================================================
//  modules/news/NewsDAO.php — Data Access Layer Tin tức & Sự kiện
// ====================================================================

class NewsDAO {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(?int $limit = null, int $offset = 0, ?string $category = null, ?string $search = null): array {
        $sql = "SELECT n.*, u.username as author_name 
                FROM `news` n
                LEFT JOIN `users` u ON n.author_id = u.id
                WHERE 1=1";
        $params = [];

        if (!empty($category) && $category !== 'all') {
            $sql .= " AND n.category = ?";
            $params[] = $category;
        }

        if (!empty($search)) {
            $sql .= " AND (n.title LIKE ? OR n.summary LIKE ? OR n.content LIKE ?)";
            $term = '%' . $search . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $sql .= " ORDER BY n.published_at DESC";

        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countAll(?string $category = null, ?string $search = null): int {
        $sql = "SELECT COUNT(*) FROM `news` WHERE 1=1";
        $params = [];

        if (!empty($category) && $category !== 'all') {
            $sql .= " AND `category` = ?";
            $params[] = $category;
        }

        if (!empty($search)) {
            $sql .= " AND (`title` LIKE ? OR `summary` LIKE ? OR `content` LIKE ?)";
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
        $stmt = $this->db->prepare("SELECT n.*, u.username as author_name 
                                    FROM `news` n 
                                    LEFT JOIN `users` u ON n.author_id = u.id 
                                    WHERE n.id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getLatest(int $limit = 5): array {
        $stmt = $this->db->prepare("SELECT * FROM `news` ORDER BY `published_at` DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFeatured(int $limit = 3): array {
        $stmt = $this->db->prepare("SELECT * FROM `news` WHERE `is_featured` = 1 ORDER BY `published_at` DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetchAll();
        if (empty($res)) {
            return $this->getLatest($limit);
        }
        return $res;
    }

    public function create(array $data): int {
        $sql = "INSERT INTO `news` 
                (`title`, `category`, `thumbnail`, `summary`, `content`, `race_period_rewards`, `important_information`, `author_id`, `is_featured`, `published_at`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['title'],
            $data['category'] ?? 'Event',
            $data['thumbnail'] ?? null,
            $data['summary'] ?? null,
            $data['content'] ?? null,
            $data['race_period_rewards'] ?? null,
            $data['important_information'] ?? null,
            $data['author_id'] ?? null,
            $data['is_featured'] ?? 0,
            $data['published_at'] ?? date('Y-m-d H:i:s')
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE `news` SET 
                `title` = ?, `category` = ?, `thumbnail` = ?, `summary` = ?, 
                `content` = ?, `race_period_rewards` = ?, `important_information` = ?, 
                `is_featured` = ?, `published_at` = ?
                WHERE `id` = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['category'] ?? 'Event',
            $data['thumbnail'] ?? null,
            $data['summary'] ?? null,
            $data['content'] ?? null,
            $data['race_period_rewards'] ?? null,
            $data['important_information'] ?? null,
            $data['is_featured'] ?? 0,
            $data['published_at'] ?? date('Y-m-d H:i:s'),
            $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM `news` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }

    public function getCategories(): array {
        return ['Event', 'Race', 'Campaign', 'Update', 'Maintenance'];
    }
}
