<?php
// ================================================================
//  pages/racing.php — Trang đua ngựa mô phỏng logic Grand Derby
// ================================================================
require_once __DIR__ . '/../config/app.php';

$charDao = new CharacterDAO();
$dbCharacters = $charDao->getAllWithStats();

// Nếu không có dữ liệu, tạo mảng fallback
if (empty($dbCharacters)) {
    $dbCharacters = [
        ['name' => 'Special Week', 'min_speed' => 0.45, 'max_speed' => 1.5, 'injury_rate' => 0.03, 'event_rate' => 8.5]
    ];
}

foreach ($dbCharacters as &$c) {
    $c['display_avatar'] = Helper::characterAvatar($c);
}
unset($c);

$pageTitle = 'Grand Derby Cup — Đường Đua Mô Phỏng';
include __DIR__ . '/../header.php';
?>

<!-- Include file CSS riêng cho trang đua -->
<link rel="stylesheet" href="<?= BASE_URL ?>/css/racing.css?v=<?php echo time(); ?>">

<div class="racing-page-wrapper">
    <div class="racing-container">

        <div class="racing-header">
            <h1 class="racing-title"><i class="fa-solid fa-flag-checkered"></i> GRAND DERBY CUP <i class="fa-solid fa-flag-checkered"></i></h1>
            <p class="racing-subtitle">Nhập số lượng chiến mã và bắt đầu!</p>

            <div class="racing-controls">
                <div class="input-group">
                    <label for="num-horses">Số lượng ngựa (2-50):</label>
                    <input type="number" id="num-horses" min="2" max="50" value="<?php echo count($dbCharacters) >= 2 ? count($dbCharacters) : 4; ?>">
                </div>
                <div class="input-group" style="flex-direction: column; align-items: flex-start; width: 100%;">
                    <label for="jockeys-input">Danh sách Jockey (mỗi dòng 1 người, hoặc chép file JSON vào):</label>
                    <textarea id="jockeys-input" rows="3" style="width: 100%; padding: 8px; border-radius: 4px; background: #222; color: #fff; border: 1px solid var(--accent-gold); resize: vertical;" placeholder="Nhập tên Jockey A&#10;Nhập tên Jockey B..."></textarea>
                    <div style="display: flex; gap: 10px; margin-top: 5px;">
                        <button id="btn-random-jockeys" class="btn-action" style="font-size: 0.8rem; padding: 5px 10px; background: #8e44ad; border-color: #732d91;"><i class="fa-solid fa-dice"></i> Random Nài Ngựa</button>
                        <button id="btn-export-jockeys" class="btn-action" style="font-size: 0.8rem; padding: 5px 10px;"><i class="fa-solid fa-download"></i> Xuất file JSON</button>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; width: 100%; justify-content: center; margin-top: 10px;">
                    <button id="btn-generate" class="btn-action">TẠO ĐƯỜNG ĐUA</button>
                    <button id="btn-start-race" class="btn-start" disabled>BẮT ĐẦU ĐUA</button>
                    <button id="btn-reset-race" class="btn-reset" disabled>LÀM LẠI</button>
                </div>
            </div>
        </div>

        <!-- Sân vận động đua ngựa -->
        <div class="race-stadium">
            <!-- Khán đài trên (giống header) -->
            <div class="grandstand grandstand-top">
                <div class="crowd">👥 🗣️ Khán đài A đang hò reo! 👏 🎉</div>
            </div>

            <!-- Khu vực đường đua chính -->
            <div class="race-track-wrapper">
                <div class="race-track" id="race-track">
                    <div class="finish-line"></div>
                    <div id="lanes-container"></div>
                </div>
            </div>

            <!-- Khán đài dưới (giống footer) -->
            <div class="grandstand grandstand-bottom">
                <div class="crowd">🎉 🎌 Khán đài B đang cổ vũ cuồng nhiệt! 📣 👥</div>
            </div>
        </div>

        <div id="race-result" class="race-result-box" style="display: none;">
            <h2>KẾT QUẢ CUỘC ĐUA</h2>
            <div id="winner-announcement" class="winner-text"></div>
            <div id="leaderboard" class="leaderboard"></div>
        </div>

        <!-- Nút Về Trang Chủ -->
        <div class="racing-footer-actions" style="text-align: center; margin-top: 40px; margin-bottom: 20px;">
            <a href="<?= BASE_URL ?>/index.php" class="btn-home"><i class="fa-solid fa-house"></i> QUAY LẠI TRANG CHỦ</a>
        </div>

    </div>
