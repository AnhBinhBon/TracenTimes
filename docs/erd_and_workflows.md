# SƠ ĐỒ THIẾT KẾ CƠ SỞ DỮ LIỆU & QUY TRÌNH NGHIỆP VỤ TRACENTIMES

Tài liệu này cung cấp toàn bộ sơ đồ ERD (Entity Relationship Diagram) và các luồng quy trình nghiệp vụ (Workflows) của hệ thống TracenTimes bằng định dạng mã nguồn **Mermaid**.

---

## 1. Sơ Đồ Quan Hệ Thực Thể (ERD - Entity Relationship Diagram)

```mermaid
erDiagram
    USERS {
        int id PK "Khóa chính, tự tăng"
        varchar username UK "Tên đăng nhập duy nhất"
        varchar password "Mật khẩu mã hóa Bcrypt"
        enum role "Phân quyền: admin | user"
        timestamp created_at "Thời gian tạo"
    }

    CHARACTERS {
        int id PK "Khóa chính, tự tăng"
        varchar name "Tên tiếng Anh (Vd: Special Week)"
        varchar japanese_name "Tên tiếng Nhật (Vd: スペシャルウィーク)"
        varchar nickname "Biệt danh / Danh hiệu"
        varchar avatar "Đường dẫn ảnh đại diện"
        varchar banner_image "Ảnh banner / Thẻ bài"
        varchar birthday "Ngày sinh"
        varchar height "Chiều cao"
        varchar weight "Cân nặng"
        varchar three_sizes "Số đo 3 vòng (B/W/H)"
        varchar voice_actor "Diễn viên lồng tiếng (Seiyuu)"
        varchar class_grade "Khối lớp / Ký túc xá"
        text profile "Tiểu sử tóm tắt"
        text personality "Tính cách đặc trưng"
        longtext detail "Cốt truyện & Sự nghiệp chi tiết"
        varchar source_url "Nguồn tham khảo Wiki"
        enum status "Trạng thái: active | hidden"
        timestamp created_at "Thời gian khởi tạo"
        timestamp updated_at "Thời gian cập nhật"
    }

    CHARACTER_STATS {
        int id PK "Khóa chính, tự tăng"
        int character_id FK,UK "Khóa ngoại 1-1 tham chiếu characters.id"
        float min_speed "Tốc độ tối thiểu trên đường đua"
        float max_speed "Tốc độ tối đa bứt phá"
        int speed_change_ms "Chu kỳ biến động tốc độ (ms)"
        float injury_rate "Tỷ lệ chấn thương (High-Risk High-Reward)"
        float event_rate "Tỷ lệ kích hoạt bứt phá tuyệt kỹ (%)"
        int base_speed "Điểm Speed (1-1200)"
        int stamina "Điểm Sức bền Stamina (1-1200)"
        int power "Điểm Sức mạnh Power (1-1200)"
        int guts "Điểm Ý chí kiên định Guts (1-1200)"
        int wit "Điểm Trí tuệ / Khéo léo Wit (1-1200)"
        enum running_style "Sở trường: Runner | Leader | Betweener | Chaser"
        varchar turf_aptitude "Độ thích nghi đường cỏ (A/B/C...)"
        varchar dirt_aptitude "Độ thích nghi đường cát (A/B/C...)"
        timestamp updated_at "Thời gian cập nhật chỉ số"
    }

    NEWS {
        int id PK "Khóa chính, tự tăng"
        varchar title "Tiêu đề bài viết tin tức"
        varchar category "Phân loại: Event | Campaign | Update | Race"
        varchar thumbnail "Ảnh thumbnail bài viết"
        text summary "Tóm tắt ngắn bài viết"
        longtext content "Nội dung bài viết chi tiết"
        text race_period_rewards "Thời gian & Phần thưởng sự kiện"
        text important_information "Lưu ý quan trọng từ học viện"
        int author_id FK "Khóa ngoại tham chiếu users.id"
        tinyint is_featured "Tin ghim nổi bật (0: Không, 1: Có)"
        datetime published_at "Thời gian phát hành tin"
        timestamp created_at "Thời gian tạo bài viết"
        timestamp updated_at "Thời gian cập nhật bài viết"
    }

    GALLERY {
        int id PK "Khóa chính, tự tăng"
        varchar image "Đường dẫn hình ảnh media biểu diễn"
    }

    CHARACTERS ||--|| CHARACTER_STATS : "sở hữu chỉ số đua 1-1 (ON DELETE CASCADE)"
    USERS ||--o{ NEWS : "đăng bài viết 1-N (ON DELETE SET NULL)"
```

---

## 2. Sơ Đồ Quy Trình Nghiệp Vụ Tổng Thể (Business Workflow)

