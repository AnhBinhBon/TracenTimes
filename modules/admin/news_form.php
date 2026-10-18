<?php
// ====================================================================
//  modules/admin/news_form.php — Form Thêm Mới / Chỉnh Sửa Bài Viết
// ====================================================================

require_once __DIR__ . '/../../config/app.php';

$newsService = new NewsService();
$categories = $newsService->getCategories();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$newsItem = null;
$isEdit = false;

if ($id > 0) {
    $newsItem = $newsService->getDetail($id);
    if ($newsItem) {
        $isEdit = true;
    }
}

// Xử lý POST lưu bài viết
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title'                 => $_POST['title'] ?? '',
        'category'              => $_POST['category'] ?? 'Event',
        'thumbnail'             => $_POST['thumbnail'] ?? '',
        'summary'               => $_POST['summary'] ?? '',
        'content'               => $_POST['content'] ?? '',
        'race_period_rewards'   => $_POST['race_period_rewards'] ?? '',
        'important_information' => $_POST['important_information'] ?? '',
        'is_featured'           => isset($_POST['is_featured']) ? 1 : 0,
        'published_at'          => !empty($_POST['published_at']) ? $_POST['published_at'] : date('Y-m-d H:i:s')
    ];

    $result = $newsService->save($data, $isEdit ? $id : null);
    if ($result['success']) {
        Helper::setFlash('success', $result['message']);
        header('Location: ' . Helper::url('modules/admin/news_manage.php'));
        exit();
    } else {
        $error = $result['message'];
    }
}

$pageTitle = $isEdit ? 'Chỉnh Sửa Tin Tức #' . $id : 'Đăng Tin Tức Mới';
$pageIcon  = $isEdit ? 'fa-solid fa-pen-to-square' : 'fa-solid fa-file-circle-plus';

include __DIR__ . '/../../includes/admin_header.php';
?>

