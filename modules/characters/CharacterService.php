<?php
// ====================================================================
//  modules/characters/CharacterService.php — Business Logic Layer Nhân vật
// ====================================================================

class CharacterService {
    private CharacterDAO $charDao;
    private StatsDAO $statsDao;

    public function __construct() {
        $this->charDao = new CharacterDAO();
        $this->statsDao = new StatsDAO();
    }

    public function getList(int $page = 1, int $perPage = 10, ?string $search = null, ?string $style = null): array {
        $page = max(1, $page);
        $total = $this->charDao->countAll($search);
        $totalPages = $total > 0 ? (int)ceil($total / $perPage) : 1;
        if ($page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $perPage;

        $items = $this->charDao->getAllWithStats($search, $style, $perPage, $offset);

        return [
            'items'       => $items,
            'total'       => $total,
            'page'        => $page,
            'perPage'     => $perPage,
            'totalPages'  => $totalPages,
            'search'      => $search,
            'style'       => $style
        ];
    }

    public function getFeatured(int $limit = 4): array {
        return $this->charDao->getAllWithStats(null, null, $limit, 0);
    }

    public function getDetail(int $id): ?array {
        if ($id <= 0) return null;
        return $this->charDao->getWithStats($id);
    }

    /** Alias for getDetail matching architecture documentation */
    public function getCharacterDetail(int $id): ?array {
        return $this->getDetail($id);
    }

    public function save(array $data, ?int $id = null): array {
        $name = trim($data['name'] ?? '');
        if (empty($name)) {
            return ['success' => false, 'message' => 'Tên nhân vật không được để trống.'];
        }

        $avatar = trim($data['avatar'] ?? '');
        $imageUrl = trim($data['image_url'] ?? '');

        $cleanData = [
            'name'          => $name,
            'japanese_name' => trim($data['japanese_name'] ?? '') ?: null,
            'nickname'      => trim($data['nickname'] ?? '') ?: null,
            'avatar'        => !empty($avatar) ? $avatar : null,
            'image_url'     => !empty($imageUrl) ? $imageUrl : null,
            'banner_image'  => trim($data['banner_image'] ?? '') ?: null,
            'birthday'      => trim($data['birthday'] ?? '') ?: null,
            'height'        => trim($data['height'] ?? '') ?: null,
            'weight'        => trim($data['weight'] ?? '') ?: null,
            'three_sizes'   => trim($data['three_sizes'] ?? '') ?: null,
            'voice_actor'   => trim($data['voice_actor'] ?? '') ?: null,
            'class_grade'   => trim($data['class_grade'] ?? '') ?: null,
            'profile'       => trim($data['profile'] ?? '') ?: null,
            'personality'   => trim($data['personality'] ?? '') ?: null,
            'detail'        => trim($data['detail'] ?? '') ?: null,
            'source_url'    => trim($data['source_url'] ?? '') ?: null,
            'status'        => in_array($data['status'] ?? '', ['active', 'hidden']) ? $data['status'] : 'active'
        ];

        if ($id !== null && $id > 0) {
            $ok = $this->charDao->update($id, $cleanData);
            return [
                'success' => $ok,
                'message' => $ok ? 'Cập nhật hồ sơ nhân vật thành công!' : 'Có lỗi khi cập nhật hồ sơ.',
                'id'      => $id
            ];
        } else {
            $newId = $this->charDao->create($cleanData);
            if ($newId > 0) {
                // Tự động khởi tạo 1 bản ghi chỉ số mặc định cho nhân vật mới trong character_stats
                $this->statsDao->createDefault($newId);
                return [
                    'success' => true,
                    'message' => 'Thêm nhân vật mới thành công!',
                    'id'      => $newId
                ];
            }
            return ['success' => false, 'message' => 'Không thể tạo mới nhân vật.'];
        }
    }

    public function delete(int $id): bool {
        if ($id <= 0) return false;
        return $this->charDao->delete($id);
    }
}
