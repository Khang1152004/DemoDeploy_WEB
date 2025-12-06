<?php
require_once __DIR__ . '/../core/Database.php';

class Employer
{
    // Lấy doanh nghiệp theo user id
    public static function findByUserId($userId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM doanh_nghiep WHERE ma_doanh_nghiep = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    // Tạo hồ sơ doanh nghiệp sau khi đăng ký
    public static function createProfile($userId, $data = [])
    {
        $conn = Database::getConnection();
        $sql = "INSERT INTO doanh_nghiep
                (ma_doanh_nghiep, ten_cong_ty, dia_chi, mo_ta, trang_thai_duyet)
                VALUES (?, ?, ?, ?, 'pending')";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $userId,
            $data['ten_cong_ty'] ?? '',
            $data['dia_chi'] ?? '',
            $data['mo_ta'] ?? '',
        ]);
    }

    // Cập nhật hồ sơ doanh nghiệp
    public static function updateProfile($userId, $data)
    {
        $conn = Database::getConnection();
        $sql = "UPDATE doanh_nghiep
                SET ten_cong_ty = ?,
                    dia_chi = ?,
                    mo_ta = ?
                WHERE ma_doanh_nghiep = ?";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $data['ten_cong_ty'],
            $data['dia_chi'],
            $data['mo_ta'],
            $userId,
        ]);
    }

    // Admin: duyệt / từ chối doanh nghiệp
    public static function updateStatus($userId, $status)
    {
        $conn = Database::getConnection();
        $sql = "UPDATE doanh_nghiep
                SET trang_thai_duyet = ?
                WHERE ma_doanh_nghiep = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$status, $userId]);
    }

    // Admin: danh sách doanh nghiệp theo trạng thái
    public static function listByStatus($status)
    {
        $conn = Database::getConnection();
        $sql = "SELECT d.*, nd.email
                FROM doanh_nghiep d
                JOIN nguoi_dung nd ON d.ma_doanh_nghiep = nd.ma_nguoi_dung
                WHERE d.trang_thai_duyet = ?
                ORDER BY d.ma_doanh_nghiep DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }
}
