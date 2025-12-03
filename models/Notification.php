<?php
require_once __DIR__ . '/../core/Database.php';

class Notification {
    // Tạo thông báo mới
    public static function create($userId, $message, $link = null) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO thong_bao (ma_nguoi_nhan, noi_dung, link) VALUES (?,?,?)");
        $stmt->bind_param("iss", $userId, $message, $link);
        $stmt->execute();
        $stmt->close();
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
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    // Lấy thông báo CHƯA ĐỌC (nếu sau này cần)
    public static function unread($userId, $limit = 10) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT ma_thong_bao, noi_dung, link, da_xem, thoi_gian_tao
            FROM thong_bao
            WHERE ma_nguoi_nhan = ? AND da_xem = 0
            ORDER BY thoi_gian_tao DESC
            LIMIT ?
        ");
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    // Đếm số thông báo chưa đọc (hiện badge)
    public static function countUnread($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT COUNT(*) AS c
            FROM thong_bao
            WHERE ma_nguoi_nhan = ? AND da_xem = 0
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return (int)$row['c'];
    }

    // Đánh dấu TẤT CẢ đã đọc
    public static function markAllRead($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE thong_bao SET da_xem = 1 WHERE ma_nguoi_nhan = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }

    // Đánh dấu 1 THÔNG BÁO đã đọc
    public static function markOneRead($id, $userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            UPDATE thong_bao
            SET da_xem = 1
            WHERE ma_thong_bao = ? AND ma_nguoi_nhan = ?
        ");
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $stmt->close();
    }

    // Gửi thông báo cho tất cả admin
    public static function notifyAdmins($message, $link = null) {
        $conn = Database::getConnection();
        $sql = "SELECT ma_nguoi_dung FROM nguoi_dung WHERE vai_tro = 'admin'";
        $res = $conn->query($sql);
        while ($row = $res->fetch_assoc()) {
            self::create((int)$row['ma_nguoi_dung'], $message, $link);
        }
        $res->free();
    }
}
