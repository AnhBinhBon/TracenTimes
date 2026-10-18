<?php
// ====================================================================
//  modules/admin/stats_form.php — Tinh Chỉnh Chỉ Số, Tốc Độ & Chấn Thương
// ====================================================================

require_once __DIR__ . '/../../config/app.php';

$charId = isset($_GET['char_id']) ? (int)$_GET['char_id'] : 0;
if ($charId <= 0) {
    Helper::setFlash('error', 'Vui lòng chọn nhân vật hợp lệ để chỉnh chỉ số.');
    header('Location: ' . Helper::url('modules/admin/stats_manage.php'));
    exit();
}

$charService  = new CharacterService();
$statsService = new StatsService();

$character = $charService->getDetail($charId);
if (!$character) {
    Helper::setFlash('error', 'Không tìm thấy thông tin nhân vật này.');
    header('Location: ' . Helper::url('modules/admin/stats_manage.php'));
    exit();
}

$stats = $statsService->getByCharacterId($charId);

// Xử lý lưu POST
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $statsService->updateStats($charId, $_POST);
    if ($result['success']) {
        Helper::setFlash('success', 'Đã cập nhật chỉ số thi đấu cho ' . $character['name'] . ' thành công!');
        header('Location: ' . Helper::url('modules/admin/stats_manage.php'));
        exit();
    } else {
        $error = $result['message'];
    }
}

$pageTitle = 'Cân Bằng Chỉ Số: ' . htmlspecialchars($character['name']);
$pageIcon  = 'fa-solid fa-sliders';

include __DIR__ . '/../../includes/admin_header.php';
?>

