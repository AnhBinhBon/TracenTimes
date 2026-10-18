<!-- sections/racing_section.php -->
<section class="racing-promo-section">
    <div class="racing-promo-content">
        <h2 class="racing-promo-title"><i class="fa-solid fa-flag-checkered"></i> GRAND DERBY CUP <i class="fa-solid fa-flag-checkered"></i></h2>
        <p class="racing-promo-desc">
            Chào mừng đến với trường đua danh giá nhất! Trải nghiệm những vòng đua nảy lửa cùng hệ thống chiến thuật, tỉ lệ chấn thương và các sự kiện lật kèo phút chót.
        </p>
        <a href="<?= BASE_URL ?>/pages/racing.php" class="btn-play-racing"><i class="fa-solid fa-horse-head"></i> THAM GIA ĐUA NGAY</a>
    </div>
</section>

<style>
.racing-promo-section {
    position: relative;
    width: 100%;
    padding: 80px 20px;
    background: linear-gradient(rgba(10, 10, 15, 0.8), rgba(10, 10, 15, 0.9)), url('<?= BASE_URL ?>/media/img/bg-news.jpg') no-repeat center center/cover;
    background-attachment: fixed;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    border-top: 2px solid var(--accent-gold, #e6b980);
    border-bottom: 2px solid var(--accent-gold, #e6b980);
    box-shadow: inset 0 0 50px rgba(0,0,0,0.8);
    overflow: hidden;
}

.racing-promo-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    background: rgba(20, 20, 25, 0.6);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 40px;
    border-radius: 20px;
    border: 1px solid rgba(230, 185, 128, 0.3);
    box-shadow: 0 15px 35px rgba(0,0,0,0.5), 0 0 20px rgba(230, 185, 128, 0.2);
}

.racing-promo-title {
    font-family: 'Cinzel', serif;
    font-size: 3rem;
    font-weight: 900;
    background: linear-gradient(to right, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    margin-bottom: 20px;
    text-shadow: 0 0 10px rgba(252, 246, 186, 0.1);
}

.racing-promo-desc {
    color: #ddd;
    font-size: 1.2rem;
    line-height: 1.6;
    margin-bottom: 35px;
    font-family: 'Outfit', sans-serif;
}

.btn-play-racing {
    display: inline-block;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: 1.2rem;
    padding: 16px 40px;
    background: linear-gradient(135deg, #e6b980, #c49a5b);
    color: #111;
    text-decoration: none;
    border-radius: 50px;
    text-transform: uppercase;
    letter-spacing: 2px;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    box-shadow: 0 4px 15px rgba(230, 185, 128, 0.4);
    position: relative;
    overflow: hidden;
}

.btn-play-racing::after {
    content: '';
    position: absolute;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
    transform: rotate(45deg) translate(-100%, -100%);
    transition: transform 0.6s ease;
}

.btn-play-racing:hover {
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 10px 25px rgba(230, 185, 128, 0.6);
    color: #000;
}

.btn-play-racing:hover::after {
    transform: rotate(45deg) translate(100%, 100%);
}

@media (max-width: 768px) {
    .racing-promo-title { font-size: 2rem; }
    .racing-promo-desc { font-size: 1rem; }
    .btn-play-racing { padding: 12px 30px; font-size: 1rem; }
}
</style>
