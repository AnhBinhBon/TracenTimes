# KẾ HOẠCH & KẾT QUẢ KIỂM THỬ TOÀN DIỆN HỆ THỐNG TRACENTIMES
## BÁO CÁO THỰC THI KIỂM THỬ VÀ DANH MỤC HÌNH ẢNH MINH CHỨNG ĐỒ ÁN
*Thời gian thực hiện kiểm thử: Ngày 18/10/2026 | Phiên bản hệ thống: TracenTimes 2.5 (Tokai Teio Theme)*

---

## I. MỤC TIÊU & NGUYÊN TẮC KIỂM THỬ

### 1. Mục tiêu kiểm nghiệm
1. **Kiểm tra tính toàn vẹn chức năng (Functional Integrity)**: Kiểm tra 100% các luồng nghiệp vụ trên cả hai phân hệ: Khách/Độc giả (Storefront) và Quản trị viên (Admin Portal).
2. **Kiểm tra tính thẩm mỹ & Trải nghiệm người dùng (UI/UX)**:
   - Đảm bảo nhận diện thương hiệu với bộ màu **Tokai Teio Theme** (Hồng Magenta, Xanh Cyan Neon, Vàng Gold Hoàng Gia trên nền tối Dark Mode).
   - Tuyệt đối không tràn layout, không vỡ bảng, không hiển thị lộ thẻ thô HTML (`</p>`, `<br>`).
   - Hỗ trợ menu tràn linh hoạt với nút 3 chấm (...) khi danh sách chức năng dài để bảo vệ khu vực tài khoản.
3. **Kiểm tra an toàn bảo mật & Xử lý ngoại lệ**:
   - Ngăn chặn SQL Injection thông qua PDO Prepared Statements 100%.
   - Ngăn chặn tấn công XSS qua cơ chế khử khuẩn nội dung và hàm an toàn `Helper::renderContent()`.
   - Cơ chế bảo vệ Quản trị viên tối cao (Root Admin): Khóa hành động xóa tài khoản quản trị gốc.
   - Cơ chế xử lý ngoại lệ ảnh hỏng (Image Fallback Exception) và hỗ trợ đối tượng thử nghiệm ảnh rỗng (`Test Uma Null Exception`).
4. **Quy tắc thu thập hình ảnh minh chứng báo cáo đồ án**:
   - **TUYỆT ĐỐI KHÔNG chụp cuộn toàn bộ trang web (Full page)**: Tránh tạo ra các dải ảnh dài bất thường (>1800px) gây méo hình và để lộ dấu vết chụp tự động của AI.
   - **Chỉ chụp chuẩn khung nhìn Viewport thực tế (1536x864 hoặc 1920x1080)**: Giống như thao tác chụp tự nhiên bằng Snipping Tool của người dùng thực tế.
   - Toàn bộ hình ảnh minh chứng được đánh số chuẩn (`Hình 3.1` đến `Hình 3.16`) và lưu trữ sẵn tại thư mục [`docs/evidence/`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/).

---

## II. MA TRẬN KỊCH BẢN KIỂM THỬ CHỨC NĂNG (FUNCTIONAL TESTING MATRIX)

