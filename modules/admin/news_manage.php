<?php
// ====================================================================
//  modules/admin/news_manage.php — Quản lý Danh sách Tin tức
// ====================================================================

require_once __DIR__ . '/../../config/app.php';

$newsService = new NewsService();

// Xử lý Xóa tin tức
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $deleteId = (int)($_POST['id'] ?? 0);
    if ($deleteId > 0) {
        $res = $newsService->delete($deleteId);
        if ($res) {
            Helper::setFlash('success', 'Đã xóa bài viết thành công!');
        } else {
            Helper::setFlash('error', 'Không thể xóa bài viết này.');
        }
    }
    header('Location: ' . Helper::url('modules/admin/news_manage.php'));
    exit();
}

$page = (int)($_GET['page'] ?? 1);
$category = trim($_GET['category'] ?? '');
$search = trim($_GET['search'] ?? '');

$data = $newsService->getList($page, 10, $category, $search);
$newsList = $data['items'];
$categories = $newsService->getCategories();

$pageTitle = 'Quản Lý Tin Tức & Sự Kiện';
$pageIcon  = 'fa-solid fa-newspaper';

include __DIR__ . '/../../includes/admin_header.php';
?>

<div class="admin-box">
    <div class="admin-box__header">
        <h2 class="admin-box__title"><i class="fas fa-list"></i> Danh sách tất cả bài viết</h2>
        <a href="<?= Helper::url('modules/admin/news_form.php') ?>" class="btn-adm btn-adm-primary">
            <i class="fas fa-plus"></i> Đăng Tin Mới
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" style="display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Tìm kiếm tiêu đề, nội dung..." class="form-input" style="max-width:320px;">
        <select name="category" class="form-select" style="max-width:180px;">
            <option value="">Tất cả chuyên mục</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat ?>" <?= ($category === $cat) ? 'selected' : '' ?>><?= $cat ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-adm btn-adm-secondary">
            <i class="fas fa-search"></i> Lọc
        </button>
        <?php if (!empty($search) || !empty($category)): ?>
            <a href="<?= Helper::url('modules/admin/news_manage.php') ?>" class="btn-adm btn-adm-secondary" title="Đặt lại bộ lọc">
                <i class="fas fa-rotate-left"></i>
            </a>
        <?php endif; ?>
    </form>

    <div class="admin-table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:55px; text-align:center;">ID</th>
                    <th style="width:80px; text-align:center;">Ảnh bìa</th>
                    <th style="max-width:440px;">Tiêu đề bài viết</th>
                    <th style="width:145px; min-width:145px; text-align:center; white-space:nowrap;">Thể loại</th>
                    <th style="width:150px; text-align:center; white-space:nowrap;">Thời gian đăng</th>
                    <th style="width:85px; text-align:center; white-space:nowrap;">Nổi bật</th>
                    <th style="width:130px; text-align:center; white-space:nowrap;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($newsList)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:30px; color:#888;">
                            <i class="fas fa-inbox fa-2x" style="margin-bottom:10px; display:block;"></i>
                            Không tìm thấy bài viết tin tức nào phù hợp.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($newsList as $item): ?>
                        <tr>
                            <td style="text-align:center; font-weight:700; color:var(--adm-text-muted);">#<?= (int)$item['id'] ?></td>
                            <td style="text-align:center;">
                                <img src="<?= Helper::asset($item['thumbnail'] ?: 'media/img/galleries/media_gallery_1.png') ?>" style="width:68px; height:44px; object-fit:cover; border-radius:6px; border:1px solid rgba(255,255,255,0.08);" onerror="this.src='<?= Helper::asset('media/img/placeholder.png') ?>'">
                            </td>
                            <td style="max-width:440px;">
                                <strong style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; line-height:1.4; color:#f1f5f9; font-size:0.92rem;" title="<?= htmlspecialchars($item['title']) ?>">
                                    <?= htmlspecialchars($item['title']) ?>
                                </strong>
                                <?php if (!empty($item['summary'])): ?>
                                    <div style="font-size:0.8rem; color:var(--adm-text-muted); margin-top:4px; display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden;">
                                        <?= htmlspecialchars(Helper::truncate($item['summary'], 75)) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="width:145px; min-width:145px; text-align:center; white-space:nowrap; vertical-align:middle;">
                                <?= Helper::categoryBadge($item['category']) ?>
                            </td>
                            <td style="font-size:0.85rem; color:#a0a0b8; text-align:center; white-space:nowrap; vertical-align:middle;">
                                <?= Helper::formatDate($item['published_at']) ?>
                            </td>
                            <td style="text-align:center; vertical-align:middle;">
                                <?php if (!empty($item['is_featured'])): ?>
                                    <span style="display:inline-flex; align-items:center; gap:4px; color:#f59e0b; font-weight:700; font-size:0.82rem; background:rgba(245, 158, 11, 0.12); padding:3px 8px; border-radius:12px; border:1px solid rgba(245, 158, 11, 0.3);">
                                        <i class="fas fa-star" style="font-size:0.75rem;"></i> Ghim
                                    </span>
                                <?php else: ?>
                                    <span style="color:#555;">-</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center; vertical-align:middle; white-space:nowrap;">
                                <div style="display:inline-flex; gap:6px;">
                                    <a href="<?= Helper::url('pages/news_detail.php?id=' . $item['id']) ?>" target="_blank" class="btn-adm btn-adm-secondary btn-adm-sm" title="Xem bài ngoài web">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= Helper::url('modules/admin/news_form.php?id=' . $item['id']) ?>" class="btn-adm btn-adm-warning btn-adm-sm" title="Chỉnh sửa">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="btn-adm btn-adm-danger btn-adm-sm" title="Xóa">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <?php if ($data['totalPages'] > 1): ?>
        <div style="display:flex; justify-content:center; gap:8px; margin-top:24px;">
            <?php for ($p = 1; $p <= $data['totalPages']; $p++): ?>
                <a href="<?= Helper::url('modules/admin/news_manage.php?page=' . $p . ($category ? '&category=' . urlencode($category) : '') . ($search ? '&search=' . urlencode($search) : '')) ?>" 
                   class="btn-adm <?= ($p === $data['page']) ? 'btn-adm-primary' : 'btn-adm-secondary' ?> btn-adm-sm">
                    <?= $p ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>
