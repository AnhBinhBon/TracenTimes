<footer class="site-footer" id="footer">
    <div class="footer-inner">

        <!-- Footer Top: Logo + Tagline + Nav -->
        <div class="footer-top">
            <div class="footer-brand">
                <div class="footer-logo-wrap">
                    <img src="<?= BASE_URL ?>/media/img/logo.png" alt="TracenTimes Logo" class="footer-logo-img" 
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                    <span class="footer-logo-fallback" style="display:none;">🏇 TRACENTIMES</span>
                </div>
                <p class="footer-tagline">
                    "Umamusume. They are born to run. Gallop beyond limits, run toward your dream."
                </p>
            </div>

            <nav class="footer-nav" aria-label="Footer navigation">
                <div class="footer-nav-col">
                    <h4 class="footer-nav-title">NỘI DUNG</h4>
                    <ul>
                        <li><a href="<?= BASE_URL ?>/pages/news.php">Tin Tức &amp; Sự Kiện</a></li>
                        <li><a href="<?= BASE_URL ?>/pages/characters_list.php">Hồ Sơ Nhân Vật</a></li>
                        <li><a href="<?= BASE_URL ?>/pages/racing.php">Đường Đua Derby Cup</a></li>
                        <li><a href="<?= BASE_URL ?>/#scouts">Gacha Scouts Live</a></li>
                        <li><a href="<?= BASE_URL ?>/#media">Media Gallery</a></li>
                    </ul>
                </div>
                <div class="footer-nav-col">
                    <h4 class="footer-nav-title">WIKI &amp; INFO</h4>
                    <ul>
                        <li><a href="https://umamusu.wiki/Main_Page" target="_blank" rel="noopener">Umamusume Wiki Main</a></li>
                        <li><a href="https://umamusu.wiki/List_of_Characters" target="_blank" rel="noopener">List of Characters</a></li>
                        <li><a href="https://umamusu.wiki/Game:List_of_Scouts_(EN)" target="_blank" rel="noopener">En Server Scouts</a></li>
                        <li><a href="https://umamusu.wiki/Game:List_of_Scouts_(JP)" target="_blank" rel="noopener">JP Server Scouts</a></li>
                    </ul>
                </div>
                <div class="footer-nav-col">
                    <h4 class="footer-nav-title">KÊNH CHÍNH THỨC</h4>
                    <ul>
                        <li><a href="https://youtube.com/@UMAMUSUME_official" target="_blank" rel="noopener"><i class="fab fa-youtube" style="color:#ff0000;"></i> PakaTube! (Official JP)</a></li>
                        <li><a href="https://twitter.com/uma_musu" target="_blank" rel="noopener"><i class="fab fa-x-twitter"></i> Twitter @uma_musu</a></li>
                        <li><a href="https://twitter.com/umamusume_eng" target="_blank" rel="noopener"><i class="fab fa-x-twitter"></i> Twitter @umamusume_eng</a></li>
                        <li><a href="https://youtube.com/@umamusume_eng" target="_blank" rel="noopener"><i class="fab fa-youtube" style="color:#ff0000;"></i> YouTube (English)</a></li>
                        <li><a href="https://discord.gg/umamusume-eng" target="_blank" rel="noopener"><i class="fab fa-discord" style="color:#5865f2;"></i> Official Discord</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Footer Divider -->
        <div class="footer-divider"></div>

        <!-- Footer Bottom: Copyright -->
        <div class="footer-bottom">
            <p class="copyright">
                &copy; <?php echo date('Y'); ?> TracenTimes — Cổng thông tin Umamusume: Pretty Derby. Dữ liệu tham chiếu từ <a href="https://umamusu.wiki/Main_Page" target="_blank" style="color:var(--color-pink); text-decoration:none;">umamusu.wiki</a>.
            </p>
            <p class="footer-disclaimer">
                Dự án phục vụ học tập &amp; nghiên cứu đồ án. Bản quyền thương hiệu và hình ảnh nhân vật thuộc về Cygames, Inc.
            </p>
        </div>

    </div>
</footer>

<!-- Global scroll to top JS -->
<div id="btn-scroll-top" style="display:none; position:fixed; bottom:25px; right:25px; width:45px; height:45px; border-radius:50%; background:var(--color-pink); color:#fff; align-items:center; justify-content:center; cursor:pointer; z-index:999; box-shadow:0 4px 15px rgba(255,75,130,0.5);">
    <i class="fas fa-arrow-up"></i>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnScrollTop = document.getElementById("btn-scroll-top");
        if (btnScrollTop) {
            window.addEventListener("scroll", function() {
                if (window.scrollY > 300) {
                    btnScrollTop.style.display = "flex";
                } else {
                    btnScrollTop.style.display = "none";
                }
            });
            btnScrollTop.addEventListener("click", function() {
                window.scrollTo({ top: 0, behavior: "smooth" });
            });
        }
    });
</script>
</body>
</html>
