<?php
require_once __DIR__ . '/../core/Database.php';

class Employer {
    public static function createProfile($userId) {
        $conn = Database::getConnection();
        $status = 'approved';
        $stmt = $conn->prepare("INSERT INTO doanh_nghiep (ma_doanh_nghiep, trang_thai_duyet) VALUES (?, ?)");
        $stmt->bind_param("is", $userId, $status);
        @$stmt->execute();
    }

    public static function getProfile($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM doanh_nghiep WHERE ma_doanh_nghiep = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function updateProfile($userId, $data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE doanh_nghiep SET ten_cong_ty=?, dia_chi=?, mo_ta=? WHERE ma_doanh_nghiep=?");
        $stmt->bind_param("sssi", $data['ten_cong_ty'], $data['dia_chi'], $data['mo_ta'], $userId);
        $stmt->execute();
    }
}