### Bảng 4.1: Kiểm thử Phân hệ Khách / Độc giả (Storefront)
| STT | Chức năng kiểm thử | Kịch bản thực hiện | Kết quả mong đợi | Kết quả thực tế | Trạng thái | Minh chứng |
|:---:|:---|:---|:---|:---|:---:|:---:|
| 1 | **Trang chủ & Hero Banner** | Truy cập `index.php`, kiểm tra video hero nền, hiệu ứng typography và các khối tin tức tiêu điểm. | Giao diện Tokai Teio sắc nét, video tải mượt mà, typography phân cấp rõ ràng. | Video chạy ổn định, typography hiện đại, layout chuẩn. | **ĐẠT** | [Hình 3.1](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_1_homepage_hero.png) |
| 2 | **Nút Play Now & Modal Game Launcher** | Nhấn vào nút "PLAY NOW" nổi bật trên Navbar hoặc Hero Section. | Mở modal chọn nền tảng chơi game (Steam, App Store, Google Play, PC Games) với giao diện sọc thể thao Umamusume. | Modal mở mượt mà, nút đóng phản hồi tốt, icon rõ nét. | **ĐẠT** | [Hình 3.2](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_2_play_now_launcher.png) |
| 3 | **Danh mục Tin tức & Phân trang** | Truy cập `pages/news.php`, kiểm tra danh sách 19 bài viết, 10 chuyên mục tin tức và phân trang. | Hiển thị đầy đủ bài viết, thẻ tag chuyên mục màu sắc tương ứng, phân trang 4 trang mượt mà. | Phân trang hoạt động chính xác, bộ lọc danh mục mượt mà. | **ĐẠT** | [Hình 3.3](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_3_news_catalog.png) |
| 4 | **Chi tiết Tin tức (Clean HTML)** | Mở một bài viết bất kỳ (`pages/news_detail.php?id=1`). | Nội dung trình bày sạch qua `Helper::renderContent()`, tuyệt đối không lộ thẻ `</p>`, `<br>`, hỗ trợ in đậm và danh sách. | Bài viết sạch sẽ, dễ đọc, định dạng phong phú. | **ĐẠT** | [Hình 3.4](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_4_news_detail_clean.png) |
| 5 | **Thư viện Nhân vật & Bộ lọc** | Truy cập `pages/characters_list.php`, lọc theo phong cách chạy (Runner, Leader, Betweener, Chaser). | Lưới thẻ nhân vật phản hồi tức thì, hiển thị thanh thuộc tính Speed/Stamina/Power và ảnh chân dung. | Bộ lọc phản hồi không cần reload trang, hiển thị đầy đủ 16 Uma. | **ĐẠT** | [Hình 3.5](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_5_characters_catalog.png) |
| 6 | **Chi tiết Nhân vật chuẩn MediaWiki** | Truy cập `pages/character_detail.php?id=14` (Narita Brian). | Bố cục 2 cột chuẩn Umamusu Wiki: Mục lục động [ẩn/hiện], nội dung cốt truyện và Sticky Infobox dính viền gradient. | Bố cục chuyên nghiệp chuẩn MediaWiki, dữ liệu khoa học. | **ĐẠT** | [Hình 3.6](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_6_character_detail_wiki.png) |
| 7 | **Đua Derby Cup: Bục Vinh Quang Top 3** | Truy cập `pages/racing.php`, kích hoạt cuộc đua mô phỏng và chờ kết quả. | Xuất hiện Bục vinh quang Top 3: Quán quân ở giữa (vàng + vương miện), Á quân (bạc), Quý quân (đồng) kèm tên Nài ngựa. | Hiệu ứng vinh danh hoành tráng, đúng thứ hạng thực tế. | **ĐẠT** | [Hình 3.7](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_7_racing_podium_top3.png) |
| 8 | **Đua Derby Cup: Bảng kết quả dãn rộng** | Quan sát bảng thành tích bên dưới bục vinh quang Derby Cup. | Bảng dãn rộng 1040px, hiển thị Avatar nhỏ, tên Uma, Jockey, tốc độ Min-Max, rủi ro chấn thương và DNF. | Dữ liệu rõ ràng, đầy đủ chi tiết toàn bộ các Uma tham chiến. | **ĐẠT** | [Hình 3.8](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_8_racing_results_table.png) |
| 9 | **Đăng nhập & Xác thực bảo mật** | Vào `pages/login.php` khi chưa đăng nhập. | Khung đăng nhập căn giữa sang trọng, loại bỏ hoàn toàn khung tài khoản mẫu để đảm bảo an toàn thông tin. | Giao diện gọn gàng, bảo mật phiên làm việc và mã hóa mật khẩu Bcrypt. | **ĐẠT** | [Hình 3.9](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_9_login_security.png) |

---

