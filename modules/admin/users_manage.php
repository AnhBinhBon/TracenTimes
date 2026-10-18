<?php
// ====================================================================
//  modules/admin/users_manage.php — Quản Lý Tài Khoản & Phân Quyền
//  Kiến trúc 3 Lớp: AuthDAO -> AuthService -> users_manage.php
//  Bảo mật: Phân quyền Admin, chống tự xóa, bảo vệ Root Admin ID 1
// ====================================================================

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../auth/AuthDAO.php';
require_once __DIR__ . '/../auth/AuthService.php';

Auth::requireAdmin();

$pageTitle = 'Quản Lý Tài Khoản';
$authService = new AuthService();
$currentUserId = Auth::id();

// Xử lý Xóa Tài Khoản
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $deleteId = (int)($_POST['id'] ?? 0);
    $res = $authService->deleteUser($deleteId, $currentUserId);
    if ($res['success']) {
        Helper::setFlash('success', $res['message']);
    } else {
        Helper::setFlash('error', $res['message']);
    }
    Helper::redirect('modules/admin/users_manage.php');
}

// Bộ lọc & Phân trang
$search = trim($_GET['search'] ?? '');
$role   = trim($_GET['role'] ?? '');
$page   = max(1, (int)($_GET['page'] ?? 1));

$stats = $authService->getUserStats();
$data  = $authService->listUsers($search ?: null, $role ?: null, $page, 12);
$users = $data['users'];

include __DIR__ . '/../../includes/admin_header.php';
?>

