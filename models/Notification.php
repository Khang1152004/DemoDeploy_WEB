<?php
require_once __DIR__ . '/../core/Database.php';

class Notification {
    public static function create($userId, $message, $link = null) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO thong_bao (ma_nguoi_nhan, noi_dung, link) VALUES (?,?,?)");
        $stmt->bind_param("iss", $userId, $message, $link);
        $stmt->execute();
    }

    public static function unread($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM thong_bao WHERE ma_nguoi_nhan = ? AND da_xem = 0 ORDER BY thoi_gian_tao DESC LIMIT 10");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function markAllRead($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE thong_bao SET da_xem = 1 WHERE ma_nguoi_nhan = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }

    public static function notifyAdmins($message, $link = null) {
        $conn = Database::getConnection();
        $res = $conn->query("SELECT ma_nguoi_dung FROM nguoi_dung WHERE vai_tro = 'admin'");
        while ($row = $res->fetch_assoc()) {
            self::create((int)$row['ma_nguoi_dung'], $message, $link);
        }
    }
}
