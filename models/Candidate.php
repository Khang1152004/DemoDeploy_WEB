<?php
require_once __DIR__ . '/../core/Database.php';

class Candidate {
    public static function createProfile($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO ung_vien (ma_ung_vien) VALUES (?)");
        try {
            $stmt->execute([(int)$userId]);
        } catch (Exception $e) {
            // bỏ qua nếu đã tồn tại
        }
    }

    public static function getProfile($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM ung_vien WHERE ma_ung_vien = ?");
        $stmt->execute([(int)$userId]);
        return $stmt->fetch();
    }

    public static function updateProfile($userId, $data) {
        $conn = Database::getConnection();
        $sql = "UPDATE ung_vien
                SET ho_ten = ?, sdt = ?, dia_chi = ?, mo_ta_ngan = ?, muc_luong_mong_muon = ?
                WHERE ma_ung_vien = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $data['ho_ten'],
            $data['sdt'],
            $data['dia_chi'],
            $data['mo_ta_ngan'],
            $data['muc_luong_mong_muon'],
            (int)$userId,
        ]);
    }

    // Alias cho ApplicationController nếu có dùng
    public static function getByUserId($userId) {
        return self::getProfile($userId);
    }
}
