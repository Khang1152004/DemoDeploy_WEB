<?php
require_once __DIR__ . '/../core/Database.php';

class Skill {
    public static function all($fieldId = null) {
        $conn = Database::getConnection();
        if ($fieldId) {
            $stmt = $conn->prepare("SELECT k.*, l.ten_linh_vuc
                FROM ky_nang k
                LEFT JOIN linh_vuc l ON k.ma_linh_vuc = l.ma_linh_vuc
                WHERE k.ma_linh_vuc = ?
                ORDER BY k.ten_ky_nang");
            $stmt->bind_param("i", $fieldId);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        $sql = "SELECT k.*, l.ten_linh_vuc
            FROM ky_nang k
            LEFT JOIN linh_vuc l ON k.ma_linh_vuc = l.ma_linh_vuc
            ORDER BY k.ten_ky_nang";
        $res = $conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function byField($fieldId) {
        if (!$fieldId) return [];
        return self::all($fieldId);
    }

    public static function create($name, $fieldId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO ky_nang (ten_ky_nang, ma_linh_vuc) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $fieldId);
        $stmt->execute();
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM ky_nang WHERE ma_ky_nang = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
