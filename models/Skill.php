<?php
require_once __DIR__ . '/../core/Database.php';

class Skill
{
    // Lấy tất cả kỹ năng
    public static function all()
    {
        $conn = Database::getConnection();
        $sql = "SELECT k.*, l.ten_linh_vuc
                FROM ky_nang k
                LEFT JOIN linh_vuc l ON k.ma_linh_vuc = l.ma_linh_vuc
                ORDER BY k.ten_ky_nang";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    }

    // Lấy kỹ năng theo lĩnh vực
    public static function byField($fieldId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM ky_nang WHERE ma_linh_vuc = ? ORDER BY ten_ky_nang");
        $stmt->execute([$fieldId]);
        return $stmt->fetchAll();
    }

    // Tìm 1 kỹ năng
    public static function find($id)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM ky_nang WHERE ma_ky_nang = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tạo mới
    public static function create($name, $fieldId = null)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO ky_nang (ten_ky_nang, ma_linh_vuc) VALUES (?, ?)");
        return $stmt->execute([$name, $fieldId]);
    }

    // Cập nhật
    public static function update($id, $name, $fieldId = null)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE ky_nang SET ten_ky_nang = ?, ma_linh_vuc = ? WHERE ma_ky_nang = ?");
        return $stmt->execute([$name, $fieldId, $id]);
    }

    // Xóa
    public static function delete($id)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM ky_nang WHERE ma_ky_nang = ?");
        return $stmt->execute([$id]);
    }
}
