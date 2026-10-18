<?php
// ====================================================================
//  modules/auth/AuthDAO.php — Data Access Layer cho Quản lý User
// ====================================================================

class AuthDAO {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByUsername(string $username): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE `username` = ? LIMIT 1");
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, username, role, created_at FROM `users` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(string $username, string $passwordHash, string $role = 'user'): int {
        $stmt = $this->db->prepare("INSERT INTO `users` (`username`, `password`, `role`) VALUES (?, ?, ?)");
        $stmt->execute([$username, $passwordHash, $role]);
        return (int)$this->db->lastInsertId();
    }

    public function countUsers(?string $search = null, ?string $role = null): int {
        $sql = "SELECT COUNT(*) FROM `users` WHERE 1=1";
        $params = [];
        if (!empty($search)) {
            $sql .= " AND `username` LIKE ?";
            $params[] = "%$search%";
        }
        if (!empty($role) && in_array($role, ['admin', 'user'])) {
            $sql .= " AND `role` = ?";
            $params[] = $role;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function countByRole(string $role): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM `users` WHERE `role` = ?");
        $stmt->execute([$role]);
        return (int)$stmt->fetchColumn();
    }

    public function getAllUsers(?string $search = null, ?string $role = null, int $limit = 20, int $offset = 0): array {
        $sql = "SELECT id, username, role, created_at FROM `users` WHERE 1=1";
        $params = [];
        if (!empty($search)) {
            $sql .= " AND `username` LIKE ?";
            $params[] = "%$search%";
        }
        if (!empty($role) && in_array($role, ['admin', 'user'])) {
            $sql .= " AND `role` = ?";
            $params[] = $role;
        }
        $sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        
        $idx = 1;
        foreach ($params as $p) {
            $stmt->bindValue($idx++, $p);
        }
        $stmt->bindValue($idx++, $limit, PDO::PARAM_INT);
        $stmt->bindValue($idx++, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isUsernameTaken(string $username, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) FROM `users` WHERE `username` = ?";
        $params = [$username];
        if ($excludeId !== null) {
            $sql .= " AND `id` != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return ((int)$stmt->fetchColumn()) > 0;
    }

    public function updateUser(int $id, string $username, ?string $passwordHash = null, string $role = 'user'): bool {
        if ($passwordHash) {
            $stmt = $this->db->prepare("UPDATE `users` SET `username` = ?, `password` = ?, `role` = ? WHERE `id` = ?");
            return $stmt->execute([$username, $passwordHash, $role, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE `users` SET `username` = ?, `role` = ? WHERE `id` = ?");
            return $stmt->execute([$username, $role, $id]);
        }
    }

    public function deleteUser(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM `users` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }
}
