<?php
// ====================================================================
//  core/Database.php — Data Access Layer (DAL) Base Connection Singleton
// ====================================================================

class Database {
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../config/database.php';
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } catch (PDOException $e) {
                die("Lỗi kết nối CSDL (TracenTimes PDO): " . htmlspecialchars($e->getMessage()));
            }
        }
        return self::$instance;
    }
}
