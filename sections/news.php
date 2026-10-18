<?php
// ====================================================================
//  sections/news.php — Khối Tin tức trên Trang chủ (3-Tier View)
// ====================================================================

require_once __DIR__ . '/../config/app.php';

$newsService = new NewsService();
$newsList = $newsService->getFeatured(3);
?>
<section id="news" class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-label" style="background: rgba(172, 196, 0, 0.15); color: #acc400; border-color: rgba(172, 196, 0, 0.4);">LATEST</span>
            <h2 class="section-title">News &amp; Updates</h2>
            <p class="section-subtitle">Thông báo sự kiện, giải đấu Derby và cập nhật tính năng mới nhất từ Học viện Tracen.</p>
        </div>
        
        <?php if (empty($newsList)): ?>
            <p style="text-align:center; color: var(--text-muted);">Chưa có tin tức nào được đăng tải.</p>
        <?php else: ?>
            <div class="news-list">
                <?php foreach ($newsList as $item): ?>
                    <div class="news-item" style="display:flex; justify-content:space-between; align-items:center; gap:20px; padding:20px; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:12px; margin-bottom:14px; transition:all 0.3s ease;">
                        <div class="news-meta" style="display:flex; align-items:center; gap:14px; min-width:240px;">
                            <?= Helper::categoryBadge($item['category'] ?? 'Event') ?>
                            <span class="news-date" style="color:var(--text-muted); font-size:0.88rem;">
                                <i class="far fa-clock"></i> <?= Helper::formatDate($item['published_at'] ?? '', 'd/m/Y') ?>
                            </span>
                        </div>
                        <div class="news-content" style="flex:1;">
                            <a href="<?= BASE_URL ?>/pages/news_detail.php?id=<?= (int)$item['id']; ?>" style="color:#fff; text-decoration:none; font-weight:700; font-size:1.1rem; transition:color 0.2s;" onmouseover="this.style.color='var(--color-pink)'" onmouseout="this.style.color='#fff'">
                                <?= htmlspecialchars($item['title'] ?? '') ?>
                            </a>
                            <?php if (!empty($item['summary'])): ?>
                                <p style="margin:5px 0 0; color:var(--text-muted); font-size:0.88rem;">
                                    <?= htmlspecialchars(Helper::truncate($item['summary'], 90)) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <a href="<?= BASE_URL ?>/pages/news_detail.php?id=<?= (int)$item['id']; ?>" class="btn-read-detail" style="display:inline-block; padding:8px 18px; background:var(--color-purple); color:#fff; text-decoration:none; border-radius:20px; font-size:0.85rem; font-weight:bold; white-space:nowrap; transition:all 0.2s;">
                                Xem Chi Tiết <i class="fas fa-arrow-right" style="margin-left:4px;"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="view-more-container" style="text-align: center; margin-top: 35px;">
                <a href="<?= BASE_URL ?>/pages/news.php" class="btn-view-more" style="display: inline-block; padding: 12px 36px; border: 2px solid var(--color-pink); color: var(--text-primary); text-transform: uppercase; font-weight: bold; letter-spacing: 0.1em; text-decoration: none; border-radius: 30px; transition: all 0.3s ease;">
                    Xem Toàn Bộ Tin Tức &rarr;
                </a>
            </div>
            
            <style>
                .btn-view-more:hover {
                    background-color: var(--color-pink);
                    color: white !important;
                    box-shadow: 0 4px 15px rgba(255, 102, 163, 0.4);
                    transform: translateY(-2px);
                }
            </style>
        <?php endif; ?>
    </div>
</section>
