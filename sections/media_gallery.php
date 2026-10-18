<?php
// ====================================================================
//  sections/media_gallery.php — Khối Media Gallery (3-Tier View)
// ====================================================================

require_once __DIR__ . '/../config/app.php';

$galleryItems = [];
try {
    $db = Database::getConnection();
    $stmt = $db->query("SELECT * FROM `gallery` ORDER BY `id` ASC");
    $galleryItems = $stmt->fetchAll();
} catch (Exception $e) {
    // fallback nếu có lỗi
}
?>
<section id="media" class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">GALLERY</span>
            <h2 class="section-title">Media &amp; Visuals</h2>
            <p class="section-subtitle">Khoảnh khắc đáng nhớ trên đường đua và những sân khấu hòa nhạc chiến thắng rực rỡ.</p>
        </div>

        <div class="slideshow-container">
            <?php if (!empty($galleryItems)): ?>
                <?php foreach ($galleryItems as $row): 
                    $imgSrc = $row['image'] ?? $row['Ảnh'] ?? 'media/img/galleries/media_gallery_1.png';
                ?>
                    <div class="mySlides fade">
                        <img src="<?= BASE_URL . '/' . htmlspecialchars($imgSrc); ?>" alt="Media Gallery Image">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="media-placeholder" style="text-align:center; color: var(--text-muted); padding: 40px;">
                    Không có ảnh nào trong thư viện
                </div>
            <?php endif; ?>
        </div>

        <?php $rowCount = count($galleryItems); ?>
        <?php if ($rowCount > 0): ?>
            <div class="dots-container">
                <?php for ($i = 0; $i < $rowCount; $i++): ?>
                    <span class="dot"></span>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    let slideIndex = 0;
    showSlides();

    function showSlides() {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        let dots = document.getElementsByClassName("dot");

        if (slides.length === 0) return;

        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }

        slideIndex++;
        if (slideIndex > slides.length) {
            slideIndex = 1;
        }

        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }

        if (slides[slideIndex - 1]) {
            slides[slideIndex - 1].style.display = "block";
        }
        if (dots[slideIndex - 1]) {
            dots[slideIndex - 1].className += " active";
        }

        setTimeout(showSlides, 4000); // Đổi ảnh mỗi 4 giây
    }
</script>