```mermaid
flowchart TD
    Start(["Khách / Người dùng truy cập TracenTimes"]) --> Home["Trang chủ (index.php)"]

    subgraph Guest_Flow["1. LUỒNG NGƯỜI DÙNG & HUẤN LUYỆN VIÊN (User Flow)"]
        Home --> ViewNews["Xem Tin tức & Sự kiện Scout<br/>(pages/news.php)"]
        ViewNews --> NewsDetail["Chi tiết Tin tức<br/>(pages/news_detail.php)"]

        Home --> ViewChars["Xem Hồ sơ Nhân vật<br/>(pages/characters_list.php)"]
        ViewChars --> CharDetail["Chi tiết Nhân vật & Biểu đồ Radar<br/>(pages/character_detail.php)"]

        Home --> Racing["Mô phỏng Đua ngựa Grand Derby<br/>(pages/racing.php)"]
        Racing --> SelectUma["Huấn luyện viên chọn Umamusume"]
        SelectUma --> RaceEngine["Racing Engine khởi chạy<br/>- Tính biến động tốc độ (min_speed -> max_speed)<br/>- Kiểm tra tỷ lệ chấn thương (injury_rate)<br/>- Kích hoạt tuyệt kỹ bứt phá (event_rate)"]
        RaceEngine --> FinishRace["Cán đích & Trao cúp vô địch"]
    end

    subgraph Auth_Flow["2. LUỒNG XÁC THỰC & PHÂN QUYỀN (Auth Flow)"]
        Home --> Login["Trang Đăng nhập (pages/login.php)"]
        Login --> AuthCheck{"Xác thực Bcrypt & Vai trò?"}
        AuthCheck -->|Sai thông tin| LoginFail["Báo lỗi đăng nhập"]
        AuthCheck -->|User thường (Trainer)| UserSuccess["Đăng nhập thành công<br/>(Tham gia giải đua & Lưu hồ sơ)"]
        AuthCheck -->|Admin| AdminCenter["Chuyển hướng vào Admin Control Center"]
    end

    subgraph Admin_Flow["3. LUỒNG QUẢN TRỊ VIÊN 3 LỚP (Admin Management Flow)"]
        AdminCenter --> AdminDash["Admin Dashboard (modules/admin/index.php)<br/>Thống kê tổng quan Nhân vật, Tin tức, Tốc độ"]
        
        AdminDash --> ManageNews["Quản lý Tin tức (news_manage.php)<br/>Thêm / Sửa / Xóa bài viết"]
        AdminDash --> ManageChars["Quản lý Nhân vật (char_manage.php)<br/>Thêm / Sửa / Xóa hồ sơ"]
        AdminDash --> ManageStats["Quản lý Chỉ số Đua (stats_manage.php)<br/>Cân bằng tốc độ & Tỷ lệ chấn thương"]
        AdminDash --> WikiSync["Trung tâm Đồng bộ Wiki (wiki_sync.php)<br/>Một chạm cào dữ liệu từ umamusu.wiki"]
        
        WikiSync --> WikiSyncService["WikiSyncService<br/>Bóc tách HTML & JSON"]
        WikiSyncService --> SaveDB["Tự động lưu vào Database qua CharacterDAO & NewsDAO"]
    end
```

---

## 3. Sơ Đồ Luồng Xử Lý Kỹ Thuật Chuẩn 3 Lớp (3-Tier Sequence Diagram)

```mermaid
sequenceDiagram
    autonumber
    actor User as Client (Trình duyệt)
    participant PL as Presentation Layer (Pages / Views)
    participant BLL as Business Logic Layer (Services)
    participant DAL as Data Access Layer (DAOs)
    participant DB as Database (MySQL tracen_times)

    Note over User,DB: Ví dụ: Luồng Truy xuất Chi tiết Nhân vật & Chỉ số Đua
    User->>PL: GET /pages/character_detail.php?id=14
    PL->>BLL: CharacterService::getCharacterDetail(14)
    BLL->>BLL: Validate ID (số nguyên dương > 0)
    
    BLL->>DAL: CharacterDAO::getById(14)
    DAL->>DB: SELECT * FROM characters WHERE id = 14 (PDO Prepared)
    DB-->>DAL: Trả về Record thông tin hồ sơ
    DAL-->>BLL: Trả về Character Array/DTO

    BLL->>DAL: StatsDAO::getByCharacterId(14)
    DAL->>DB: SELECT * FROM character_stats WHERE character_id = 14
    DB-->>DAL: Trả về Record chỉ số (Speed, Stamina, Injury Rate...)
    DAL-->>BLL: Trả về Stats Array/DTO

    BLL->>BLL: Đánh giá Radar Chart, Kiểm tra cân bằng High Risk - High Reward
    BLL-->>PL: Trả về Dữ liệu tổng hợp đã xử lý
    PL->>PL: Render HTML + Xử lý Image Fallback (nếu thiếu ảnh)
    PL-->>User: Hiển thị giao diện mượt mà cho người dùng
```
