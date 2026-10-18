<?php
// ====================================================================
//  core/Auth.php — Middleware Xác thực & Phân quyền Quản trị (RBAC)
// ====================================================================

class Auth {
    public static function init(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isLoggedIn(): bool {
        self::init();
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public static function isAdmin(): bool {
        self::init();
        return self::isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public static function id(): ?int {
        self::init();
        return $_SESSION['user_id'] ?? null;
    }

    public static function username(): ?string {
        self::init();
        return $_SESSION['username'] ?? null;
    }

    public static function role(): ?string {
        self::init();
        return $_SESSION['role'] ?? 'guest';
    }

    public static function login(int $userId, string $username, string $role): void {
        self::init();
        if (!headers_sent()) {
            session_regenerate_id(true);
        }
        $_SESSION['user_id']  = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['role']     = $role;
    }

    public static function logout(): void {
        self::init();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    /**
     * Chặn truy cập nếu không phải Admin
     */
    public static function requireAdmin(string $redirectPath = '/pages/login.php'): void {
        self::init();
        if (!self::isAdmin()) {
            $_SESSION['flash_error'] = 'Bạn cần quyền Quản trị viên (Admin) để truy cập khu vực này!';
            header('Location: ' . BASE_URL . $redirectPath);
            exit();
        }
    }

    /**
     * Chặn truy cập nếu chưa đăng nhập
     */
    public static function requireLogin(string $redirectPath = '/pages/login.php'): void {
        self::init();
        if (!self::isLoggedIn()) {
            $_SESSION['flash_error'] = 'Vui lòng đăng nhập để tiếp tục.';
            header('Location: ' . BASE_URL . $redirectPath);
            exit();
        }
    }
}
