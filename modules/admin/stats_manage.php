<?php
// ====================================================================
//  modules/admin/stats_manage.php — Quản lý Tốc Độ, Chấn Thương & Cân Bằng Game
// ====================================================================

require_once __DIR__ . '/../../config/app.php';

$statsService = new StatsService();
$search = trim($_GET['search'] ?? '');

$statsList = $statsService->getAll($search);
$summary   = $statsService->getSummary();

$pageTitle = 'Quản Lý Tốc Độ & Tỷ Lệ Chấn Thương';
$pageIcon  = 'fa-solid fa-bolt';

include __DIR__ . '/../../includes/admin_header.php';
?>

<!-- ── Thống kê tổng hợp đường đua ─────────────────────────────────── -->
<div class="admin-stat-grid">
    <div class="admin-stat-card">
        <div>
            <div class="admin-stat-card__lbl">Tốc độ Max cao nhất</div>
            <div class="admin-stat-card__val" style="color:#f59e0b;">
                <?= round($summary['highest_max_speed'] ?? 0, 2) ?>
            </div>
        </div>
        <div class="admin-stat-card__icon" style="background:rgba(245, 158, 11, 0.15); color:#f59e0b;">
            <i class="fas fa-gauge-max"></i>
        </div>
    </div>

    <div class="admin-stat-card">
        <div>
            <div class="admin-stat-card__lbl">Tỷ lệ Chấn thương TB</div>
            <div class="admin-stat-card__val" style="color:#06b6d4;">
                <?= round(($summary['avg_injury_rate'] ?? 0.05) * 100, 1) ?>%
            </div>
        </div>
        <div class="admin-stat-card__icon" style="background:rgba(6, 182, 212, 0.15); color:#06b6d4;">
            <i class="fas fa-heart-pulse"></i>
        </div>
    </div>

    <div class="admin-stat-card">
        <div>
            <div class="admin-stat-card__lbl">Chấn thương cao nhất</div>
            <div class="admin-stat-card__val" style="color:#ef4444;">
                <?= round(($summary['max_injury_rate'] ?? 0.1) * 100, 1) ?>%
            </div>
        </div>
        <div class="admin-stat-card__icon" style="background:rgba(239, 68, 68, 0.15); color:#ef4444;">
            <i class="fas fa-skull-crossbones"></i>
        </div>
    </div>

    <div class="admin-stat-card">
        <div>
            <div class="admin-stat-card__lbl">Điểm Speed RPG TB</div>
            <div class="admin-stat-card__val" style="color:#10b981;">
                <?= round($summary['avg_base_speed'] ?? 450) ?>
            </div>
        </div>
        <div class="admin-stat-card__icon" style="background:rgba(16, 185, 129, 0.15); color:#10b981;">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>
</div>

