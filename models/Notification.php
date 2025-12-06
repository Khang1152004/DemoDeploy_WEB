<?php
require_once __DIR__ . '/../core/Database.php';

class Notification
{
    // Lấy X thông báo gần nhất của 1 user
    public static function recent($userId, $limit = 10)
    {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM thong_bao
                WHERE ma_nguoi_nhan = ?
                ORDER BY created_at DESC
                LIMIT :limit";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Lấy tất cả thông báo (trang lịch sử)
    public static function allByUser($userId)
    {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM thong_bao
                WHERE ma_nguoi_nhan = ?
                ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Tạo thông báo mới
    public static function create($userId, $content)
    {
        $conn = Database::getConnection();
        $sql = "INSERT INTO thong_bao (ma_nguoi_nhan, noi_dung, is_read, created_at)
                VALUES (?, ?, 0, NOW())";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$userId, $content]);
    }

    // Đánh dấu 1 thông báo đã đọc
    public static function markOneRead($id, $userId)
    {
        $conn = Database::getConnection();
        $sql = "UPDATE thong_bao
                SET is_read = 1
                WHERE ma_thong_bao = ? AND ma_nguoi_nhan = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id, $userId]);
    }

    // Đánh dấu tất cả đã đọc
    public static function markAllRead($userId)
    {
        $conn = Database::getConnection();
        $sql = "UPDATE thong_bao
                SET is_read = 1
                WHERE ma_nguoi_nhan = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$userId]);
    }

    // Đếm số thông báo chưa đọc
    public static function countUnread($userId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM thong_bao WHERE ma_nguoi_nhan = ? AND is_read = 0");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return (int)$row['c'];
    }
}
