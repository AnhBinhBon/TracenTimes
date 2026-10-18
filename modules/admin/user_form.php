<?php
// ====================================================================
//  modules/admin/user_form.php — Thêm Mới / Chỉnh Sửa Tài Khoản
// ====================================================================

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../auth/AuthDAO.php';
require_once __DIR__ . '/../auth/AuthService.php';

Auth::requireAdmin();

$authService = new AuthService();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = ($id > 0);

$userItem = null;
if ($isEdit) {
    $userItem = $authService->getUser($id);
    if (!$userItem) {
        Helper::setFlash('error', 'Không tìm thấy tài khoản yêu cầu.');
        Helper::redirect('modules/admin/users_manage.php');
    }
}

$pageTitle = $isEdit ? 'Chỉnh Sửa Tài Khoản: ' . htmlspecialchars($userItem['username']) : 'Thêm Tài Khoản Mới';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');
    $role = trim($_POST['role'] ?? 'user');

    if ($isEdit) {
        if (!empty($password) && $password !== $confirmPassword) {
            $error = 'Mật khẩu xác nhận không khớp.';
        } else {
            $res = $authService->updateUser($id, $username, $password ?: null, $role);
            if ($res['success']) {
                Helper::setFlash('success', $res['message']);
                Helper::redirect('modules/admin/users_manage.php');
            } else {
                $error = $res['message'];
            }
        }
    } else {
        if (empty($password)) {
            $error = 'Vui lòng nhập mật khẩu cho tài khoản mới.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Mật khẩu xác nhận không khớp.';
        } else {
            $res = $authService->createUser($username, $password, $role);
            if ($res['success']) {
                Helper::setFlash('success', $res['message']);
                Helper::redirect('modules/admin/users_manage.php');
            } else {
                $error = $res['message'];
            }
        }
    }
}

include __DIR__ . '/../../includes/admin_header.php';
?>

<div class="admin-container" style="max-width: 800px; margin: 0 auto;">
    
    <div class="admin-toolbar" style="margin-bottom: 24px;">
        <div class="admin-toolbar__left">
            <h2 style="margin:0; font-size:1.3rem; font-weight:700; color:#fff; display:flex; align-items:center; gap:10px;">
                <i class="fas <?= $isEdit ? 'fa-user-pen' : 'fa-user-plus' ?>" style="color:var(--adm-pink);"></i>
                <?= $pageTitle ?>
            </h2>
        </div>
        <div class="admin-toolbar__right">
            <a href="<?= Helper::url('modules/admin/users_manage.php') ?>" class="btn-adm btn-adm-secondary btn-adm-sm">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="admin-alert admin-alert-danger" style="margin-bottom: 20px;">
            <i class="fas fa-circle-exclamation fa-lg"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <div style="background:var(--adm-surface); border:1px solid var(--adm-border); border-radius:var(--adm-radius); padding:28px; box-shadow:0 6px 24px rgba(0,0,0,0.3);">
        <form method="POST">
            
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-user" style="color:var(--adm-cyan);"></i> Tên đăng nhập (Username) <span style="color:red;">*</span>
                </label>
                <input type="text" name="username" class="form-input" required 
                       placeholder="Ví dụ: trainer_tachyon, special_admin..." 
                       value="<?= htmlspecialchars($_POST['username'] ?? $userItem['username'] ?? '') ?>">
                <div style="font-size:0.75rem; color:var(--adm-text-dim); margin-top:4px;">
                    Độ dài từ 3 đến 30 ký tự, viết liền không dấu hoặc có dấu gạch dưới.
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-key" style="color:var(--adm-gold);"></i> 
                        <?= $isEdit ? 'Mật khẩu mới (Tùy chọn)' : 'Mật khẩu đăng nhập' ?> <span style="color:<?= $isEdit ? 'transparent' : 'red' ?>;">*</span>
                    </label>
                    <input type="password" name="password" class="form-input" 
                           placeholder="<?= $isEdit ? 'Để trống nếu không muốn đổi mật khẩu' : 'Nhập ít nhất 6 ký tự...' ?>"
                           <?= $isEdit ? '' : 'required' ?>>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-check-double" style="color:var(--adm-gold);"></i> Xác nhận mật khẩu
                    </label>
                    <input type="password" name="confirm_password" class="form-input" 
                           placeholder="Nhập lại mật khẩu để xác nhận...">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-shield-halved" style="color:var(--adm-pink);"></i> Phân quyền tài khoản (Role)
                </label>
                
                <?php if ($isEdit && $id === 1): ?>
                    <input type="hidden" name="role" value="admin">
                    <div style="padding:12px 16px; background:rgba(245, 158, 11, 0.1); border:1px solid rgba(245, 158, 11, 0.3); border-radius:6px; color:#f59e0b; font-size:0.9rem; font-weight:700; display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-crown"></i> Quản Trị Viên Tối Cao (Root Admin) — Quyền hạn cố định, được bảo vệ bởi hệ thống
                    </div>
                <?php else: ?>
                    <?php $selectedRole = $_POST['role'] ?? $userItem['role'] ?? 'user'; ?>
                    <select name="role" class="form-select">
                        <option value="user" <?= ($selectedRole === 'user') ? 'selected' : '' ?>>
                            Huấn Luyện Viên (Trainer / User) — Độc giả tham gia đua Derby Cup & Tra cứu thông tin
                        </option>
                        <option value="admin" <?= ($selectedRole === 'admin') ? 'selected' : '' ?>>
                            Quản Trị Viên (Admin) — Toàn quyền đăng tin, quản lý hồ sơ & hệ thống
                        </option>
                    </select>
                <?php endif; ?>
            </div>

            <?php if ($isEdit && !empty($userItem['created_at'])): ?>
                <div style="font-size:0.8rem; color:var(--adm-text-dim); margin-top:15px; padding-top:15px; border-top:1px solid rgba(255,255,255,0.06);">
                    <i class="far fa-calendar-check"></i> Tài khoản được khởi tạo vào lúc: <?= Helper::formatDate($userItem['created_at']) ?>
                </div>
            <?php endif; ?>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:28px;">
                <a href="<?= Helper::url('modules/admin/users_manage.php') ?>" class="btn-adm btn-adm-secondary">Hủy bỏ</a>
                <button type="submit" class="btn-adm btn-adm-primary">
                    <i class="fas fa-save"></i> <?= $isEdit ? 'Lưu Cập Nhật Tài Khoản' : 'Tạo Tài Khoản Mới' ?>
                </button>
            </div>

        </form>
    </div>

</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>
