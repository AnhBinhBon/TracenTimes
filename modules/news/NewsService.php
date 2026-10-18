<?php
// ====================================================================
//  modules/news/NewsService.php — Business Logic Layer Tin tức
// ====================================================================

class NewsService {
    private NewsDAO $dao;

    public function __construct() {
        $this->dao = new NewsDAO();
    }

    public function getList(int $page = 1, int $perPage = 6, ?string $category = null, ?string $search = null): array {
        $page = max(1, $page);
        $total = $this->dao->countAll($category, $search);
        $totalPages = $total > 0 ? (int)ceil($total / $perPage) : 1;
        if ($page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $perPage;

        $items = $this->dao->getAll($perPage, $offset, $category, $search);

        return [
            'items'       => $items,
            'total'       => $total,
            'page'        => $page,
            'perPage'     => $perPage,
            'totalPages'  => $totalPages,
            'category'    => $category,
            'search'      => $search
        ];
    }

    public function getLatest(int $limit = 4): array {
        return $this->dao->getLatest($limit);
    }

    public function getFeatured(int $limit = 3): array {
        return $this->dao->getFeatured($limit);
    }

    public function getDetail(int $id): ?array {
        if ($id <= 0) return null;
        return $this->dao->getById($id);
    }

    /** Alias for getDetail matching architecture documentation */
    public function getNewsDetail(int $id): ?array {
        return $this->getDetail($id);
    }

    public function getCategories(): array {
        return $this->dao->getCategories();
    }

    public function save(array $data, ?int $id = null): array {
        $title = trim($data['title'] ?? '');
        if (empty($title)) {
            return ['success' => false, 'message' => 'Tiêu đề tin tức không được để trống.'];
        }

        $category = trim($data['category'] ?? 'Event');
        if (!in_array($category, $this->getCategories())) {
            $category = 'Event';
        }

        $publishedAt = trim($data['published_at'] ?? '');
        if (empty($publishedAt)) {
            $publishedAt = date('Y-m-d H:i:s');
        }

        $cleanData = [
            'title'                 => $title,
            'category'              => $category,
            'thumbnail'             => trim($data['thumbnail'] ?? '') ?: 'media/img/galleries/media_gallery_1.png',
            'summary'               => trim($data['summary'] ?? '') ?: null,
            'content'               => trim($data['content'] ?? '') ?: null,
            'race_period_rewards'   => trim($data['race_period_rewards'] ?? '') ?: null,
            'important_information' => trim($data['important_information'] ?? '') ?: null,
            'author_id'             => Auth::id() ?: 1,
            'is_featured'           => !empty($data['is_featured']) ? 1 : 0,
            'published_at'          => $publishedAt
        ];

        if ($id !== null && $id > 0) {
            $ok = $this->dao->update($id, $cleanData);
            return [
                'success' => $ok,
                'message' => $ok ? 'Cập nhật tin tức thành công!' : 'Có lỗi khi cập nhật tin tức.',
                'id'      => $id
            ];
        } else {
            $newId = $this->dao->create($cleanData);
            return [
                'success' => $newId > 0,
                'message' => $newId > 0 ? 'Đăng tin tức mới thành công!' : 'Không thể tạo bài viết.',
                'id'      => $newId
            ];
        }
    }

    public function delete(int $id): bool {
        if ($id <= 0) return false;
        return $this->dao->delete($id);
    }
}
