<?php
require_once __DIR__ . '/../core/Database.php';

class Employer {
    public static function createProfile($userId) {
        $conn = Database::getConnection();
        $status = 'approved';
        $stmt = $conn->prepare("INSERT INTO doanh_nghiep (ma_doanh_nghiep, trang_thai_duyet) VALUES (?, ?)");
        try {
            $stmt->execute([(int)$userId, $status]);
        } catch (Exception $e) {
            // đã tồn tại thì thôi
        }
    }

    public static function getProfile($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM doanh_nghiep WHERE ma_doanh_nghiep = ?");
        $stmt->execute([(int)$userId]);
        return $stmt->fetch();
    }

    public static function updateProfile($userId, $data) {
        $conn = Database::getConnection();
        $sql = "UPDATE doanh_nghiep
                SET ten_cong_ty = ?, dia_chi = ?, mo_ta = ?
                WHERE ma_doanh_nghiep = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $data['ten_cong_ty'],
            $data['dia_chi'],
            $data['mo_ta'],
            (int)$userId,
        ]);
    }
}
