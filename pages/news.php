<?php
// ====================================================================
//  pages/news.php — Trang Danh sách Tin tức & Sự kiện (3-Tier View)
// ====================================================================

require_once __DIR__ . '/../config/app.php';

$newsService = new NewsService();

$page = (int)($_GET['page'] ?? 1);
$category = trim($_GET['category'] ?? '');
$search = trim($_GET['search'] ?? '');

$data = $newsService->getList($page, 6, $category, $search);
$newsList = $data['items'];
$categories = $newsService->getCategories();

$pageTitle = 'Tin Tức & Sự Kiện — TracenTimes';
include __DIR__ . '/../header.php';
?>

<div style="padding-top: 50px; padding-bottom: 70px; min-height: 85vh; background: #0c0e17; color: #fff;">
    <div class="container" style="max-width: 1140px; margin: 0 auto; padding: 0 20px;">
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 45px;">
            <span class="section-label" style="background: rgba(255, 75, 130, 0.15); color: #ff4b82; border-color: rgba(255, 75, 130, 0.4); font-size:0.85rem; padding:4px 14px; border-radius:20px; font-weight:bold; letter-spacing:1px;">
                TRACEN BULLETINS
            </span>
            <h1 style="font-family: 'Cinzel', serif; font-size: 2.8rem; margin: 15px 0 10px; color: #fff; text-shadow: 0 0 20px rgba(255, 75, 130, 0.3);">
                Tin Tức &amp; Sự Kiện
            </h1>
            <p style="color: #94a3b8; font-size: 1.05rem; max-width: 600px; margin: 0 auto;">
                Cập nhật thông tin nhanh nhất về các mùa giải Derby, banner nhân vật và chiến dịch quà tặng.
            </p>
        </div>

        <!-- Filter & Search Toolbar -->
        <div style="background: #141824; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 20px; margin-bottom: 40px; display: flex; flex-wrap: wrap; gap: 15px; justify-content: space-between; align-items: center;">
            
            <!-- Category Tabs -->
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="<?= Helper::url('pages/news.php' . ($search ? '?search=' . urlencode($search) : '')) ?>" 
                   style="padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: all 0.2s; <?= empty($category) ? 'background:#ff4b82; color:#fff;' : 'background:rgba(255,255,255,0.06); color:#cbd5e1;' ?>">
                    Tất Cả
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="<?= Helper::url('pages/news.php?category=' . urlencode($cat) . ($search ? '&search=' . urlencode($search) : '')) ?>" 
                       style="padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: all 0.2s; <?= ($category === $cat) ? 'background:#ff4b82; color:#fff;' : 'background:rgba(255,255,255,0.06); color:#cbd5e1;' ?>">
                        <?= $cat ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Search Input -->
            <form method="GET" style="display: flex; gap: 10px; width: 100%; max-width: 340px;">
                <?php if ($category): ?>
                    <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
                <?php endif; ?>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Tìm kiếm bài viết..." 
                       style="flex: 1; padding: 10px 16px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); border-radius: 25px; color: #fff; font-size: 0.9rem; outline: none;">
                <button type="submit" style="padding: 10px 20px; background: #ff4b82; border: none; border-radius: 25px; color: #fff; font-weight: bold; cursor: pointer; transition: background 0.2s;">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- News Card Grid -->
        <?php if (empty($newsList)): ?>
            <div style="text-align: center; padding: 60px 20px; background: #141824; border-radius: 16px; border: 1px solid rgba(255,255,255,0.08);">
                <i class="fas fa-newspaper fa-3x" style="color: #64748b; margin-bottom: 15px;"></i>
                <h3 style="color: #cbd5e1; margin-bottom: 8px;">Không tìm thấy bài viết tin tức nào</h3>
                <p style="color: #94a3b8;">Vui lòng thử tìm kiếm với từ khóa khác hoặc xóa bộ lọc chuyên mục.</p>
                <a href="<?= Helper::url('pages/news.php') ?>" style="display: inline-block; margin-top: 15px; padding: 8px 22px; background: #ff4b82; color: #fff; text-decoration: none; border-radius: 20px; font-weight: bold;">
                    Xem Tất Cả Tin Tức
                </a>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 28px;">
                <?php foreach ($newsList as $item): ?>
                    <article style="background: #141824; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;"
                             onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='rgba(255, 75, 130, 0.4)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.5)';"
                             onmouseout="this.style.transform='none'; this.style.borderColor='rgba(255,255,255,0.08)'; this.style.boxShadow='none';">
                        
                        <!-- Thumbnail -->
                        <div style="position: relative; height: 190px; overflow: hidden; background: #1e2433;">
                            <img src="<?= Helper::asset($item['thumbnail'] ?: 'media/img/galleries/media_gallery_1.png') ?>" 
                                 alt="<?= htmlspecialchars($item['title']) ?>" 
                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;"
                                 onerror="this.src='<?= Helper::asset('media/img/galleries/media_gallery_1.png') ?>'">
                            
                            <div style="position: absolute; top: 14px; left: 14px;">
                                <?= Helper::categoryBadge($item['category']) ?>
                            </div>
                            <?php if (!empty($item['is_featured'])): ?>
                                <div style="position: absolute; top: 14px; right: 14px; background: rgba(245, 158, 11, 0.9); color: #000; font-size: 0.75rem; font-weight: 800; padding: 4px 10px; border-radius: 12px; letter-spacing: 0.5px;">
                                    ⭐ TIÊU ĐIỂM
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Content -->
                        <div style="padding: 24px; flex: 1; display: flex; flex-direction: column;">
                            <div style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                                <i class="far fa-calendar-alt"></i> <?= Helper::formatDate($item['published_at'], 'd/m/Y H:i') ?>
                            </div>

                            <h2 style="font-size: 1.25rem; font-weight: 700; line-height: 1.4; margin: 0 0 12px; color: #fff;">
                                <a href="<?= Helper::url('pages/news_detail.php?id=' . $item['id']) ?>" style="color: inherit; text-decoration: none;">
                                    <?= htmlspecialchars($item['title']) ?>
                                </a>
                            </h2>

                            <p style="color: #94a3b8; font-size: 0.92rem; line-height: 1.6; margin: 0 0 20px; flex: 1;">
                                <?= htmlspecialchars(Helper::truncate($item['summary'] ?: $item['content'], 120)) ?>
                            </p>

                            <div style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.82rem; color: #64748b;">
                                    <i class="fas fa-feather-pointed"></i> <?= htmlspecialchars($item['author_name'] ?? 'Admin Tracen') ?>
                                </span>
                                <a href="<?= Helper::url('pages/news_detail.php?id=' . $item['id']) ?>" 
                                   style="color: #ff4b82; font-weight: 700; font-size: 0.9rem; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                                    Đọc Chi Tiết <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
                                </a>
                            </div>
                        </div>

                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Phân Trang -->
            <?php if ($data['totalPages'] > 1): ?>
                <div style="display: flex; justify-content: center; gap: 10px; margin-top: 50px;">
                    <?php for ($p = 1; $p <= $data['totalPages']; $p++): ?>
                        <a href="<?= Helper::url('pages/news.php?page=' . $p . ($category ? '&category=' . urlencode($category) : '') . ($search ? '&search=' . urlencode($search) : '')) ?>" 
                           style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; text-decoration: none; transition: all 0.2s; <?= ($p === $data['page']) ? 'background: #ff4b82; color: #fff; box-shadow: 0 4px 14px rgba(255,75,130,0.4);' : 'background: #141824; border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1;' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
