<?php
require_once __DIR__ . '/../core/Database.php';

class Notification {
    // Tạo thông báo mới
    public static function create($userId, $message, $link = null) {
        $conn = Database::getConnection();
        $sql = "INSERT INTO thong_bao (ma_nguoi_nhan, noi_dung, link, da_xem, thoi_gian_tao)
                VALUES (?, ?, ?, 0, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([(int)$userId, $message, $link]);
    }

    // Lấy danh sách gần nhất (dropdown chuông)
    public static function recent($userId, $limit = 10) {
        $conn = Database::getConnection();
        $sql = "SELECT ma_thong_bao, noi_dung, link, da_xem, thoi_gian_tao
                FROM thong_bao
                WHERE ma_nguoi_nhan = ?
                ORDER BY thoi_gian_tao DESC
                LIMIT :limit";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute([(int)$userId]);
        return $stmt->fetchAll();
    }

    // Thông báo chưa đọc (nếu cần)
    public static function unread($userId, $limit = 10) {
        $conn = Database::getConnection();
        $sql = "SELECT ma_thong_bao, noi_dung, link, da_xem, thoi_gian_tao
                FROM thong_bao
                WHERE ma_nguoi_nhan = ? AND da_xem = 0
                ORDER BY thoi_gian_tao DESC
                LIMIT :limit";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute([(int)$userId]);
        return $stmt->fetchAll();
    }

    // Đếm số chưa đọc (badge)
    public static function countUnread($userId) {
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(*) AS c
                FROM thong_bao
                WHERE ma_nguoi_nhan = ? AND da_xem = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute([(int)$userId]);
        $row = $stmt->fetch();
        return $row ? (int)$row['c'] : 0;
    }

    // Đánh dấu tất cả đã đọc
    public static function markAllRead($userId) {
        $conn = Database::getConnection();
        $sql = "UPDATE thong_bao
                SET da_xem = 1
                WHERE ma_nguoi_nhan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([(int)$userId]);
    }

    // Đánh dấu 1 cái đã đọc
    public static function markOneRead($id, $userId) {
        $conn = Database::getConnection();
        $sql = "UPDATE thong_bao
                SET da_xem = 1
                WHERE ma_thong_bao = ? AND ma_nguoi_nhan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([(int)$id, (int)$userId]);
    }

    // Gửi thông báo cho tất cả admin
    public static function notifyAdmins($message, $link = null) {
        $conn = Database::getConnection();
        $sql = "SELECT ma_nguoi_dung FROM nguoi_dung WHERE vai_tro = 'admin'";
        $stmt = $conn->query($sql);
        $admins = $stmt->fetchAll();
        foreach ($admins as $admin) {
            self::create((int)$admin['ma_nguoi_dung'], $message, $link);
        }
    }
}