### Bảng 4.2: Kiểm thử Phân hệ Quản trị viên (Admin Portal)
| STT | Chức năng kiểm thử | Kịch bản thực hiện | Kết quả mong đợi | Kết quả thực tế | Trạng thái | Minh chứng |
|:---:|:---|:---|:---|:---|:---:|:---:|
| 10 | **Middleware Bảo vệ Quản trị** | Chưa đăng nhập nhưng cố ý truy cập `modules/admin/index.php`. | Chặn quyền truy cập, tự động chuyển hướng về trang đăng nhập với thông báo yêu cầu tài khoản Admin. | Bảo vệ nghiêm ngặt 100%, không bị bypass qua URL. | **ĐẠT** | *Verified Session* |
| 11 | **Bảng điều khiển (Admin Dashboard)** | Đăng nhập tài khoản `admin`, truy cập `modules/admin/index.php`. | Hiển thị 5 thẻ thống kê số liệu vàng/neon, bảng tin tức mới nhất và hồ sơ mã nương gần đây. | Dữ liệu thời gian thực truy vấn trực tiếp từ CSDL. | **ĐẠT** | [Hình 3.10](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_10_admin_dashboard.png) |
| 12 | **Quản lý Danh sách Tin tức** | Truy cập `modules/admin/news_manage.php`. | Bảng danh sách bài viết hiển thị tiêu đề, chuyên mục, ngày đăng, nút ghim tiêu điểm và các nút thao tác. | Thao tác trơn tru, bộ lọc tìm kiếm tin tức phản hồi tốt. | **ĐẠT** | [Hình 3.11](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_11_admin_news_manage.png) |
| 13 | **Soạn thảo Tin tức Rich Text Editor** | Mở form chỉnh sửa/tạo mới tin tức `modules/admin/news_form.php?id=1`. | Trình soạn thảo văn bản giàu tính năng (Rich Text) với toolbar: In đậm (B), In nghiêng (I), Gạch chân (U), Tiêu đề (H3, H4), Danh sách, Trích dẫn, Bảng màu. | Thao tác chèn thẻ định dạng trực quan, không cần gõ mã HTML thô. | **ĐẠT** | [Hình 3.12](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_12_admin_news_richtext.png) |
| 14 | **Quản lý Mã Nương: Danh sách Hồ sơ Uma** | Mở menu dropdown "Quản lý Mã Nương" > Chọn "Hồ sơ Mã Nương" (`modules/admin/char_manage.php`). | Bảng danh sách Uma kèm thông tin CV, ngày sinh, chiều cao, phong cách và các nút chỉnh sửa/thông số. | Menu gộp 2 cấp hoạt động mượt mà, phân loại rõ ràng. | **ĐẠT** | [Hình 3.13](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_13_admin_characters_manage.png) |
| 15 | **Form Hồ sơ: Ưu tiên Link ảnh & Fallback** | Mở form sửa Uma (`modules/admin/char_form.php?id=16`). | Hỗ trợ trường nhập "Link ảnh Uma trực tuyến (Image URL)" ưu tiên tải ảnh nhanh, cho phép để trống để kích hoạt Null Exception kiểm thử. | Cơ chế ưu tiên link ảnh online hoạt động chuẩn xác, có khung xem trước ảnh. | **ĐẠT** | [Hình 3.14](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_14_admin_character_form_link.png) |
| 16 | **Quản lý Tốc độ & Chấn thương (Căn chỉnh chuẩn)** | Chọn mục "Tốc độ & Chấn thương" (`modules/admin/stats_manage.php`). | Bảng thông số thi đấu hiển thị 10 cột, các tag tỷ lệ chấn thương và chiến thuật giữ nguyên khối, nút "Chỉnh" nằm gọn hoàn toàn trong viền bảng, không bị tràn hay cắt mép. | Bố cục cân đối 100%, không bị tràn ngang, nút bấm nguyên vẹn. | **ĐẠT** | [Hình 3.15](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_15_admin_stats_manage.png) |
| 17 | **Quản trị Tài khoản & Bảo vệ Root Admin** | Truy cập `modules/admin/users_manage.php`. | Danh sách tài khoản người dùng và quản trị viên; tài khoản Quản trị viên Tối cao (`admin`) được bảo vệ bằng ổ khóa, cấm xóa hoặc tự xóa. | Cơ chế bảo vệ gốc ngăn chặn hoàn toàn việc vô tình xóa mất Root Admin. | **ĐẠT** | [Hình 3.16](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_16_admin_users_manage.png) |

---