<!-- ── Bảng Dữ Liệu Chỉ Số Nhân Vật ─────────────────────────────────── -->
<div class="admin-box">
    <div class="admin-box__header">
        <h2 class="admin-box__title">
            <i class="fas fa-sliders" style="color:var(--adm-accent-gold);"></i> Bảng Cân Bằng Thông Số Thi Đấu
        </h2>
        <a href="<?= Helper::url('pages/racing.php') ?>" target="_blank" class="btn-adm btn-adm-secondary btn-adm-sm">
            <i class="fas fa-flag-checkered"></i> Mở Trường Đua Grand Derby
        </a>
    </div>

    <!-- Search bar -->
    <form method="GET" style="display:flex; gap:12px; margin-bottom:20px;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Tìm kiếm theo tên nhân vật..." class="form-input" style="max-width:320px;">
        <button type="submit" class="btn-adm btn-adm-secondary">
            <i class="fas fa-search"></i> Lọc
        </button>
        <?php if (!empty($search)): ?>
            <a href="<?= Helper::url('modules/admin/stats_manage.php') ?>" class="btn-adm btn-adm-secondary">
                <i class="fas fa-rotate-left"></i>
            </a>
        <?php endif; ?>
    </form>

    <div class="admin-table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:45px; text-align:center;">ID</th>
                    <th style="width:55px; text-align:center;">Avatar</th>
                    <th style="min-width:130px;">Nhân vật</th>
                    <th style="min-width:115px;">Tốc độ (Min-Max)</th>
                    <th style="min-width:95px;">Chu kỳ đổi</th>
                    <th style="min-width:135px;">Tỷ lệ chấn thương</th>
                    <th style="min-width:95px;">Tỷ lệ bứt phá</th>
                    <th style="min-width:135px;">Chiến thuật</th>
                    <th style="min-width:150px;">Thuộc tính (S/St/P)</th>
                    <th style="width:80px; text-align:center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($statsList)): ?>
                    <tr>
                        <td colspan="10" style="text-align:center; padding:30px; color:#888;">
                            Không tìm thấy dữ liệu chỉ số nào.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($statsList as $row): ?>
                        <tr>
                            <td style="text-align:center; font-weight:700; color:var(--adm-text-muted); font-size:0.82rem;">#<?= (int)$row['character_id'] ?></td>
                            <td style="text-align:center;">
                                <img src="<?= htmlspecialchars(Helper::characterAvatar($row)) ?>" class="table-avatar" 
                                     style="width:40px; height:40px; max-width:40px; max-height:40px; object-fit:cover; border-radius:8px; display:inline-block;"
                                     data-fallback="<?= htmlspecialchars(!empty($row['avatar']) ? Helper::asset($row['avatar']) : Helper::asset('media/img/placeholder.png')) ?>"
                                     onerror="if (typeof handleImageError === 'function') { handleImageError(this, this.dataset.fallback); } else { this.onerror=null; this.src='<?= Helper::asset('media/img/placeholder.png') ?>'; }">
                            </td>
                            <td style="white-space:nowrap;">
                                <strong style="display:block; white-space:nowrap; font-size:0.88rem;"><?= htmlspecialchars($row['name']) ?></strong>
                                <?php if (!empty($row['japanese_name'])): ?>
                                    <div style="font-size:0.75rem; color:#888; white-space:nowrap;"><?= htmlspecialchars($row['japanese_name']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td style="white-space:nowrap;">
                                <div style="display:inline-flex; align-items:center; gap:5px; white-space:nowrap; font-size:0.85rem;">
                                    <span style="color:#06b6d4; font-weight:bold;"><?= number_format($row['min_speed'], 2) ?></span>
                                    <span style="color:#666; font-size:0.75rem;">→</span>
                                    <span style="color:#f59e0b; font-weight:bold;"><?= number_format($row['max_speed'], 2) ?></span>
                                </div>
                            </td>
                            <td style="white-space:nowrap;">
                                <span style="font-size:0.82rem; color:#aaa; white-space:nowrap;"><?= (int)$row['speed_change_ms'] ?> ms</span>
                            </td>
                            <td style="white-space:nowrap;">
                                <?= Helper::injuryBadge((float)$row['injury_rate']) ?>
                            </td>
                            <td style="white-space:nowrap;">
                                <span style="color:#ff4b82; font-weight:bold; font-size:0.82rem; white-space:nowrap; display:inline-flex; align-items:center; gap:4px;"><i class="fas fa-bolt"></i> <?= number_format($row['event_rate'], 1) ?>%</span>
                            </td>
                            <td style="white-space:nowrap;">
                                <?= Helper::runningStyleBadge($row['running_style'] ?? 'Leader') ?>
                            </td>
                            <td style="font-size:0.82rem; white-space:nowrap;">
                                <span title="Speed" style="color:#3498db; font-weight:bold;">S:<?= $row['base_speed'] ?></span> | 
                                <span title="Stamina" style="color:#2ecc71; font-weight:bold;">St:<?= $row['stamina'] ?></span> | 
                                <span title="Power" style="color:#e67e22; font-weight:bold;">P:<?= $row['power'] ?></span>
                            </td>
                            <td style="text-align:center; white-space:nowrap;">
                                <a href="<?= Helper::url('modules/admin/stats_form.php?char_id=' . $row['character_id']) ?>" class="btn-adm btn-adm-warning btn-adm-sm" style="white-space:nowrap; padding:4px 10px; font-size:0.78rem;">
                                    <i class="fas fa-sliders"></i> Chỉnh
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>
