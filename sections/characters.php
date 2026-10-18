<?php
// ====================================================================
//  sections/characters.php — Khối Nhân vật trên Trang chủ (3-Tier View)
// ====================================================================

require_once __DIR__ . '/../config/app.php';

$charService = new CharacterService();
$charactersList = $charService->getFeatured(4);
?>
<section id="characters" class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">ROSTER</span>
            <h2 class="section-title">Characters</h2>
            <p class="section-subtitle">Những cô gái ngựa huyền thoại của Học viện Tracen đang chờ bạn khám phá.</p>
        </div>

        <?php if (empty($charactersList)): ?>
            <p style="text-align:center; color: var(--color-pink);">Chưa có nhân vật nào trong hệ thống.</p>
        <?php else: ?>
            <div class="char-grid">
                <?php foreach ($charactersList as $row): ?>
                    <!-- Bọc toàn bộ card trong thẻ <a> để click vào xem chi tiết -->
                    <a href="<?= BASE_URL ?>/pages/character_detail.php?id=<?= (int)$row['id']; ?>"
                        class="char-card char-card--link"
                        id="char-home-card-<?= (int)$row['id']; ?>">

                        <!-- Avatar -->
                        <div class="char-avatar" style="padding: 0; background-color: transparent; border: none; position:relative;">
                            <img src="<?= htmlspecialchars(Helper::characterAvatar($row)); ?>"
                                alt="<?= htmlspecialchars($row['name']); ?>"
                                class="char-avatar__img"
                                data-fallback="<?= htmlspecialchars(!empty($row['avatar']) ? Helper::asset($row['avatar']) : Helper::asset('media/img/placeholder.png')); ?>"
                                onerror="if (typeof handleImageError === 'function') { handleImageError(this, this.dataset.fallback); } else { this.onerror=null; this.src='<?= Helper::asset('media/img/placeholder.png') ?>'; }">

                            <!-- Overlay hover -->
                            <div class="char-card__overlay">
                                <span class="char-card__view-btn">
                                    <i class="fas fa-eye"></i> Xem Hồ Sơ
                                </span>
                            </div>
                        </div>

                        <!-- Nameplate -->
                        <div class="char-name">
                            <span class="char-name__id">#<?= str_pad((int)$row['id'], 2, '0', STR_PAD_LEFT); ?></span>
                            <?= htmlspecialchars($row['name']); ?>
                            <?php if (!empty($row['japanese_name'])): ?>
                                <div style="font-size:0.75rem; color:#aaa; font-weight:normal; margin-top:2px;">
                                    <?= htmlspecialchars($row['japanese_name']); ?>
                                </div>
                            <?php endif; ?>
                            <div style="margin-top:6px;">
                                <?= Helper::runningStyleBadge($row['running_style'] ?? 'Leader') ?>
                            </div>
                        </div>

                    </a>
                <?php endforeach; ?>
            </div>

            <!-- CTA — xem tất cả nhân vật -->
            <div style="text-align: center; margin-top: 48px;">
                <a href="<?= BASE_URL ?>/pages/characters_list.php" class="btn btn-cta" id="btn-view-all-chars">
                    <i class="fas fa-users"></i> Xem tất cả nhân vật &rarr;
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>