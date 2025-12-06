<?php
require_once __DIR__ . '/../core/Database.php';

class Candidate
{
    // Lấy hồ sơ ứng viên theo user id
    public static function findByUserId($userId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM ung_vien WHERE ma_ung_vien = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    // Tạo hồ sơ ứng viên mới sau khi đăng ký
    public static function createProfile($userId, $data = [])
    {
        $conn = Database::getConnection();
        $sql = "INSERT INTO ung_vien
                (ma_ung_vien, ho_ten, sdt, dia_chi, mo_ta_ngan, muc_luong_mong_muon)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $userId,
            $data['ho_ten'] ?? '',
            $data['sdt'] ?? '',
            $data['dia_chi'] ?? '',
            $data['mo_ta_ngan'] ?? '',
            $data['muc_luong_mong_muon'] ?? '',
        ]);
    }

    // Cập nhật thông tin ứng viên
    public static function updateProfile($userId, $data)
    {
        $conn = Database::getConnection();
        $sql = "UPDATE ung_vien
                SET ho_ten = ?,
                    sdt = ?,
                    dia_chi = ?,
                    mo_ta_ngan = ?,
                    muc_luong_mong_muon = ?
                WHERE ma_ung_vien = ?";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $data['ho_ten'],
            $data['sdt'],
            $data['dia_chi'],
            $data['mo_ta_ngan'],
            $data['muc_luong_mong_muon'],
            $userId,
        ]);
    }

    // Dành cho admin: lấy danh sách tất cả ứng viên
    public static function all()
    {
        $conn = Database::getConnection();
        $sql = "SELECT u.*, nd.email
                FROM ung_vien u
                JOIN nguoi_dung nd ON u.ma_ung_vien = nd.ma_nguoi_dung
                ORDER BY u.ma_ung_vien DESC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    }
}
