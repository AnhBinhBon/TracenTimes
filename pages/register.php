<?php
// ================================================================
//  pages/register.php — Trang đăng ký (3-Tier View)
// ================================================================

require_once __DIR__ . '/../config/app.php';

// Redirect nếu đã đăng nhập
if (Auth::isLoggedIn()) {
    header('Location: ' . Helper::url('index.php'));
    exit;
}

$error   = '';
$success = '';

// ── Handle POST ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username         = trim($_POST['username'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $authService = new AuthService();
    $result = $authService->register($username, $password, $confirm_password);

    if ($result['success']) {
        $success = '🎉 ' . $result['message'];
        // Tự động chuyển hướng về trang chủ sau khi đăng ký thành công
        header('Refresh: 2; URL=' . Helper::url('index.php'));
    } else {
        $error = $result['message'];
    }
}

$pageTitle = 'Đăng Ký Tài Khoản — TracenTimes';
include __DIR__ . '/../header.php';
?>

<div class="auth-page-wrapper auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 0 25px rgba(16, 185, 129, 0.4);">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h2 class="auth-title">ĐĂNG KÝ TÀI KHOẢN</h2>
            <p class="auth-subtitle">Gia nhập Học Viện Huấn Luyện Viên Tracen</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="auth-alert auth-alert--error" style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #ef4444; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-circle-exclamation auth-alert-icon"></i>
                <div><?php echo htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="auth-alert auth-alert--success" style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #10b981; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <i class="fa-solid fa-circle-check auth-alert-icon"></i>
                    <div><?php echo htmlspecialchars($success); ?></div>
                </div>
                <div style="font-size:0.85rem; margin-top:6px; color:#a7f3d0;">Đang tự động chuyển hướng về trang chủ...</div>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="auth-form" autocomplete="off">
            <div class="auth-form-group form-group">
                <label for="username" class="auth-label">Tên tài khoản</label>
                <div class="auth-input-wrapper input-with-icon">
                    <i class="fa-solid fa-user auth-input-icon"></i>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="auth-input"
                        placeholder="Chọn tên tài khoản (3-30 ký tự)..."
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                        required>
                </div>
            </div>

            <div class="auth-form-group form-group">
                <label for="password" class="auth-label">Mật khẩu</label>
                <div class="auth-input-wrapper input-with-icon">
                    <i class="fa-solid fa-lock auth-input-icon"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="auth-input auth-input--has-toggle"
                        placeholder="Mật khẩu tối thiểu 6 ký tự..."
                        required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password', this)" title="Ẩn/Hiện mật khẩu" tabindex="-1">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="auth-form-group form-group">
                <label for="confirm_password" class="auth-label">Xác nhận mật khẩu</label>
                <div class="auth-input-wrapper input-with-icon">
                    <i class="fa-solid fa-shield-halved auth-input-icon"></i>
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        class="auth-input auth-input--has-toggle"
                        placeholder="Nhập lại mật khẩu..."
                        required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirm_password', this)" title="Ẩn/Hiện mật khẩu" tabindex="-1">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="auth-submit btn-auth-submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 4px 18px rgba(16, 185, 129, 0.35);">
                <i class="fa-solid fa-user-plus"></i> TẠO TÀI KHOẢN
            </button>
        </form>

        <div class="auth-footer auth-footer-link">
            Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php include __DIR__ . '/../footer.php'; ?>
