<?php
require_once __DIR__ . '/../core/Database.php';

class Candidate {
    public static function createProfile($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO ung_vien (ma_ung_vien) VALUES (?)");
        $stmt->bind_param("i", $userId);
        @$stmt->execute();
    }

    public static function getProfile($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM ung_vien WHERE ma_ung_vien = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function updateProfile($userId, $data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE ung_vien SET ho_ten=?, sdt=?, dia_chi=?, mo_ta_ngan=?, muc_luong_mong_muon=? WHERE ma_ung_vien=?");
        $stmt->bind_param("sssssi",
            $data['ho_ten'], $data['sdt'], $data['dia_chi'],
            $data['mo_ta_ngan'], $data['muc_luong_mong_muon'], $userId
        );
        $stmt->execute();
    }
}
