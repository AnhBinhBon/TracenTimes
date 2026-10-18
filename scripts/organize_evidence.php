<?php
$src = 'C:/Users/Admin/.gemini/antigravity-ide/brain/e1860d9c-36b7-425f-b7b4-4cb715b287da/';
$dest = 'C:/xampp/htdocs/TracenTimes/docs/evidence/';

if (!is_dir($dest)) {
    mkdir($dest, 0777, true);
}

$files = [
    'homepage_hero_1792334035259.png' => 'fig_3_1_homepage_hero.png',
    'play_now_modal_opened_1792334102800.png' => 'fig_3_2_play_now_launcher.png',
    'report_news_catalog_1792334395154.png' => 'fig_3_3_news_catalog.png',
    'report_news_detail_1792334406097.png' => 'fig_3_4_news_detail_clean.png',
    'report_characters_list_1792334417772.png' => 'fig_3_5_characters_catalog.png',
    'report_character_detail_wiki_1792334432191.png' => 'fig_3_6_character_detail_wiki.png',
    'report_racing_podium_1792334634989.png' => 'fig_3_7_racing_podium_top3.png',
    'report_racing_table_1792334655184.png' => 'fig_3_8_racing_results_table.png',
    'report_login_clean_1792334728121.png' => 'fig_3_9_login_security.png',
    'report_admin_dashboard_1792334855157.png' => 'fig_3_10_admin_dashboard.png',
    'report_admin_news_manage_1792334860724.png' => 'fig_3_11_admin_news_manage.png',
    'report_admin_news_richtext_1792334866202.png' => 'fig_3_12_admin_news_richtext.png',
    'report_admin_char_manage_1792334871282.png' => 'fig_3_13_admin_characters_manage.png',
    'report_admin_char_form_1792334877779.png' => 'fig_3_14_admin_character_form_link.png',
    'stats_manage_view_1792333881220.png' => 'fig_3_15_admin_stats_manage.png',
    'report_admin_users_manage_1792334883147.png' => 'fig_3_16_admin_users_manage.png'
];

foreach ($files as $sourceName => $targetName) {
    $sourceFile = $src . $sourceName;
    $targetFile = $dest . $targetName;
    if (file_exists($sourceFile)) {
        copy($sourceFile, $targetFile);
        echo "COPIED: $targetName (" . filesize($targetFile) . " bytes)\n";
    } else {
        echo "NOT FOUND: $sourceFile\n";
    }
}