<div class="admin-container">
    
    <!-- ── 1. Thẻ Thống Kê Tài Khoản ────────────────────────────────────── -->
    <div class="admin-stat-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 24px;">
        <div class="admin-stat-card">
            <div>
                <div class="admin-stat-card__lbl">Tổng Tài Khoản</div>
                <div class="admin-stat-card__val" style="color:var(--adm-cyan);"><?= (int)$stats['total'] ?></div>
                <div style="font-size:0.75rem; color:var(--adm-text-dim); margin-top:4px;">Thành viên TracenTimes</div>
            </div>
            <div class="admin-stat-card__icon" style="background:rgba(56, 189, 248, 0.15); color:var(--adm-cyan);">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <div class="admin-stat-card">
            <div>
                <div class="admin-stat-card__lbl">Quản Trị Viên (Admin)</div>
                <div class="admin-stat-card__val" style="color:var(--adm-gold);"><?= (int)$stats['admins'] ?></div>
                <div style="font-size:0.75rem; color:var(--adm-text-dim); margin-top:4px;">Quyền điều hành tối cao</div>
            </div>
            <div class="admin-stat-card__icon" style="background:rgba(245, 158, 11, 0.15); color:var(--adm-gold);">
                <i class="fas fa-user-shield"></i>
            </div>
        </div>

        <div class="admin-stat-card">
            <div>
                <div class="admin-stat-card__lbl">Huấn Luyện Viên (Trainer)</div>
                <div class="admin-stat-card__val" style="color:#c084fc;"><?= (int)$stats['users'] ?></div>
                <div style="font-size:0.75rem; color:var(--adm-text-dim); margin-top:4px;">Người chơi tham gia Derby Cup</div>
            </div>
            <div class="admin-stat-card__icon" style="background:rgba(168, 85, 247, 0.15); color:#c084fc;">
                <i class="fas fa-user-astronaut"></i>
            </div>
        </div>

        <div class="admin-stat-card">
            <div>
                <div class="admin-stat-card__lbl">Cơ Chế Bảo Vệ An Toàn</div>
                <div class="admin-stat-card__val" style="color:var(--adm-emerald); font-size:1.3rem;">BẢO VỆ GỐC</div>
                <div style="font-size:0.75rem; color:var(--adm-text-dim); margin-top:4px;">Chống xóa Root Admin & Tự xóa</div>
            </div>
            <div class="admin-stat-card__icon" style="background:rgba(16, 185, 129, 0.15); color:var(--adm-emerald);">
                <i class="fas fa-lock"></i>
            </div>
        </div>
    </div>

    <!-- ── 2. Khối Quản Lý Tài Khoản ────────────────────────────────────── -->
    <div class="admin-box">
        <div class="admin-box__header">
            <h2 class="admin-box__title">
                <i class="fas fa-users-gear" style="color:var(--adm-pink);"></i> Danh Sách Tài Khoản Hệ Thống
            </h2>
        </div>

        <!-- Thanh Tìm Kiếm (Bên Trái) & Nút Thêm Tài Khoản (Bên Phải) -->
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:20px; flex-wrap:wrap;">
            <!-- Phần tìm kiếm bên trái -->
            <form method="GET" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin:0;">
                <input type="text" name="search" class="form-input" 
                       style="width:280px; max-width:100%; height:38px;" 
                       placeholder="Tìm kiếm theo tên tài khoản..." 
                       value="<?= htmlspecialchars($search) ?>">
                
                <select name="role" class="form-select" style="width:190px; height:38px;">
                    <option value="">Tất cả vai trò</option>
                    <option value="admin" <?= ($role === 'admin') ? 'selected' : '' ?>>Quản trị viên (Admin)</option>
                    <option value="user" <?= ($role === 'user') ? 'selected' : '' ?>>Huấn luyện viên (User)</option>
                </select>

                <button type="submit" class="btn-adm btn-adm-secondary" style="height:38px; padding:0 16px; display:inline-flex; align-items:center; gap:6px;">
                    <i class="fas fa-search"></i> Lọc
                </button>

                <?php if (!empty($search) || !empty($role)): ?>
                    <a href="<?= Helper::url('modules/admin/users_manage.php') ?>" class="btn-adm btn-adm-secondary" style="height:38px; padding:0 12px; display:inline-flex; align-items:center;" title="Đặt lại bộ lọc">
                        <i class="fas fa-rotate-left"></i>
                    </a>
                <?php endif; ?>
            </form>

            <!-- Thêm tài khoản ở bên phải -->
            <div style="margin-left:auto;">
                <a href="<?= Helper::url('modules/admin/user_form.php') ?>" class="btn-adm btn-adm-primary" style="height:38px; padding:0 18px; display:inline-flex; align-items:center; gap:8px; white-space:nowrap;">
                    <i class="fas fa-user-plus"></i> Thêm Tài Khoản Mới
                </a>
            </div>
        </div>

        <!-- ── 3. Bảng Dữ Liệu Tài Khoản ────────────────────────────────────── -->
    <div class="admin-table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:60px; text-align:center;">ID</th>
                    <th style="width:70px; text-align:center;">Hồ sơ</th>
                    <th>Tên đăng nhập (Username)</th>
                    <th style="width:220px; text-align:center;">Phân quyền hệ thống</th>
                    <th style="width:180px; text-align:center;">Ngày khởi tạo</th>
                    <th style="width:140px; text-align:center;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding:35px; color:#888;">
                            <i class="fas fa-user-slash fa-2x" style="margin-bottom:10px; display:block;"></i>
                            Không tìm thấy tài khoản người dùng nào phù hợp.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td style="text-align:center; font-weight:700; color:var(--adm-text-muted);">
                                #<?= (int)$u['id'] ?>
                            </td>
                            <td style="text-align:center;">
                                <div style="width:42px; height:42px; border-radius:50%; background:<?= ($u['role'] === 'admin') ? 'linear-gradient(135deg, #f59e0b, #b45309)' : 'linear-gradient(135deg, #a855f7, #6366f1)' ?>; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-size:1.1rem; box-shadow:0 2px 8px rgba(0,0,0,0.3); border:1px solid rgba(255,255,255,0.15);">
                                    <i class="fas <?= ($u['role'] === 'admin') ? 'fa-user-shield' : 'fa-user-graduate' ?>"></i>
                                </div>
                            </td>
                            <td>
                                <strong style="font-size:0.95rem; color:#f1f5f9; display:flex; align-items:center; gap:8px;">
                                    <?= htmlspecialchars($u['username']) ?>
                                    <?php if ($u['id'] === $currentUserId): ?>
                                        <span style="font-size:0.72rem; background:rgba(255, 102, 163, 0.2); color:var(--adm-pink); border:1px solid var(--adm-pink); padding:2px 8px; border-radius:12px; font-weight:700;">
                                            Bạn đang đăng nhập
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($u['id'] === 1): ?>
                                        <span style="font-size:0.72rem; background:rgba(245, 158, 11, 0.2); color:#f59e0b; border:1px solid #f59e0b; padding:2px 8px; border-radius:12px; font-weight:700;">
                                            👑 Root Admin
                                        </span>
                                    <?php endif; ?>
                                </strong>
                                <div style="font-size:0.8rem; color:var(--adm-text-muted); margin-top:3px;">
                                    Mã định danh nội bộ: USR-<?= str_pad($u['id'], 4, '0', STR_PAD_LEFT) ?>
                                </div>
                            </td>
                            <td style="text-align:center; vertical-align:middle;">
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="user-role-badge user-role-admin">
                                        <i class="fas fa-shield-halved"></i> QUẢN TRỊ VIÊN
                                    </span>
                                <?php else: ?>
                                    <span class="user-role-badge user-role-user">
                                        <i class="fas fa-user-astronaut"></i> HUẤN LUYỆN VIÊN
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size:0.85rem; color:#a0a0b8; text-align:center; vertical-align:middle;">
                                <i class="far fa-clock" style="margin-right:4px;"></i> <?= Helper::formatDate($u['created_at']) ?>
                            </td>
                            <td style="text-align:center; vertical-align:middle; white-space:nowrap;">
                                <div style="display:inline-flex; gap:6px;">
                                    <!-- Nút Sửa -->
                                    <a href="<?= Helper::url('modules/admin/user_form.php?id=' . $u['id']) ?>" 
                                       class="btn-adm btn-adm-warning btn-adm-sm" 
                                       title="Chỉnh sửa tài khoản & Đổi mật khẩu">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <!-- Nút Xóa (Bảo vệ an toàn) -->
                                    <?php if ($u['id'] === 1): ?>
                                        <button type="button" class="btn-adm btn-adm-secondary btn-adm-sm" disabled style="opacity:0.4; cursor:not-allowed;" title="Quản trị viên tối cao được bảo vệ vĩnh viễn">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    <?php elseif ($u['id'] === $currentUserId): ?>
                                        <button type="button" class="btn-adm btn-adm-secondary btn-adm-sm" disabled style="opacity:0.4; cursor:not-allowed;" title="Không thể tự xóa chính mình">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    <?php else: ?>
                                        <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản <?= htmlspecialchars($u['username']) ?> không? Hành động này không thể hoàn tác!');" style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                            <button type="submit" class="btn-adm btn-adm-danger btn-adm-sm" title="Xóa tài khoản này">
                                                <i class="fas fa-trash-can"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ── 4. Phân Trang ────────────────────────────────────────────────── -->
    <?php if ($data['totalPages'] > 1): ?>
        <div style="display:flex; justify-content:center; gap:8px; margin-top:24px;">
            <?php for ($p = 1; $p <= $data['totalPages']; $p++): ?>
                <a href="<?= Helper::url('modules/admin/users_manage.php?page=' . $p . ($search ? '&search=' . urlencode($search) : '') . ($role ? '&role=' . urlencode($role) : '')) ?>" 
                   class="btn-adm <?= ($p === $data['page']) ? 'btn-adm-primary' : 'btn-adm-secondary' ?> btn-adm-sm">
                    <?= $p ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    </div> <!-- /.admin-box -->
</div> <!-- /.admin-container -->

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>
