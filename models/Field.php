<?php
require_once __DIR__ . '/../core/Database.php';

class Field
{
    // Lấy tất cả lĩnh vực
    public static function all()
    {
        $conn = Database::getConnection(); // PDO
        $sql = "SELECT * FROM linh_vuc ORDER BY ten_linh_vuc";
        $stmt = $conn->query($sql);        // dùng query trực tiếp
        return $stmt->fetchAll();          // ✅ PDO: trả về mảng các dòng (FETCH_ASSOC)
    }

    // Tìm 1 lĩnh vực theo id (nếu có dùng)
    public static function find($id)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM linh_vuc WHERE ma_linh_vuc = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(); // 1 dòng hoặc false
    }

    // Tạo lĩnh vực mới (cho trang admin nếu có)
    public static function create($name)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO linh_vuc (ten_linh_vuc) VALUES (?)");
        return $stmt->execute([$name]);
    }

    // Cập nhật lĩnh vực
    public static function update($id, $name)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE linh_vuc SET ten_linh_vuc = ? WHERE ma_linh_vuc = ?");
        return $stmt->execute([$name, $id]);
    }

    // Xóa lĩnh vực
    public static function delete($id)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM linh_vuc WHERE ma_linh_vuc = ?");
        return $stmt->execute([$id]);
    }
}
