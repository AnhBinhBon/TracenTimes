<?php
// ====================================================================
//  modules/admin/wiki_sync.php — Trung Tâm Đồng Bộ Dữ Liệu Umamusu Wiki
// ====================================================================

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../wiki/WikiSyncService.php';

$pageTitle = 'Đồng Bộ Dữ Liệu Umamusu Wiki';
$pageIcon  = 'fa-solid fa-arrows-rotate';

$wikiService = new WikiSyncService();
$syncResult = null;
$actionError = null;

// Xử lý các thao tác đồng bộ khi người dùng bấm nút
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'sync_scouts') {
            $syncResult = $wikiService->syncScoutsToNews();
            Helper::setFlash('success', "Đã đồng bộ thành công banner Scouts từ Umamusu Wiki! (Thêm mới: {$syncResult['synced']}, Cập nhật: {$syncResult['updated']})");
        } elseif ($action === 'sync_chars') {
            $charResult = $wikiService->syncCharactersFromWiki();
            Helper::setFlash('success', "Đã đồng bộ thành công hồ sơ & chỉ số nhân vật từ Umamusu Wiki! (Thêm mới: {$charResult['imported']}, Cập nhật: {$charResult['updated']})");
        } elseif ($action === 'sync_all') {
            $scoutRes = $wikiService->syncScoutsToNews();
            $charRes  = $wikiService->syncCharactersFromWiki();
            Helper::setFlash('success', "Đồng bộ toàn diện thành công! Scouts: +{$scoutRes['synced']} mới / {$scoutRes['updated']} cập nhật. Nhân vật: +{$charRes['imported']} mới / {$charRes['updated']} cập nhật.");
        }
    } catch (Exception $e) {
        $actionError = $e->getMessage();
        Helper::setFlash('danger', "Lỗi trong quá trình đồng bộ: " . $e->getMessage());
    }

    header('Location: ' . Helper::url('modules/admin/wiki_sync.php'));
    exit;
}

// Lấy thông tin trạng thái và dữ liệu preview
$fetchInfo = $wikiService->fetchWikiMainPage();
$scoutsData = $wikiService->parseScouts($fetchInfo['html'] ?? '');
$featuredChars = $wikiService->getFeaturedWikiCharacters();

include __DIR__ . '/../../includes/admin_header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px;">
    <div>
        <p style="color:#aaa; margin:0;">Kết nối trực tiếp tới <strong>https://umamusu.wiki/Main_Page</strong> để cập nhật tin tức gacha, banner tuyển trạch và hồ sơ Umamusume chuẩn xác nhất.</p>
    </div>
    <div style="display:flex; gap:10px;">
        <form method="POST" style="display:inline;">
            <input type="hidden" name="action" value="sync_all">
            <button type="submit" class="btn-adm btn-adm-primary" style="box-shadow: 0 0 15px rgba(255,75,130,0.4);">
                <i class="fas fa-bolt"></i> Đồng Bộ Toàn Bộ Dữ Liệu
            </button>
        </form>
    </div>
</div>

