<?php
require_once __DIR__ . '/../core/Database.php';

class Notification {
    // Tạo thông báo mới
    public static function create($userId, $message, $link = null) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO thong_bao (ma_nguoi_nhan, noi_dung, link) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $message, $link]);
    }

    // Lấy DANH SÁCH GẦN NHẤT (cả đọc & chưa đọc) cho dropdown
    public static function recent($userId, $limit = 10) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT ma_thong_bao, noi_dung, link, da_xem, thoi_gian_tao
            FROM thong_bao
            WHERE ma_nguoi_nhan = ?
            ORDER BY thoi_gian_tao DESC
            LIMIT ?
        ");
        $stmt->execute([(int)$userId, (int)$limit]);
        $rows = $stmt->fetchAll();
        return $rows;
    }

    // Lấy thông báo CHƯA ĐỌC
    public static function unread($userId, $limit = 10) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT ma_thong_bao, noi_dung, link, da_xem, thoi_gian_tao
            FROM thong_bao
            WHERE ma_nguoi_nhan = ? AND da_xem = 0
            ORDER BY thoi_gian_tao DESC
            LIMIT ?
        ");
        $stmt->execute([(int)$userId, (int)$limit]);
        return $stmt->fetchAll();
    }

    // Đếm số thông báo chưa đọc (badge)
    public static function countUnread($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT COUNT(*) AS c
            FROM thong_bao
            WHERE ma_nguoi_nhan = ? AND da_xem = 0
        ");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return (int)$row['c'];
    }

    // Đánh dấu tất cả thông báo đã đọc
    public static function markAllRead($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE thong_bao SET da_xem = 1 WHERE ma_nguoi_nhan = ?");
        $stmt->execute([$userId]);
    }

    // Đánh dấu một thông báo đã đọc
    public static function markOneRead($id, $userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            UPDATE thong_bao
            SET da_xem = 1
            WHERE ma_thong_bao = ? AND ma_nguoi_nhan = ?
        ");
        $stmt->execute([(int)$id, (int)$userId]);
    }

    // Gửi thông báo cho tất cả admin
    public static function notifyAdmins($message, $link = null) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT ma_nguoi_dung FROM nguoi_dung WHERE vai_tro = 'admin'");
        $stmt->execute();
        $adminList = $stmt->fetchAll();
        foreach ($adminList as $row) {
            self::create((int)$row['ma_nguoi_dung'], $message, $link);
        }
    }
}
