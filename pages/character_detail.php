<?php
// ====================================================================
//  pages/character_detail.php — Trang Chi Tiết Nhân Vật Chuẩn Wiki Umamusume
//  Phân bổ toàn bộ nội dung hiện có theo phong cách MediaWiki / umamusu.wiki
// ====================================================================

require_once __DIR__ . '/../config/app.php';

$charService = new CharacterService();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$char = $charService->getDetail($id);

if (!$char) {
    header('Location: ' . Helper::url('pages/404.php'));
    exit();
}

$pageTitle = htmlspecialchars($char['name']) . ' — Umamusume Wiki Profile';
include __DIR__ . '/../header.php';

$injRate = (float)($char['injury_rate'] ?? 0.05);
$injPercent = round($injRate * 100, 1);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* ── WIKI THEME STYLING ─────────────────────────────────────────────── */
.wiki-page-wrapper {
    background: #0d1117;
    color: #e6edf3;
    min-height: 90vh;
    padding-top: 30px;
    padding-bottom: 70px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
}

.wiki-container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Breadcrumb */
.wiki-breadcrumb {
    font-size: 0.88rem;
    color: #8b949e;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.wiki-breadcrumb a {
    color: #58a6ff;
    text-decoration: none;
}
.wiki-breadcrumb a:hover {
    text-decoration: underline;
}

/* Page Header */
.wiki-title-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    padding-bottom: 14px;
    margin-bottom: 20px;
}
.wiki-page-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0 0 10px;
    color: #f0f6fc;
    letter-spacing: -0.5px;
    font-family: 'Cinzel', serif, sans-serif;
}

/* Top Wiki Navigation Subtabs */
.wiki-subtabs {
    display: flex;
    gap: 8px;
    margin-top: 12px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.08);
}
.wiki-subtab-btn {
    padding: 8px 18px;
    background: transparent;
    color: #8b949e;
    border: none;
    border-bottom: 3px solid transparent;
    font-weight: 700;
    font-size: 0.92rem;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.wiki-subtab-btn:hover {
    color: #f0f6fc;
}
.wiki-subtab-btn.active {
    color: #a855f7;
    border-bottom-color: #a855f7;
    background: rgba(168, 85, 247, 0.08);
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
}

/* Intro banner */
.wiki-intro-text {
    font-size: 1.05rem;
    line-height: 1.7;
    color: #c9d1d9;
    margin: 20px 0 25px;
    padding: 12px 18px;
    background: rgba(255, 255, 255, 0.03);
    border-left: 4px solid #a855f7;
    border-radius: 0 8px 8px 0;
}

/* Main 2-Column Wiki Grid */
.wiki-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 35px;
    align-items: start;
}
@media (max-width: 992px) {
    .wiki-grid {
        grid-template-columns: 1fr;
    }
    .wiki-infobox-sidebar {
        order: -1;
        margin-bottom: 30px;
    }
}

/* Table of Contents (Mục Lục) */
.wiki-toc {
    background: #161b22;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 10px;
    padding: 16px 20px;
    margin-bottom: 35px;
    max-width: 520px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.4);
}
.wiki-toc-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 700;
    color: #f0f6fc;
    font-size: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding-bottom: 8px;
    margin-bottom: 12px;
}
.wiki-toc-toggle {
    color: #58a6ff;
    font-size: 0.85rem;
    cursor: pointer;
    text-decoration: none;
    font-weight: 600;
}
.wiki-toc-toggle:hover {
    text-decoration: underline;
}
.wiki-toc-list {
    list-style: none;
    padding-left: 0;
    margin: 0;
    font-size: 0.92rem;
}
.wiki-toc-list li {
    margin-bottom: 8px;
}
.wiki-toc-list a {
    color: #58a6ff;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.wiki-toc-list a:hover {
    text-decoration: underline;
    color: #a855f7;
}
.wiki-toc-num {
    color: #8b949e;
    font-weight: bold;
    min-width: 18px;
}

