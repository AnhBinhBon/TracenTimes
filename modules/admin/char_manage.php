<?php
// ====================================================================
//  modules/admin/char_manage.php — Quản lý Danh sách Hồ sơ Nhân vật
// ====================================================================

require_once __DIR__ . '/../../config/app.php';

$charService = new CharacterService();

// Xử lý Xóa nhân vật
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $deleteId = (int)($_POST['id'] ?? 0);
    if ($deleteId > 0) {
        $res = $charService->delete($deleteId);
        if ($res) {
            Helper::setFlash('success', 'Đã xóa nhân vật và chỉ số liên quan thành công!');
        } else {
            Helper::setFlash('error', 'Không thể xóa nhân vật này.');
        }
    }
    header('Location: ' . Helper::url('modules/admin/char_manage.php'));
    exit();
}

$page = (int)($_GET['page'] ?? 1);
$search = trim($_GET['search'] ?? '');
$style = trim($_GET['style'] ?? '');

$data = $charService->getList($page, 12, $search, $style);
$charList = $data['items'];

$pageTitle = 'Quản Lý Hồ Sơ Nhân Vật';
$pageIcon  = 'fa-solid fa-id-card-clip';

include __DIR__ . '/../../includes/admin_header.php';
?>

<div class="admin-box">
    <div class="admin-box__header">
        <h2 class="admin-box__title"><i class="fas fa-horse"></i> Danh sách tất cả Uma Musume</h2>
        <a href="<?= Helper::url('modules/admin/char_form.php') ?>" class="btn-adm btn-adm-primary">
            <i class="fas fa-user-plus"></i> Thêm Nhân Vật Mới
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" style="display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Tìm tên nhân vật, biệt danh, tên tiếng Nhật..." class="form-input" style="max-width:340px;">
        <select name="style" class="form-select" style="max-width:200px;">
            <option value="">Tất cả phong cách chạy</option>
            <option value="Runner" <?= ($style === 'Runner') ? 'selected' : '' ?>>Runner (Tiên phong)</option>
            <option value="Leader" <?= ($style === 'Leader') ? 'selected' : '' ?>>Leader (Dẫn đầu)</option>
            <option value="Betweener" <?= ($style === 'Betweener') ? 'selected' : '' ?>>Betweener (Giữ cự ly)</option>
            <option value="Chaser" <?= ($style === 'Chaser') ? 'selected' : '' ?>>Chaser (Bứt tốc cuối)</option>
        </select>
        <button type="submit" class="btn-adm btn-adm-secondary">
            <i class="fas fa-search"></i> Tìm kiếm
        </button>
        <?php if (!empty($search) || !empty($style)): ?>
            <a href="<?= Helper::url('modules/admin/char_manage.php') ?>" class="btn-adm btn-adm-secondary" title="Đặt lại bộ lọc">
                <i class="fas fa-rotate-left"></i>
            </a>
        <?php endif; ?>
    </form>

    <div class="admin-table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th style="width:70px;">Avatar</th>
                    <th>Tên nhân vật</th>
                    <th>CV (Lồng tiếng)</th>
                    <th>Sinh nhật</th>
                    <th>Chiều cao</th>
                    <th>Phong cách chạy</th>
                    <th style="width:180px; text-align:center;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($charList)): ?>
                    <tr>
                        <td colspan="8" style="text-align:center; padding:30px; color:#888;">
                            <i class="fas fa-horse fa-2x" style="margin-bottom:10px; display:block;"></i>
                            Không tìm thấy nhân vật nào phù hợp.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($charList as $char): ?>
                        <tr>
                            <td>#<?= (int)$char['id'] ?></td>
                            <td>
                                <img src="<?= htmlspecialchars(Helper::characterAvatar($char)) ?>" class="table-avatar" 
                                     style="width:50px; height:50px; max-width:50px; max-height:50px; object-fit:cover; border-radius:8px; display:block;"
                                     data-fallback="<?= htmlspecialchars(!empty($char['avatar']) ? Helper::asset($char['avatar']) : Helper::asset('media/img/placeholder.png')) ?>"
                                     onerror="if (typeof handleImageError === 'function') { handleImageError(this, this.dataset.fallback); } else { this.onerror=null; this.src='<?= Helper::asset('media/img/placeholder.png') ?>'; }">
                            </td>

                            <td>
                                <strong><?= htmlspecialchars($char['name']) ?></strong>
                                <?php if (!empty($char['japanese_name'])): ?>
                                    <div style="font-size:0.8rem; color:#888;"><?= htmlspecialchars($char['japanese_name']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($char['nickname'])): ?>
                                    <div style="font-size:0.78rem; color:var(--adm-accent-gold);"><i class="fas fa-crown"></i> <?= htmlspecialchars($char['nickname']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td style="font-size:0.9rem; color:#cbd5e1;"><?= htmlspecialchars($char['voice_actor'] ?? 'Chưa rõ') ?></td>
                            <td style="font-size:0.85rem; color:#aaa;"><?= htmlspecialchars($char['birthday'] ?? '-') ?></td>
                            <td style="font-size:0.85rem; color:#aaa;"><?= htmlspecialchars($char['height'] ?? '-') ?></td>
                            <td>
                                <?= Helper::runningStyleBadge($char['running_style'] ?? 'Leader') ?>
                            </td>
                            <td style="text-align:center;">
                                <div style="display:inline-flex; gap:6px;">
                                    <a href="<?= Helper::url('pages/character_detail.php?id=' . $char['id']) ?>" target="_blank" class="btn-adm btn-adm-secondary btn-adm-sm" title="Xem hồ sơ">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= Helper::url('modules/admin/char_form.php?id=' . $char['id']) ?>" class="btn-adm btn-adm-warning btn-adm-sm" title="Sửa thông tin">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="<?= Helper::url('modules/admin/stats_form.php?char_id=' . $char['id']) ?>" class="btn-adm btn-adm-secondary btn-adm-sm" style="color:#06b6d4; border-color:#06b6d4;" title="Chỉnh chỉ số">
                                        <i class="fas fa-bolt"></i>
                                    </a>
                                    <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân vật <?= htmlspecialchars(addslashes($char['name'])) ?> không?');" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $char['id'] ?>">
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
                <a href="<?= Helper::url('modules/admin/char_manage.php?page=' . $p . ($search ? '&search=' . urlencode($search) : '') . ($style ? '&style=' . urlencode($style) : '')) ?>" 
                   class="btn-adm <?= ($p === $data['page']) ? 'btn-adm-primary' : 'btn-adm-secondary' ?> btn-adm-sm">
                    <?= $p ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>
