<?php
// ====================================================================
//  core/Helper.php — Các hàm tiện ích dùng chung toàn hệ thống
// ====================================================================

class Helper {
    /**
     * Trả về đường dẫn tài nguyên (hỗ trợ kiểm tra ngoại lệ file và fallback)
     */
    public static function asset(?string $path, ?string $fallback = null): string {
        $defaultPlaceholder = BASE_URL . '/media/img/placeholder.png';
        if (empty($path)) {
            return $fallback ? (str_starts_with($fallback, 'http') ? $fallback : BASE_URL . '/' . ltrim($fallback, '/')) : $defaultPlaceholder;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $relPath = ltrim($path, '/');
        $filePathOnly = explode('?', $relPath)[0];
        $fullPath = __DIR__ . '/../' . $filePathOnly;

        // Nếu file cục bộ không tồn tại, kích hoạt xử lý ngoại lệ ImageNotFoundException
        if (!file_exists($fullPath)) {
            try {
                throw new ImageNotFoundException($relPath, $fallback);
            } catch (ImageNotFoundException $e) {
                // Trả về fallback tương ứng
                return $e->getResolvedFallback();
            }
        }

        return BASE_URL . '/' . $relPath;
    }

    /**
     * Lấy đường dẫn ảnh dự phòng cục bộ của Mã Nương từ thư mục media/img/characters/fallback/
     * Chỉ dùng khi ảnh gốc (URL ngoài mạng hoặc file chính) bị lỗi
     */
    public static function characterFallback(string|int|null $identifier): string {
        if (empty($identifier)) {
            return BASE_URL . '/media/img/placeholder.png';
        }

        $slugMap = [
            1  => 'special_week.png',
            2  => 'tokai_teio.png',
            3  => 'gold_ship.png',
            4  => 'anges_tachyon.png',
            5  => 'silence_suzuka.png',
            6  => 'mejiro_mcqueen.png',
            7  => 'rice_shower.png',
            8  => 'vodka.png',
            9  => 'daiwa_scarlet.png',
            10 => 'symboli_rudolf.png',
            11 => 'zenno_rob_roy.png',
            12 => 'mayano_top_gun.png',
            13 => 'air_groove.png',
            14 => 'narita_brian.png',
            15 => 'sweep_tosho.png',
            'special week'   => 'special_week.png',
            'tokai teio'     => 'tokai_teio.png',
            'gold ship'      => 'gold_ship.png',
            'agnes tachyon'  => 'anges_tachyon.png',
            'anges tachyon'  => 'anges_tachyon.png',
            'silence suzuka' => 'silence_suzuka.png',
            'mejiro mcqueen' => 'mejiro_mcqueen.png',
            'rice shower'    => 'rice_shower.png',
            'vodka'          => 'vodka.png',
            'daiwa scarlet'  => 'daiwa_scarlet.png',
            'symboli rudolf' => 'symboli_rudolf.png',
            'zenno rob roy'  => 'zenno_rob_roy.png',
            'mayano top gun' => 'mayano_top_gun.png',
            'air groove'     => 'air_groove.png',
            'narita brian'   => 'narita_brian.png',
            'sweep tosho'    => 'sweep_tosho.png'
        ];

        $key = is_numeric($identifier) ? (int)$identifier : strtolower(trim((string)$identifier));
        $filename = $slugMap[$key] ?? null;

        if (!$filename) {
            // Tự động chuẩn hóa slug
            $slug = preg_replace('/[^a-z0-9]+/i', '_', strtolower(trim((string)$identifier)));
            $filename = trim($slug, '_') . '.png';
        }

        $fallbackPath = 'media/img/characters/fallback/' . $filename;
        if (file_exists(__DIR__ . '/../' . $fallbackPath)) {
            return BASE_URL . '/' . $fallbackPath;
        }

        $directPath = 'media/img/characters/' . $filename;
        if (file_exists(__DIR__ . '/../' . $directPath)) {
            return BASE_URL . '/' . $directPath;
        }

        return BASE_URL . '/media/img/placeholder.png';
    }

    /**
     * Lấy ảnh đại diện Uma:
     * 1. Ưu tiên đường link ảnh Uma trực tuyến (image_url) thay vì đường dẫn nội bộ
     * 2. Nếu không có image_url, sử dụng đường dẫn nội bộ (avatar)
     * 3. Nếu rỗng cả hai, sử dụng ảnh null exception (media/img/placeholder.png)
     * Cho phép để ảnh rỗng trong DB/Form phục vụ kiểm thử đối tượng test
     */
    public static function characterAvatar(array|object|null $char): string {
        $placeholder = self::asset('media/img/placeholder.png');
        if (empty($char)) {
            return $placeholder;
        }

        // 1. Ưu tiên link ảnh Uma trực tuyến (image_url)
        $imageUrl = is_array($char) ? ($char['image_url'] ?? null) : ($char->image_url ?? null);
        if (!empty($imageUrl) && is_string($imageUrl) && trim($imageUrl) !== '') {
            return trim($imageUrl);
        }

        // 2. Sử dụng đường dẫn nội bộ (avatar)
        $avatar = is_array($char) ? ($char['avatar'] ?? null) : ($char->avatar ?? null);
        if (!empty($avatar) && is_string($avatar) && trim($avatar) !== '') {
            return self::asset(trim($avatar));
        }

        // 3. Rỗng cả 2 -> fallback ảnh null exception
        return $placeholder;
    }

    public static function url(string $path): string {
        return BASE_URL . '/' . ltrim($path, '/');
    }

    /**
     * Chuyển hướng trang (Redirect) an toàn và kết thúc script
     */
    public static function redirect(string $path): void {
        $url = (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) 
            ? $path 
            : self::url($path);
        header('Location: ' . $url);
        exit;
    }

    public static function formatDate(?string $datetime, string $format = 'd/m/Y H:i'): string {
        if (empty($datetime)) return 'Chưa cập nhật';
        $ts = strtotime($datetime);
        return $ts ? date($format, $ts) : htmlspecialchars($datetime);
    }

    public static function truncate(string $text, int $length = 140, string $ellipsis = '...'): string {
        $clean = strip_tags($text);
        if (mb_strlen($clean) <= $length) return $clean;
        return mb_substr($clean, 0, $length) . $ellipsis;
    }

    /**
     * Render nội dung bài viết an toàn, tự động xử lý thẻ HTML hoặc plain text, tránh lộ thẻ thô </p>, <br>
     */
    public static function renderContent(?string $content): string {
        if (empty($content)) return '';
        if (preg_match('/<[a-z\/][^>]*>/i', $content)) {
            $allowed = '<p><br><strong><b><em><i><u><ul><ol><li><h4><h3><h2><h1><a><blockquote><code>';
            return strip_tags($content, $allowed);
        }
        return nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));
    }

    public static function setFlash(string $key, string $message): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['flash_' . $key] = $message;
    }

    public static function getFlash(string $key): ?string {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $sessionKey = 'flash_' . $key;
        if (isset($_SESSION[$sessionKey])) {
            $msg = $_SESSION[$sessionKey];
            unset($_SESSION[$sessionKey]);
            return $msg;
        }
        return null;
    }

    /**
     * Tạo badge màu sắc cho Tỷ lệ chấn thương (Hệ thống phân cấp High Risk - High Reward)
     */
    public static function injuryBadge(float $rate): string {
        $percent = round($rate * 100, 1);
        $baseStyle = "display:inline-flex; align-items:center; justify-content:center; gap:5px; white-space:nowrap; word-break:keep-all; box-decoration-break:clone; -webkit-box-decoration-break:clone; padding:3px 9px; border-radius:12px; font-size:0.78rem; line-height:1.2; box-shadow:0 1px 4px rgba(0,0,0,0.2);";
        if ($percent <= 3.0) {
            return "<span class='badge badge-safe' style='{$baseStyle} background:rgba(46,204,113,0.18); color:#2ecc71; border:1px solid rgba(46,204,113,0.5); font-weight:600;'><i class='fas fa-shield-alt'></i> Cực an toàn ({$percent}%)</span>";
        } elseif ($percent <= 6.0) {
            return "<span class='badge badge-normal' style='{$baseStyle} background:rgba(241,196,15,0.18); color:#f1c40f; border:1px solid rgba(241,196,15,0.5); font-weight:600;'><i class='fas fa-check-circle'></i> Tiêu chuẩn ({$percent}%)</span>";
        } elseif ($percent <= 8.0) {
            return "<span class='badge badge-warning' style='{$baseStyle} background:rgba(230,126,34,0.18); color:#e67e22; border:1px solid rgba(230,126,34,0.5); font-weight:600;'><i class='fas fa-exclamation-triangle'></i> Cảnh báo ({$percent}%)</span>";
        } elseif ($percent <= 11.0) {
            return "<span class='badge badge-danger' style='{$baseStyle} background:rgba(239,68,68,0.18); color:#ef4444; border:1px solid rgba(239,68,68,0.5); font-weight:700;'><i class='fas fa-triangle-exclamation'></i> Nguy cơ cao ({$percent}%)</span>";
        } else {
            return "<span class='badge badge-extreme' style='{$baseStyle} background:linear-gradient(135deg, rgba(239,68,68,0.25), rgba(168,85,247,0.25)); color:#ff3366; border:1px solid rgba(255,51,102,0.5); font-weight:800; text-shadow:0 0 8px rgba(255,51,102,0.4);'><i class='fas fa-skull-crossbones'></i> Rủi ro ({$percent}%)</span>";
        }
    }

    /**
     * Badge phong cách chạy (Running style) - Đóng gói dạng khối tag nguyên vẹn
     */
    public static function runningStyleBadge(string $style): string {
        $map = [
            'Runner'    => ['label' => 'Tiên phong (Runner)',    'color' => '#38bdf8', 'bg' => 'rgba(56,189,248,0.15)',  'border' => 'rgba(56,189,248,0.45)'],
            'Leader'    => ['label' => 'Dẫn đầu (Leader)',       'color' => '#34d399', 'bg' => 'rgba(52,211,153,0.15)',  'border' => 'rgba(52,211,153,0.45)'],
            'Betweener' => ['label' => 'Giữ cự ly (Betweener)',  'color' => '#fb923c', 'bg' => 'rgba(251,146,60,0.15)',  'border' => 'rgba(251,146,60,0.45)'],
            'Chaser'    => ['label' => 'Bứt tốc cuối (Chaser)',  'color' => '#c084fc', 'bg' => 'rgba(192,132,252,0.15)', 'border' => 'rgba(192,132,252,0.45)']
        ];
        $conf = $map[$style] ?? ['label' => $style, 'color' => '#cbd5e1', 'bg' => 'rgba(255,255,255,0.08)', 'border' => 'rgba(255,255,255,0.2)'];
        return "<span class='ops-badge-tag' style='display:inline-flex; align-items:center; justify-content:center; white-space:nowrap; word-break:keep-all; box-decoration-break:clone; -webkit-box-decoration-break:clone; background:{$conf['bg']}; color:{$conf['color']}; border:1px solid {$conf['border']}; padding:3px 9px; border-radius:12px; font-weight:700; font-size:0.78rem; line-height:1.2; box-shadow:0 1px 4px rgba(0,0,0,0.2);'>{$conf['label']}</span>";
    }

    /**
     * Huy hiệu thể loại tin tức
     */
    /**
     * Huy hiệu thể loại tin tức
     */
    public static function categoryBadge(string $cat): string {
        $cleanCat = trim($cat);
        $map = [
            'Event'       => ['bg' => 'linear-gradient(135deg, #ff4b82, #e11d48)', 'icon' => 'fa-calendar-star'],
            'Race'        => ['bg' => 'linear-gradient(135deg, #f59e0b, #d97706)', 'icon' => 'fa-flag-checkered'],
            'Campaign'    => ['bg' => 'linear-gradient(135deg, #a855f7, #7c3aed)', 'icon' => 'fa-gift'],
            'Update'      => ['bg' => 'linear-gradient(135deg, #0284c7, #0369a1)', 'icon' => 'fa-arrows-rotate'],
            'Maintenance' => ['bg' => 'linear-gradient(135deg, #64748b, #475569)', 'icon' => 'fa-wrench'],
            'Gacha'       => ['bg' => 'linear-gradient(135deg, #8b5cf6, #6d28d9)', 'icon' => 'fa-gem'],
            'Interview'   => ['bg' => 'linear-gradient(135deg, #ec4899, #db2777)', 'icon' => 'fa-microphone'],
            'Medical'     => ['bg' => 'linear-gradient(135deg, #06b6d4, #0891b2)', 'icon' => 'fa-notes-medical'],
            'Community'   => ['bg' => 'linear-gradient(135deg, #6366f1, #4f46e5)', 'icon' => 'fa-users'],
            'Lore'        => ['bg' => 'linear-gradient(135deg, #10b981, #059669)', 'icon' => 'fa-book-open'],
            'Guide'       => ['bg' => 'linear-gradient(135deg, #f59e0b, #b45309)', 'icon' => 'fa-graduation-cap'],
        ];
        
        // So khớp không phân biệt hoa thường
        $matchedKey = null;
        foreach ($map as $key => $val) {
            if (strcasecmp($cleanCat, $key) === 0) {
                $matchedKey = $key;
                break;
            }
        }
        
        $item = $matchedKey ? $map[$matchedKey] : ['bg' => 'linear-gradient(135deg, #6366f1, #4338ca)', 'icon' => 'fa-newspaper'];
        $displayName = htmlspecialchars(strtoupper($cleanCat));
        
        return "<span style='display:inline-flex; align-items:center; justify-content:center; gap:6px; background:{$item['bg']}; color:#ffffff; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; white-space:nowrap; word-break:keep-all; box-shadow:0 2px 8px rgba(0,0,0,0.35); border:1px solid rgba(255,255,255,0.15);'><i class='fas {$item['icon']}' style='font-size:0.75rem;'></i> {$displayName}</span>";
    }
}