/* Wiki Ribbon Section Headers (Khung tiêu đề băng rôn phong cách Wiki) */
.wiki-ribbon-heading {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #ec4899, #db2777);
    color: #fff;
    font-size: 1.18rem;
    font-weight: 800;
    padding: 7px 22px 7px 16px;
    border-radius: 6px 18px 18px 0;
    position: relative;
    box-shadow: 0 4px 14px rgba(236, 72, 153, 0.35);
    margin-top: 35px;
    margin-bottom: 18px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.wiki-ribbon-heading::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 0;
    border-left: 6px solid transparent;
    border-top: 6px solid #9d174d;
}
.wiki-ribbon-heading.cyan {
    background: linear-gradient(135deg, #06b6d4, #0284c7);
    box-shadow: 0 4px 14px rgba(6, 182, 212, 0.35);
}
.wiki-ribbon-heading.cyan::after {
    border-top-color: #0369a1;
}
.wiki-ribbon-heading.purple {
    background: linear-gradient(135deg, #a855f7, #7c3aed);
    box-shadow: 0 4px 14px rgba(168, 85, 247, 0.35);
}
.wiki-ribbon-heading.purple::after {
    border-top-color: #581c87;
}
.wiki-ribbon-heading.amber {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
}
.wiki-ribbon-heading.amber::after {
    border-top-color: #92400e;
}

/* Wiki Section Content Box */
.wiki-section-box {
    background: #161b22;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 25px;
    line-height: 1.8;
    color: #e2e8f0;
    font-size: 0.98rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

/* Wiki Infobox (Sidebar Card - Sticky) */
.wiki-infobox-sidebar {
    background: #161b22;
    border: 1px solid rgba(168, 85, 247, 0.3);
    border-radius: 16px;
    overflow: hidden;
    position: sticky;
    top: 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
}
.wiki-infobox-header {
    background: linear-gradient(135deg, #7c3aed 0%, #a855f7 50%, #ec4899 100%);
    color: #fff;
    text-align: center;
    padding: 18px 15px;
    position: relative;
}
.wiki-infobox-name {
    font-size: 1.55rem;
    font-weight: 800;
    margin: 0 0 4px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    font-family: 'Cinzel', serif, sans-serif;
}
.wiki-infobox-nickname {
    font-size: 0.88rem;
    font-weight: 700;
    color: #fef08a;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Infobox Subtabs */
.wiki-infobox-tabs {
    display: flex;
    background: #0d1117;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}
.wiki-infobox-tab {
    flex: 1;
    padding: 9px;
    font-size: 0.82rem;
    font-weight: 700;
    text-align: center;
    background: transparent;
    color: #8b949e;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}
.wiki-infobox-tab.active {
    color: #a855f7;
    background: #161b22;
    border-bottom: 2px solid #a855f7;
}

/* Infobox Image Container */
.wiki-infobox-image-wrap {
    height: 310px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: radial-gradient(circle, #252d42 0%, #161b22 100%);
    position: relative;
    padding: 15px;
}
.wiki-infobox-img {
    max-height: 94%;
    max-width: 94%;
    object-fit: contain;
    filter: drop-shadow(0 10px 20px rgba(0,0,0,0.6));
    transition: transform 0.3s ease;
}
.wiki-infobox-img:hover {
    transform: scale(1.04);
}

/* Infobox Table Rows */
.wiki-infobox-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.88rem;
}
.wiki-infobox-table th {
    background: rgba(255, 255, 255, 0.03);
    color: #8b949e;
    padding: 10px 14px;
    text-align: left;
    width: 42%;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    font-weight: 600;
}
.wiki-infobox-table td {
    padding: 10px 14px;
    color: #f0f6fc;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    font-weight: 600;
}
.wiki-infobox-table tr:hover td, .wiki-infobox-table tr:hover th {
    background: rgba(255, 255, 255, 0.05);
}

/* Specs Badges Grid inside Appearance section */
.wiki-specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-top: 15px;
}
.wiki-spec-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    padding: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.wiki-spec-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: rgba(168, 85, 247, 0.15);
    color: #a855f7;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.wiki-spec-label {
    font-size: 0.78rem;
    color: #8b949e;
    text-transform: uppercase;
    font-weight: 700;
}
.wiki-spec-val {
    font-size: 1rem;
    color: #fff;
    font-weight: 700;
    margin-top: 2px;
}

/* RPG Attributes Hexagon Cards */
.wiki-rpg-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 15px;
    margin: 20px 0;
}
.wiki-rpg-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 14px;
    text-align: center;
}
.wiki-rpg-val {
    font-size: 1.8rem;
    font-weight: 900;
    color: #fff;
    margin: 4px 0;
}

/* Quick Navigation Anchor Smooth Scroll */
html {
    scroll-behavior: smooth;
}
</style>

<div class="wiki-page-wrapper">
    <div class="wiki-container">

        <!-- ── BREADCRUMB ─────────────────────────────────────────────── -->
        <div class="wiki-breadcrumb">
            <a href="<?= Helper::url('index.php') ?>"><i class="fas fa-home"></i> Trang Chủ</a>
            <span>/</span>
            <a href="<?= Helper::url('pages/characters_list.php') ?>">Hồ Sơ Nhân Vật</a>
            <span>/</span>
            <span style="color: #f0f6fc; font-weight: 700;"><?= htmlspecialchars($char['name']) ?></span>
        </div>

        <!-- ── WIKI PAGE TITLE & TOP SUBTABS ──────────────────────────── -->
        <div class="wiki-title-header">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 class="wiki-page-title"><?= htmlspecialchars($char['name']) ?></h1>
                    <?php if (!empty($char['japanese_name'])): ?>
                        <div style="color: #8b949e; font-size: 1.15rem; font-weight: 600;">
                            <?= htmlspecialchars($char['japanese_name']) ?>
                            <?php if (!empty($char['nickname'])): ?>
                                <span style="color: #f59e0b; margin-left: 10px; font-size: 0.95rem;">★ <?= htmlspecialchars($char['nickname']) ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (Auth::isAdmin()): ?>
                    <div style="display: flex; gap: 10px;">
                        <a href="<?= Helper::url('modules/admin/char_form.php?id=' . $char['id']) ?>" class="btn-adm btn-adm-warning btn-adm-sm">
                            <i class="fas fa-pen"></i> Sửa Hồ Sơ (Admin)
                        </a>
                        <a href="<?= Helper::url('modules/admin/stats_form.php?char_id=' . $char['id']) ?>" class="btn-adm btn-adm-secondary btn-adm-sm" style="color:#06b6d4; border-color:#06b6d4;">
                            <i class="fas fa-bolt"></i> Chỉnh Chỉ Số
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Wiki Subtabs (Main, Stats, Lore, Gallery) -->
            <div class="wiki-subtabs">
                <a href="#biography" class="wiki-subtab-btn active"><i class="fas fa-file-lines"></i> Main</a>
                <a href="#appearance" class="wiki-subtab-btn"><i class="fas fa-user"></i> Appearance</a>
                <a href="#personality" class="wiki-subtab-btn"><i class="fas fa-heart"></i> Personality</a>
                <a href="#career" class="wiki-subtab-btn"><i class="fas fa-book-open"></i> Story &amp; IRL</a>
                <a href="#racing-stats" class="wiki-subtab-btn"><i class="fas fa-bolt"></i> Game Stats</a>
            </div>
        </div>

        <!-- Introductory Sentence -->
        <div class="wiki-intro-text">
            <strong><?= htmlspecialchars($char['name']) ?></strong> 
            <?php if (!empty($char['japanese_name'])): ?>
                (tiếng Nhật: <em><?= htmlspecialchars($char['japanese_name']) ?></em>)
            <?php endif; ?>
            là một nhân vật Uma Musume nổi bật trong vũ trụ <em>Umamusume: Pretty Derby</em> thuộc Học viện Tracen danh giá.
        </div>

        <!-- ── MAIN 2-COLUMN WIKI GRID ────────────────────────────────── -->
        <div class="wiki-grid">

            <!-- ════ CỘT TRÁI: NỘI DUNG BÀI VIẾT CHUẨN WIKI ════════════════ -->
            <div class="wiki-main-article">

                <!-- 1. TABLE OF CONTENTS (MỤC LỤC [ẨN/HIỆN]) -->
                <div class="wiki-toc" id="wiki-toc-box">
                    <div class="wiki-toc-header">
                        <span><i class="fas fa-list-ol"></i> Mục Lục (Contents)</span>
                        <a href="javascript:void(0)" class="wiki-toc-toggle" onclick="toggleToc()">[ẩn]</a>
                    </div>
                    <ol class="wiki-toc-list" id="wiki-toc-content">
                        <li><a href="#biography"><span class="wiki-toc-num">1</span> Tiểu sử &amp; Giới thiệu (Biography)</a></li>
                        <li><a href="#appearance"><span class="wiki-toc-num">2</span> Ngoại hình &amp; Chỉ số sinh học (Appearance)</a></li>
                        <li><a href="#personality"><span class="wiki-toc-num">3</span> Tính cách &amp; Các mối quan hệ (Personality)</a></li>
                        <li><a href="#career"><span class="wiki-toc-num">4</span> Cốt truyện &amp; Sự nghiệp thi đấu (Story &amp; Career)</a></li>
                        <li><a href="#racing-stats"><span class="wiki-toc-num">5</span> Thuộc tính ngũ giác RPG &amp; Biểu đồ Radar (Stats)</a></li>
                        <li><a href="#medical-stats"><span class="wiki-toc-num">6</span> Đánh giá rủi ro chấn thương &amp; Tốc độ (Medical Analysis)</a></li>
                    </ol>
                </div>

                <!-- SECTION 1: BIOGRAPHY (TIỂU SỬ) -->
                <div id="biography">
                    <div class="wiki-ribbon-heading">
                        <i class="fas fa-id-card"></i> 1. Tiểu Sử &amp; Giới Thiệu (Biography)
                    </div>
                    <div class="wiki-section-box">
                        <p style="font-size: 1.05rem; line-height: 1.8; margin-top: 0;">
                            <?= nl2br(htmlspecialchars($char['profile'] ?? 'Một trong những cô gái ngựa tài năng hàng đầu tại Học viện Tracen.')) ?>
                        </p>
                        <?php if (!empty($char['class_grade'])): ?>
                            <div style="background: rgba(255,255,255,0.03); padding: 12px 16px; border-radius: 8px; border-left: 3px solid #ec4899; font-size: 0.95rem; margin-top: 15px;">
                                <strong style="color: #ec4899;">Khối học viện:</strong> <?= htmlspecialchars($char['class_grade']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- SECTION 2: APPEARANCE & PHYSICAL SPECS (NGOẠI HÌNH) -->
                <div id="appearance">
                    <div class="wiki-ribbon-heading cyan">
                        <i class="fas fa-ruler-combined"></i> 2. Ngoại Hình &amp; Chỉ Số Sinh Học (Appearance)
                    </div>
                    <div class="wiki-section-box">
                        <p style="margin-top: 0; color: #cbd5e1;">
                            Thông số hình thể và đặc điểm sinh học chính thức từ tài liệu trích lục Học viện Tracen:
                        </p>
                        
                        <div class="wiki-specs-grid">
                            <!-- Diễn viên lồng tiếng -->
                            <div class="wiki-spec-card">
                                <div class="wiki-spec-icon"><i class="fas fa-microphone"></i></div>
                                <div>
                                    <div class="wiki-spec-label">Diễn viên lồng tiếng (CV)</div>
                                    <div class="wiki-spec-val" style="color: #38bdf8;"><?= htmlspecialchars($char['voice_actor'] ?? 'Chưa rõ') ?></div>
                                </div>
                            </div>
                            <!-- Ngày sinh -->
                            <div class="wiki-spec-card">
                                <div class="wiki-spec-icon"><i class="fas fa-cake-candles"></i></div>
                                <div>
                                    <div class="wiki-spec-label">Ngày sinh</div>
                                    <div class="wiki-spec-val"><?= htmlspecialchars($char['birthday'] ?? 'Chưa rõ') ?></div>
                                </div>
                            </div>
                            <!-- Chiều cao -->
                            <div class="wiki-spec-card">
                                <div class="wiki-spec-icon"><i class="fas fa-arrows-up-down"></i></div>
                                <div>
                                    <div class="wiki-spec-label">Chiều cao</div>
                                    <div class="wiki-spec-val"><?= htmlspecialchars($char['height'] ?? 'Chưa rõ') ?></div>
                                </div>
                            </div>
                            <!-- Cân nặng -->
                            <div class="wiki-spec-card">
                                <div class="wiki-spec-icon"><i class="fas fa-weight-scale"></i></div>
                                <div>
                                    <div class="wiki-spec-label">Cân nặng</div>
                                    <div class="wiki-spec-val"><?= htmlspecialchars($char['weight'] ?? 'Chưa rõ') ?></div>
                                </div>
                            </div>
                            <!-- Số đo 3 vòng -->
                            <div class="wiki-spec-card" style="grid-column: 1 / -1;">
                                <div class="wiki-spec-icon"><i class="fas fa-tape"></i></div>
                                <div>
                                    <div class="wiki-spec-label">Số đo 3 vòng &amp; Cỡ giày</div>
                                    <div class="wiki-spec-val" style="color: #f472b6;"><?= htmlspecialchars($char['three_sizes'] ?? 'Chưa công bố') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: PERSONALITY (TÍNH CÁCH) -->
                <div id="personality">
                    <div class="wiki-ribbon-heading purple">
                        <i class="fas fa-heart"></i> 3. Tính Cách &amp; Mối Quan Hệ (Personality)
                    </div>
                    <div class="wiki-section-box">
                        <?php if (!empty($char['personality'])): ?>
                            <p style="margin-top: 0; line-height: 1.8;">
                                <?= nl2br(htmlspecialchars($char['personality'])) ?>
                            </p>
                        <?php else: ?>
                            <p style="color: #8b949e; font-style: italic;">Tính cách đang tiếp tục được cập nhật từ hồ sơ trường đua.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- SECTION 4: STORY & CAREER (CỐT TRUYỆN) -->
                <div id="career">
                    <div class="wiki-ribbon-heading amber">
                        <i class="fas fa-book-open"></i> 4. Cốt Truyện &amp; Sự Nghiệp Thi Đấu (Story &amp; Career)
                    </div>
                    <div class="wiki-section-box">
                        <?php if (!empty($char['detail'])): ?>
                            <p style="margin-top: 0; line-height: 1.8;">
                                <?= nl2br(htmlspecialchars($char['detail'])) ?>
                            </p>
                        <?php else: ?>
                            <p style="color: #8b949e; font-style: italic;">Hồ sơ sự nghiệp đang được đồng bộ thêm từ thư viện Umamusume Wiki.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- SECTION 5: RACING STATS & RADAR (CHỈ SỐ THI ĐẤU RPG) -->
                <div id="racing-stats">
                    <div class="wiki-ribbon-heading cyan">
                        <i class="fas fa-chart-pie"></i> 5. Chỉ Số Thi Đấu RPG &amp; Phân Tích (Game Stats)
                    </div>
                    <div class="wiki-section-box">
                        <p style="margin-top: 0; color: #cbd5e1;">
                            Hệ thống 5 thuộc tính thi đấu chính thức (Scale 1 - 1200 điểm) và khả năng thích nghi địa hình:
                        </p>

                        <div class="wiki-rpg-grid">
                            <!-- Speed -->
                            <div class="wiki-rpg-card" style="border-top: 3px solid #38bdf8;">
                                <div style="color: #38bdf8; font-size: 0.78rem; font-weight: 800;">SPEED (TỐC ĐỘ)</div>
                                <div class="wiki-rpg-val"><?= (int)($char['base_speed'] ?? 450) ?></div>
                                <div style="height: 5px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                                    <div style="width: <?= min(100, (($char['base_speed'] ?? 450) / 1200) * 100) ?>%; height: 100%; background: #38bdf8;"></div>
                                </div>
                            </div>
                            <!-- Stamina -->
                            <div class="wiki-rpg-card" style="border-top: 3px solid #4ade80;">
                                <div style="color: #4ade80; font-size: 0.78rem; font-weight: 800;">STAMINA (THỂ LỰC)</div>
                                <div class="wiki-rpg-val"><?= (int)($char['stamina'] ?? 450) ?></div>
                                <div style="height: 5px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                                    <div style="width: <?= min(100, (($char['stamina'] ?? 450) / 1200) * 100) ?>%; height: 100%; background: #4ade80;"></div>
                                </div>
                            </div>
                            <!-- Power -->
                            <div class="wiki-rpg-card" style="border-top: 3px solid #fb923c;">
                                <div style="color: #fb923c; font-size: 0.78rem; font-weight: 800;">POWER (SỨC MẠNH)</div>
                                <div class="wiki-rpg-val"><?= (int)($char['power'] ?? 450) ?></div>
                                <div style="height: 5px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                                    <div style="width: <?= min(100, (($char['power'] ?? 450) / 1200) * 100) ?>%; height: 100%; background: #fb923c;"></div>
                                </div>
                            </div>
                            <!-- Guts -->
                            <div class="wiki-rpg-card" style="border-top: 3px solid #f87171;">
                                <div style="color: #f87171; font-size: 0.78rem; font-weight: 800;">GUTS (Ý CHÍ)</div>
                                <div class="wiki-rpg-val"><?= (int)($char['guts'] ?? 450) ?></div>
                                <div style="height: 5px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                                    <div style="width: <?= min(100, (($char['guts'] ?? 450) / 1200) * 100) ?>%; height: 100%; background: #f87171;"></div>
                                </div>
                            </div>
                            <!-- Wit -->
                            <div class="wiki-rpg-card" style="border-top: 3px solid #c084fc;">
                                <div style="color: #c084fc; font-size: 0.78rem; font-weight: 800;">WIT (TRÍ TUỆ)</div>
                                <div class="wiki-rpg-val"><?= (int)($char['wit'] ?? 450) ?></div>
                                <div style="height: 5px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                                    <div style="width: <?= min(100, (($char['wit'] ?? 450) / 1200) * 100) ?>%; height: 100%; background: #c084fc;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Aptitudes -->
                        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.08);">
                            <div style="background: rgba(46, 204, 113, 0.1); border: 1px solid #2ecc71; padding: 10px 18px; border-radius: 8px;">
                                <span style="color: #94a3b8; font-size: 0.88rem;">Thích nghi Đường Cỏ (Turf):</span> 
                                <strong style="color: #2ecc71; font-size: 1.15rem; margin-left: 6px;"><?= htmlspecialchars($char['turf_aptitude'] ?? 'A') ?></strong>
                            </div>
                            <div style="background: rgba(230, 126, 34, 0.1); border: 1px solid #e67e22; padding: 10px 18px; border-radius: 8px;">
                                <span style="color: #94a3b8; font-size: 0.88rem;">Thích nghi Đường Cát (Dirt):</span> 
                                <strong style="color: #e67e22; font-size: 1.15rem; margin-left: 6px;"><?= htmlspecialchars($char['dirt_aptitude'] ?? 'G') ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 6: MEDICAL & HIGH-RISK BALANCE (CHỈ SỐ Y TẾ) -->
                <div id="medical-stats">
                    <div class="wiki-ribbon-heading" style="background: linear-gradient(135deg, #ef4444, #b91c1c);">
                        <i class="fas fa-heart-pulse"></i> 6. Đánh Giá Y Tế &amp; Cân Bằng High-Risk High-Reward
                    </div>
                    <div class="wiki-section-box" style="border-color: rgba(239, 68, 68, 0.25);">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
                            
                            <!-- Vận tốc -->
                            <div style="background: rgba(255,255,255,0.02); padding: 16px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.06);">
                                <div style="color: #94a3b8; font-size: 0.85rem; font-weight: 700;">TỐC ĐỘ ĐƯỜNG ĐUA</div>
                                <div style="margin: 12px 0 6px; font-size: 1.1rem;">
                                    Min: <strong style="color: #38bdf8;"><?= number_format($char['min_speed'] ?? 0.45, 2) ?></strong> 
                                    → Max: <strong style="color: #f59e0b; font-size: 1.3rem;"><?= number_format($char['max_speed'] ?? 1.5, 2) ?></strong>
                                </div>
                                <div style="color: #8b949e; font-size: 0.82rem;">
                                    Chu kỳ đổi nhịp: <?= (int)($char['speed_change_ms'] ?? 3000) ?> ms
                                </div>
                            </div>

                            <!-- Tỷ lệ chấn thương & Tuyệt kỹ -->
                            <div style="background: rgba(255,255,255,0.02); padding: 16px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.06);">
                                <div style="color: #94a3b8; font-size: 0.85rem; font-weight: 700;">TỶ LỆ CHẤN THƯƠNG &amp; EVENT</div>
                                <div style="margin: 10px 0;">
                                    <?= Helper::injuryBadge($injRate) ?>
                                </div>
                                <div style="color: #ec4899; font-size: 0.88rem; font-weight: 700;">
                                    <i class="fas fa-sparkles"></i> Bứt phá tuyệt kỹ: <?= number_format($char['event_rate'] ?? 8.0, 1) ?>%
                                </div>
                            </div>

                        </div>

                        <!-- CTA to Derby Simulator -->
                        <div style="text-align: center; margin-top: 25px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.06);">
                            <a href="<?= Helper::url('pages/racing.php') ?>" class="btn-adm btn-adm-primary" style="padding: 12px 30px; font-size: 1rem; border-radius: 25px; text-decoration: none;">
                                <i class="fas fa-flag-checkered"></i> Đưa <?= htmlspecialchars($char['name']) ?> Vào Đua Tại Grand Derby Cup &rarr;
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ════ CỘT PHẢI: WIKI INFOBOX SIDEBAR (STICKY CARD) ═════════ -->
            <div class="wiki-infobox-sidebar">
                
                <!-- Infobox Title Banner -->
                <div class="wiki-infobox-header">
                    <h2 class="wiki-infobox-name"><?= htmlspecialchars($char['name']) ?></h2>
                    <?php if (!empty($char['nickname'])): ?>
                        <div class="wiki-infobox-nickname">
                            ∩ "<?= htmlspecialchars($char['nickname']) ?>" ∩
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Subtabs inside Infobox -->
                <div class="wiki-infobox-tabs">
                    <button class="wiki-infobox-tab active" onclick="switchInfoboxTab('main', this)">Main Profile</button>
                    <button class="wiki-infobox-tab" onclick="switchInfoboxTab('stats', this)">Quick Stats</button>
                </div>

                <!-- Character Portrait Wrap -->
                <div class="wiki-infobox-image-wrap">
                    <img src="<?= htmlspecialchars(Helper::characterAvatar($char)) ?>" 
                         alt="<?= htmlspecialchars($char['name']) ?>" 
                         class="wiki-infobox-img"
                         data-fallback="<?= htmlspecialchars(!empty($char['avatar']) ? Helper::asset($char['avatar']) : Helper::asset('media/img/placeholder.png')) ?>"
                         onerror="if (typeof handleImageError === 'function') { handleImageError(this, this.dataset.fallback); } else { this.onerror=null; this.src='<?= Helper::asset('media/img/placeholder.png') ?>'; }">
                </div>

                <!-- Structured Biographical Data Table -->
                <table class="wiki-infobox-table" id="infobox-table-main">
                    <tbody>
                        <?php if (!empty($char['japanese_name'])): ?>
                            <tr>
                                <th>Tên tiếng Nhật</th>
                                <td><?= htmlspecialchars($char['japanese_name']) ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Diễn viên (CV)</th>
                            <td style="color: #38bdf8;"><?= htmlspecialchars($char['voice_actor'] ?? 'Chưa rõ') ?></td>
                        </tr>
                        <tr>
                            <th>Ngày sinh</th>
                            <td><?= htmlspecialchars($char['birthday'] ?? 'Chưa rõ') ?></td>
                        </tr>
                        <tr>
                            <th>Chiều cao</th>
                            <td><?= htmlspecialchars($char['height'] ?? 'Chưa rõ') ?></td>
                        </tr>
                        <tr>
                            <th>Cân nặng</th>
                            <td><?= htmlspecialchars($char['weight'] ?? 'Chưa rõ') ?></td>
                        </tr>
                        <tr>
                            <th>Số đo 3 vòng</th>
                            <td style="color: #f472b6;"><?= htmlspecialchars($char['three_sizes'] ?? 'Chưa rõ') ?></td>
                        </tr>
                        <tr>
                            <th>Khối học viện</th>
                            <td><?= htmlspecialchars($char['class_grade'] ?? 'Học viện Tracen') ?></td>
                        </tr>
                        <tr>
                            <th>Phong cách chạy</th>
                            <td><?= Helper::runningStyleBadge($char['running_style'] ?? 'Leader') ?></td>
                        </tr>
                        <tr>
                            <th>Tốc độ tối đa</th>
                            <td style="color: #f59e0b; font-weight: 800; font-size: 1.05rem;"><?= number_format($char['max_speed'] ?? 1.5, 2) ?></td>
                        </tr>
                        <tr>
                            <th>Rủi ro chấn thương</th>
                            <td><?= Helper::injuryBadge($injRate) ?></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Quick Stats Table (Tab 2) -->
                <table class="wiki-infobox-table" id="infobox-table-stats" style="display: none;">
                    <tbody>
                        <tr>
                            <th>Tốc độ (Speed)</th>
                            <td style="color:#38bdf8; font-weight:800;"><?= (int)($char['base_speed'] ?? 450) ?> / 1200</td>
                        </tr>
                        <tr>
                            <th>Thể lực (Stamina)</th>
                            <td style="color:#4ade80; font-weight:800;"><?= (int)($char['stamina'] ?? 450) ?> / 1200</td>
                        </tr>
                        <tr>
                            <th>Sức mạnh (Power)</th>
                            <td style="color:#fb923c; font-weight:800;"><?= (int)($char['power'] ?? 450) ?> / 1200</td>
                        </tr>
                        <tr>
                            <th>Ý chí (Guts)</th>
                            <td style="color:#f87171; font-weight:800;"><?= (int)($char['guts'] ?? 450) ?> / 1200</td>
                        </tr>
                        <tr>
                            <th>Trí tuệ (Wit)</th>
                            <td style="color:#c084fc; font-weight:800;"><?= (int)($char['wit'] ?? 450) ?> / 1200</td>
                        </tr>
                        <tr>
                            <th>Đường Cỏ (Turf)</th>
                            <td style="color:#2ecc71; font-weight:800;"><?= htmlspecialchars($char['turf_aptitude'] ?? 'A') ?></td>
                        </tr>
                        <tr>
                            <th>Đường Cát (Dirt)</th>
                            <td style="color:#e67e22; font-weight:800;"><?= htmlspecialchars($char['dirt_aptitude'] ?? 'G') ?></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Reference Link in Infobox Footer -->
                <?php if (!empty($char['source_url'])): ?>
                    <div style="padding: 12px 15px; background: rgba(0,0,0,0.3); border-top: 1px solid rgba(255,255,255,0.08); text-align: center;">
                        <a href="<?= htmlspecialchars($char['source_url']) ?>" target="_blank" rel="noopener noreferrer" 
                           style="color: #a855f7; font-size: 0.85rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fas fa-arrow-up-right-from-square"></i> Nguồn Umamusume Wiki
                        </a>
                    </div>
                <?php endif; ?>

            </div>

        </div>

    </div>
</div>

<script>
// Toggle Table of Contents
function toggleToc() {
    const list = document.getElementById('wiki-toc-content');
    const toggleBtn = document.querySelector('.wiki-toc-toggle');
    if (list.style.display === 'none') {
        list.style.display = 'block';
        toggleBtn.textContent = '[ẩn]';
    } else {
        list.style.display = 'none';
        toggleBtn.textContent = '[hiện]';
    }
}

// Switch Infobox internal tabs (Main vs Quick Stats)
function switchInfoboxTab(tab, btn) {
    document.querySelectorAll('.wiki-infobox-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    const mainTable = document.getElementById('infobox-table-main');
    const statsTable = document.getElementById('infobox-table-stats');
    
    if (tab === 'main') {
        mainTable.style.display = 'table';
        statsTable.style.display = 'none';
    } else {
        mainTable.style.display = 'none';
        statsTable.style.display = 'table';
    }
}
</script>

<?php include __DIR__ . '/../footer.php'; ?>
