<?php
require_once __DIR__ . '/../core/Database.php';

class Location
{
    // Lấy tất cả địa điểm
    public static function all()
    {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM danh_muc
                WHERE loai_danh_muc = 'dia_diem'
                ORDER BY ten_danh_muc";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    }

    // Tìm 1 địa điểm
    public static function find($id)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM danh_muc WHERE ma_danh_muc = ? AND loai_danh_muc = 'dia_diem'");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tạo địa điểm mới
    public static function create($name)
    {
        $conn = Database::getConnection();
        $sql = "INSERT INTO danh_muc (ten_danh_muc, loai_danh_muc) VALUES (?, 'dia_diem')";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$name]);
    }

    // Cập nhật
    public static function update($id, $name)
    {
        $conn = Database::getConnection();
        $sql = "UPDATE danh_muc
                SET ten_danh_muc = ?
                WHERE ma_danh_muc = ? AND loai_danh_muc = 'dia_diem'";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$name, $id]);
    }

    // Xóa
    public static function delete($id)
    {
        $conn = Database::getConnection();
        $sql = "DELETE FROM danh_muc
                WHERE ma_danh_muc = ? AND loai_danh_muc = 'dia_diem'";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
