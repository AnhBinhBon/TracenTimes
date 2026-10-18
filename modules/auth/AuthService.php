<?php
// ====================================================================
//  modules/auth/AuthService.php — Business Logic Layer cho Xác thực
// ====================================================================

class AuthService {
    private AuthDAO $dao;

    public function __construct() {
        $this->dao = new AuthDAO();
    }

    public function login(string $username, string $password): array {
        $username = trim($username);
        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => 'Vui lòng nhập đầy đủ tên tài khoản và mật khẩu.'];
        }

        $user = $this->dao->findByUsername($username);
        if (!$user) {
            return ['success' => false, 'message' => 'Tài khoản không tồn tại trên hệ thống TracenTimes.'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Mật khẩu nhập vào không chính xác.'];
        }

        Auth::login((int)$user['id'], $user['username'], $user['role']);
        return [
            'success' => true,
            'message' => 'Đăng nhập thành công!',
            'user'    => $user
        ];
    }

    public function register(string $username, string $password, string $confirmPassword): array {
        $username = trim($username);
        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => 'Vui lòng điền đầy đủ các thông tin yêu cầu.'];
        }

        if (mb_strlen($username) < 3 || mb_strlen($username) > 30) {
            return ['success' => false, 'message' => 'Tên tài khoản phải từ 3 đến 30 ký tự.'];
        }

        if (mb_strlen($password) < 6) {
            return ['success' => false, 'message' => 'Mật khẩu phải chứa ít nhất 6 ký tự.'];
        }

        if ($password !== $confirmPassword) {
            return ['success' => false, 'message' => 'Mật khẩu xác nhận không khớp.'];
        }

        if ($this->dao->findByUsername($username)) {
            return ['success' => false, 'message' => 'Tên tài khoản này đã được sử dụng. Vui lòng chọn tên khác.'];
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $userId = $this->dao->create($username, $hash, 'user');

        Auth::login($userId, $username, 'user');
        return ['success' => true, 'message' => 'Đăng ký tài khoản thành công!'];
    }

    public function logout(): void {
        Auth::logout();
    }

    public function getUserStats(): array {
        return [
            'total'  => $this->dao->countUsers(),
            'admins' => $this->dao->countByRole('admin'),
            'users'  => $this->dao->countByRole('user'),
        ];
    }

    public function listUsers(?string $search = null, ?string $role = null, int $page = 1, int $perPage = 15): array {
        $page = max(1, $page);
        $total = $this->dao->countUsers($search, $role);
        $totalPages = (int)ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        $users = $this->dao->getAllUsers($search, $role, $perPage, $offset);

        return [
            'users'      => $users,
            'total'      => $total,
            'page'       => $page,
            'totalPages' => $totalPages,
            'perPage'    => $perPage
        ];
    }

    public function getUser(int $id): ?array {
        return $this->dao->findById($id);
    }

    public function createUser(string $username, string $password, string $role = 'user'): array {
        $username = trim($username);
        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => 'Vui lòng nhập đầy đủ tên tài khoản và mật khẩu.'];
        }

        if (mb_strlen($username) < 3 || mb_strlen($username) > 30) {
            return ['success' => false, 'message' => 'Tên tài khoản phải từ 3 đến 30 ký tự.'];
        }

        if (mb_strlen($password) < 6) {
            return ['success' => false, 'message' => 'Mật khẩu phải chứa ít nhất 6 ký tự.'];
        }

        if (!in_array($role, ['admin', 'user'])) {
            $role = 'user';
        }

        if ($this->dao->isUsernameTaken($username)) {
            return ['success' => false, 'message' => 'Tên tài khoản này đã được sử dụng.'];
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $id = $this->dao->create($username, $hash, $role);

        return ['success' => true, 'message' => 'Tạo tài khoản thành công!', 'id' => $id];
    }

    public function updateUser(int $id, string $username, ?string $password = null, string $role = 'user'): array {
        $user = $this->dao->findById($id);
        if (!$user) {
            return ['success' => false, 'message' => 'Không tìm thấy tài khoản.'];
        }

        $username = trim($username);
        if (empty($username)) {
            return ['success' => false, 'message' => 'Tên tài khoản không được để trống.'];
        }

        if (mb_strlen($username) < 3 || mb_strlen($username) > 30) {
            return ['success' => false, 'message' => 'Tên tài khoản phải từ 3 đến 30 ký tự.'];
        }

        if ($this->dao->isUsernameTaken($username, $id)) {
            return ['success' => false, 'message' => 'Tên tài khoản này đã tồn tại trên hệ thống.'];
        }

        if (!in_array($role, ['admin', 'user'])) {
            $role = 'user';
        }

        // Không cho phép hạ quyền tài khoản admin gốc (ID: 1)
        if ($id === 1 && $role !== 'admin') {
            return ['success' => false, 'message' => 'Không thể hạ quyền Quản trị viên tối cao (Root Admin).'];
        }

        $hash = null;
        if (!empty($password)) {
            if (mb_strlen($password) < 6) {
                return ['success' => false, 'message' => 'Mật khẩu mới phải từ 6 ký tự trở lên.'];
            }
            $hash = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->dao->updateUser($id, $username, $hash, $role);
        return ['success' => true, 'message' => 'Cập nhật tài khoản thành công!'];
    }

    public function deleteUser(int $id, int $currentUserId): array {
        if ($id === $currentUserId) {
            return ['success' => false, 'message' => 'Bạn không thể tự xóa tài khoản của chính mình khi đang đăng nhập.'];
        }

        if ($id === 1) {
            return ['success' => false, 'message' => 'Không được phép xóa Quản trị viên tối cao (Root Admin).'];
        }

        $user = $this->dao->findById($id);
        if (!$user) {
            return ['success' => false, 'message' => 'Tài khoản không tồn tại.'];
        }

        $this->dao->deleteUser($id);
        return ['success' => true, 'message' => 'Đã xóa tài khoản ' . htmlspecialchars($user['username']) . ' thành công!'];
    }
}
