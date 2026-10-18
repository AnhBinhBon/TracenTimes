<?php
// ====================================================================
//  modules/stats/StatsService.php — Business Logic Layer Chỉ số & Đua ngựa
// ====================================================================

class StatsService {
    private StatsDAO $dao;

    public function __construct() {
        $this->dao = new StatsDAO();
    }

    public function getAll(?string $search = null): array {
        return $this->dao->getAllWithCharacterInfo($search);
    }

    /** Alias for getAll matching architecture documentation */
    public function getAllWithCharacter(?string $search = null): array {
        return $this->getAll($search);
    }

    public function getByCharacterId(int $charId): ?array {
        return $this->dao->getByCharacterId($charId);
    }

    public function getSummary(): array {
        return $this->dao->getSummary();
    }

    public function updateStats(int $charId, array $data): array {
        if ($charId <= 0) {
            return ['success' => false, 'message' => 'ID nhân vật không hợp lệ.'];
        }

        $minSpeed = (float)($data['min_speed'] ?? 0.2);
        $maxSpeed = (float)($data['max_speed'] ?? 1.0);
        $speedChangeMs = (int)($data['speed_change_ms'] ?? 3000);
        
        // Xử lý tỷ lệ chấn thương: nếu người dùng nhập dạng % (ví dụ: 5%) thì đổi ra 0.05
        $injuryRaw = (float)($data['injury_rate'] ?? 0.05);
        if ($injuryRaw > 1.0) {
            $injuryRate = $injuryRaw / 100.0;
        } else {
            $injuryRate = $injuryRaw;
        }
        $injuryRate = max(0.0, min(1.0, $injuryRate));

        $eventRate = (float)($data['event_rate'] ?? 5.0);

        // Validation logic
        if ($minSpeed <= 0) {
            return ['success' => false, 'message' => 'Tốc độ tối thiểu phải lớn hơn 0.'];
        }
        if ($maxSpeed <= $minSpeed) {
            return ['success' => false, 'message' => 'Tốc độ tối đa phải lớn hơn tốc độ tối thiểu.'];
        }
        if ($speedChangeMs < 500) {
            $speedChangeMs = 500;
        }

        $cleanData = [
            'min_speed'       => $minSpeed,
            'max_speed'       => $maxSpeed,
            'speed_change_ms' => $speedChangeMs,
            'injury_rate'     => $injuryRate,
            'event_rate'      => max(0.0, min(100.0, $eventRate)),
            'base_speed'      => max(1, min(2000, (int)($data['base_speed'] ?? 100))),
            'stamina'         => max(1, min(2000, (int)($data['stamina'] ?? 100))),
            'power'           => max(1, min(2000, (int)($data['power'] ?? 100))),
            'guts'            => max(1, min(2000, (int)($data['guts'] ?? 100))),
            'wit'             => max(1, min(2000, (int)($data['wit'] ?? 100))),
            'running_style'   => in_array($data['running_style'] ?? '', ['Runner', 'Leader', 'Betweener', 'Chaser']) ? $data['running_style'] : 'Leader',
            'turf_aptitude'   => strtoupper(trim($data['turf_aptitude'] ?? 'A')),
            'dirt_aptitude'   => strtoupper(trim($data['dirt_aptitude'] ?? 'G'))
        ];

        $ok = $this->dao->updateByCharacterId($charId, $cleanData);
        return [
            'success' => $ok,
            'message' => $ok ? 'Cập nhật chỉ số thi đấu thành công!' : 'Có lỗi khi cập nhật chỉ số.'
        ];
    }
}