<div class="admin-box" style="max-width:920px; margin:0 auto;">
    <div class="admin-box__header">
        <div style="display:flex; align-items:center; gap:16px;">
            <img src="<?= Helper::characterAvatar($character) ?>" class="table-avatar" style="width:58px; height:58px; border-radius:12px;" onerror="this.src='<?= Helper::asset('media/img/placeholder.png') ?>'">
            <div>
                <h2 class="admin-box__title" style="margin:0;"><?= htmlspecialchars($character['name']) ?></h2>
                <div style="font-size:0.85rem; color:#888;">
                    <?= htmlspecialchars($character['japanese_name'] ?? '') ?> 
                    <?= !empty($character['nickname']) ? '— <span style="color:var(--adm-accent-gold);">' . htmlspecialchars($character['nickname']) . '</span>' : '' ?>
                </div>
            </div>
        </div>
        <a href="<?= Helper::url('modules/admin/stats_manage.php') ?>" class="btn-adm btn-adm-secondary btn-adm-sm">
            <i class="fas fa-arrow-left"></i> Danh sách chỉ số
        </a>
    </div>

    <?php if ($error): ?>
        <div class="admin-alert admin-alert-danger">
            <i class="fas fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <!-- ── KHỐI 1: TỐC ĐỘ & BIẾN THIÊN ───────────────────────────── -->
        <div style="background:rgba(6, 182, 212, 0.05); border:1px solid rgba(6, 182, 212, 0.2); padding:20px; border-radius:12px; margin-bottom:24px;">
            <h3 style="margin-top:0; color:#06b6d4; font-size:1.1rem; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-gauge-high"></i> 1. Thông Số Tốc Độ Đường Đua (Racing Speed)
            </h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tốc độ tối thiểu (Min Speed) <span style="color:red;">*</span></label>
                    <input type="number" step="0.05" min="0.1" max="2.0" name="min_speed" class="form-input" required
                           value="<?= htmlspecialchars($_POST['min_speed'] ?? $stats['min_speed'] ?? 0.45) ?>">
                    <span style="font-size:0.8rem; color:#888;">Vận tốc khi mới xuất phát hoặc khi xuống sức (Mặc định: 0.2 - 0.6)</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Tốc độ tối đa (Max Speed) <span style="color:red;">*</span></label>
                    <input type="number" step="0.05" min="0.5" max="3.0" name="max_speed" class="form-input" required
                           value="<?= htmlspecialchars($_POST['max_speed'] ?? $stats['max_speed'] ?? 1.50) ?>">
                    <span style="font-size:0.8rem; color:#888;">Vận tốc nước rút tối đa có thể đạt được (Mặc định: 1.0 - 2.2)</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Chu kỳ biến động tốc độ (ms)</label>
                    <input type="number" step="100" min="500" max="10000" name="speed_change_ms" class="form-input" required
                           value="<?= htmlspecialchars($_POST['speed_change_ms'] ?? $stats['speed_change_ms'] ?? 3000) ?>">
                    <span style="font-size:0.8rem; color:#888;">Thời gian giữa các đợt đổi nhịp chạy (Mili-giây, ví dụ 3000ms = 3 giây)</span>
                </div>
            </div>
        </div>

        <!-- ── KHỐI 2: TỶ LỆ CHẤN THƯƠNG & BỨT PHÁ ─────────────────── -->
        <div style="background:rgba(239, 68, 68, 0.05); border:1px solid rgba(239, 68, 68, 0.2); padding:20px; border-radius:12px; margin-bottom:24px;">
            <h3 style="margin-top:0; color:#ef4444; font-size:1.1rem; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-heart-pulse"></i> 2. Rủi Ro Y Tế & Kích Hoạt Sự Kiện (Injury & Burst)
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tỷ lệ Chấn thương (Injury Rate: 0.00 - 1.00 hoặc %)</label>
                    <?php 
                    $currInj = (float)($_POST['injury_rate'] ?? $stats['injury_rate'] ?? 0.05); 
                    ?>
                    <input type="number" step="0.005" min="0" max="1.0" name="injury_rate" class="form-input" required
                           value="<?= $currInj ?>">
                    <div style="margin-top:6px;">
                        Đánh giá mức độ an toàn: <?= Helper::injuryBadge($currInj) ?>
                    </div>
                    <span style="font-size:0.8rem; color:#94a3b8;">Quy luật Đánh Đổi (High Risk - High Reward): Tỷ lệ chấn thương càng cao (0.08 - 0.15) thì càng nên tăng Tốc độ Tối Đa (Max Speed 1.95 - 2.30) để tạo sự bứt phá xứng đáng.</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Tỷ lệ kích hoạt bứt phá / Tuyệt kỹ (%)</label>
                    <input type="number" step="0.5" min="0" max="100" name="event_rate" class="form-input" required
                           value="<?= htmlspecialchars($_POST['event_rate'] ?? $stats['event_rate'] ?? 8.0) ?>">
                    <span style="font-size:0.8rem; color:#888;">Khả năng bật kỹ năng tăng tốc đột biến trong chặng đua (Đơn vị: %)</span>
                </div>
            </div>
        </div>

        <!-- ── KHỐI 3: CHIẾN THUẬT & THUỘC TÍNH RPG UMAMUSUME ──────── -->
        <div style="background:rgba(245, 158, 11, 0.05); border:1px solid rgba(245, 158, 11, 0.2); padding:20px; border-radius:12px; margin-bottom:24px;">
            <h3 style="margin-top:0; color:var(--adm-accent-gold); font-size:1.1rem; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-chess-knight"></i> 3. Chiến Thuật & Thuộc Tính Ngũ Giác (Core RPG Attributes)
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Phong cách chạy (Running Style)</label>
                    <?php $styleVal = $_POST['running_style'] ?? $stats['running_style'] ?? 'Leader'; ?>
                    <select name="running_style" class="form-select">
                        <option value="Runner" <?= ($styleVal === 'Runner') ? 'selected' : '' ?>>Runner (Tiên phong - Luôn dẫn đầu)</option>
                        <option value="Leader" <?= ($styleVal === 'Leader') ? 'selected' : '' ?>>Leader (Dẫn đầu - Giữ vị trí trong top đầu)</option>
                        <option value="Betweener" <?= ($styleVal === 'Betweener') ? 'selected' : '' ?>>Betweener (Giữ cự ly - Bứt tốc giữa tốp)</option>
                        <option value="Chaser" <?= ($styleVal === 'Chaser') ? 'selected' : '' ?>>Chaser (Bứt tốc cuối - Tăng tốc ngoạn mục đoạn cua)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Độ thích nghi đường cỏ (Turf)</label>
                    <input type="text" name="turf_aptitude" class="form-input" style="text-transform:uppercase;"
                           value="<?= htmlspecialchars($_POST['turf_aptitude'] ?? $stats['turf_aptitude'] ?? 'A') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Độ thích nghi đường cát (Dirt)</label>
                    <input type="text" name="dirt_aptitude" class="form-input" style="text-transform:uppercase;"
                           value="<?= htmlspecialchars($_POST['dirt_aptitude'] ?? $stats['dirt_aptitude'] ?? 'G') ?>">
                </div>
            </div>

            <!-- 5 chỉ số RPG -->
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:12px; margin-top:15px;">
                <div class="form-group">
                    <label class="form-label" style="color:#3498db;"><i class="fas fa-bolt"></i> Speed</label>
                    <input type="number" min="1" max="2000" name="base_speed" class="form-input"
                           value="<?= (int)($_POST['base_speed'] ?? $stats['base_speed'] ?? 450) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" style="color:#2ecc71;"><i class="fas fa-heart"></i> Stamina</label>
                    <input type="number" min="1" max="2000" name="stamina" class="form-input"
                           value="<?= (int)($_POST['stamina'] ?? $stats['stamina'] ?? 450) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" style="color:#e67e22;"><i class="fas fa-fist-raised"></i> Power</label>
                    <input type="number" min="1" max="2000" name="power" class="form-input"
                           value="<?= (int)($_POST['power'] ?? $stats['power'] ?? 450) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" style="color:#e74c3c;"><i class="fas fa-fire"></i> Guts</label>
                    <input type="number" min="1" max="2000" name="guts" class="form-input"
                           value="<?= (int)($_POST['guts'] ?? $stats['guts'] ?? 450) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" style="color:#9b59b6;"><i class="fas fa-brain"></i> Wit</label>
                    <input type="number" min="1" max="2000" name="wit" class="form-input"
                           value="<?= (int)($_POST['wit'] ?? $stats['wit'] ?? 450) ?>">
                </div>
            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:12px;">
            <a href="<?= Helper::url('modules/admin/stats_manage.php') ?>" class="btn-adm btn-adm-secondary">Hủy</a>
            <button type="submit" class="btn-adm btn-adm-primary">
                <i class="fas fa-save"></i> Lưu Thông Số Thi Đấu
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>
