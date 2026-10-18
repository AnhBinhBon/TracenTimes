<?php
// ====================================================================
//  core/ImageNotFoundException.php — Xử lý ngoại lệ khi hình ảnh không tồn tại / không load được
// ====================================================================

class ImageNotFoundException extends Exception {
    private string $imagePath;
    private ?string $characterFallback;
    private string $defaultPlaceholder;

    public function __construct(
        string $imagePath,
        ?string $characterFallback = null,
        string $message = "",
        int $code = 404,
        ?Throwable $previous = null
    ) {
        $this->imagePath = $imagePath;
        $this->characterFallback = $characterFallback;
        $this->defaultPlaceholder = BASE_URL . '/media/img/placeholder.png';

        if (empty($message)) {
            $message = "Hình ảnh không thể tải hoặc không tồn tại: '{$imagePath}'";
        }

        parent::__construct($message, $code, $previous);
    }

    public function getImagePath(): string {
        return $this->imagePath;
    }

    public function getCharacterFallback(): ?string {
        return $this->characterFallback;
    }

    /**
     * Lấy URL dự phòng tốt nhất (Ưu tiên ảnh backup của mã nương, sau đó đến ảnh Error Placeholder)
     */
    public function getResolvedFallback(): string {
        if (!empty($this->characterFallback)) {
            // Kiểm tra xem file fallback cục bộ có tồn tại không
            $localRel = ltrim(str_replace(BASE_URL, '', $this->characterFallback), '/');
            $localAbs = __DIR__ . '/../' . $localRel;
            if (file_exists($localAbs)) {
                return $this->characterFallback;
            }
        }
        return $this->defaultPlaceholder;
    }
}
