<?php
// ================================================================
//  pages/logout.php — Đăng xuất và xóa session (3-Tier View)
// ================================================================

require_once __DIR__ . '/../config/app.php';

Auth::logout();
Helper::setFlash('success', 'Bạn đã đăng xuất an toàn khỏi hệ thống.');
header('Location: ' . Helper::url('index.php'));
exit;
