<?php
// ====================================================================
//  config/app.php — Cấu hình ứng dụng toàn cục & Tự động phát hiện BASE_URL
// ====================================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    $projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/../'));
    $docRoot     = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/'));
    $baseUrl     = rtrim(str_replace($docRoot, '', $projectRoot), '/');
    define('BASE_URL', $baseUrl);
}

if (!defined('APP_NAME')) {
    define('APP_NAME', 'TracenTimes — Umamusume News & Database');
}

// Autoload các class Core và Modules cơ bản
spl_autoload_register(function ($class) {
    // 1. Kiểm tra core/
    $coreFile = __DIR__ . '/../core/' . $class . '.php';
    if (file_exists($coreFile)) {
        require_once $coreFile;
        return;
    }

    // 2. Kiểm tra các module
    $modules = ['auth', 'news', 'characters', 'stats', 'admin'];
    foreach ($modules as $mod) {
        $modFile = __DIR__ . '/../modules/' . $mod . '/' . $class . '.php';
        if (file_exists($modFile)) {
            require_once $modFile;
            return;
        }
    }
});
