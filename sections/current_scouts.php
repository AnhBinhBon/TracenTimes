<?php
// ====================================================================
//  sections/current_scouts.php — Khối Gacha & Scout cập nhật từ Umamusu Wiki
//  Nguồn tham chiếu: https://umamusu.wiki/Main_Page
// ====================================================================
?>
<section id="scouts" class="section" style="background: radial-gradient(circle at 50% 20%, rgba(255, 75, 130, 0.08) 0%, rgba(12, 14, 23, 1) 75%); border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); padding: 70px 0;">
    <div class="container">
        
        <!-- Wiki Lore Quote -->
        <div style="max-width: 860px; margin: 0 auto 50px; text-align: center; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 18px; padding: 25px 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
            <i class="fas fa-quote-left" style="color: var(--color-pink); font-size: 1.5rem; margin-bottom: 10px; display: inline-block;"></i>
            <p style="font-style: italic; font-size: 1.05rem; line-height: 1.8; color: #e2e8f0; margin: 0 0 10px; font-family: 'Outfit', sans-serif;">
                "Umamusume. They are born to run. They inherit the names of horses from another world, whose histories were sometimes tragic and sometimes wonderful, and run ever forward. That is their fate. No one knows how the races waiting in the futures of these Umamusume will end. But they will continue to run, aiming only toward the goal in front of them."
            </p>
            <div style="font-size: 0.85rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                — Trích từ Tuyên Ngôn Huyền Thoại (Official Umamusume Lore)
            </div>
        </div>

        <div class="section-header">
            <span class="section-label" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; border-color: rgba(245, 158, 11, 0.4);">
                LIVE SCOUTS
            </span>
            <h2 class="section-title">Current Game Scouts &amp; Banners</h2>
            <p class="section-subtitle">Dữ liệu sự kiện tuyển trạch và biểu ngữ gacha độc quyền phát hành trên hệ thống TracenTimes.</p>
        </div>

        <!-- Banners Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-bottom: 35px;">
            
            <!-- Banner 1: Global/English Server Pretty Derby Scout -->
            <div style="background: #141824; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 24px; display: flex; flex-direction: column; transition: transform 0.25s, border-color 0.25s;"
                 onmouseover="this.style.transform='translateY(-4px)'; this.style.borderColor='var(--color-pink)';"
                 onmouseout="this.style.transform='none'; this.style.borderColor='rgba(255,255,255,0.08)';">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                    <span style="background: rgba(52, 152, 219, 0.2); color: #3498db; border: 1px solid #3498db; font-size: 0.75rem; font-weight: 800; padding: 3px 10px; border-radius: 12px;">
                        <i class="fas fa-globe"></i> GLOBAL / EN SERVER
                    </span>
                    <span style="font-size: 0.8rem; color: #f59e0b; font-weight: 600;">
                        <i class="fas fa-hourglass-half"></i> Active
                    </span>
                </div>

                <h3 style="font-size: 1.25rem; font-weight: 700; color: #fff; margin: 0 0 10px;">
                    Spotlight Pretty Derby Scout
                </h3>
                <p style="color: #94a3b8; font-size: 0.9rem; line-height: 1.5; margin: 0 0 16px;">
                    Tuyển trạch nhân vật 3 sao đặc biệt với tỷ lệ xuất hiện tăng cao.
                </p>

                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 14px; margin-top: auto;">
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Pickups Tăng Tỷ Lệ:</div>
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-weight: 700; color: #fbcfe8;">
                            ★3 [Heroic Author] Zenno Rob Roy
                        </span>
                        <span style="background: rgba(255,75,130,0.2); color: var(--color-pink); font-size: 0.8rem; font-weight: bold; padding: 2px 8px; border-radius: 8px;">
                            0.75%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Banner 2: Global/English Support Card Scout -->
            <div style="background: #141824; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 24px; display: flex; flex-direction: column; transition: transform 0.25s, border-color 0.25s;"
                 onmouseover="this.style.transform='translateY(-4px)'; this.style.borderColor='#f59e0b';"
                 onmouseout="this.style.transform='none'; this.style.borderColor='rgba(255,255,255,0.08)';">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                    <span style="background: rgba(52, 152, 219, 0.2); color: #3498db; border: 1px solid #3498db; font-size: 0.75rem; font-weight: 800; padding: 3px 10px; border-radius: 12px;">
                        <i class="fas fa-globe"></i> GLOBAL / EN SERVER
                    </span>
                    <span style="font-size: 0.8rem; color: #f59e0b; font-weight: 600;">
                        <i class="fas fa-hourglass-half"></i> Active
                    </span>
                </div>

                <h3 style="font-size: 1.25rem; font-weight: 700; color: #fff; margin: 0 0 10px;">
                    Spotlight Support Card Scout
                </h3>
                <p style="color: #94a3b8; font-size: 0.9rem; line-height: 1.5; margin: 0 0 16px;">
                    Thẻ hỗ trợ huấn luyện cao cấp gia tăng chỉ số vượt trội trên đường đua.
                </p>

                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 14px; margin-top: auto; display: flex; flex-direction: column; gap: 8px;">
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Pickups Tăng Tỷ Lệ:</div>
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.88rem;">
                        <span style="font-weight: 700; color: #fde68a;">
                            SSR [From the Ground Up] Light Hello (Pal)
                        </span>
                        <span style="background: rgba(245,158,11,0.2); color: #f59e0b; font-size: 0.78rem; font-weight: bold; padding: 2px 6px; border-radius: 6px;">0.75%</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.88rem;">
                        <span style="font-weight: 700; color: #a7f3d0;">
                            SSR [Party Formation] Mayano Top Gun (Speed)
                        </span>
                        <span style="background: rgba(245,158,11,0.2); color: #f59e0b; font-size: 0.78rem; font-weight: bold; padding: 2px 6px; border-radius: 6px;">0.75%</span>
                    </div>
                </div>
            </div>

            <!-- Banner 3: Japanese Server Special Pickups -->
            <div style="background: #141824; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 24px; display: flex; flex-direction: column; transition: transform 0.25s, border-color 0.25s;"
                 onmouseover="this.style.transform='translateY(-4px)'; this.style.borderColor='#10b981';"
                 onmouseout="this.style.transform='none'; this.style.borderColor='rgba(255,255,255,0.08)';">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                    <span style="background: rgba(231, 76, 60, 0.2); color: #e74c3c; border: 1px solid #e74c3c; font-size: 0.75rem; font-weight: 800; padding: 3px 10px; border-radius: 12px;">
                        <i class="fas fa-torii-gate"></i> JAPAN SERVER (JP)
                    </span>
                    <span style="font-size: 0.8rem; color: #10b981; font-weight: 600;">
                        <i class="fas fa-check-circle"></i> Twinkle Collection
                    </span>
                </div>

                <h3 style="font-size: 1.25rem; font-weight: 700; color: #fff; margin: 0 0 10px;">
                    ピックアップ プリティーダービーガチャ
                </h3>
                <p style="color: #94a3b8; font-size: 0.9rem; line-height: 1.5; margin: 0 0 16px;">
                    Banner Nhật Bản mới nhất quy tụ Hoàng đế Symboli Rudolf &amp; Air Groove.
                </p>

                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 14px; margin-top: auto; display: flex; flex-direction: column; gap: 8px;">
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Pickups Nổi Bật:</div>
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.88rem;">
                        <span style="font-weight: 700; color: #bae6fd;">
                            ★3 [Adamant Sovereign] Symboli Rudolf
                        </span>
                        <span style="background: rgba(16,185,129,0.2); color: #10b981; font-size: 0.78rem; font-weight: bold; padding: 2px 6px; border-radius: 6px;">0.75%</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.88rem;">
                        <span style="font-weight: 700; color: #e9d5ff;">
                            ★3 [悠久なるヘカテイア] Air Groove
                        </span>
                        <span style="background: rgba(16,185,129,0.2); color: #10b981; font-size: 0.78rem; font-weight: bold; padding: 2px 6px; border-radius: 6px;">0.75%</span>
                    </div>
                </div>
            </div>

        </div>

        <div style="text-align: center;">
            <a href="<?= Helper::url('pages/news.php') ?>" 
               style="display: inline-flex; align-items: center; gap: 8px; color: #f43f5e; font-weight: 700; text-decoration: none; padding: 12px 28px; background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.4); border-radius: 25px; transition: all 0.25s; box-shadow: 0 4px 15px rgba(244, 63, 94, 0.2);"
               onmouseover="this.style.background='#f43f5e'; this.style.color='#fff'; this.style.boxShadow='0 6px 20px rgba(244, 63, 94, 0.4)';"
               onmouseout="this.style.background='rgba(244, 63, 94, 0.12)'; this.style.color='#f43f5e'; this.style.boxShadow='0 4px 15px rgba(244, 63, 94, 0.2)';">
                <i class="fas fa-newspaper"></i> Xem Tất Cả Bản Tin Tuyển Trạch &amp; Sự Kiện TracenTimes <i class="fas fa-arrow-right" style="font-size:0.85rem;"></i>
            </a>
        </div>

    </div>
</section>