<!-- ── Trạng Thái Kết Nối Wiki ────────────────────────────────────────── -->
<div class="admin-stat-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); margin-bottom:30px;">
    <div class="admin-stat-card">
        <div>
            <div class="admin-stat-card__lbl">Trạng thái kết nối Umamusu Wiki</div>
            <div class="admin-stat-card__val" style="font-size:1.3rem; color: <?= !empty($fetchInfo['success']) ? '#2ecc71' : '#e74c3c' ?>;">
                <i class="fas <?= !empty($fetchInfo['success']) ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
                <?= !empty($fetchInfo['success']) ? 'Trực tuyến (200 OK)' : 'Gián đoạn' ?>
            </div>
        </div>
        <div class="admin-stat-card__icon" style="background: rgba(46, 204, 113, 0.15); color: #2ecc71;">
            <i class="fas fa-globe"></i>
        </div>
    </div>

    <div class="admin-stat-card">
        <div>
            <div class="admin-stat-card__lbl">Nguồn nạp dữ liệu</div>
            <div class="admin-stat-card__val" style="font-size:1.3rem; color:#3498db;">
                <i class="fas fa-server"></i> <?= strtoupper($fetchInfo['source'] ?? 'LIVE') ?>
            </div>
        </div>
        <div class="admin-stat-card__icon" style="background: rgba(52, 152, 219, 0.15); color: #3498db;">
            <i class="fas fa-database"></i>
        </div>
    </div>

    <div class="admin-stat-card">
        <div>
            <div class="admin-stat-card__lbl">Banner Scout đang chạy</div>
            <div class="admin-stat-card__val" style="font-size:1.3rem; color:#f1c40f;">
                5 Banners (EN & JP)
            </div>
        </div>
        <div class="admin-stat-card__icon" style="background: rgba(241, 196, 15, 0.15); color: #f1c40f;">
            <i class="fas fa-gem"></i>
        </div>
    </div>

    <div class="admin-stat-card">
        <div>
            <div class="admin-stat-card__lbl">Nhân vật Wiki nổi bật</div>
            <div class="admin-stat-card__val" style="font-size:1.3rem; color:#9b59b6;">
                <?= count($featuredChars) ?> Umamusume
            </div>
        </div>
        <div class="admin-stat-card__icon" style="background: rgba(155, 89, 182, 0.15); color: #9b59b6;">
            <i class="fas fa-users-viewfinder"></i>
        </div>
    </div>
</div>

<?php if (!empty($fetchInfo['warning'])): ?>
    <div style="background:rgba(241, 196, 15, 0.15); border:1px solid #f1c40f; color:#f1c40f; padding:12px 18px; border-radius:10px; margin-bottom:25px; display:flex; align-items:center; gap:12px;">
        <i class="fas fa-triangle-exclamation" style="font-size:1.4rem;"></i>
        <div><?= htmlspecialchars($fetchInfo['warning']) ?></div>
    </div>
<?php endif; ?>

<!-- ── Quick Sync Actions ──────────────────────────────────────────────── -->
<div style="display:flex; gap:15px; margin-bottom:30px; flex-wrap:wrap;">
    <form method="POST">
        <input type="hidden" name="action" value="sync_scouts">
        <button type="submit" class="btn-adm btn-adm-secondary">
            <i class="fas fa-newspaper"></i> 1. Đồng Bộ Banners & Scouts vào Tin Tức
        </button>
    </form>

    <form method="POST">
        <input type="hidden" name="action" value="sync_chars">
        <button type="submit" class="btn-adm btn-adm-warning">
            <i class="fas fa-horse"></i> 2. Đồng Bộ Roster & Chỉ Số Nhân Vật
        </button>
    </form>

    <a href="https://umamusu.wiki/Main_Page" target="_blank" rel="noopener" class="btn-adm btn-adm-secondary">
        <i class="fas fa-external-link-alt"></i> Mở Trang Chủ Umamusu Wiki
    </a>
</div>

