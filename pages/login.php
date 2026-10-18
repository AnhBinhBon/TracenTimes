<?php
// ================================================================
//  pages/login.php — Trang đăng nhập (3-Tier View)
// ================================================================

require_once __DIR__ . '/../config/app.php';

// Redirect nếu đã đăng nhập
if (Auth::isLoggedIn()) {
    if (Auth::isAdmin()) {
        header('Location: ' . Helper::url('modules/admin/index.php'));
    } else {
        header('Location: ' . Helper::url('index.php'));
    }
    exit;
}

$error         = $_SESSION['login_error']    ?? '';
$temp_username = $_SESSION['login_username'] ?? '';
unset($_SESSION['login_error'], $_SESSION['login_username']);

$flashMsg = Helper::getFlash('error');
if ($flashMsg) {
    $error = $flashMsg;
}

// ── Handle POST ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $authService = new AuthService();
    $result = $authService->login($username, $password);

    if ($result['success']) {
        if (Auth::isAdmin()) {
            Helper::setFlash('success', 'Chào mừng Quản trị viên ' . Auth::username() . ' đăng nhập thành công!');
            header('Location: ' . Helper::url('modules/admin/index.php'));
        } else {
            Helper::setFlash('success', 'Đăng nhập thành công!');
            header('Location: ' . Helper::url('index.php'));
        }
        exit;
    } else {
        $_SESSION['login_error']    = $result['message'];
        $_SESSION['login_username'] = $username;
        header('Location: login.php');
        exit;
    }
}

$pageTitle = 'Đăng Nhập — TracenTimes';
include __DIR__ . '/../header.php';
?>

<div class="auth-page-wrapper auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fa-solid fa-horse-head"></i>
            </div>
            <h2 class="auth-title">ĐĂNG NHẬP</h2>
            <p class="auth-subtitle">Cổng Thông Tin TracenTimes — Umamusume</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="auth-alert auth-alert--error" style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #ef4444; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-circle-exclamation auth-alert-icon"></i>
                <div><?php echo htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>


        <form action="login.php" method="POST" class="auth-form" autocomplete="off">
            <div class="auth-form-group form-group">
                <label for="username" class="auth-label">Tên tài khoản</label>
                <div class="auth-input-wrapper input-with-icon">
                    <i class="fa-solid fa-user auth-input-icon"></i>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="auth-input"
                        placeholder="Nhập tên tài khoản..."
                        value="<?php echo htmlspecialchars($temp_username); ?>"
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
                        placeholder="Nhập mật khẩu..."
                        required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password', this)" title="Ẩn/Hiện mật khẩu" tabindex="-1">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="auth-submit btn-auth-submit">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> ĐĂNG NHẬP
            </button>
        </form>

        <div class="auth-footer auth-footer-link">
            Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
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
