<?php
require_once __DIR__ . '/../core/Database.php';

class Location {
    /**
     * Lấy tất cả địa điểm (dùng bảng danh_muc, loại = 'dia_diem')
     */
    public static function all() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT ma_danh_muc AS ma_dia_diem, ten_danh_muc 
                                FROM danh_muc 
                                WHERE loai_danh_muc = 'dia_diem'
                                ORDER BY ten_danh_muc");
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Tạo địa điểm mới
     */
    public static function create($name) {
        $name = trim($name);
        if ($name === '') return;
        $conn = Database::getConnection();

        $stmt = $conn->prepare("INSERT INTO danh_muc (ten_danh_muc, loai_danh_muc) 
                                VALUES (?, 'dia_diem')");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }

    /**
     * Xóa địa điểm (chỉ xóa trong bảng danh_muc với đúng loại 'dia_diem')
     */
    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM danh_muc 
                                WHERE ma_danh_muc = ? AND loai_danh_muc = 'dia_diem'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