<!-- ── Chi Tiết Dữ Liệu Bóc Tách từ Umamusu Wiki ──────────────────────── -->
<div style="display:grid; grid-template-columns: 1fr; gap:30px;">

    <!-- Khối 1: Danh sách Banner Scouts -->
    <div class="admin-box">
        <div class="admin-box__header">
            <h2 class="admin-box__title"><i class="fas fa-gem" style="color:var(--adm-accent);"></i> Các Banner Tuyển Trạch Đang Mở (Scouts Preview)</h2>
            <span class="badge" style="background:rgba(255,75,130,0.2); color:var(--adm-accent); border:1px solid var(--adm-accent); padding:4px 10px; border-radius:15px;">Dữ liệu trực tiếp từ Main Page</span>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(340px, 1fr)); gap:20px;">
            <?php foreach (['en', 'jp'] as $srvKey): ?>
                <?php foreach ($scoutsData[$srvKey]['banners'] as $banner): ?>
                    <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:12px; overflow:hidden; display:flex; flex-direction:column;">
                        <div style="position:relative; width:100%; height:130px; background:#111; overflow:hidden;">
                            <img src="<?= htmlspecialchars($banner['banner_image']) ?>" style="width:100%; height:100%; object-fit:cover;" onerror="this.src='<?= Helper::asset('media/img/placeholder.png') ?>'">
                            <span style="position:absolute; top:8px; right:8px; background:rgba(0,0,0,0.75); color:#fff; font-size:0.75rem; padding:3px 8px; border-radius:6px; border:1px solid rgba(255,255,255,0.2);">
                                <?= strtoupper($srvKey) ?> SERVER
                            </span>
                        </div>
                        <div style="padding:15px; flex:1; display:flex; flex-direction:column; justify-content:space-between;">
                            <div>
                                <h4 style="margin:0 0 8px 0; font-size:1.05rem; color:#fff;"><?= htmlspecialchars($banner['title']) ?></h4>
                                <div style="font-size:0.85rem; color:#f1c40f; margin-bottom:10px;">
                                    <i class="far fa-clock"></i> Kết thúc: <strong><?= htmlspecialchars($banner['end_date']) ?></strong>
                                </div>
                                <div style="font-size:0.85rem; color:#aaa; margin-bottom:12px;">
                                    <?= htmlspecialchars($banner['description']) ?>
                                </div>
                                <div style="font-size:0.85rem; background:rgba(0,0,0,0.25); padding:10px; border-radius:8px; border:1px solid rgba(255,255,255,0.05);">
                                    <strong style="color:var(--adm-accent);"><i class="fas fa-star"></i> Tỷ lệ Pickups:</strong>
                                    <ul style="margin:5px 0 0 18px; padding:0; color:#ddd;">
                                        <?php foreach ($banner['pickups'] as $pk): ?>
                                            <li><?= htmlspecialchars($pk['name']) ?> — <span style="color:#2ecc71; font-weight:600;"><?= htmlspecialchars($pk['rate']) ?></span></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Khối 2: Danh sách 5 Nhân vật Đặc Trưng từ Wiki -->
    <div class="admin-box">
        <div class="admin-box__header">
            <h2 class="admin-box__title"><i class="fas fa-horse" style="color:var(--adm-accent-gold);"></i> Nhân Vật Trọng Điểm Từ Umamusu Wiki Roster</h2>
            <span class="badge" style="background:rgba(241,196,15,0.2); color:#f1c40f; border:1px solid #f1c40f; padding:4px 10px; border-radius:15px;">Tách biệt Profile & Stats 3-Tier</span>
        </div>

        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nhân vật</th>
                        <th>Ký túc xá & Bạn phòng</th>
                        <th>Diễn viên lồng tiếng (CV)</th>
                        <th>Chỉ số Tốc độ (Min - Max)</th>
                        <th>Tỷ lệ Chấn thương</th>
                        <th>Chiến thuật</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($featuredChars as $char): ?>
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <img src="<?= htmlspecialchars($char['profile']['avatar']) ?>" class="table-avatar" style="width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid var(--adm-accent);" onerror="this.src='<?= Helper::asset('media/img/placeholder.png') ?>'">
                                    <div>
                                        <strong style="color:#fff; font-size:1rem;"><?= htmlspecialchars($char['profile']['name']) ?></strong>
                                        <div style="font-size:0.8rem; color:#aaa;"><?= htmlspecialchars($char['profile']['japanese_name']) ?></div>
                                        <div style="font-size:0.75rem; color:var(--adm-accent-gold);"><?= htmlspecialchars($char['profile']['nickname']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:0.9rem; color:#ddd;">
                                    <?= htmlspecialchars($char['profile']['class_grade']) ?>
                                </div>
                                <div style="font-size:0.8rem; color:#888;">
                                    <?= htmlspecialchars($char['profile']['birthday']) ?> | <?= htmlspecialchars($char['profile']['height']) ?>
                                </div>
                            </td>
                            <td style="font-size:0.85rem; color:#3498db;">
                                <?= htmlspecialchars($char['profile']['voice_actor']) ?>
                            </td>
                            <td>
                                <strong style="color:#2ecc71;"><?= $char['stats']['min_speed'] ?></strong> ~ <strong style="color:#e74c3c;"><?= $char['stats']['max_speed'] ?></strong>
                                <div style="font-size:0.75rem; color:#888;">Speed: <?= $char['stats']['stat_speed'] ?> pts</div>
                            </td>
                            <td>
                                <?= Helper::injuryBadge($char['stats']['injury_rate']) ?>
                            </td>
                            <td>
                                <?= Helper::runningStyleBadge($char['stats']['running_style']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>
