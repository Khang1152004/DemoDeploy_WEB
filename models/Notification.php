<?php
require_once __DIR__ . '/../core/Database.php';

class Notification
{
    // Tạo thông báo mới
    public static function create($userId, $message, $link = null)
    {
        $conn = Database::getConnection(); // PDO

        $sql = "INSERT INTO thong_bao (ma_nguoi_nhan, noi_dung, link, da_xem, thoi_gian_tao)
                VALUES (:uid, :msg, :link, 0, NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':uid'  => (int)$userId,
            ':msg'  => $message,
            ':link' => $link,
        ]);
    }

    // Lấy DANH SÁCH GẦN NHẤT (cả đọc & chưa đọc) cho dropdown
    public static function recent($userId, $limit = 10)
    {
        $conn = Database::getConnection();

        $sql = "SELECT ma_thong_bao, noi_dung, link, da_xem, thoi_gian_tao
                FROM thong_bao
                WHERE ma_nguoi_nhan = :uid
                ORDER BY thoi_gian_tao DESC
                LIMIT :lim";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':uid', (int)$userId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(); // giống fetch_all(MYSQLI_ASSOC)
    }

    // Lấy THÔNG BÁO CHƯA ĐỌC (nếu sau này cần)
    public static function unread($userId, $limit = 10)
    {
        $conn = Database::getConnection();

        $sql = "SELECT ma_thong_bao, noi_dung, link, da_xem, thoi_gian_tao
                FROM thong_bao
                WHERE ma_nguoi_nhan = :uid AND da_xem = 0
                ORDER BY thoi_gian_tao DESC
                LIMIT :lim";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':uid', (int)$userId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Đếm số thông báo chưa đọc (hiện badge)
    public static function countUnread($userId)
    {
        $conn = Database::getConnection();

        $sql = "SELECT COUNT(*) AS c
                FROM thong_bao
                WHERE ma_nguoi_nhan = :uid AND da_xem = 0";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':uid' => (int)$userId]);
        $row = $stmt->fetch();

        return $row ? (int)$row['c'] : 0;
    }

    // Đánh dấu TẤT CẢ đã đọc
    public static function markAllRead($userId)
    {
        $conn = Database::getConnection();

        $sql = "UPDATE thong_bao
                SET da_xem = 1
                WHERE ma_nguoi_nhan = :uid";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':uid' => (int)$userId]);
    }

    // Đánh dấu 1 THÔNG BÁO đã đọc
    public static function markOneRead($id, $userId)
    {
        $conn = Database::getConnection();

        $sql = "UPDATE thong_bao
                SET da_xem = 1
                WHERE ma_thong_bao = :id AND ma_nguoi_nhan = :uid";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id'  => (int)$id,
            ':uid' => (int)$userId,
        ]);
    }

    // Gửi thông báo cho tất cả admin
    public static function notifyAdmins($message, $link = null)
    {
        $conn = Database::getConnection();

        $sql = "SELECT ma_nguoi_dung
                FROM nguoi_dung
                WHERE vai_tro = 'admin'";

        $stmt = $conn->query($sql);
        $admins = $stmt->fetchAll();

        foreach ($admins as $admin) {
            self::create((int)$admin['ma_nguoi_dung'], $message, $link);
        }
    }
}
