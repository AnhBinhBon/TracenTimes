<?php
// ====================================================================
//  modules/admin/char_form.php — Form Thêm Mới / Chỉnh Sửa Hồ Sơ Nhân Vật
// ====================================================================

require_once __DIR__ . '/../../config/app.php';

$charService = new CharacterService();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$charItem = null;
$isEdit = false;

if ($id > 0) {
    $charItem = $charService->getDetail($id);
    if ($charItem) {
        $isEdit = true;
    }
}

// Xử lý POST lưu hồ sơ nhân vật
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name'          => $_POST['name'] ?? '',
        'japanese_name' => $_POST['japanese_name'] ?? '',
        'nickname'      => $_POST['nickname'] ?? '',
        'image_url'     => $_POST['image_url'] ?? '',
        'avatar'        => $_POST['avatar'] ?? '',
        'banner_image'  => $_POST['banner_image'] ?? '',
        'birthday'      => $_POST['birthday'] ?? '',
        'height'        => $_POST['height'] ?? '',
        'weight'        => $_POST['weight'] ?? '',
        'three_sizes'   => $_POST['three_sizes'] ?? '',
        'voice_actor'   => $_POST['voice_actor'] ?? '',
        'class_grade'   => $_POST['class_grade'] ?? '',
        'profile'       => $_POST['profile'] ?? '',
        'personality'   => $_POST['personality'] ?? '',
        'detail'        => $_POST['detail'] ?? '',
        'source_url'    => $_POST['source_url'] ?? '',
        'status'        => $_POST['status'] ?? 'active'
    ];

    $result = $charService->save($data, $isEdit ? $id : null);
    if ($result['success']) {
        Helper::setFlash('success', $result['message']);
        header('Location: ' . Helper::url('modules/admin/char_manage.php'));
        exit();
    } else {
        $error = $result['message'];
    }
}

$pageTitle = $isEdit ? 'Chỉnh Sửa Hồ Sơ: ' . htmlspecialchars($charItem['name']) : 'Thêm Nhân Vật Mới';
$pageIcon  = $isEdit ? 'fa-solid fa-user-pen' : 'fa-solid fa-user-plus';

include __DIR__ . '/../../includes/admin_header.php';
?>

