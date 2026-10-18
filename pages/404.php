<?php
// ================================================================
//  404.php — Trang lỗi "Không tìm thấy nhân vật"
//  Hiển thị khi: ID không tồn tại, hoặc truy cập thiếu tham số
// ================================================================
// Gửi HTTP status code 404 đúng chuẩn
http_response_code(404);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errorContext = $_SESSION['404_context'] ?? null;
unset($_SESSION['404_context']); // Dọn sạch sau khi đọc

require_once '../config/db.php'; // cần BASE_URL
include '../header.php';
?>

<main class="page-404" id="page-404" aria-label="404 - Không tìm thấy nhân vật">

    <!-- ── Background Decorations ─────────────────────────────────── -->
    <div class="p404-bg" aria-hidden="true">
        <div class="p404-bg__ring p404-bg__ring--1"></div>
        <div class="p404-bg__ring p404-bg__ring--2"></div>
        <div class="p404-bg__ring p404-bg__ring--3"></div>
        <div class="p404-bg__streak p404-bg__streak--1"></div>
        <div class="p404-bg__streak p404-bg__streak--2"></div>
        <div class="p404-bg__glow"></div>
    </div>

    <div class="container">
        <div class="p404-inner">

            <!-- ── Giant 404 Number ────────────────────────────────── -->
            <div class="p404-number" aria-hidden="true">
                <span class="p404-number__digit">4</span>
                <span class="p404-number__icon">
                    <i class="fas fa-horse-head"></i>
                </span>
                <span class="p404-number__digit">4</span>
            </div>

            <!-- ── Divider Line ────────────────────────────────────── -->
            <div class="p404-divider" aria-hidden="true">
                <span class="p404-divider__dot"></span>
                <span class="p404-divider__line"></span>
                <span class="p404-divider__label">ERROR</span>
                <span class="p404-divider__line"></span>
                <span class="p404-divider__dot"></span>
            </div>

            <!-- ── Message Block ───────────────────────────────────── -->
            <div class="p404-message">
                <h1 class="p404-title">Không tìm thấy nhân vật này!</h1>
                <p class="p404-subtitle">
                    Có vẻ như nhân vật bạn đang tìm đã phi nước đại về nơi khác rồi…<br>
                    Hoặc ID không tồn tại trong cơ sở dữ liệu của chúng tôi.
                </p>

                <?php if ($errorContext): ?>
                    <div class="p404-context-badge">
                        <i class="fas fa-info-circle"></i>
                        <?php echo htmlspecialchars($errorContext); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ── CTA Buttons ─────────────────────────────────────── -->
            <div class="p404-actions">
                <a href="<?= BASE_URL ?>/pages/characters_list.php" class="btn btn-cta p404-btn-primary" id="btn-404-back-list">
                    <i class="fas fa-list"></i>
                    Quay lại danh sách
                </a>
                <a href="<?= BASE_URL ?>/" class="btn btn-ghost p404-btn-secondary" id="btn-404-home">
                    <i class="fas fa-home"></i>
                    Trang chủ
                </a>
            </div>

            <!-- ── Fun Footer ──────────────────────────────────────── -->
            <p class="p404-hint">
                <i class="fas fa-lightbulb"></i>
                Thử kiểm tra lại URL, hoặc khám phá danh sách nhân vật đầy đủ bên dưới.
            </p>

        </div>
    </div>

</main>

<!-- ── Inline styles cho trang 404 ────────────────────────────────── -->
<style>
/* ── Layout ─────────────────────────────────────────────────────── */
.page-404 {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-base);
    position: relative;
    overflow: hidden;
    padding: 80px 0 60px;
}

/* ── Background Decorations ─────────────────────────────────────── */
.p404-bg {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 0;
}

/* Pulsing rings */
.p404-bg__ring {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(255, 51, 102, 0.12);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: p404RingPulse 4s ease-in-out infinite;
}
.p404-bg__ring--1 { width: 400px; height: 400px; animation-delay: 0s;    border-color: rgba(255, 51, 102, 0.15); }
.p404-bg__ring--2 { width: 650px; height: 650px; animation-delay: 0.8s;  border-color: rgba(255, 51, 102, 0.09); }
.p404-bg__ring--3 { width: 900px; height: 900px; animation-delay: 1.6s;  border-color: rgba(255, 51, 102, 0.05); }

@keyframes p404RingPulse {
    0%, 100% { opacity: 0.4; transform: translate(-50%, -50%) scale(1);    }
    50%       { opacity: 1;   transform: translate(-50%, -50%) scale(1.04); }
}

/* Speed streaks */
.p404-bg__streak {
    position: absolute;
    height: 2px;
    border-radius: 999px;
    background: linear-gradient(90deg, transparent, rgba(255, 51, 102, 0.4), transparent);
    animation: p404Streak 3s ease-in-out infinite;
}
.p404-bg__streak--1 { width: 300px; top: 30%; left: -10%; animation-delay: 0s;   animation-duration: 2.8s; }
.p404-bg__streak--2 { width: 200px; top: 65%; right: -5%; animation-delay: 1.4s; animation-duration: 3.2s; transform: rotate(180deg); }

