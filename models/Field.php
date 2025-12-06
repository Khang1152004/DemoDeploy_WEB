<?php
require_once __DIR__ . '/../core/Database.php';

class Field {
    public static function all() {
        $conn = Database::getConnection();
        $stmt = $conn->query("SELECT * FROM linh_vuc ORDER BY ten_linh_vuc");
        return $stmt->fetchAll();
    }

    public static function create($name) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO linh_vuc (ten_linh_vuc) VALUES (?)");
        $stmt->execute([$name]);
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM linh_vuc WHERE ma_linh_vuc = ?");
        $stmt->execute([(int)$id]);
    }
}