<div class="admin-box" style="max-width:960px; margin:0 auto;">
    <div class="admin-box__header">
        <h2 class="admin-box__title">
            <i class="<?= $pageIcon ?>"></i> <?= $pageTitle ?>
        </h2>
        <div style="display:flex; gap:10px;">
            <?php if ($isEdit): ?>
                <a href="<?= Helper::url('modules/admin/stats_form.php?char_id=' . $id) ?>" class="btn-adm btn-adm-warning btn-adm-sm">
                    <i class="fas fa-bolt"></i> Chỉnh chỉ số đua
                </a>
            <?php endif; ?>
            <a href="<?= Helper::url('modules/admin/char_manage.php') ?>" class="btn-adm btn-adm-secondary btn-adm-sm">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="admin-alert admin-alert-danger">
            <i class="fas fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <!-- Tên và danh hiệu -->
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Tên nhân vật (Tiếng Anh / Romaji) <span style="color:red;">*</span></label>
                <input type="text" name="name" class="form-input" required 
                       placeholder="Ví dụ: Special Week, Tokai Teio..."
                       value="<?= htmlspecialchars($_POST['name'] ?? $charItem['name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Tên tiếng Nhật (Kanji / Katakana)</label>
                <input type="text" name="japanese_name" class="form-input" 
                       placeholder="Ví dụ: スペシャルウィーク..."
                       value="<?= htmlspecialchars($_POST['japanese_name'] ?? $charItem['japanese_name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Danh hiệu / Biệt danh (Nickname)</label>
                <input type="text" name="nickname" class="form-input" 
                       placeholder="Ví dụ: Ngôi sao Hokkaido, Bất kham & Hoang dã..."
                       value="<?= htmlspecialchars($_POST['nickname'] ?? $charItem['nickname'] ?? '') ?>">
            </div>
        </div>

        <!-- Ảnh & Trạng thái -->
        <div style="background:rgba(255,255,255,0.02); border:1px solid var(--adm-border); padding:18px; border-radius:10px; margin-bottom:20px;">
            <h3 style="margin-top:0; color:var(--adm-accent-cyan); font-size:1.05rem; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-image"></i> Hình Ảnh Đại Diện & Trạng Thái
            </h3>

            <div style="display:grid; grid-template-columns:1fr 140px; gap:20px; align-items:start;">
                <div style="display:flex; flex-direction:column; gap:16px;">
                    <!-- 1. Thuộc tính đường link lấy ảnh Uma (Ưu tiên số 1) -->
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="display:flex; justify-content:space-between; align-items:center;">
                            <span><i class="fas fa-link" style="color:var(--adm-accent);"></i> Link ảnh Uma trực tuyến (Image URL)</span>
                            <span style="font-size:0.75rem; background:rgba(37,99,235,0.25); color:#60a5fa; padding:2px 8px; border-radius:4px; font-weight:600;">Ưu tiên số 1</span>
                        </label>
                        <input type="text" name="image_url" id="input-image-url" class="form-input" 
                               placeholder="https://example.com/uma_image.png hoặc link ảnh trực tuyến..."
                               value="<?= htmlspecialchars($_POST['image_url'] ?? $charItem['image_url'] ?? '') ?>">
                        <small style="color:var(--adm-text-muted); font-size:0.75rem; display:block; margin-top:4px;">
                            <i class="fas fa-info-circle"></i> Hệ thống luôn ưu tiên sử dụng link này. Nếu để trống sẽ chuyển sang đường dẫn cục bộ bên dưới hoặc ảnh null exception.
                        </small>
                    </div>

                    <!-- 2. Đường dẫn cục bộ (Avatar fallback - Cho phép để trống cho đối tượng test) -->
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="display:flex; justify-content:space-between; align-items:center;">
                            <span><i class="fas fa-folder-open" style="color:#f59e0b;"></i> Đường dẫn ảnh nội bộ (Avatar Fallback)</span>
                            <span style="font-size:0.75rem; background:rgba(255,255,255,0.06); color:var(--adm-text-muted); padding:2px 8px; border-radius:4px;">Tùy chọn / Test Object</span>
                        </label>
                        <input type="text" name="avatar" id="input-avatar" class="form-input"
                               placeholder="media/img/characters/ten_nhan_vat.png (Để trống nếu dùng cho kiểm thử)"
                               value="<?= htmlspecialchars($_POST['avatar'] ?? $charItem['avatar'] ?? '') ?>">
                        <small style="color:var(--adm-text-muted); font-size:0.75rem; display:block; margin-top:4px;">
                            <i class="fas fa-flask"></i> Cho phép để rỗng để kiểm thử đối tượng test (sẽ tự động kích hoạt xử lý ảnh Null Exception).
                        </small>
                    </div>
                </div>

                <!-- Live Preview Box -->
                <div style="background:rgba(0,0,0,0.3); border:1px solid var(--adm-border); border-radius:8px; padding:12px; text-align:center;">
                    <div style="font-size:0.75rem; color:var(--adm-text-muted); margin-bottom:8px; font-weight:600;">Xem Trước Ảnh</div>
                    <?php 
                    $previewAvatar = Helper::characterAvatar($charItem ?? null); 
                    ?>
                    <img id="img-preview" src="<?= htmlspecialchars($previewAvatar) ?>" alt="Preview" 
                         style="width:96px; height:96px; object-fit:cover; border-radius:12px; border:2px solid var(--adm-accent); background:#111; margin:0 auto; display:block;"
                         onerror="this.src='<?= Helper::asset('media/img/placeholder.png') ?>'">
                    <div id="preview-status" style="font-size:0.7rem; color:#10b981; margin-top:8px; word-break:break-word;">
                        <?= !empty($charItem['image_url'] ?? '') ? 'Link URL' : (!empty($charItem['avatar'] ?? '') ? 'File cục bộ' : 'Null Exception') ?>
                    </div>
                </div>
            </div>

            <div class="form-grid" style="margin-top:16px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Ảnh bìa / Banner ngang (Nếu có)</label>
                    <input type="text" name="banner_image" class="form-input" 
                           placeholder="media/img/galleries/ten_anh.png"
                           value="<?= htmlspecialchars($_POST['banner_image'] ?? $charItem['banner_image'] ?? '') ?>">
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Trạng thái hiển thị</label>
                    <select name="status" class="form-select">
                        <?php $statusVal = $_POST['status'] ?? $charItem['status'] ?? 'active'; ?>
                        <option value="active" <?= ($statusVal === 'active') ? 'selected' : '' ?>>Hiển thị (Active)</option>
                        <option value="hidden" <?= ($statusVal === 'hidden') ? 'selected' : '' ?>>Ẩn (Hidden)</option>
                    </select>
                </div>
            </div>
        </div>

        <script>
            // Live Preview Real-time update script
            (function() {
                const imgUrlInput = document.getElementById('input-image-url');
                const avatarInput = document.getElementById('input-avatar');
                const previewImg = document.getElementById('img-preview');
                const previewStatus = document.getElementById('preview-status');
                const placeholderUrl = '<?= Helper::asset('media/img/placeholder.png') ?>';
                const baseUrl = '<?= BASE_URL ?>';

                function updatePreview() {
                    const imgUrl = (imgUrlInput.value || '').trim();
                    const avatar = (avatarInput.value || '').trim();

                    if (imgUrl) {
                        previewImg.src = imgUrl;
                        previewStatus.textContent = 'Dùng Link URL';
                        previewStatus.style.color = '#38bdf8';
                    } else if (avatar) {
                        const src = (avatar.startsWith('http://') || avatar.startsWith('https://')) ? avatar : (baseUrl + '/' + avatar.replace(/^\/+/, ''));
                        previewImg.src = src;
                        previewStatus.textContent = 'Dùng File cục bộ';
                        previewStatus.style.color = '#10b981';
                    } else {
                        previewImg.src = placeholderUrl;
                        previewStatus.textContent = 'Null Exception';
                        previewStatus.style.color = '#f59e0b';
                    }
                }

                if (imgUrlInput && avatarInput && previewImg) {
                    imgUrlInput.addEventListener('input', updatePreview);
                    avatarInput.addEventListener('input', updatePreview);
                }
            })();
        </script>

        <!-- Thông số cá nhân -->
        <div style="background:rgba(255,255,255,0.02); border:1px solid var(--adm-border); padding:18px; border-radius:10px; margin-bottom:20px;">
            <h3 style="margin-top:0; color:var(--adm-accent-cyan); font-size:1.05rem; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-id-card"></i> Thông Tin Sinh Học & Học Viện Tracen
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Diễn viên lồng tiếng (Voice Actor / CV)</label>
                    <input type="text" name="voice_actor" class="form-input" placeholder="Ví dụ: Azumi Waki, Machico..."
                           value="<?= htmlspecialchars($_POST['voice_actor'] ?? $charItem['voice_actor'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Ngày sinh (Birthday)</label>
                    <input type="text" name="birthday" class="form-input" placeholder="Ví dụ: May 2, April 20..."
                           value="<?= htmlspecialchars($_POST['birthday'] ?? $charItem['birthday'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Chiều cao (Height)</label>
                    <input type="text" name="height" class="form-input" placeholder="Ví dụ: 158cm..."
                           value="<?= htmlspecialchars($_POST['height'] ?? $charItem['height'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Cân nặng (Weight)</label>
                    <input type="text" name="weight" class="form-input" placeholder="Ví dụ: Không thay đổi, Dao động nhẹ..."
                           value="<?= htmlspecialchars($_POST['weight'] ?? $charItem['weight'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Số đo ba vòng (Three Sizes)</label>
                    <input type="text" name="three_sizes" class="form-input" placeholder="Ví dụ: B87 W57 H81..."
                           value="<?= htmlspecialchars($_POST['three_sizes'] ?? $charItem['three_sizes'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Khối lớp / Cấp bậc (Class Grade)</label>
                    <input type="text" name="class_grade" class="form-input" placeholder="Ví dụ: Trung học phổ thông, Trung học cơ sở..."
                           value="<?= htmlspecialchars($_POST['class_grade'] ?? $charItem['class_grade'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Tiểu sử & Tính cách (Rich Text Editors) -->
        <div class="form-group">
            <label class="form-label"><i class="fas fa-feather-pointed" style="color:var(--adm-pink);"></i> Tiểu sử tóm tắt (Profile)</label>
            <div class="rich-editor-wrap">
                <div class="rich-toolbar">
                    <button type="button" class="rich-btn" title="In đậm" onclick="formatRichText('char_profile', '<b>', '</b>')"><i class="fas fa-bold"></i></button>
                    <button type="button" class="rich-btn" title="In nghiêng" onclick="formatRichText('char_profile', '<i>', '</i>')"><i class="fas fa-italic"></i></button>
                    <button type="button" class="rich-btn" title="Gạch chân" onclick="formatRichText('char_profile', '<u>', '</u>')"><i class="fas fa-underline"></i></button>
                    <span class="rich-btn-sep"></span>
                    <button type="button" class="rich-btn" title="Tiêu đề 3" onclick="formatRichText('char_profile', '<h3>', '</h3>')"><i class="fas fa-heading"></i>3</button>
                    <button type="button" class="rich-btn" title="Danh sách" onclick="formatRichText('char_profile', '• ', '')"><i class="fas fa-list-ul"></i></button>
                    <button type="button" class="rich-btn" title="Trích dẫn" onclick="formatRichText('char_profile', '<blockquote>', '</blockquote>')"><i class="fas fa-quote-left"></i></button>
                    <span class="rich-btn-sep"></span>
                    <button type="button" class="rich-btn" title="Xóa định dạng" onclick="clearRichText('char_profile')"><i class="fas fa-eraser"></i></button>
                </div>
                <textarea id="char_profile" name="profile" class="rich-textarea" rows="4" placeholder="Giới thiệu xuất thân, ước mơ của cô gái ngựa..."><?= htmlspecialchars($_POST['profile'] ?? $charItem['profile'] ?? '') ?></textarea>
                <div class="rich-hint">
                    <span><i class="fas fa-circle-info"></i> Tự do định dạng văn bản (hỗ trợ HTML/BBCode)</span>
                    <span class="rich-hint-tag">Rich-Text Editor</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label"><i class="fas fa-heart" style="color:var(--adm-pink);"></i> Tính cách đặc trưng (Personality)</label>
            <div class="rich-editor-wrap">
                <div class="rich-toolbar">
                    <button type="button" class="rich-btn" title="In đậm" onclick="formatRichText('char_personality', '<b>', '</b>')"><i class="fas fa-bold"></i></button>
                    <button type="button" class="rich-btn" title="In nghiêng" onclick="formatRichText('char_personality', '<i>', '</i>')"><i class="fas fa-italic"></i></button>
                    <button type="button" class="rich-btn" title="Gạch chân" onclick="formatRichText('char_personality', '<u>', '</u>')"><i class="fas fa-underline"></i></button>
                    <span class="rich-btn-sep"></span>
                    <button type="button" class="rich-btn" title="Tiêu đề 3" onclick="formatRichText('char_personality', '<h3>', '</h3>')"><i class="fas fa-heading"></i>3</button>
                    <button type="button" class="rich-btn" title="Danh sách" onclick="formatRichText('char_personality', '• ', '')"><i class="fas fa-list-ul"></i></button>
                    <button type="button" class="rich-btn" title="Trích dẫn" onclick="formatRichText('char_personality', '<blockquote>', '</blockquote>')"><i class="fas fa-quote-left"></i></button>
                    <span class="rich-btn-sep"></span>
                    <button type="button" class="rich-btn" title="Xóa định dạng" onclick="clearRichText('char_personality')"><i class="fas fa-eraser"></i></button>
                </div>
                <textarea id="char_personality" name="personality" class="rich-textarea" rows="4" placeholder="Miêu tả tính cách, thói quen đặc biệt ngoài đời và trên sân tập..."><?= htmlspecialchars($_POST['personality'] ?? $charItem['personality'] ?? '') ?></textarea>
                <div class="rich-hint">
                    <span><i class="fas fa-circle-info"></i> Tự do định dạng văn bản (hỗ trợ HTML/BBCode)</span>
                    <span class="rich-hint-tag">Rich-Text Editor</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label"><i class="fas fa-book-open" style="color:var(--adm-pink);"></i> Cốt truyện chi tiết mở rộng (Detail)</label>
            <div class="rich-editor-wrap">
                <div class="rich-toolbar">
                    <button type="button" class="rich-btn" title="In đậm" onclick="formatRichText('char_detail', '<b>', '</b>')"><i class="fas fa-bold"></i></button>
                    <button type="button" class="rich-btn" title="In nghiêng" onclick="formatRichText('char_detail', '<i>', '</i>')"><i class="fas fa-italic"></i></button>
                    <button type="button" class="rich-btn" title="Gạch chân" onclick="formatRichText('char_detail', '<u>', '</u>')"><i class="fas fa-underline"></i></button>
                    <span class="rich-btn-sep"></span>
                    <button type="button" class="rich-btn" title="Tiêu đề 3" onclick="formatRichText('char_detail', '<h3>', '</h3>')"><i class="fas fa-heading"></i>3</button>
                    <button type="button" class="rich-btn" title="Danh sách" onclick="formatRichText('char_detail', '• ', '')"><i class="fas fa-list-ul"></i></button>
                    <button type="button" class="rich-btn" title="Trích dẫn" onclick="formatRichText('char_detail', '<blockquote>', '</blockquote>')"><i class="fas fa-quote-left"></i></button>
                    <span class="rich-btn-sep"></span>
                    <button type="button" class="rich-btn" title="Xóa định dạng" onclick="clearRichText('char_detail')"><i class="fas fa-eraser"></i></button>
                </div>
                <textarea id="char_detail" name="detail" class="rich-textarea" rows="6" placeholder="Chi tiết về hành trình thi đấu, các khoảnh khắc cảm xúc và di sản trên đường đua..."><?= htmlspecialchars($_POST['detail'] ?? $charItem['detail'] ?? '') ?></textarea>
                <div class="rich-hint">
                    <span><i class="fas fa-circle-info"></i> Tự do định dạng văn bản (hỗ trợ HTML/BBCode)</span>
                    <span class="rich-hint-tag">Rich-Text Editor</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Nguồn tham khảo (Source URL)</label>
            <input type="text" name="source_url" class="form-input" placeholder="https://umamusume.fandom.com/wiki/..."
                   value="<?= htmlspecialchars($_POST['source_url'] ?? $charItem['source_url'] ?? '') ?>">
        </div>

        <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
            <a href="<?= Helper::url('modules/admin/char_manage.php') ?>" class="btn-adm btn-adm-secondary">Hủy bỏ</a>
            <button type="submit" class="btn-adm btn-adm-primary">
                <i class="fas fa-save"></i> <?= $isEdit ? 'Lưu Cập Nhật Hồ Sơ' : 'Tạo Nhân Vật' ?>
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>
