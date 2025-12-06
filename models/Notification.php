<?php
require_once __DIR__ . '/../core/Database.php';

class Notification {

    // Tạo thông báo cho 1 user
    public static function create($userId, $message, $link = null) {
        $conn = Database::getConnection();

        $sql = "INSERT INTO thong_bao (ma_nguoi_nhan, noi_dung, link, is_read, created_at)
                VALUES (?, ?, ?, 0, NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->execute([(int)$userId, $message, $link]);
    }

    // Gửi thông báo cho tất cả admin
    public static function notifyAdmins($message, $link = null) {
        $conn = Database::getConnection();

        $admins = $conn->query("SELECT ma_nguoi_dung FROM nguoi_dung WHERE vai_tro = 'admin'")
                       ->fetchAll();

        foreach ($admins as $admin) {
            self::create($admin['ma_nguoi_dung'], $message, $link);
        }
    }

    // Dropdown chuông – lấy 10 thông báo gần nhất
    public static function recent($userId, $limit = 10) {
        $conn = Database::getConnection();

        $sql = "SELECT ma_thong_bao, noi_dung, link, is_read, created_at
                FROM thong_bao
                WHERE ma_nguoi_nhan = ?
                ORDER BY created_at DESC
                LIMIT :limit";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute([(int)$userId]);

        return $stmt->fetchAll();
    }

    // Mark tất cả đã đọc
    public static function markAllRead($userId) {
        $conn = Database::getConnection();

        $sql = "UPDATE thong_bao SET is_read = 1 WHERE ma_nguoi_nhan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([(int)$userId]);
    }

    // Mark 1 thông báo
    public static function markOneRead($id, $userId) {
        $conn = Database::getConnection();

        $sql = "UPDATE thong_bao
                SET is_read = 1
                WHERE ma_thong_bao = ? AND ma_nguoi_nhan = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([(int)$id, (int)$userId]);
    }

    // Đếm số chưa đọc
    public static function countUnread($userId) {
        $conn = Database::getConnection();

        $sql = "SELECT COUNT(*) AS c
                FROM thong_bao
                WHERE ma_nguoi_nhan = ? AND is_read = 0";

        $stmt = $conn->prepare($sql);
        $stmt->execute([(int)$userId]);

        $row = $stmt->fetch();
        return (int)$row['c'];
    }
}
