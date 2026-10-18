<!-- PLAY NOW MODAL -->
<div id="play-modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-play-title">
    <div class="modal-overlay" id="modal-overlay"></div>
    <div class="modal-content play-now-dialog">
        <!-- Vibrant Blue Header -->
        <div class="play-modal-header">
            <div class="play-modal-title" id="modal-play-title">
                <i class="fa-solid fa-arrows-rotate play-modal-reload-icon"></i>
                <span>Play Now!</span>
            </div>
            <button class="play-modal-close-btn" id="modal-close-btn" aria-label="Đóng">&times;</button>
        </div>

        <!-- Patterned White Body -->
        <div class="play-modal-body">
            <!-- Row 1: Umamusume Logo + Special Week Icon -->
            <div class="play-modal-brand-row">
                <div class="play-modal-logo-wrap">
                    <img src="<?= BASE_URL ?>/media/img/logo.png" alt="Umamusume Pretty Derby" class="play-modal-logo">
                </div>
                <div class="play-modal-icon-wrap">
                    <img src="<?= BASE_URL ?>/media/img/icon.png" alt="Special Week" class="play-modal-app-icon">
                </div>
            </div>

            <!-- Row 2 & 3: Store Badges Grid (Steam, App Store, Google Play, Google Play Games) -->
            <div class="play-store-grid">
                <!-- Upper Row: Steam & App Store -->
                <div class="play-store-row">
                    <!-- Steam -->
                    <a href="https://store.steampowered.com/app/3224770/Umamusume_Pretty_Derby/" target="_blank" rel="noopener noreferrer" class="store-badge-btn" id="btn-store-steam" title="Chơi trên Steam">
                        <i class="fa-brands fa-steam store-badge-icon store-badge-icon--steam"></i>
                        <div class="store-badge-content store-badge-content--steam">
                            <span class="store-brand-title font-steam">STEAM<sup class="store-tm">&reg;</sup></span>
                        </div>
                    </a>

                    <!-- Apple App Store -->
                    <a href="https://apps.apple.com/us/app/umamusume-pretty-derby/id6480433538" target="_blank" rel="noopener noreferrer" class="store-badge-btn" id="btn-store-apple" title="Tải trên App Store">
                        <i class="fa-brands fa-apple store-badge-icon store-badge-icon--apple"></i>
                        <div class="store-badge-content">
                            <span class="store-badge-sub">Download on the</span>
                            <span class="store-brand-title">App Store</span>
                        </div>
                    </a>
                </div>

                <!-- Lower Row: Google Play & Google Play Games -->
                <div class="play-store-row">
                    <!-- Google Play -->
                    <a href="https://play.google.com/store/apps/details?id=com.cygames.umamusume&hl=en_GB" target="_blank" rel="noopener noreferrer" class="store-badge-btn" id="btn-store-google-play" title="Tải trên Google Play">
                        <svg class="store-badge-icon store-badge-icon--svg" viewBox="0 0 512 512" width="22" height="22" aria-hidden="true">
                            <path fill="#4285F4" d="M48.7 13.9C40.6 22.4 36 35.7 36 53.1v405.8c0 17.4 4.6 30.7 12.7 39.2l2.2 2.1 227.1-227.1v-5.2L50.9 11.8l-2.2 2.1z"/>
                            <path fill="#FFBB00" d="M353.7 200.7l-75.7 75.7 75.7 75.7 1.7 1 89.6-50.9c25.6-14.5 25.6-38.3 0-52.8l-89.6-50.9-1.7 2.2z"/>
                            <path fill="#EA4335" d="M278 276.4L50.9 503.5c8.3 8.8 22.1 9.8 37.9 0.9l266.6-151.3-77.4-76.7z"/>
                            <path fill="#34A853" d="M278 235.6l77.4-76.7L88.8 7.6C73-1.3 59.2-0.3 50.9 8.5L278 235.6z"/>
                        </svg>
                        <div class="store-badge-content">
                            <span class="store-badge-sub">GET IT ON</span>
                            <span class="store-brand-title">Google Play</span>
                        </div>
                    </a>

                    <!-- Google Play Games PC -->
                    <a href="https://play.google.com/store/apps/details?id=com.cygames.umamusume&utm_source=umamusume.com&utm_medium=referral&utm_campaign=organic" target="_blank" rel="noopener noreferrer" class="store-badge-btn" id="btn-store-gpg-pc" title="Chơi trên Google Play Games (PC)">
                        <svg class="store-badge-icon store-badge-icon--svg" viewBox="0 0 24 24" width="23" height="23" fill="#00E676" aria-hidden="true">
                            <path d="M19.5 6H4.5C3.12 6 2 7.12 2 8.5v7C2 16.88 3.12 18 4.5 18h15c1.38 0 2.5-1.12 2.5-2.5v-7C22 7.12 20.88 6 19.5 6zM8 13.5H6.5V15H5v-1.5H3.5v-1.5H5V10.5h1.5V12H8v1.5zm8.25.75c-.69 0-1.25-.56-1.25-1.25s.56-1.25 1.25-1.25 1.25.56 1.25 1.25-.56 1.25-1.25 1.25zm2.5-2.5c-.69 0-1.25-.56-1.25-1.25s.56-1.25 1.25-1.25 1.25.56 1.25 1.25-.56 1.25-1.25 1.25z"/>
                        </svg>
                        <div class="store-badge-content">
                            <span class="store-badge-sub">PLAY ON PC</span>
                            <span class="store-brand-title store-brand-title--sm">Google Play Games</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