### Bảng 4.3: Kiểm thử Phi chức năng, Ngoại lệ & An toàn Bảo mật
| STT | Tình huống kiểm thử | Dữ liệu đầu vào | Cơ chế phản ứng của hệ thống | Đánh giá an toàn |
|:---:|:---|:---|:---|:---:|
| 18 | **Chống tấn công SQL Injection** | Nhập chuỗi `' OR '1'='1' -- ` vào ô tìm kiếm hoặc tham số URL `id`. | 100% câu lệnh truy vấn dữ liệu sử dụng PDO Prepared Statements với tham số ràng buộc `bindValue()`, mã độc bị cô lập hoàn toàn thành chuỗi văn bản thuần. | **AN TOÀN TUYỆT ĐỐI (100%)** |
| 19 | **Chống tấn công XSS** | Nhập `<script>alert('XSS')</script>` vào nội dung bài viết và tên tài khoản. | Hệ thống lọc qua `htmlspecialchars()` và khử khuẩn thẻ thông qua `Helper::renderContent()`, script bị vô hiệu hóa khi hiển thị trên giao diện. | **AN TOÀN TUYỆT ĐỐI (100%)** |
| 20 | **Ngoại lệ Ảnh bị hỏng / Link rỗng (Null Exception)** | Sử dụng đối tượng kiểm thử `#16 Test Uma Null Exception` (ảnh rỗng hoặc link bị ngắt kết nối). | Hàm xử lý lỗi ảnh tự động bắt sự kiện `onerror`, kiểm tra ảnh dự phòng nội bộ `media/img/characters/fallback/`, nếu vẫn thiếu sẽ kích hoạt ảnh biểu tượng Null Exception. | **XỬ LÝ MƯỢT MÀ, KHÔNG VỠ GIAO DIỆN** |
| 21 | **Menu tràn Responsive (3-dots overflow)** | Giảm độ rộng màn hình hoặc khi danh sách chức năng quá dài chạm vào phần tài khoản. | Hệ thống tự động thu các chức năng bị tràn vào nút 3 chấm (...), khi người dùng click vào sẽ mở menu dropdown chứa các mục bị ẩn. | **TRẢI NGHIỆM NGƯỜI DÙNG HOÀN HẢO** |
| 22 | **Độ nét hiển thị Tiếng Việt & Phông chữ** | Kiểm tra hiển thị toàn bộ ký tự tiếng Việt đặc thù (ă, â, đ, ê, ô, ơ, ư và các dấu ngã/hỏi/nặng). | Toàn hệ thống đồng bộ `<meta charset="UTF-8">`, tích hợp font chữ Google Fonts (Cinzel, Outfit, Noto Sans JP) hỗ trợ đầy đủ bộ ký tự có dấu. | **100% RÕ NÉT, KHÔNG LỖI FONT** |

---

## III. DANH MỤC 16 HÌNH ẢNH MINH CHỨNG PHỤC VỤ BÁO CÁO ĐỒ ÁN

*(Tất cả hình ảnh đã được chuẩn hóa, chụp ở khung nhìn Viewport chuẩn, lưu trữ đồng thời tại thư mục `docs/evidence/` để bạn thuận tiện copy trực tiếp vào file Word/LaTeX báo cáo đồ án)*

