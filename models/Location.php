<?php
require_once __DIR__ . '/../core/Database.php';

class Location {
    // Lấy tất cả địa điểm (trong bảng danh_muc loại 'dia_diem')
    public static function all() {
        $conn = Database::getConnection();
        $sql = "SELECT ma_danh_muc AS ma_dia_diem, ten_danh_muc
                FROM danh_muc
                WHERE loai_danh_muc = 'dia_diem'
                ORDER BY ten_danh_muc";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function create($name) {
        $conn = Database::getConnection();
        $sql = "INSERT INTO danh_muc (ten_danh_muc, loai_danh_muc)
                VALUES (?, 'dia_diem')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name]);
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $sql = "DELETE FROM danh_muc
                WHERE ma_danh_muc = ? AND loai_danh_muc = 'dia_diem'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([(int)$id]);
    }
}
