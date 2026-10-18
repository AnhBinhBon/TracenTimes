<?php
// ====================================================================
//  header.php — Layout Header chính của TracenTimes
// ====================================================================

require_once __DIR__ . '/config/app.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TracenTimes — Cổng thông tin tin tức, hồ sơ nhân vật và đường đua mô phỏng Umamusume Pretty Derby.">
    <title><?= $pageTitle ?? 'TracenTimes — Cổng Thông Tin Umamusume Pretty Derby' ?></title>
    <link rel="icon" href="<?= BASE_URL ?>/media/img/icon.png" type="image/png">

    <!-- Google Fonts: Cinzel & Outfit & Noto Sans JP -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Noto+Sans+JP:wght@300;400;600;700&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Modular CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/banner_video.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/news.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/characters.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/media_gallery.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/auth.css">
    
    <!-- Font Awesome 6.5.2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer" />

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
                    // Nếu ảnh mã nương dự phòng cũng không tải được, dùng ảnh Error Placeholder
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

        // Bắt lỗi toàn cục cho mọi ảnh bị hỏng trên trang
        document.addEventListener('error', function(e) {
            if (e.target && e.target.tagName === 'IMG') {
                const img = e.target;
                if (!img.dataset.hasHandledError) {
                    img.dataset.hasHandledError = 'true';
                    window.handleImageError(img);
                }
            }
        }, true);
    </script>
</head>

