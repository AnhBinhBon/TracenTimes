<section id="hero" class="hero-section" style="position: relative; width: 100%; height: 100vh; overflow: hidden;">
    <!-- Make the video act as a background -->
    <video autoplay muted loop playsinline id="myVideo" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0;">
        <source src="<?= BASE_URL ?>/media/video/banner_video.mp4" type="video/mp4">
    </video>
    
    <!-- Content wrapper -->
    <div class="flex-center" style="position: relative; z-index: 1; height: 100%; width: 100%; color: white; background: rgba(0,0,0,0.4);">
        <!-- Add any text or overlay content here -->
    </div>
</section>