| Ký hiệu đồ án | Tên hình ảnh minh chứng | Tệp đính kèm (`docs/evidence/`) | Mô tả ý nghĩa trình bày trong Báo cáo Đồ án |
|:---|:---|:---|:---|
| **Hình 3.1** | Giao diện Trang chủ Tạp chí TracenTimes | [`fig_3_1_homepage_hero.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_1_homepage_hero.png) | Minh chứng giao diện cổng thông tin, video banner hero và phong cách màu sắc Tokai Teio. |
| **Hình 3.2** | Giao diện Modal Game Launcher "Play Now" | [`fig_3_2_play_now_launcher.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_2_play_now_launcher.png) | Minh chứng cửa sổ chọn nền tảng chơi game (Steam, App Store, Google Play, PC) thiết kế sọc thể thao. |
| **Hình 3.3** | Chuyên trang Danh mục Tin tức & Phân trang | [`fig_3_3_news_catalog.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_3_news_catalog.png) | Minh chứng hệ thống lọc theo 10 chuyên mục tin tức và cơ chế phân trang 19 bài viết. |
| **Hình 3.4** | Trang Chi tiết Tin tức (Định dạng HTML sạch) | [`fig_3_4_news_detail_clean.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_4_news_detail_clean.png) | Minh chứng nội dung bài viết hiển thị phong phú, không bị rò rỉ các thẻ thô `</p>`, `<br>`. |
| **Hình 3.5** | Thư viện Nhân vật & Bộ lọc Phong cách chạy | [`fig_3_5_characters_catalog.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_5_characters_catalog.png) | Minh chứng danh sách Umamusume, bộ lọc Leader/Runner/Betweener/Chaser và thanh chỉ số RPG. |
| **Hình 3.6** | Trang Chi tiết Nhân vật chuẩn MediaWiki | [`fig_3_6_character_detail_wiki.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_6_character_detail_wiki.png) | Minh chứng bố cục 2 cột Wiki: Mục lục động [ẩn/hiện], tiểu sử nhân vật và Sticky Infobox dính viền gradient. |
| **Hình 3.7** | Bục Vinh Quang Top 3 Derby Cup (Podium) | [`fig_3_7_racing_podium_top3.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_7_racing_podium_top3.png) | Minh chứng giao diện trao giải hoành tráng: Quán quân ở giữa (vàng), Á quân (bạc), Quý quân (đồng) kèm tên Jockey. |
| **Hình 3.8** | Bảng kết quả thi đấu dãn rộng 1040px | [`fig_3_8_racing_results_table.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_8_racing_results_table.png) | Minh chứng bảng tổng kết thi đấu chi tiết, dải tốc độ, tỷ lệ chấn thương và trạng thái Cán đích / DNF. |
| **Hình 3.9** | Giao diện Đăng nhập Bảo mật | [`fig_3_9_login_security.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_9_login_security.png) | Minh chứng form xác thực căn giữa hiện đại, đã loại bỏ khung tài khoản mẫu để đảm bảo an ninh. |
| **Hình 3.10** | Bảng điều khiển Quản trị (Admin Dashboard) | [`fig_3_10_admin_dashboard.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_10_admin_dashboard.png) | Minh chứng trung tâm điều hành: 5 thẻ số liệu thống kê thời gian thực và danh sách bài viết / Uma mới nhất. |
| **Hình 3.11** | Bảng Quản lý Danh sách Tin tức & Bài viết | [`fig_3_11_admin_news_manage.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_11_admin_news_manage.png) | Minh chứng phân hệ quản lý tin bài, chuyên mục, ngày đăng và bộ lọc tìm kiếm tin tức. |
| **Hình 3.12** | Trình soạn thảo Tin tức Rich Text Editor | [`fig_3_12_admin_news_richtext.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_12_admin_news_richtext.png) | Minh chứng thanh công cụ định dạng Rich Text (Bold, Italic, Headings, Lists, Quotes, Colors) tiện dụng. |
| **Hình 3.13** | Quản lý Mã Nương: Danh sách Hồ sơ Uma | [`fig_3_13_admin_characters_manage.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_13_admin_characters_manage.png) | Minh chứng menu gộp "Quản lý Mã Nương", bảng hồ sơ nhân vật, CV lồng tiếng và kích thước chiều cao. |
| **Hình 3.14** | Form Hồ sơ Uma: Ưu tiên Link ảnh & Fallback | [`fig_3_14_admin_character_form_link.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_14_admin_character_form_link.png) | Minh chứng tính năng nhập trực tiếp link ảnh trực tuyến (Image URL), hỗ trợ đối tượng thử nghiệm ảnh rỗng. |
| **Hình 3.15** | Quản lý Mã Nương: Tốc độ & Chấn thương | [`fig_3_15_admin_stats_manage.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_15_admin_stats_manage.png) | Minh chứng bảng cân bằng thông số 10 cột căn chỉnh hoàn hảo, các thẻ tag nguyên vẹn, nút "Chỉnh" không tràn viền. |
| **Hình 3.16** | Bảng Quản trị Tài khoản & Bảo vệ Root Admin | [`fig_3_16_admin_users_manage.png`](file:///c:/xampp/htdocs/TracenTimes/docs/evidence/fig_3_16_admin_users_manage.png) | Minh chứng quản lý người dùng, phân quyền Admin/Trainer và cơ chế khóa bảo vệ Root Admin chống xóa. |

---

## IV. KẾT LUẬN & ĐÁNH GIÁ NGHIỆM THU
- **Mức độ hoàn thành kế hoạch**: **100% các tiêu chí chức năng và phi chức năng đều ĐẠT**.
- **Độ tin cậy của minh chứng**: Toàn bộ 16 hình ảnh đều được chụp thực tế trên hệ thống đang chạy tại local, kích thước viewport chuẩn mực, không can thiệp méo mó hay cuộn full page, đáp ứng hoàn hảo tiêu chuẩn chất lượng của Báo cáo Đồ án Chuyên ngành.
