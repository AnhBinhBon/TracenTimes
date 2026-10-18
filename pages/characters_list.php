<?php
// ====================================================================
//  pages/characters_list.php — Trang Danh sách Nhân vật (3-Tier View)
// ====================================================================

require_once __DIR__ . '/../config/app.php';

$charService = new CharacterService();

$page   = (int)($_GET['page'] ?? 1);
$search = trim($_GET['search'] ?? '');
$style  = trim($_GET['style'] ?? '');

$data = $charService->getList($page, 12, $search, $style);
$charList = $data['items'];

$pageTitle = 'Danh Sách Nhân Vật — TracenTimes';
include __DIR__ . '/../header.php';
?>

<div style="padding-top: 50px; padding-bottom: 80px; min-height: 85vh; background: #0c0e17; color: #fff;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        
        <!-- Header Hero -->
        <div style="text-align: center; margin-bottom: 45px;">
            <span class="section-label" style="background: rgba(6, 182, 212, 0.15); color: #06b6d4; border-color: rgba(6, 182, 212, 0.4); font-size:0.85rem; padding:4px 14px; border-radius:20px; font-weight:bold; letter-spacing:1px;">
                TRACEN ROSTER
            </span>
            <h1 style="font-family: 'Cinzel', serif; font-size: 2.8rem; margin: 15px 0 10px; color: #fff; text-shadow: 0 0 20px rgba(6, 182, 212, 0.3);">
                Đội Hình Uma Musume
            </h1>
            <p style="color: #94a3b8; font-size: 1.05rem; max-width: 600px; margin: 0 auto;">
                Tra cứu tiểu sử, phong cách thi đấu, chỉ số vận tốc và tỷ lệ chấn thương của từng nữ chiến mã.
            </p>
        </div>

        <!-- Filter & Search Toolbar -->
        <div style="background: #141824; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 20px; margin-bottom: 40px; display: flex; flex-wrap: wrap; gap: 15px; justify-content: space-between; align-items: center;">
            
            <!-- Running Style Filter Tabs -->
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="<?= Helper::url('pages/characters_list.php' . ($search ? '?search=' . urlencode($search) : '')) ?>" 
                   style="padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: all 0.2s; <?= empty($style) ? 'background:#06b6d4; color:#000;' : 'background:rgba(255,255,255,0.06); color:#cbd5e1;' ?>">
                    Tất Cả
                </a>
                <a href="<?= Helper::url('pages/characters_list.php?style=Runner' . ($search ? '&search=' . urlencode($search) : '')) ?>" 
                   style="padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: all 0.2s; <?= ($style === 'Runner') ? 'background:#3498db; color:#fff;' : 'background:rgba(255,255,255,0.06); color:#cbd5e1;' ?>">
                    🏃 Tiên Phong (Runner)
                </a>
                <a href="<?= Helper::url('pages/characters_list.php?style=Leader' . ($search ? '&search=' . urlencode($search) : '')) ?>" 
                   style="padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: all 0.2s; <?= ($style === 'Leader') ? 'background:#2ecc71; color:#fff;' : 'background:rgba(255,255,255,0.06); color:#cbd5e1;' ?>">
                    🏆 Dẫn Đầu (Leader)
                </a>
                <a href="<?= Helper::url('pages/characters_list.php?style=Betweener' . ($search ? '&search=' . urlencode($search) : '')) ?>" 
                   style="padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: all 0.2s; <?= ($style === 'Betweener') ? 'background:#e67e22; color:#fff;' : 'background:rgba(255,255,255,0.06); color:#cbd5e1;' ?>">
                    ⚡ Giữ Cự Ly (Betweener)
                </a>
                <a href="<?= Helper::url('pages/characters_list.php?style=Chaser' . ($search ? '&search=' . urlencode($search) : '')) ?>" 
                   style="padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: all 0.2s; <?= ($style === 'Chaser') ? 'background:#9b59b6; color:#fff;' : 'background:rgba(255,255,255,0.06); color:#cbd5e1;' ?>">
                    🚀 Bứt Tốc Cuối (Chaser)
                </a>
            </div>

            <!-- Search Form -->
            <form method="GET" style="display: flex; gap: 10px; width: 100%; max-width: 320px;">
                <?php if ($style): ?>
                    <input type="hidden" name="style" value="<?= htmlspecialchars($style) ?>">
                <?php endif; ?>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Tìm tên, tiếng Nhật, biệt danh..." 
                       style="flex: 1; padding: 10px 16px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); border-radius: 25px; color: #fff; font-size: 0.9rem; outline: none;">
                <button type="submit" style="padding: 10px 20px; background: #06b6d4; border: none; border-radius: 25px; color: #000; font-weight: bold; cursor: pointer; transition: background 0.2s;">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Character Cards Grid -->
        <?php if (empty($charList)): ?>
            <div style="text-align: center; padding: 60px 20px; background: #141824; border-radius: 16px; border: 1px solid rgba(255,255,255,0.08);">
                <i class="fas fa-horse fa-3x" style="color: #64748b; margin-bottom: 15px;"></i>
                <h3 style="color: #cbd5e1; margin-bottom: 8px;">Không tìm thấy nhân vật nào phù hợp</h3>
                <p style="color: #94a3b8;">Vui lòng thử tìm kiếm lại với tên khác.</p>
                <a href="<?= Helper::url('pages/characters_list.php') ?>" style="display: inline-block; margin-top: 15px; padding: 8px 22px; background: #06b6d4; color: #000; text-decoration: none; border-radius: 20px; font-weight: bold;">
                    Xem Tất Cả Nhân Vật
                </a>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px;">
                <?php foreach ($charList as $char): ?>
                    <div style="background: #141824; border: 1px solid rgba(255,255,255,0.08); border-radius: 18px; overflow: hidden; display: flex; flex-direction: column; transition: all 0.25s ease;"
                         onmouseover="this.style.transform='translateY(-6px)'; this.style.borderColor='rgba(6, 182, 212, 0.4)'; this.style.boxShadow='0 12px 25px rgba(0,0,0,0.6)';"
                         onmouseout="this.style.transform='none'; this.style.borderColor='rgba(255,255,255,0.08)'; this.style.boxShadow='none';">
                        
                        <!-- Card Image -->
                        <div style="height: 240px; position: relative; background: radial-gradient(circle, #252d42 0%, #141824 100%); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img src="<?= htmlspecialchars(Helper::characterAvatar($char)) ?>" alt="<?= htmlspecialchars($char['name']) ?>" 
                                 style="max-height: 90%; max-width: 90%; object-fit: contain; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.5)); transition: transform 0.3s ease;"
                                 data-fallback="<?= htmlspecialchars(!empty($char['avatar']) ? Helper::asset($char['avatar']) : Helper::asset('media/img/placeholder.png')) ?>"
                                 onerror="if (typeof handleImageError === 'function') { handleImageError(this, this.dataset.fallback); } else { this.onerror=null; this.src='<?= Helper::asset('media/img/placeholder.png') ?>'; }">
                            
                            <!-- Badges on top -->
                            <div style="position: absolute; top: 12px; left: 12px;">
                                <span style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); color: #fff; font-size: 0.75rem; font-weight: 800; padding: 3px 8px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2);">
                                    #<?= str_pad((int)$char['id'], 2, '0', STR_PAD_LEFT) ?>
                                </span>
                            </div>
                            <?php if (!empty($char['nickname'])): ?>
                                <div style="position: absolute; bottom: 10px; left: 12px; right: 12px; text-align: center;">
                                    <span style="background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); color: #f59e0b; font-size: 0.72rem; font-weight: 700; padding: 3px 10px; border-radius: 12px; border: 1px solid rgba(245, 158, 11, 0.3); display: inline-block; max-width: 90%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        ★ <?= htmlspecialchars($char['nickname']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Card Body -->
                        <div style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
                            <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0 0 4px; color: #fff;">
                                <?= htmlspecialchars($char['name']) ?>
                            </h3>
                            <?php if (!empty($char['japanese_name'])): ?>
                                <div style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 12px;">
                                    <?= htmlspecialchars($char['japanese_name']) ?>
                                </div>
                            <?php endif; ?>

                            <!-- Running Style & Injury Rate -->
                            <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; font-size: 0.82rem;">
                                <div><?= Helper::runningStyleBadge($char['running_style'] ?? 'Leader') ?></div>
                                <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.03); padding: 6px 10px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                                    <span style="color: #94a3b8;"><i class="fas fa-heart-pulse"></i> Chấn thương:</span>
                                    <span><?= Helper::injuryBadge((float)($char['injury_rate'] ?? 0.05)) ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.03); padding: 6px 10px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                                    <span style="color: #94a3b8;"><i class="fas fa-gauge"></i> Tốc độ:</span>
                                    <span style="color: #06b6d4; font-weight: bold;"><?= $char['min_speed'] ?? 0.2 ?> → <?= $char['max_speed'] ?? 1.0 ?></span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div style="margin-top: auto;">
                                <a href="<?= Helper::url('pages/character_detail.php?id=' . $char['id']) ?>" 
                                   style="display: block; text-align: center; padding: 10px; background: rgba(6, 182, 212, 0.15); border: 1px solid #06b6d4; color: #06b6d4; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 0.88rem; transition: all 0.2s;"
                                   onmouseover="this.style.background='#06b6d4'; this.style.color='#000';"
                                   onmouseout="this.style.background='rgba(6, 182, 212, 0.15)'; this.style.color='#06b6d4';">
                                    <i class="fas fa-id-card"></i> Xem Chi Tiết Hồ Sơ
                                </a>
                            </div>

                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Phân Trang -->
            <?php if ($data['totalPages'] > 1): ?>
                <div style="display: flex; justify-content: center; gap: 10px; margin-top: 50px;">
                    <?php for ($p = 1; $p <= $data['totalPages']; $p++): ?>
                        <a href="<?= Helper::url('pages/characters_list.php?page=' . $p . ($search ? '&search=' . urlencode($search) : '') . ($style ? '&style=' . urlencode($style) : '')) ?>" 
                           style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; text-decoration: none; transition: all 0.2s; <?= ($p === $data['page']) ? 'background: #06b6d4; color: #000; box-shadow: 0 4px 14px rgba(6,182,212,0.4);' : 'background: #141824; border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1;' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
