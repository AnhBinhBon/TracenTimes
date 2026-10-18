<?php
// ====================================================================
//  modules/admin/index.php — Bảng Điều Khiển Quản Trị Hệ Thống TracenTimes
//  Quản lý trực tiếp các phần thực tế: Tin tức, Nhân vật, Chỉ số, Đua Derby
// ====================================================================

require_once __DIR__ . '/../../config/app.php';

$pageTitle = 'Tổng Quan Hệ Thống';
$pageIcon  = 'fa-solid fa-chart-line';

$adminService = new AdminService();
$metrics = $adminService->getDashboardMetrics();

$stats = $metrics['stats_summary'];
$avgInjury = isset($stats['avg_injury_rate']) ? round($stats['avg_injury_rate'] * 100, 1) : 6.8;
$maxSpeed  = isset($stats['highest_max_speed']) ? round($stats['highest_max_speed'], 2) : 2.30;
$totalNews = (int)$metrics['total_news'];
$totalChars = (int)$metrics['total_characters'];

// Thống kê phân bổ chiến thuật thực tế từ DB
$db = Database::getConnection();
$styleDistribution = $db->query("
    SELECT running_style, COUNT(*) as count 
    FROM character_stats 
    GROUP BY running_style
")->fetchAll(PDO::FETCH_KEY_PAIR);

// Top 5 Uma tốc độ cao nhất
$topSpeedUmas = $db->query("
    SELECT c.id, c.name, c.avatar, cs.min_speed, cs.max_speed, cs.injury_rate, cs.running_style
    FROM characters c
    JOIN character_stats cs ON c.id = cs.character_id
    ORDER BY cs.max_speed DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../../includes/admin_header.php';
?>

<!-- ── 1. Thẻ Thống Kê Chỉ Số Trọng Tâm (Real Database Metrics) ─────── -->
<div class="admin-stat-grid">
    <div class="admin-stat-card">
        <div>
            <div class="admin-stat-card__lbl">Tin Tức / Bài Viết</div>
            <div class="admin-stat-card__val" style="color:var(--adm-pink);"><?= $totalNews ?></div>
            <div style="font-size:0.75rem; color:var(--adm-text-dim); margin-top:4px;">10 chuyên mục đã xuất bản</div>
        </div>
        <div class="admin-stat-card__icon" style="background:rgba(255, 102, 163, 0.15); color:var(--adm-pink);">
            <i class="fas fa-newspaper"></i>
        </div>
    </div>

    <div class="admin-stat-card">
        <div>
            <div class="admin-stat-card__lbl">Hồ Sơ Mã Nương</div>
            <div class="admin-stat-card__val" style="color:var(--adm-cyan);"><?= $totalChars ?></div>
            <div style="font-size:0.75rem; color:var(--adm-text-dim); margin-top:4px;">Đầy đủ tiểu sử & hình ảnh</div>
        </div>
        <div class="admin-stat-card__icon" style="background:rgba(56, 189, 248, 0.15); color:var(--adm-cyan);">
            <i class="fas fa-horse"></i>
        </div>
    </div>

    <div class="admin-stat-card">
        <div>
            <div class="admin-stat-card__lbl">Tốc Độ Max Kỷ Lục</div>
            <div class="admin-stat-card__val" style="color:var(--adm-gold);"><?= $maxSpeed ?> <span style="font-size:0.9rem; font-weight:normal; color:var(--adm-text-muted);">pts</span></div>
            <div style="font-size:0.75rem; color:var(--adm-text-dim); margin-top:4px;">Chiến mã: Narita Brian</div>
        </div>
        <div class="admin-stat-card__icon" style="background:rgba(245, 158, 11, 0.15); color:var(--adm-gold);">
            <i class="fas fa-bolt"></i>
        </div>
    </div>

    <div class="admin-stat-card">
        <div>
            <div class="admin-stat-card__lbl">Tỷ Lệ Chấn Thương TB</div>
            <div class="admin-stat-card__val" style="color:var(--adm-emerald);"><?= $avgInjury ?>%</div>
            <div style="font-size:0.75rem; color:var(--adm-text-dim); margin-top:4px;">Quy luật High Risk - High Reward</div>
        </div>
        <div class="admin-stat-card__icon" style="background:rgba(16, 185, 129, 0.15); color:var(--adm-emerald);">
            <i class="fas fa-heart-pulse"></i>
        </div>
    </div>

    <div class="admin-stat-card" style="cursor:pointer;" onclick="location.href='<?= Helper::url('modules/admin/users_manage.php') ?>'" title="Nhấp để Quản lý Tài khoản & Phân quyền">
        <div>
            <div class="admin-stat-card__lbl">Quản Trị Tài Khoản</div>
            <div class="admin-stat-card__val" style="color:#c084fc;"><?= (int)($metrics['total_users'] ?? 2) ?></div>
            <div style="font-size:0.75rem; color:var(--adm-text-dim); margin-top:4px;">Admin &amp; Huấn Luyện Viên ↗</div>
        </div>
        <div class="admin-stat-card__icon" style="background:rgba(168, 85, 247, 0.15); color:#c084fc;">
            <i class="fas fa-users-gear"></i>
        </div>
    </div>
</div>

<!-- ── 2. Hai Bảng Quản Lý Nghiệp Vụ Chính (Tin tức & Nhân vật) ──────── -->
<div class="ops-two-col" style="margin-bottom:24px;">

    <!-- Bảng Tin Tức Gần Đây -->
    <div class="admin-box" style="margin-bottom:0;">
        <div class="admin-box__header">
            <h3 class="admin-box__title"><i class="fas fa-newspaper" style="color:var(--adm-pink); margin-right:8px;"></i> Quản Lý Bài Viết Mới Nhất</h3>
            <div style="display:flex; gap:8px;">
                <a href="<?= Helper::url('modules/admin/news_form.php') ?>" class="btn-adm btn-adm-primary btn-adm-sm"><i class="fas fa-plus"></i> Đăng tin</a>
                <a href="<?= Helper::url('modules/admin/news_manage.php') ?>" class="btn-adm btn-adm-secondary btn-adm-sm">Xem tất cả (<?= $totalNews ?>) →</a>
            </div>
        </div>

        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Bài viết</th>
                        <th>Chuyên mục</th>
                        <th>Ngày đăng</th>
                        <th style="text-align:center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($metrics['recent_news'], 0, 5) as $n): ?>
                        <tr>
                            <td>
                                <strong style="color:var(--adm-text); font-size:0.88rem;"><?= htmlspecialchars(Helper::truncate($n['title'], 42)) ?></strong>
                            </td>
                            <td>
                                <span class="ops-tag"><?= htmlspecialchars($n['category']) ?></span>
                            </td>
                            <td style="color:var(--adm-text-muted); font-size:0.82rem; white-space:nowrap;">
                                <?= date('d/m/Y', strtotime($n['published_at'])) ?>
                            </td>
                            <td style="text-align:center; white-space:nowrap;">
                                <a href="<?= Helper::url('modules/admin/news_form.php?id=' . $n['id']) ?>" class="btn-adm btn-adm-secondary btn-adm-sm" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                <a href="<?= Helper::url('pages/news_detail.php?id=' . $n['id']) ?>" target="_blank" class="btn-adm btn-adm-secondary btn-adm-sm" title="Xem trên web"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bảng Nhân Vật & Chỉ Số Gần Đây -->
    <div class="admin-box" style="margin-bottom:0;">
        <div class="admin-box__header">
            <h3 class="admin-box__title"><i class="fas fa-horse" style="color:var(--adm-cyan); margin-right:8px;"></i> Quản Lý Hồ Sơ Mã Nương</h3>
            <div style="display:flex; gap:8px;">
                <a href="<?= Helper::url('modules/admin/char_form.php') ?>" class="btn-adm btn-adm-primary btn-adm-sm"><i class="fas fa-plus"></i> Thêm Uma</a>
                <a href="<?= Helper::url('modules/admin/char_manage.php') ?>" class="btn-adm btn-adm-secondary btn-adm-sm">Xem tất cả (<?= $totalChars ?>) →</a>
            </div>
        </div>

        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:60px;">Avatar</th>
                        <th>Nhân vật</th>
                        <th>Chiến thuật</th>
                        <th>Tốc độ</th>
                        <th>Rủi ro</th>
                        <th style="text-align:center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($metrics['recent_chars'], 0, 5) as $c): ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars(Helper::characterAvatar($c)) ?>" class="table-avatar"
                                     style="width:45px; height:45px; max-width:45px; max-height:45px; object-fit:cover; border-radius:8px; display:block;"
                                     data-fallback="<?= htmlspecialchars(!empty($c['avatar']) ? Helper::asset($c['avatar']) : Helper::asset('media/img/placeholder.png')) ?>"
                                     onerror="if (typeof handleImageError === 'function') { handleImageError(this, this.dataset.fallback); } else { this.onerror=null; this.src='<?= Helper::asset('media/img/placeholder.png') ?>'; }">
                            </td>
                            <td>
                                <strong style="color:var(--adm-text);"><?= htmlspecialchars($c['name']) ?></strong>
                            </td>
                            <td>
                                <span class="ops-tag ops-tag-info"><?= htmlspecialchars($c['running_style'] ?? 'Leader') ?></span>
                            </td>
                            <td style="color:var(--adm-cyan); font-weight:700;">
                                <?= number_format((float)($c['max_speed'] ?? 1.5), 2) ?>
                            </td>
                            <td>
                                <span class="ops-risk-pill" style="background:<?= ((float)($c['injury_rate'] ?? 0) > 0.09) ? 'rgba(239, 68, 68, 0.2); color:#f87171;' : 'rgba(16, 185, 129, 0.2); color:#34d399;' ?>">
                                    <?= round(((float)($c['injury_rate'] ?? 0)) * 100, 1) ?>%
                                </span>
                            </td>
                            <td style="text-align:center; white-space:nowrap;">
                                <a href="<?= Helper::url('modules/admin/char_form.php?id=' . $c['id']) ?>" class="btn-adm btn-adm-secondary btn-adm-sm" title="Sửa hồ sơ"><i class="fas fa-edit"></i></a>
                                <a href="<?= Helper::url('modules/admin/stats_form.php?id=' . $c['id']) ?>" class="btn-adm btn-adm-warning btn-adm-sm" title="Sửa chỉ số"><i class="fas fa-bolt"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ── 3. Phân Tích Kỹ Thuật Đua Thực Tế (Phân bố Chiến thuật & Top Tốc Độ) -->
<div class="ops-two-col">

    <!-- Top 5 Chiến Mã Tốc Độ Cao Nhất -->
    <div class="admin-box">
        <div class="admin-box__header">
            <h3 class="admin-box__title"><i class="fas fa-trophy" style="color:var(--adm-gold); margin-right:8px;"></i> Top 5 Mã Nương Bứt Tốc Cao Nhất (High Risk)</h3>
            <a href="<?= Helper::url('modules/admin/stats_manage.php') ?>" class="btn-adm btn-adm-secondary btn-adm-sm">Điều chỉnh chỉ số →</a>
        </div>

        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Avatar</th>
                        <th>Chiến mã</th>
                        <th>Chiến thuật</th>
                        <th>Tốc độ Min → Max</th>
                        <th>Tỷ lệ chấn thương</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1; foreach ($topSpeedUmas as $uma): ?>
                        <tr>
                            <td style="font-weight:700; color:var(--adm-gold);">#<?= $rank++ ?></td>
                            <td>
                                <img src="<?= htmlspecialchars(Helper::characterAvatar($uma)) ?>" class="table-avatar"
                                     style="width:40px; height:40px; max-width:40px; max-height:40px; object-fit:cover; border-radius:8px; display:block;"
                                     data-fallback="<?= htmlspecialchars(!empty($uma['avatar']) ? Helper::asset($uma['avatar']) : Helper::asset('media/img/placeholder.png')) ?>"
                                     onerror="if (typeof handleImageError === 'function') { handleImageError(this, this.dataset.fallback); } else { this.onerror=null; this.src='<?= Helper::asset('media/img/placeholder.png') ?>'; }">
                            </td>
                            <td><strong><?= htmlspecialchars($uma['name']) ?></strong></td>
                            <td><span class="ops-tag"><?= htmlspecialchars($uma['running_style']) ?></span></td>
                            <td>
                                <span style="color:var(--adm-cyan); font-weight:700;"><?= number_format($uma['min_speed'], 2) ?></span>
                                <span style="color:var(--adm-text-dim); margin:0 4px;">→</span>
                                <span style="color:var(--adm-gold); font-weight:800; font-size:1.05rem;"><?= number_format($uma['max_speed'], 2) ?></span>
                            </td>
                            <td>
                                <span class="ops-risk-pill" style="background:<?= ($uma['injury_rate'] > 0.09) ? 'rgba(239, 68, 68, 0.2); color:#f87171;' : 'rgba(16, 185, 129, 0.2); color:#34d399;' ?>">
                                    <?= round($uma['injury_rate'] * 100, 1) ?>% (<?= ($uma['injury_rate'] > 0.09) ? 'Rủi ro cao' : 'Ổn định' ?>)
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Phân Bổ Chiến Thuật & Trạng Thái Hệ Thống Thực Tế -->
    <div class="admin-box">
        <div class="admin-box__header">
            <h3 class="admin-box__title"><i class="fas fa-chart-pie" style="color:var(--adm-pink); margin-right:8px;"></i> Phân Bổ Phong Cách Chạy (15 Mã Nương)</h3>
            <a href="<?= Helper::url('pages/racing.php') ?>" target="_blank" class="btn-adm btn-adm-warning btn-adm-sm"><i class="fas fa-flag-checkered"></i> Mở Derby Cup ↗</a>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:20px;">
            <div style="background:var(--adm-surface-2); padding:14px; border-radius:8px; border-left:4px solid #10b981;">
                <div style="font-size:0.8rem; color:var(--adm-text-muted);">Runner (Tiên phong / Dẫn đầu)</div>
                <div style="font-size:1.5rem; font-weight:800; color:#10b981;"><?= (int)($styleDistribution['Runner'] ?? 0) ?> Mã Nương</div>
                <div style="font-size:0.75rem; color:var(--adm-text-dim);">Silence Suzuka, Daiwa Scarlet...</div>
            </div>

            <div style="background:var(--adm-surface-2); padding:14px; border-radius:8px; border-left:4px solid #38bdf8;">
                <div style="font-size:0.8rem; color:var(--adm-text-muted);">Leader (Đeo bám dẫn đầu)</div>
                <div style="font-size:1.5rem; font-weight:800; color:#38bdf8;"><?= (int)($styleDistribution['Leader'] ?? 0) ?> Mã Nương</div>
                <div style="font-size:0.75rem; color:var(--adm-text-dim);">Tokai Teio, Narita Brian...</div>
            </div>

            <div style="background:var(--adm-surface-2); padding:14px; border-radius:8px; border-left:4px solid #f59e0b;">
                <div style="font-size:0.8rem; color:var(--adm-text-muted);">Betweener (Chạy giữa đoàn)</div>
                <div style="font-size:1.5rem; font-weight:800; color:#f59e0b;"><?= (int)($styleDistribution['Betweener'] ?? 0) ?> Mã Nương</div>
                <div style="font-size:0.75rem; color:var(--adm-text-dim);">Agnes Tachyon, Vodka, Rice...</div>
            </div>

            <div style="background:var(--adm-surface-2); padding:14px; border-radius:8px; border-left:4px solid #a855f7;">
                <div style="font-size:0.8rem; color:var(--adm-text-muted);">Chaser (Bứt tốc đoạn cuối)</div>
                <div style="font-size:1.5rem; font-weight:800; color:#a855f7;"><?= (int)($styleDistribution['Chaser'] ?? 0) ?> Mã Nương</div>
                <div style="font-size:0.75rem; color:var(--adm-text-dim);">Gold Ship, Sweep Tosho...</div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>
