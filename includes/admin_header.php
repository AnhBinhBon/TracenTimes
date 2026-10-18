<?php
// ====================================================================
//  includes/admin_header.php — Layout Header Quản Trị TracenTimes
//  Thiết kế: Thanh Điều Hướng Ngang (Horizontal Topbar) × Dark Tokai Teio Theme
//  Tập trung 100% vào nghiệp vụ thực tế: Tin tức, Nhân vật, Chỉ số, Đua Derby
// ====================================================================

require_once __DIR__ . '/../config/app.php';

// Bảo vệ bằng Middleware Auth (Chỉ admin mới được vào)
Auth::requireAdmin();

$currentScript = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Quản Trị Hệ Thống' ?> — TracenTimes Admin</title>
    <link rel="icon" href="<?= Helper::asset('media/img/icon.png') ?>" type="image/png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Cinzel:wght@600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?= Helper::asset('css/admin.css?v=' . time()) ?>">

    <!-- Global Image Exception & Fallback Handler -->
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
        window.handleImageError = function(img, customFallback) {
            if (!img) return;
            img.onerror = null;

            // 1. Kiểm tra ảnh dự phòng riêng của Mã Nương
            const charFallback = customFallback || img.getAttribute('data-fallback');
            if (charFallback && img.src !== charFallback && !img.dataset.triedCharFallback) {
                img.dataset.triedCharFallback = 'true';
                img.onerror = function() {
                    this.onerror = null;
                    this.src = window.BASE_URL + '/media/img/placeholder.png';
                    this.classList.add('img-load-error');
                };
                img.src = charFallback;
                return;
            }

            // 2. Chuyển sang ảnh lỗi Error Placeholder mặc định
            img.src = window.BASE_URL + '/media/img/placeholder.png';
            img.classList.add('img-load-error');
        };

        document.addEventListener('error', function(e) {
            if (e.target && e.target.tagName === 'IMG') {
                const img = e.target;
                if (!img.dataset.hasHandledError) {
                    img.dataset.hasHandledError = 'true';
                    window.handleImageError(img);
                }
            }
        }, true);

        // Rich-Text Editor Helpers
        window.formatRichText = function(id, before, after) {
            const el = document.getElementById(id);
            if (!el) return;
            el.focus();
            const start = el.selectionStart;
            const end = el.selectionEnd;
            const selectedText = el.value.substring(start, end) || 'Văn bản';
            const replacement = before + selectedText + (after || '');
            el.setRangeText(replacement, start, end, 'select');
            el.dispatchEvent(new Event('input', { bubbles: true }));
        };

        window.clearRichText = function(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.focus();
            const start = el.selectionStart;
            const end = el.selectionEnd;
            if (start !== end) {
                let sel = el.value.substring(start, end);
                sel = sel.replace(/<\/?[^>]+(>|$)/g, "");
                el.setRangeText(sel, start, end, 'select');
            } else {
                el.value = el.value.replace(/<\/?[^>]+(>|$)/g, "");
            }
            el.dispatchEvent(new Event('input', { bubbles: true }));
        };

        // Navigation Dropdown & 3-Dots Handler
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.querySelector('.ops-nav__dropdown');
            const toggle = document.querySelector('.ops-nav__dropdown-toggle');
            if (toggle && dropdown) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropdown.classList.toggle('is-open');
                });
            }

            const moreMenu = document.getElementById('ops-nav-more');
            const moreBtn = document.getElementById('ops-nav-more-btn');
            if (moreBtn && moreMenu) {
                moreBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    moreMenu.classList.toggle('is-open');
                });
            }

            document.addEventListener('click', function(e) {
                if (dropdown && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('is-open');
                }
                if (moreMenu && !moreMenu.contains(e.target)) {
                    moreMenu.classList.remove('is-open');
                }
            });
        });
    </script>
