<?php
require_once __DIR__ . '/../core/Database.php';

class Field {
    public static function all() {
        $conn = Database::getConnection();
        $res = $conn->query("SELECT * FROM linh_vuc ORDER BY ten_linh_vuc");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function create($name) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO linh_vuc (ten_linh_vuc) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM linh_vuc WHERE ma_linh_vuc = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