</div>

<script>
    // Load data từ PHP sang JS
    const dbCharacters = <?php echo json_encode($dbCharacters, JSON_UNESCAPED_UNICODE); ?>;

    document.addEventListener("DOMContentLoaded", function() {
        const btnGenerate = document.getElementById("btn-generate");
        const btnStart = document.getElementById("btn-start-race");
        const btnReset = document.getElementById("btn-reset-race");
        const inputHorses = document.getElementById("num-horses");
        const lanesContainer = document.getElementById("lanes-container");
        const resultBox = document.getElementById("race-result");
        const winnerAnnouncement = document.getElementById("winner-announcement");
        const leaderboardDiv = document.getElementById("leaderboard");

        let isRacing = false;
        let raceIntervals = [];
        let speedChangeIntervals = [];
        let horsesData = [];
        let finishedHorses = [];

        // Tạo đường đua
        btnGenerate.addEventListener("click", function() {
            if (isRacing) return;

            let numHorses = parseInt(inputHorses.value);
            if (numHorses < 2) numHorses = 2;
            if (numHorses > 50) numHorses = 50;
            inputHorses.value = numHorses;

            let rawJockeys = document.getElementById("jockeys-input").value.trim();
            let jockeysList = [];
            if (rawJockeys) {
                try {
                    let parsed = JSON.parse(rawJockeys);
                    if (Array.isArray(parsed)) jockeysList = parsed;
                } catch (e) {
                    jockeysList = rawJockeys.split('\n').map(j => j.trim()).filter(j => j !== '');
                }
            }

            lanesContainer.innerHTML = '';
            horsesData = [];
            finishedHorses = [];
            resultBox.style.display = "none";

            // Tối đa 10 đường đua
            const numLanes = Math.min(numHorses, 10);

            // Tạo lanes
            for (let i = 0; i < numLanes; i++) {
                const lane = document.createElement('div');
                lane.className = 'lane';
                lane.id = 'lane-' + i;

                const laneNum = document.createElement('div');
                laneNum.className = 'lane-number';
                laneNum.innerText = i + 1;
                lane.appendChild(laneNum);

                lanesContainer.appendChild(lane);
            }

            // Đặt ngựa vào lane (chia theo modulo 10)
            for (let i = 0; i < numHorses; i++) {
                const laneIndex = i % 10;
                const laneEl = document.getElementById('lane-' + laneIndex);

                const horseDiv = document.createElement('div');
                horseDiv.className = 'horse';
                horseDiv.id = 'horse-' + i;

                // Nếu có nhiều ngựa trong 1 lane, dịch chuyển chúng dọc xuống một chút để không đè hẳn lên nhau
                const offsetLayer = Math.floor(i / 10);
                const zIndex = 100 - offsetLayer;
                // Thu nhỏ khoảng cách lệch và bao gồm calc(-50%) để luôn căn giữa làn đua
                const topOffset = (offsetLayer * 8) - 10;

                horseDiv.style.zIndex = zIndex;
                horseDiv.style.transform = `translateY(calc(-50% + ${topOffset}px))`;

                // Lấy thông tin từ DB Characters (quay vòng nếu số lượng ngựa > số char trong DB)
                const dbChar = dbCharacters[i % dbCharacters.length];
                const suffix = i >= dbCharacters.length ? ` (Clone ${Math.floor(i/dbCharacters.length)})` : '';
                const baseHorseName = (dbChar['name'] || dbChar['Tên nhân vật'] || 'Ngựa') + suffix;

                // Cấp jockey (nếu có)
                let jockeyName = "";
                if (jockeysList.length > 0) {
                    jockeyName = jockeysList[i % jockeysList.length];
                }

                // Lấy tốc độ
                const minSpeed = parseFloat(dbChar['min_speed'] || 0.2);
                const maxSpeed = parseFloat(dbChar['max_speed'] || 1.0);
                const speedChangeMs = parseInt(dbChar['speed_change_ms'] || 3000);
                const injuryRate = parseFloat(dbChar['injury_rate'] || 0.05);
                const eventRate = parseFloat(dbChar['event_rate'] || 5.0);

                // Sprite ngựa (nếu có ảnh thì dùng, không thì dùng emoji mặc định)
                let spriteHtml = '🐎';
                if (dbChar['Ảnh nhân vật']) {
                    spriteHtml = '🐎';
                }

                // Chỉ hiển thị tên ngựa trên đường đua
                horseDiv.innerHTML = `
                <div class="horse-sprite">${spriteHtml}</div>
                <span class="horse-name">[${i+1}] ${baseHorseName}</span>
            `;

                laneEl.appendChild(horseDiv);

                horsesData.push({
                    id: i,
                    charId: dbChar['id'] || (i + 1),
                    name: baseHorseName,
                    avatar: dbChar['display_avatar'] || '<?= Helper::asset('media/img/placeholder.png') ?>',
                    runningStyle: dbChar['running_style'] || 'Leader',
                    jockey: jockeyName,
                    element: horseDiv,
                    pos: 0,
                    baseSpeed: 0,
                    minSpeed: minSpeed,
                    maxSpeed: maxSpeed,
                    speedChangeMs: speedChangeMs,
                    injuryRate: injuryRate,
                    eventRate: eventRate,
                    injured: false,
                    distanceToFinish: 0,
                    finished: false,
                    finishTime: 0
                });
            }

            btnStart.disabled = false;
            btnReset.disabled = true;
        });

        // Bắt đầu đua
        btnStart.addEventListener("click", function() {
            if (isRacing || horsesData.length === 0) return;
            isRacing = true;
            btnStart.disabled = true;
            btnGenerate.disabled = true;
            btnReset.disabled = true;
            resultBox.style.display = "none";
            finishedHorses = [];

            const trackWidth = document.querySelector('.race-track').offsetWidth;
            const finishLinePos = trackWidth - 80;

            // Khởi tạo tốc độ ban đầu cho từng ngựa dựa trên min/max từ DB
            horsesData.forEach(h => {
                h.pos = 0;
                h.finished = false;
                h.element.style.left = "0px";
                h.baseSpeed = Math.random() * (h.maxSpeed - h.minSpeed) + h.minSpeed;
            });

            const startTime = Date.now();

            // Vòng lặp đua chính
            const raceInterval = setInterval(() => {
                let allFinished = true;

                horsesData.forEach(h => {
                    if (h.finished) return;
                    allFinished = false;

                    // Tốc độ nhấp nhô mỗi khung hình
                    let frameBoost = Math.random() * 0.5;
                    h.pos += h.baseSpeed + frameBoost;

                    if (h.pos >= finishLinePos) {
                        h.pos = finishLinePos;
                        h.finished = true;
                        h.finishTime = Date.now() - startTime;
                        h.distanceToFinish = 0;
                        finishedHorses.push(h);

                        if (finishedHorses.filter(hx => !hx.injured).length === 1 && !h.injured) {
                            announceWinner(h);
                        }
                    }

                    h.element.style.left = h.pos + "px";
                });

                if (allFinished) {
                    stopRace(raceInterval);
                    showLeaderboard();
                }
            }, 30);

            // Thiết lập bộ đếm thay đổi tốc độ riêng biệt cho từng con ngựa
            horsesData.forEach(h => {
                const interval = setInterval(() => {
                    if (!h.finished) {
                        // Kiểm tra chấn thương (chỉ kiểm tra mỗi khi thay đổi tốc độ)
                        // Lấy giá trị DB làm % (0.05 nghĩa là 5%)
                        const injuryPercent = (h.injuryRate <= 1.0) ? (h.injuryRate * 100) : h.injuryRate;
                        if (Math.random() * 100 < injuryPercent) {
                            h.injured = true;
                            h.finished = true;
                            h.finishTime = Infinity;
                            h.distanceToFinish = finishLinePos - h.pos;

                            h.element.querySelector('.horse-name').style.color = "#ff3366";
                            h.element.querySelector('.horse-name').innerText += " 🚑 (Chấn thương)";
                            const spriteEl = h.element.querySelector('.horse-sprite');
                            if (spriteEl) {
                                spriteEl.innerHTML = '🚑';
                                spriteEl.style.filter = 'drop-shadow(0 0 8px #ff3366)';
                            }

                            finishedHorses.push(h);
                            return;
                        }

                        // Random lại tốc độ cơ bản trong khoảng min_speed -> max_speed
                        h.baseSpeed = Math.random() * (h.maxSpeed - h.minSpeed) + h.minSpeed;

                        // Sự kiện bất ngờ (Dựa trên event_rate, lưu dưới dạng %)
                        if (Math.random() * 100 < h.eventRate) {
                            if (Math.random() < 0.5) {
                                // Tăng tốc độ mạnh (Buff)
                                h.baseSpeed += h.maxSpeed * 0.8;
                                h.element.querySelector('.horse-name').style.color = "#00ffff"; // Xanh lơ (Buff)
                                h.element.querySelector('.horse-name').style.textShadow = "0 0 10px #0055ff";
                            } else {
                                // Giảm tốc độ mạnh (Nerf) - Khựng lại
                                h.baseSpeed = h.minSpeed * 0.3; // Chạy cực chậm
                                h.element.querySelector('.horse-name').style.color = "#ff9900"; // Cam (Nerf)
                                h.element.querySelector('.horse-name').style.textShadow = "0 0 10px #ff0000";
                            }
                        } else {
                            // Chạy bình thường
                            h.element.querySelector('.horse-name').style.color = "#fff";
                            h.element.querySelector('.horse-name').style.textShadow = "none";
                        }
                    }
                }, h.speedChangeMs);
                speedChangeIntervals.push(interval);
            });

            raceIntervals.push(raceInterval);
        });

        btnReset.addEventListener("click", function() {
            horsesData.forEach(h => {
                h.pos = 0;
                h.finished = false;
                h.injured = false;
                h.element.style.left = "0px";
                h.element.querySelector('.horse-name').style.color = "#fff";
                // Xóa chữ 🚑 nếu có
                let originalName = h.name;
                h.element.querySelector('.horse-name').innerText = `[${h.id + 1}] ${originalName}`;
                const spriteEl = h.element.querySelector('.horse-sprite');
                if (spriteEl) {
                    spriteEl.innerHTML = '🐎';
                    spriteEl.style.filter = 'none';
                }
            });
            finishedHorses = [];
            resultBox.style.display = "none";
            btnStart.disabled = false;
            btnGenerate.disabled = false;
            btnReset.disabled = true;
        });

        const defaultJockeysPool = [
            "Yutaka Take", "C. Lemaire", "M. Demuro", "Oisin Murphy", "Joao Moreira",
            "Yuichi Fukunaga", "Ryan Moore", "F. Dettori", "K. Matsuyama", "C. Soumillon",
            "K. Ikezoe", "Y. Kawada", "M. Iwata", "Z. Purton", "H. Bowman",
            "W. Buick", "T. Marquand", "K. Tosaki", "N. Yokoyama", "T. Bachelot"
        ];

        document.getElementById("btn-random-jockeys").addEventListener("click", function() {
            let numHorses = parseInt(inputHorses.value);
            if (isNaN(numHorses) || numHorses < 2) numHorses = 2;
            if (numHorses > 50) numHorses = 50;

            let randomList = [];
            for (let i = 0; i < numHorses; i++) {
                let randomName = defaultJockeysPool[Math.floor(Math.random() * defaultJockeysPool.length)];
                randomList.push(randomName);
            }
            document.getElementById("jockeys-input").value = randomList.join('\n');
        });

        document.getElementById("btn-export-jockeys").addEventListener("click", function() {
            let rawJockeys = document.getElementById("jockeys-input").value.trim();
            let jockeysList = [];
            if (rawJockeys) {
                try {
                    let parsed = JSON.parse(rawJockeys);
                    if (Array.isArray(parsed)) jockeysList = parsed;
                } catch (e) {
                    jockeysList = rawJockeys.split('\n').map(j => j.trim()).filter(j => j !== '');
                }
            }

            if (jockeysList.length === 0) {
                alert("Vui lòng nhập ít nhất 1 Jockey để xuất JSON!");
                return;
            }

            const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(jockeysList, null, 2));
            const downloadAnchorNode = document.createElement('a');
            downloadAnchorNode.setAttribute("href", dataStr);
            downloadAnchorNode.setAttribute("download", "jockeys_list.json");
            document.body.appendChild(downloadAnchorNode);
            downloadAnchorNode.click();
            downloadAnchorNode.remove();
        });

        function stopRace(mainInterval) {
            isRacing = false;
            clearInterval(mainInterval);
            speedChangeIntervals.forEach(clearInterval);
            speedChangeIntervals = [];
            btnReset.disabled = false;
            btnGenerate.disabled = false;

            const healthyHorses = finishedHorses.filter(hx => !hx.injured);
            if (healthyHorses.length === 0) {
                resultBox.style.display = "block";
                winnerAnnouncement.innerHTML = `<span style="color:#ff3366; font-size:1.05rem;"><i class="fa-solid fa-triangle-exclamation"></i> Toàn bộ chiến mã đều gặp chấn thương 🚑 do thi đấu ở cường độ quá tải! Không có ai cán đích an toàn.</span>`;
            }
        }

        function announceWinner(horse) {
            resultBox.style.display = "block";
            let displayString = `[${horse.id + 1}] ${horse.name}`;
            if (horse.jockey) displayString += ` <span style="font-size:0.8em; color:#ddd;">(Nài: ${horse.jockey})</span>`;
            winnerAnnouncement.innerHTML = `🏆 Về nhất: <strong>${displayString}</strong>!`;
            leaderboardDiv.innerHTML = "<em>Đang chờ các chiến mã khác về đích...</em>";
        }

        function showLeaderboard() {
            // Sắp xếp lại mảng kết quả: Hoàn thành trước, Chấn thương sau (dựa vào khoảng cách tới đích)
            finishedHorses.sort((a, b) => {
                if (!a.injured && !b.injured) return a.finishTime - b.finishTime;
                if (!a.injured && b.injured) return -1;
                if (a.injured && !b.injured) return 1;
                return a.distanceToFinish - b.distanceToFinish;
            });

            function resolveAvatarUrl(path) {
                if (!path) return '<?= BASE_URL ?>/media/img/placeholder.png';
                if (path.startsWith('http://') || path.startsWith('https://')) return path;
                return '<?= BASE_URL ?>/' + path.replace(/^\/+/, '');
            }

            // Lấy Top 3 chiến mã hoàn thành đua an toàn
            const healthyFinishers = finishedHorses.filter(h => !h.injured);
            const top3 = healthyFinishers.slice(0, 3);

            let html = '';

            // ── 1. BỤC VINH QUANG PODIUM CHO TOP 3 ───────────────────────
            if (top3.length > 0) {
                html += `
                <div style="text-align: center; margin-bottom: 25px;">
                    <span style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid #f59e0b; padding: 5px 18px; border-radius: 20px; font-weight: 800; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase;">
                        <i class="fa-solid fa-trophy"></i> Bục Vinh Quang Grand Derby
                    </span>
                    <h2 style="font-family: 'Cinzel', serif; color: #fff; font-size: 2rem; margin: 10px 0 5px; text-shadow: 0 0 15px rgba(245, 158, 11, 0.4);">
                        TOP 3 CHIẾN MÃ XUẤT SẮC NHẤT
                    </h2>
                    <p style="color: #94a3b8; font-size: 0.95rem; margin: 0;">Vinh danh các Uma Musume và Nài ngựa đã xuất sắc vượt qua giới hạn tốc độ</p>
                </div>

                <div class="racing-podium">
                `;

                // 2nd Place (Bạc)
                if (top3.length >= 2) {
                    const h2 = top3[1];
                    const av2 = resolveAvatarUrl(h2.avatar);
                    html += `
                    <div class="podium-card second">
                        <div class="podium-badge-rank">🥈</div>
                        <div style="font-size: 0.78rem; font-weight: 800; color: #cbd5e1; text-transform: uppercase; margin-bottom: 6px;">Á Quân 1 (2nd)</div>
                        <div class="podium-avatar-wrap">
                            <img src="${av2}" alt="${h2.name}" class="podium-avatar-img" onerror="this.onerror=null; this.src='<?= BASE_URL ?>/media/img/placeholder.png';">
                        </div>
                        <div class="podium-horse-name">[${h2.id + 1}] ${h2.name}</div>
                        <div class="podium-jockey"><i class="fa-solid fa-user-tie"></i> Nài: <strong>${h2.jockey || 'Chưa định'}</strong></div>
                        <div class="podium-time">${(h2.finishTime / 1000).toFixed(2)}s</div>
                        <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 6px;">Tốc độ max: <strong style="color: #06b6d4;">${h2.maxSpeed.toFixed(2)}</strong></div>
                    </div>
                    `;
                }

                // 1st Place (Vàng - Quán Quân)
                if (top3.length >= 1) {
                    const h1 = top3[0];
                    const av1 = resolveAvatarUrl(h1.avatar);
                    html += `
                    <div class="podium-card first">
                        <div style="font-size: 1.6rem; margin-bottom: 2px;">👑</div>
                        <div class="podium-badge-rank">🥇</div>
                        <div style="font-size: 0.85rem; font-weight: 900; color: #fbbf24; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 1px;">CHAMPION (1st)</div>
                        <div class="podium-avatar-wrap">
                            <img src="${av1}" alt="${h1.name}" class="podium-avatar-img" onerror="this.onerror=null; this.src='<?= BASE_URL ?>/media/img/placeholder.png';">
                        </div>
                        <div class="podium-horse-name">[${h1.id + 1}] ${h1.name}</div>
                        <div class="podium-jockey"><i class="fa-solid fa-user-tie"></i> Nài: <strong>${h1.jockey || 'Chưa định'}</strong></div>
                        <div class="podium-time">${(h1.finishTime / 1000).toFixed(2)}s</div>
                        <div style="font-size: 0.8rem; color: #fbbf24; margin-top: 6px;">Tốc độ kỷ lục: <strong style="color: #fff;">${h1.maxSpeed.toFixed(2)}</strong></div>
                    </div>
                    `;
                }

                // 3rd Place (Đồng)
                if (top3.length >= 3) {
                    const h3 = top3[2];
                    const av3 = resolveAvatarUrl(h3.avatar);
                    html += `
                    <div class="podium-card third">
                        <div class="podium-badge-rank">🥉</div>
                        <div style="font-size: 0.78rem; font-weight: 800; color: #d97706; text-transform: uppercase; margin-bottom: 6px;">Á Quân 2 (3rd)</div>
                        <div class="podium-avatar-wrap">
                            <img src="${av3}" alt="${h3.name}" class="podium-avatar-img" onerror="this.onerror=null; this.src='<?= BASE_URL ?>/media/img/placeholder.png';">
                        </div>
                        <div class="podium-horse-name">[${h3.id + 1}] ${h3.name}</div>
                        <div class="podium-jockey"><i class="fa-solid fa-user-tie"></i> Nài: <strong>${h3.jockey || 'Chưa định'}</strong></div>
                        <div class="podium-time">${(h3.finishTime / 1000).toFixed(2)}s</div>
                        <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 6px;">Tốc độ max: <strong style="color: #06b6d4;">${h3.maxSpeed.toFixed(2)}</strong></div>
                    </div>
                    `;
                }

                html += `</div>`;
            }

            // ── 2. BẢNG XẾP HẠNG CHI TIẾT DÃN RỘNG (KÈM AVATAR TỪNG UMA) ──
            html += `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; flex-wrap: wrap; gap: 10px;">
                <h3 style="margin: 0; color: #e6b980; font-size: 1.25rem; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-list-ol"></i> Bảng Kết Quả Chi Tiết Toàn Bộ Cuộc Đua
                </h3>
                <span style="font-size: 0.85rem; color: #94a3b8;">
                    Tổng số: <strong style="color: #fff;">${finishedHorses.length}</strong> chiến mã tham gia
                </span>
            </div>

            <div class="leaderboard-table-wrapper" style="max-height: 480px; overflow-y: auto;">
                <table class="leaderboard-table">
                    <thead>
                        <tr>
                            <th class="col-rank" style="width: 70px;">Hạng</th>
                            <th style="width: 60px; text-align: center;">Avatar</th>
                            <th>Chiến mã &amp; Phong cách</th>
                            <th>Nài ngựa (Jockey)</th>
                            <th>Thông số đua</th>
                            <th style="text-align: right;">Thời gian &amp; Kết quả</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            let rankNum = 0;
            for (let i = 0; i < finishedHorses.length; i++) {
                let h = finishedHorses[i];
                let medal = '-';
                let timeStr = '-';
                const injPct = (h.injuryRate <= 1.0 ? h.injuryRate * 100 : h.injuryRate).toFixed(1);
                const avUrl = resolveAvatarUrl(h.avatar);

                if (!h.injured) {
                    rankNum++;
                    timeStr = (h.finishTime / 1000).toFixed(2) + 's';
                    medal = rankNum === 1 ? '🥇 1' : (rankNum === 2 ? '🥈 2' : (rankNum === 3 ? '🥉 3' : `#${rankNum}`));
                } else {
                    medal = '🚑';
                    timeStr = `DNF`;
                }

                let jockeyDisplay = h.jockey ? h.jockey : '<span style="color:#666;">-</span>';
                let rowBg = h.injured ? 'background: rgba(255, 51, 102, 0.08);' : (rankNum <= 3 ? 'background: rgba(245, 158, 11, 0.05);' : '');

                html += `
                    <tr style="${rowBg}">
                        <td class="col-rank" style="font-weight: 800; font-size: ${rankNum <= 3 ? '1.25rem' : '1rem'}; color: ${rankNum === 1 ? '#fbbf24' : (rankNum === 2 ? '#cbd5e1' : (rankNum === 3 ? '#d97706' : '#94a3b8'))};">
                            ${medal}
                        </td>
                        <td style="text-align: center;">
                            <div class="table-uma-avatar" style="${rankNum === 1 ? 'border-color: #f59e0b;' : (h.injured ? 'border-color: #ff3366;' : '')}">
                                <img src="${avUrl}" alt="${h.name}" onerror="this.onerror=null; this.src='<?= BASE_URL ?>/media/img/placeholder.png';">
                            </div>
                        </td>
                        <td class="col-horse">
                            <strong style="font-size: 1.05rem; color: ${h.injured ? '#ff3366' : '#fff'};">[${h.id + 1}] ${h.name}</strong>
                            <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 2px;">
                                Style: <span style="color: #38bdf8; font-weight: 700;">${h.runningStyle || 'Leader'}</span>
                                ${h.injured ? ' | <span style="color: #ff3366; font-weight: 800;"><i class="fa-solid fa-triangle-exclamation"></i> Chấn thương quá tải</span>' : ''}
                            </div>
                        </td>
                        <td class="col-jockey">
                            <i class="fa-solid fa-user-tie" style="color: var(--gold-primary); font-size: 0.8rem;"></i> ${jockeyDisplay}
                        </td>
                        <td>
                            <div style="font-size: 0.82rem; color: #cbd5e1;">
                                Tốc độ: <span style="color: #38bdf8; font-weight: bold;">${h.minSpeed.toFixed(2)}</span> → <span style="color: #f59e0b; font-weight: bold;">${h.maxSpeed.toFixed(2)}</span>
                            </div>
                            <div style="font-size: 0.76rem; color: #8b949e; margin-top: 2px;">
                                Rủi ro: <span style="color: ${injPct > 8 ? '#ff3366' : (injPct > 4 ? '#f59e0b' : '#10b981')}; font-weight: bold;">${injPct}%</span> 
                                | Đổi nhịp: ${h.speedChangeMs}ms
                            </div>
                        </td>
                        <td class="col-time" style="text-align: right;">
                            <div style="font-size: 1.15rem; font-weight: 800; color: ${h.injured ? '#ff3366' : '#38bdf8'}; font-family: monospace;">
                                ${timeStr}
                            </div>
                            ${h.injured ? `<div style="font-size: 0.75rem; color: #ff3366;">Cách đích ${Math.round(h.distanceToFinish)}px</div>` : `<span style="font-size: 0.72rem; background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 2px 6px; border-radius: 4px;">Cán đích</span>`}
                        </td>
                    </tr>
                `;
            }

            html += `
                    </tbody>
                </table>
            </div>
            `;

            leaderboardDiv.innerHTML = html;
            btnReset.disabled = false;
        }
    });
</script>

<?php include '../footer.php'; ?>