<body>

    <header class="site-header" id="site-header">
        <div class="nav-container">

            <!-- Logo -->
            <a href="<?= BASE_URL ?>/" class="site-logo" id="site-logo">
                <div class="logo-placeholder" style="display:flex; align-items:center; gap:8px;">
                    <img src="<?= BASE_URL ?>/media/img/logo.png" alt="TracenTimes Logo" onerror="this.style.display='none'; this.parentNode.innerHTML='<span class=\'logo-text\'>🏇 TRACENTIMES</span>'">
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="main-nav" aria-label="Main navigation">
                <ul class="main-menu" id="main-menu">
                    <li><a href="<?= BASE_URL ?>/" class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'index.php' && strpos($_SERVER['PHP_SELF'], 'modules') === false) ? 'active' : '' ?>">HOME</a></li>
                    <li><a href="<?= BASE_URL ?>/pages/news.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'news.php') ? 'active' : '' ?>">NEWS</a></li>
                    <li class="nav-item-chars"><a href="<?= BASE_URL ?>/pages/characters_list.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'characters_list.php') ? 'active' : '' ?>">CHARACTERS</a></li>
                    <li class="nav-item-derby">
                        <a href="<?= BASE_URL ?>/pages/racing.php" class="nav-link nav-link-derby <?= (basename($_SERVER['PHP_SELF']) === 'racing.php') ? 'active' : '' ?>">
                            <i class="fas fa-flag-checkered"></i> DERBY CUP
                        </a>
                    </li>
                    <li class="nav-item-media"><a href="<?= BASE_URL ?>/#media" class="nav-link">MEDIA</a></li>

                    <!-- 3-dots Overflow Dropdown Menu -->
                    <li class="nav-item-more" id="nav-item-more">
                        <button type="button" class="nav-more-btn" id="nav-more-btn" title="Chức năng thêm" aria-label="Xem thêm chức năng">
                            <i class="fas fa-ellipsis"></i>
                        </button>
                        <div class="nav-more-dropdown" id="nav-more-dropdown">
                            <a href="<?= BASE_URL ?>/pages/racing.php" class="nav-more-item nav-more-item--derby <?= (basename($_SERVER['PHP_SELF']) === 'racing.php') ? 'active' : '' ?>">
                                <i class="fas fa-flag-checkered"></i> <span>DERBY CUP</span>
                            </a>
                            <a href="<?= BASE_URL ?>/#media" class="nav-more-item nav-more-item--media">
                                <i class="fas fa-photo-film"></i> <span>MEDIA</span>
                            </a>
                            <a href="<?= BASE_URL ?>/pages/characters_list.php" class="nav-more-item nav-more-item--chars <?= (basename($_SERVER['PHP_SELF']) === 'characters_list.php') ? 'active' : '' ?>">
                                <i class="fas fa-users"></i> <span>CHARACTERS</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Right Actions & User Hub -->
            <div class="header-actions">
                <?php if (Auth::isAdmin()): ?>
                    <a href="<?= BASE_URL ?>/modules/admin/index.php" class="header-btn header-btn-admin" title="Vào khu vực Quản trị Hệ thống">
                        <i class="fas fa-screwdriver-wrench"></i> <span>Quản Trị</span>
                    </a>
                <?php endif; ?>

                <a href="#" class="header-btn header-btn-play" id="btn-play-now">
                    <i class="fas fa-play"></i> <span>Play Now</span>
                </a>

                <?php if (Auth::isLoggedIn()): ?>
                    <div class="header-user-capsule">
                        <div class="header-user-avatar" title="<?= htmlspecialchars(Auth::username()) ?>">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <span class="header-user-name"><?= htmlspecialchars(Auth::username()); ?></span>
                        <?php if (Auth::isAdmin()): ?>
                            <span class="header-user-badge">ADMIN</span>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>/pages/logout.php" class="header-logout-btn" title="Đăng xuất">
                            <i class="fas fa-arrow-right-from-bracket"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/pages/login.php" class="header-btn header-btn-login">
                        <i class="fas fa-right-to-bracket"></i> <span>Đăng nhập</span>
                    </a>
                <?php endif; ?>

                <!-- Mobile Hamburger Toggle -->
                <button class="hamburger" id="hamburger-btn" aria-label="Open navigation menu" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

        </div>

        <!-- Mobile Dropdown Menu -->
        <nav class="mobile-nav" id="mobile-nav" aria-hidden="true">
            <ul class="mobile-menu">
                <li><a href="<?= BASE_URL ?>/" class="mobile-nav-link">HOME</a></li>
                <li><a href="<?= BASE_URL ?>/pages/news.php" class="mobile-nav-link">NEWS</a></li>
                <li><a href="<?= BASE_URL ?>/pages/characters_list.php" class="mobile-nav-link">CHARACTERS</a></li>
                <li><a href="<?= BASE_URL ?>/pages/racing.php" class="mobile-nav-link">DERBY CUP</a></li>
                <li><a href="<?= BASE_URL ?>/#media" class="mobile-nav-link">MEDIA</a></li>
                <?php if (Auth::isAdmin()): ?>
                    <li><a href="<?= BASE_URL ?>/modules/admin/index.php" class="mobile-nav-link" style="color:#f59e0b; font-weight:bold;"><i class="fas fa-gear"></i> KHU VỰC QUẢN TRỊ (ADMIN)</a></li>
                <?php endif; ?>
                <li><a href="#" class="mobile-nav-link mobile-cta" id="btn-play-now-mobile">PLAY NOW</a></li>
                <?php if (Auth::isLoggedIn()): ?>
                    <li><span class="mobile-nav-username">👤 <?= htmlspecialchars(Auth::username()); ?></span></li>
                    <li><a href="<?= BASE_URL ?>/pages/logout.php" class="mobile-nav-link mobile-auth-logout">ĐĂNG XUẤT</a></li>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>/pages/login.php" class="mobile-nav-link mobile-auth-login">ĐĂNG NHẬP</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php 
        $modalFile = __DIR__ . '/sections/play_modal.php';
        if (file_exists($modalFile)) include $modalFile; 
        ?>
    </header>

    <div class="header-spacer"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Play Now Modal Toggles
            const playModal = document.getElementById('play-modal');
            const btnPlay = document.getElementById('btn-play-now');
            const btnPlayMobile = document.getElementById('btn-play-now-mobile');
            const btnCloseModal = document.getElementById('modal-close-btn');
            const modalOverlay = document.getElementById('modal-overlay');

            function openModal(e) {
                if (e) e.preventDefault();
                if (playModal) playModal.classList.add('show');
            }

            function closeModal(e) {
                if (e) e.preventDefault();
                if (playModal) playModal.classList.remove('show');
            }

            if (btnPlay) btnPlay.addEventListener('click', openModal);
            if (btnPlayMobile) btnPlayMobile.addEventListener('click', openModal);
            if (btnCloseModal) btnCloseModal.addEventListener('click', closeModal);
            if (modalOverlay) modalOverlay.addEventListener('click', closeModal);

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && playModal && playModal.classList.contains('show')) {
                    closeModal();
                }
            });

            // 2. 3-dots Menu Toggle (Desktop Navigation Overflow)
            const navMoreItem = document.getElementById('nav-item-more');
            const navMoreBtn = document.getElementById('nav-more-btn');
            if (navMoreBtn && navMoreItem) {
                navMoreBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    navMoreItem.classList.toggle('is-open');
                });
                document.addEventListener('click', function(e) {
                    if (!navMoreItem.contains(e.target)) {
                        navMoreItem.classList.remove('is-open');
                    }
                });
            }

            // 3. Hamburger Mobile Navigation Toggle
            const hamburger = document.getElementById('hamburger-btn');
            const mobileNav = document.getElementById('mobile-nav');
            if (hamburger && mobileNav) {
                hamburger.addEventListener('click', function() {
                    const isOpen = mobileNav.classList.toggle('is-open');
                    hamburger.classList.toggle('is-active', isOpen);
                    hamburger.setAttribute('aria-expanded', isOpen);
                });
            }
        });
    </script>