<div class="admin-box" style="max-width:960px; margin:0 auto;">
    <div class="admin-box__header">
        <h2 class="admin-box__title">
            <i class="<?= $pageIcon ?>"></i> <?= $pageTitle ?>
        </h2>
        <a href="<?= Helper::url('modules/admin/news_manage.php') ?>" class="btn-adm btn-adm-secondary btn-adm-sm">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <?php if ($error): ?>
        <div class="admin-alert admin-alert-danger">
            <i class="fas fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label class="form-label">Tiêu đề bài viết <span style="color:red;">*</span></label>
            <input type="text" name="title" class="form-input" required 
                   placeholder="Nhập tiêu đề tin tức hoặc sự kiện..."
                   value="<?= htmlspecialchars($_POST['title'] ?? $newsItem['title'] ?? '') ?>">
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Chuyên mục <span style="color:red;">*</span></label>
                <select name="category" class="form-select">
                    <?php 
                    $selectedCat = $_POST['category'] ?? $newsItem['category'] ?? 'Event';
                    foreach ($categories as $cat): 
                    ?>
                        <option value="<?= $cat ?>" <?= ($selectedCat === $cat) ? 'selected' : '' ?>><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Thời gian đăng bài</label>
                <?php 
                $pubDate = $_POST['published_at'] ?? $newsItem['published_at'] ?? date('Y-m-d H:i');
                $pubFormatted = date('Y-m-d\TH:i', strtotime($pubDate));
                ?>
                <input type="datetime-local" name="published_at" class="form-input" value="<?= $pubFormatted ?>">
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Đường dẫn ảnh bìa (Thumbnail)</label>
                <input type="text" name="thumbnail" class="form-input" 
                       placeholder="media/img/galleries/media_gallery_1.png"
                       value="<?= htmlspecialchars($_POST['thumbnail'] ?? $newsItem['thumbnail'] ?? 'media/img/galleries/media_gallery_1.png') ?>">
            </div>

            <div class="form-group" style="justify-content:center;">
                <label class="form-label" style="display:flex; align-items:center; gap:8px; cursor:pointer; margin-top:25px;">
                    <input type="checkbox" name="is_featured" value="1" style="width:18px; height:18px;"
                           <?= (!empty($_POST['is_featured']) || !empty($newsItem['is_featured'])) ? 'checked' : '' ?>>
                    <span>⭐ Ghim bài viết làm <strong>Tin Tức Tiêu Điểm</strong> trên Trang Chủ</span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label"><i class="fas fa-align-left" style="color:var(--adm-pink);"></i> Tóm tắt ngắn (Summary)</label>
            <div class="rich-editor-wrap">
                <div class="rich-toolbar">
                    <button type="button" class="rich-btn" title="In đậm" onclick="formatRichText('news_summary', '<b>', '</b>')"><i class="fas fa-bold"></i></button>
                    <button type="button" class="rich-btn" title="In nghiêng" onclick="formatRichText('news_summary', '<i>', '</i>')"><i class="fas fa-italic"></i></button>
                    <span class="rich-btn-sep"></span>
                    <button type="button" class="rich-btn" title="Xóa định dạng" onclick="clearRichText('news_summary')"><i class="fas fa-eraser"></i></button>
                </div>
                <textarea id="news_summary" name="summary" class="rich-textarea" rows="2" style="min-height:80px;" placeholder="Tóm tắt ngắn gọn tin tức hiển thị ở danh sách bài viết..."><?= htmlspecialchars($_POST['summary'] ?? $newsItem['summary'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label"><i class="fas fa-newspaper" style="color:var(--adm-pink);"></i> Nội dung chi tiết (Content)</label>
            <div class="rich-editor-wrap">
                <div class="rich-toolbar">
                    <button type="button" class="rich-btn" title="In đậm" onclick="formatRichText('news_content', '<b>', '</b>')"><i class="fas fa-bold"></i></button>
                    <button type="button" class="rich-btn" title="In nghiêng" onclick="formatRichText('news_content', '<i>', '</i>')"><i class="fas fa-italic"></i></button>
                    <button type="button" class="rich-btn" title="Gạch chân" onclick="formatRichText('news_content', '<u>', '</u>')"><i class="fas fa-underline"></i></button>
                    <span class="rich-btn-sep"></span>
                    <button type="button" class="rich-btn" title="Tiêu đề 3" onclick="formatRichText('news_content', '<h3>', '</h3>')"><i class="fas fa-heading"></i>3</button>
                    <button type="button" class="rich-btn" title="Tiêu đề 4" onclick="formatRichText('news_content', '<h4>', '</h4>')"><i class="fas fa-heading"></i>4</button>
                    <button type="button" class="rich-btn" title="Danh sách" onclick="formatRichText('news_content', '• ', '')"><i class="fas fa-list-ul"></i></button>
                    <button type="button" class="rich-btn" title="Trích dẫn" onclick="formatRichText('news_content', '<blockquote>', '</blockquote>')"><i class="fas fa-quote-left"></i></button>
                    <span class="rich-btn-sep"></span>
                    <button type="button" class="rich-btn" title="Xóa định dạng" onclick="clearRichText('news_content')"><i class="fas fa-eraser"></i></button>
                </div>
                <textarea id="news_content" name="content" class="rich-textarea" rows="8" placeholder="Nội dung bài viết đầy đủ..."><?= htmlspecialchars($_POST['content'] ?? $newsItem['content'] ?? '') ?></textarea>
                <div class="rich-hint">
                    <span><i class="fas fa-circle-info"></i> Hỗ trợ HTML, tiêu đề h3/h4, danh sách và trích dẫn</span>
                    <span class="rich-hint-tag">Rich-Text Editor</span>
                </div>
            </div>
        </div>

        <!-- Khối Thông Tin Đặc Thù của TracenTimes -->
        <div style="background:rgba(255,255,255,0.02); border:1px solid var(--adm-border); padding:18px; border-radius:10px; margin-bottom:20px;">
            <h3 style="margin-top:0; color:var(--adm-gold); font-size:1.05rem; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-trophy"></i> Các Khối Thông Tin Derby Đặc Thù
            </h3>
            
            <div class="form-group">
                <label class="form-label">Race / Period / Rewards (Thời gian chặng đua & Phần thưởng)</label>
                <div class="rich-editor-wrap">
                    <div class="rich-toolbar">
                        <button type="button" class="rich-btn" title="In đậm" onclick="formatRichText('race_rewards', '<b>', '</b>')"><i class="fas fa-bold"></i></button>
                        <button type="button" class="rich-btn" title="Danh sách" onclick="formatRichText('race_rewards', '• ', '')"><i class="fas fa-list-ul"></i></button>
                    </div>
                    <textarea id="race_rewards" name="race_period_rewards" class="rich-textarea" rows="3" placeholder="Ví dụ: Đường đua: Kyoto 3200m&#10;Thời gian: 10/05 - 20/05/2026&#10;Phần thưởng: Jewels, Mảnh nhân vật..."><?= htmlspecialchars($_POST['race_period_rewards'] ?? $newsItem['race_period_rewards'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Important Information (Lưu ý quan trọng từ Huấn Luyện Viên)</label>
                <div class="rich-editor-wrap">
                    <div class="rich-toolbar">
                        <button type="button" class="rich-btn" title="In đậm" onclick="formatRichText('important_info', '<b>', '</b>')"><i class="fas fa-bold"></i></button>
                        <button type="button" class="rich-btn" title="Danh sách" onclick="formatRichText('important_info', '• ', '')"><i class="fas fa-list-ul"></i></button>
                    </div>
                    <textarea id="important_info" name="important_information" class="rich-textarea" rows="3" placeholder="Ví dụ: Đối thủ có thể lực cực khủng, hãy chuẩn bị chiến mã có Stamina hạng S..."><?= htmlspecialchars($_POST['important_information'] ?? $newsItem['important_information'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
            <a href="<?= Helper::url('modules/admin/news_manage.php') ?>" class="btn-adm btn-adm-secondary">Hủy bỏ</a>
            <button type="submit" class="btn-adm btn-adm-primary">
                <i class="fas fa-save"></i> <?= $isEdit ? 'Lưu Cập Nhật' : 'Đăng Bài Viết' ?>
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>
