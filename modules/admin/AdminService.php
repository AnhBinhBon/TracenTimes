<?php
// ====================================================================
//  modules/admin/AdminService.php — Business Logic Layer Quản trị hệ thống
// ====================================================================

class AdminService {
    private NewsDAO $newsDao;
    private CharacterDAO $charDao;
    private StatsDAO $statsDao;
    private AuthDAO $authDao;

    public function __construct() {
        $this->newsDao = new NewsDAO();
        $this->charDao = new CharacterDAO();
        $this->statsDao = new StatsDAO();
        $this->authDao = new AuthDAO();
    }

    public function getDashboardMetrics(): array {
        $totalNews = $this->newsDao->countAll();
        $totalChars = $this->charDao->countAll(null, 'all');
        $totalUsers = $this->authDao->countUsers();
        $statsSummary = $this->statsDao->getSummary();

        $recentNews = $this->newsDao->getLatest(5);
        $recentChars = $this->charDao->getAllWithStats(null, null, 5, 0);

        return [
            'total_news'       => $totalNews,
            'total_characters' => $totalChars,
            'total_users'      => $totalUsers,
            'stats_summary'    => $statsSummary,
            'recent_news'      => $recentNews,
            'recent_chars'     => $recentChars
        ];
    }
}
