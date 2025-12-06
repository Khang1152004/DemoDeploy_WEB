<?php
require_once __DIR__ . '/../core/Database.php';

class Skill {
    public static function all($fieldId = null) {
        $conn = Database::getConnection();
        if ($fieldId) {
            $sql = "SELECT k.*, l.ten_linh_vuc
                    FROM ky_nang k
                    LEFT JOIN linh_vuc l ON k.ma_linh_vuc = l.ma_linh_vuc
                    WHERE k.ma_linh_vuc = ?
                    ORDER BY k.ten_ky_nang";
            $stmt = $conn->prepare($sql);
            $stmt->execute([(int)$fieldId]);
            return $stmt->fetchAll();
        }
        $sql = "SELECT k.*, l.ten_linh_vuc
                FROM ky_nang k
                LEFT JOIN linh_vuc l ON k.ma_linh_vuc = l.ma_linh_vuc
                ORDER BY k.ten_ky_nang";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    }

    public static function byField($fieldId) {
        if (!$fieldId) return [];
        return self::all($fieldId);
    }

    public static function create($name, $fieldId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO ky_nang (ten_ky_nang, ma_linh_vuc) VALUES (?, ?)");
        $stmt->execute([$name, (int)$fieldId]);
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM ky_nang WHERE ma_ky_nang = ?");
        $stmt->execute([(int)$id]);
    }
}
