<?php
// ====================================================================
//  config/db.php — Cầu nối tương thích ngược (Backward Compatibility)
//  Tự động nạp app.php và kết nối CSDL `tracen_times`
// ====================================================================

require_once __DIR__ . '/app.php';

// ── PDO connection (Chuẩn PDO chính dùng cho toàn bộ hệ thống) ───────
try {
    $pdo = Database::getConnection();
} catch (Exception $e) {
    die("Lỗi kết nối CSDL (PDO): " . $e->getMessage());
}

// ── mysqli connection (Hỗ trợ tương thích các đoạn code cũ nếu cần) ──
try {
    $conn = mysqli_connect("localhost", "root", "", "tracen_times");
    if ($conn) {
        mysqli_set_charset($conn, "utf8mb4");
    }
} catch (Exception $e) {
    // Không bắt buộc nếu đã chuyển hết sang PDO 3 lớp
}