</head>
<body class="ops-body">

    <!-- ── 1. Top Horizontal Navigation Bar ────────────────────────────── -->
    <header class="ops-header">
        <div class="ops-header__brand">
            <a href="<?= Helper::url('modules/admin/index.php') ?>" class="ops-logo">
                <i class="fas fa-horse-head ops-logo__icon"></i>
                <div class="ops-logo__text">
                    <span class="ops-logo__title">TRACENTIMES</span>
                    <span class="ops-logo__subtitle">QUẢN TRỊ HỆ THỐNG</span>
                </div>
            </a>
        </div>

        <nav class="ops-nav">
            <a href="<?= Helper::url('modules/admin/index.php') ?>" class="ops-nav__link <?= ($currentScript === 'index.php') ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i>
                <span>Tổng quan</span>
            </a>
            <a href="<?= Helper::url('modules/admin/news_manage.php') ?>" class="ops-nav__link ops-nav__link--news <?= (strpos($currentScript, 'news') !== false) ? 'active' : '' ?>">
                <i class="fas fa-newspaper"></i>
                <span>Quản lý Tin tức</span>
            </a>
            <!-- Quản lý Mã Nương (Dropdown gộp Hồ sơ & Tốc độ chấn thương) -->
            <div class="ops-nav__dropdown <?= (strpos($currentScript, 'char') !== false || strpos($currentScript, 'stats') !== false) ? 'active' : '' ?>">
                <a href="javascript:void(0)" id="btn-char-dropdown" class="ops-nav__link ops-nav__dropdown-toggle <?= (strpos($currentScript, 'char') !== false || strpos($currentScript, 'stats') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-horse-head"></i>
                    <span>Quản lý Mã Nương</span>
                    <i class="fas fa-chevron-down ops-nav__caret"></i>
                </a>
                <div class="ops-nav__dropdown-menu">
                    <a href="<?= Helper::url('modules/admin/char_manage.php') ?>" class="ops-nav__dropdown-item <?= (strpos($currentScript, 'char') !== false) ? 'active' : '' ?>">
                        <i class="fas fa-id-card-clip"></i>
                        <div class="ops-nav__dropdown-text">
                            <span class="ops-nav__dropdown-title">Hồ sơ Mã Nương</span>
                            <span class="ops-nav__dropdown-desc">Tiểu sử, giọng nói &amp; thông tin học viện</span>
                        </div>
                    </a>
                    <a href="<?= Helper::url('modules/admin/stats_manage.php') ?>" class="ops-nav__dropdown-item <?= (strpos($currentScript, 'stats') !== false) ? 'active' : '' ?>">
                        <i class="fas fa-bolt"></i>
                        <div class="ops-nav__dropdown-text">
                            <span class="ops-nav__dropdown-title">Tốc độ &amp; Chấn thương</span>
                            <span class="ops-nav__dropdown-desc">Tốc độ max, rủi ro chấn thương &amp; chiến thuật</span>
                        </div>
                    </a>
                </div>
            </div>
            <a href="<?= Helper::url('modules/admin/users_manage.php') ?>" class="ops-nav__link ops-nav__link--users <?= (strpos($currentScript, 'user') !== false) ? 'active' : '' ?>">
                <i class="fas fa-users-gear"></i>
                <span>Quản trị Tài khoản</span>
            </a>
            <a href="<?= Helper::url('pages/racing.php') ?>" target="_blank" class="ops-nav__link ops-nav__link--racing" style="color:#f59e0b;">
                <i class="fas fa-flag-checkered"></i>
                <span>Đua Derby Cup ↗</span>
            </a>

            <!-- 3-Dots Overflow Menu (Ẩn các chức năng khi thanh điều hướng dài lấn sang tài khoản) -->
            <div class="ops-nav__more" id="ops-nav-more">
                <button type="button" class="ops-nav__more-btn" id="ops-nav-more-btn" title="Chức năng thêm" aria-label="Xem thêm chức năng">
                    <i class="fas fa-ellipsis"></i>
                </button>
                <div class="ops-nav__more-menu" id="ops-nav-more-menu">
                    <a href="<?= Helper::url('modules/admin/users_manage.php') ?>" class="ops-nav__more-item ops-nav__more-item--users <?= (strpos($currentScript, 'user') !== false) ? 'active' : '' ?>">
                        <i class="fas fa-users-gear"></i>
                        <span>Quản trị Tài khoản</span>
                    </a>
                    <a href="<?= Helper::url('pages/racing.php') ?>" target="_blank" class="ops-nav__more-item ops-nav__more-item--racing" style="color:#f59e0b;">
                        <i class="fas fa-flag-checkered"></i>
                        <span>Đua Derby Cup ↗</span>
                    </a>
                    <a href="<?= Helper::url('modules/admin/news_manage.php') ?>" class="ops-nav__more-item ops-nav__more-item--news <?= (strpos($currentScript, 'news') !== false) ? 'active' : '' ?>">
                        <i class="fas fa-newspaper"></i>
                        <span>Quản lý Tin tức</span>
                    </a>
                </div>
            </div>
        </nav>

        <div class="ops-header__tools">
            <div class="ops-user-menu">
                <div class="ops-user-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="ops-user-details">
                    <span class="ops-user-name"><?= htmlspecialchars(Auth::username() ?? 'Admin') ?></span>
                    <span class="ops-user-role">Quản Trị Viên</span>
                </div>
                <div class="ops-user-actions">
                    <a href="<?= Helper::url('index.php') ?>" title="Xem Trang Chủ Độc Giả" class="ops-user-action-btn">
                        <i class="fas fa-globe"></i> Trang chủ
                    </a>
                    <a href="<?= Helper::url('pages/logout.php') ?>" title="Đăng Xuất" class="ops-user-action-btn ops-logout">
                        <i class="fas fa-arrow-right-from-bracket"></i> Thoát
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- ── 2. Breadcrumb & Quick Action Ribbon ─────────────────────────── -->
    <div class="ops-ribbon">
        <div class="ops-ribbon__left">
            <div class="ops-breadcrumb">
                <a href="<?= Helper::url('index.php') ?>"><i class="fas fa-home"></i> TracenTimes</a>
                <span class="ops-bc-sep">/</span>
                <a href="<?= Helper::url('modules/admin/index.php') ?>">Quản trị hệ thống</a>
                <span class="ops-bc-sep">/</span>
                <span class="ops-bc-current"><?= $pageTitle ?? 'Tổng quan' ?></span>
            </div>
        </div>

        <div class="ops-ribbon__right">
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a href="<?= Helper::url('modules/admin/news_form.php') ?>" class="btn-adm btn-adm-primary btn-adm-sm">
                    <i class="fas fa-plus"></i> Đăng tin mới
                </a>
                <a href="<?= Helper::url('modules/admin/char_form.php') ?>" class="btn-adm btn-adm-secondary btn-adm-sm">
                    <i class="fas fa-user-plus"></i> Thêm nhân vật
                </a>
                <a href="<?= Helper::url('modules/admin/user_form.php') ?>" class="btn-adm btn-adm-secondary btn-adm-sm" style="border-color:rgba(168, 85, 247, 0.4); color:#c084fc;">
                    <i class="fas fa-user-shield"></i> Thêm tài khoản
                </a>
            </div>
        </div>
    </div>

    <!-- ── 3. Main Content Container ───────────────────────────────────── -->
    <main class="ops-content">
        <!-- Flash Message Alerts -->
        <?php if ($successMsg = Helper::getFlash('success')): ?>
            <div class="ops-alert ops-alert-success">
                <i class="fas fa-check-circle fa-lg"></i>
                <span><?= htmlspecialchars($successMsg) ?></span>
            </div>
        <?php endif; ?>
        <?php if ($errorMsg = Helper::getFlash('error')): ?>
            <div class="ops-alert ops-alert-danger">
                <i class="fas fa-exclamation-triangle fa-lg"></i>
                <span><?= htmlspecialchars($errorMsg) ?></span>
            </div>
        <?php endif; ?>