@keyframes p404Streak {
    0%   { opacity: 0; transform: translateX(0);    }
    30%  { opacity: 1; }
    100% { opacity: 0; transform: translateX(120vw); }
}

/* Radial glow */
.p404-bg__glow {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 70% 50% at 50% 40%, rgba(255, 51, 102, 0.08) 0%, transparent 70%),
        radial-gradient(ellipse 40% 40% at 20% 70%, rgba(139, 37, 54, 0.12) 0%, transparent 60%);
}

/* ── Inner Content ───────────────────────────────────────────────── */
.p404-inner {
    position: relative;
    z-index: 1;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 36px;
    padding: 20px 0;
}

/* ── Giant 404 Number ───────────────────────────────────────────── */
.p404-number {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
    line-height: 1;
    filter: drop-shadow(0 0 60px rgba(255, 51, 102, 0.4));
}

.p404-number__digit {
    font-family: var(--font-heading);
    font-size: clamp(7rem, 18vw, 12rem);
    font-weight: 900;
    /* Vibrant pink gradient fill */
    background: linear-gradient(135deg, #ff3366 0%, #FF66A3 40%, #FF99C8 60%, #ff3366 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmerText 3s linear infinite;
    letter-spacing: -0.02em;
}

.p404-number__icon {
    font-size: clamp(4rem, 10vw, 7rem);
    color: #ff3366;
    margin: 0 8px;
    /* Floating animation */
    animation: p404Float 3s ease-in-out infinite;
    display: inline-flex;
    align-items: center;
    /* Override text fill */
    -webkit-text-fill-color: #ff3366;
    filter: drop-shadow(0 0 20px rgba(255, 51, 102, 0.6));
}

@keyframes p404Float {
    0%, 100% { transform: translateY(0px) rotate(-5deg); }
    50%       { transform: translateY(-14px) rotate(5deg); }
}

/* ── Divider ─────────────────────────────────────────────────────── */
.p404-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    max-width: 380px;
}

.p404-divider__line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 51, 102, 0.4), transparent);
}

.p404-divider__dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: rgba(255, 51, 102, 0.5);
    flex-shrink: 0;
}

.p404-divider__label {
    font-family: var(--font-body);
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.35em;
    color: rgba(255, 51, 102, 0.7);
    text-transform: uppercase;
    flex-shrink: 0;
}

/* ── Message ─────────────────────────────────────────────────────── */
.p404-message {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}

.p404-title {
    font-family: var(--font-heading);
    font-size: clamp(1.5rem, 4vw, 2.2rem);
    font-weight: 900;
    color: var(--text-primary);
    letter-spacing: 0.03em;
    text-transform: uppercase;
    /* Override h1 global styles */
    line-height: 1.2;
}

/* Highlight the exclamation mark in pink */
.p404-title::after {
    content: none; /* Remove global .section-title underline */
}

.p404-subtitle {
    color: var(--text-secondary);
    font-size: var(--size-base);
    line-height: 1.8;
    max-width: 52ch;
}

/* Optional context badge (error detail from session) */
.p404-context-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    background: rgba(255, 51, 102, 0.08);
    border: 1px solid rgba(255, 51, 102, 0.2);
    border-radius: var(--radius-pill);
    font-size: var(--size-xs);
    color: rgba(255, 51, 102, 0.9);
    font-weight: 600;
    letter-spacing: 0.05em;
}

/* ── Action Buttons ──────────────────────────────────────────────── */
.p404-actions {
    display: flex;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
}

/* Override btn-cta to ensure proper padding */
.p404-btn-primary {
    padding: 16px 40px;
    font-size: var(--size-sm);
    letter-spacing: 0.12em;
    box-shadow: 0 6px 30px rgba(255, 51, 102, 0.45);
}

.p404-btn-primary:hover {
    box-shadow: 0 8px 40px rgba(255, 51, 102, 0.65);
    transform: translateY(-3px) scale(1.03);
}

.p404-btn-secondary {
    padding: 15px 32px;
    font-size: var(--size-sm);
    letter-spacing: 0.10em;
}

/* ── Hint ────────────────────────────────────────────────────────── */
.p404-hint {
    font-size: var(--size-xs);
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 8px;
    max-width: none;
}

.p404-hint i {
    color: rgba(255, 200, 66, 0.7);
}

/* ── Responsive ──────────────────────────────────────────────────── */
@media (max-width: 600px) {
    .p404-number__digit { font-size: clamp(5rem, 20vw, 8rem); }
    .p404-number__icon  { font-size: clamp(3rem, 12vw, 5rem); }
    .p404-actions       { flex-direction: column; width: 100%; }
    .p404-btn-primary,
    .p404-btn-secondary { width: 100%; justify-content: center; }
    .p404-bg__ring--2,
    .p404-bg__ring--3   { display: none; }
}
</style>

<?php include '../footer.php'; ?>

