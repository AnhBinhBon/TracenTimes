<?php
// ====================================================================
//  pages/news_detail.php — Trang Chi tiết Tin tức & Sự kiện (3-Tier View)
// ====================================================================

require_once __DIR__ . '/../config/app.php';

$newsService = new NewsService();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$newsItem = $newsService->getDetail($id);

if (!$newsItem) {
    header('Location: ' . Helper::url('pages/404.php'));
    exit();
}

$relatedNews = $newsService->getLatest(3);

$pageTitle = htmlspecialchars($newsItem['title']) . ' — TracenTimes';
include __DIR__ . '/../header.php';
?>

<div style="padding-top: 50px; padding-bottom: 80px; min-height: 85vh; background: #0c0e17; color: #fff;">
    <div class="container" style="max-width: 960px; margin: 0 auto; padding: 0 20px;">
        
        <!-- Breadcrumb -->
        <div style="font-size: 0.9rem; color: #94a3b8; margin-bottom: 25px; display: flex; align-items: center; gap: 8px;">
            <a href="<?= Helper::url('index.php') ?>" style="color: #cbd5e1; text-decoration: none;"><i class="fas fa-home"></i> Trang Chủ</a>
            <span>/</span>
            <a href="<?= Helper::url('pages/news.php') ?>" style="color: #cbd5e1; text-decoration: none;">Tin Tức</a>
            <span>/</span>
            <span style="color: #ff4b82;"><?= htmlspecialchars(Helper::truncate($newsItem['title'], 35)) ?></span>
        </div>

        <article style="background: #141824; border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.5);">
            
            <!-- Header -->
            <div style="margin-bottom: 30px;">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 16px;">
                    <?= Helper::categoryBadge($newsItem['category'] ?? 'Event') ?>
                    <span style="font-size: 0.9rem; color: #94a3b8;">
                        <i class="far fa-clock"></i> <?= Helper::formatDate($newsItem['published_at']) ?>
                    </span>
                    <span style="font-size: 0.9rem; color: #64748b;">
                        <i class="fas fa-user-edit"></i> <?= htmlspecialchars($newsItem['author_name'] ?? 'Admin Tracen') ?>
                    </span>
                </div>

                <h1 style="font-size: 2.4rem; font-weight: 800; line-height: 1.35; margin: 0 0 16px; color: #fff;">
                    <?= htmlspecialchars($newsItem['title']) ?>
                </h1>

                <?php if (!empty($newsItem['summary'])): ?>
                    <p style="font-size: 1.15rem; color: #cbd5e1; line-height: 1.7; font-style: italic; border-left: 4px solid #ff4b82; padding-left: 18px; margin: 20px 0 0;">
                        <?= htmlspecialchars($newsItem['summary']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Banner / Thumbnail nếu có -->
            <?php if (!empty($newsItem['thumbnail'])): ?>
                <div style="border-radius: 14px; overflow: hidden; margin-bottom: 35px; border: 1px solid rgba(255,255,255,0.1); max-height: 420px;">
                    <img src="<?= Helper::asset($newsItem['thumbnail']) ?>" alt="<?= htmlspecialchars($newsItem['title']) ?>" 
                         style="width: 100%; height: 100%; object-fit: cover;"
                         onerror="this.src='<?= Helper::asset('media/img/galleries/media_gallery_1.png') ?>'">
                </div>
            <?php endif; ?>

            <!-- Nội Dung Chính (Main Content) -->
            <?php if (!empty($newsItem['content'])): ?>
                <div style="font-size: 1.05rem; line-height: 1.8; color: #e2e8f0; margin-bottom: 40px;">
                    <?= Helper::renderContent($newsItem['content']) ?>
                </div>
            <?php endif; ?>

            <!-- ── KHỐI 1: RACE / PERIOD / REWARDS ────────────────────────── -->
            <?php if (!empty($newsItem['race_period_rewards'])): ?>
                <div style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 14px; padding: 24px; margin-bottom: 25px;">
                    <h3 style="margin-top: 0; color: #f59e0b; font-size: 1.25rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-trophy"></i> Race / Period / Rewards
                    </h3>
                    <div style="color: #fde68a; font-size: 1rem; line-height: 1.7; white-space: pre-line;">
                        <?= htmlspecialchars($newsItem['race_period_rewards']) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ── KHỐI 2: IMPORTANT INFORMATION ─────────────────────────── -->
            <?php if (!empty($newsItem['important_information'])): ?>
                <div style="background: rgba(255, 75, 130, 0.08); border: 1px solid rgba(255, 75, 130, 0.3); border-radius: 14px; padding: 24px; margin-bottom: 35px;">
                    <h3 style="margin-top: 0; color: #ff4b82; font-size: 1.25rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-circle-exclamation"></i> Important Information
                    </h3>
                    <div style="color: #fbcfe8; font-size: 1rem; line-height: 1.7; white-space: pre-line;">
                        <?= htmlspecialchars($newsItem['important_information']) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Footer Actions -->
            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 25px; margin-top: 40px; flex-wrap: wrap; gap: 15px;">
                <a href="<?= Helper::url('pages/news.php') ?>" style="display: inline-flex; align-items: center; gap: 8px; color: #cbd5e1; text-decoration: none; font-weight: bold; padding: 10px 22px; background: rgba(255,255,255,0.05); border-radius: 25px; transition: background 0.2s;">
                    <i class="fas fa-arrow-left"></i> Quay lại Danh Sách Tin
                </a>
                
                <?php if (Auth::isAdmin()): ?>
                    <a href="<?= Helper::url('modules/admin/news_form.php?id=' . $newsItem['id']) ?>" style="display: inline-flex; align-items: center; gap: 8px; color: #f59e0b; text-decoration: none; font-weight: bold; padding: 10px 22px; background: rgba(245, 158, 11, 0.15); border: 1px solid #f59e0b; border-radius: 25px;">
                        <i class="fas fa-pen-to-square"></i> Sửa bài viết (Admin)
                    </a>
                <?php endif; ?>
            </div>

        </article>

        <!-- Tin Tức Liên Quan -->
        <div style="margin-top: 60px;">
            <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 25px; color: #fff; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-newspaper" style="color: #ff4b82;"></i> Tin Tức Khác Mới Đăng
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                <?php foreach ($relatedNews as $rel): 
                    if ($rel['id'] == $newsItem['id']) continue;
                ?>
                    <div style="background: #141824; border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="margin-bottom: 8px;"><?= Helper::categoryBadge($rel['category']) ?></div>
                            <h4 style="margin: 0 0 10px; font-size: 1.05rem;">
                                <a href="<?= Helper::url('pages/news_detail.php?id=' . $rel['id']) ?>" style="color: #fff; text-decoration: none;">
                                    <?= htmlspecialchars($rel['title']) ?>
                                </a>
                            </h4>
                        </div>
                        <div style="font-size: 0.8rem; color: #94a3b8; display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                            <span><?= Helper::formatDate($rel['published_at'], 'd/m/Y') ?></span>
                            <a href="<?= Helper::url('pages/news_detail.php?id=' . $rel['id']) ?>" style="color: #ff4b82; text-decoration: none; font-weight: bold;">
                                Xem &rarr;
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
