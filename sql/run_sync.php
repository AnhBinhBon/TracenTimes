<?php
// ====================================================================
//  sql/run_sync.php — Chạy đồng bộ dữ liệu Wiki từ dòng lệnh
// ====================================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../modules/wiki/WikiSyncService.php';

echo ">>> Bắt đầu đồng bộ dữ liệu từ umamusu.wiki/Main_Page...\n";

$wikiService = new WikiSyncService();

echo "1. Đồng bộ banner Scouts & Gacha...\n";
$scoutRes = $wikiService->syncScoutsToNews();
echo "   - Kết quả: Thêm mới {$scoutRes['synced']}, Cập nhật {$scoutRes['updated']} (Nguồn: {$scoutRes['source']})\n";

echo "2. Đồng bộ danh sách Nhân vật & Chỉ số...\n";
$charRes = $wikiService->syncCharactersFromWiki();
echo "   - Kết quả: Thêm mới {$charRes['imported']}, Cập nhật {$charRes['updated']}\n";

echo ">>> Hoàn tất đồng bộ dữ liệu thành công!\n";
