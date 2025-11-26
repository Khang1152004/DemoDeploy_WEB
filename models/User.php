<?php
require_once __DIR__ . '/../core/Database.php';

class User {
    public static function findByEmail($email) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function findById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE ma_nguoi_dung = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function register($email, $password, $role) {
        $conn = Database::getConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO nguoi_dung (email, mat_khau_hash, vai_tro) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $hash, $role);
        if ($stmt->execute()) {
            return $conn->insert_id;
        }
        return false;
    }

    public static function updatePassword($userId, $current, $new, $confirm) {
        $user = self::findById($userId);
        if (!$user) {
            return ['success' => false, 'message' => 'Không tìm thấy người dùng'];
        }
        if (!password_verify($current, $user['mat_khau_hash'])) {
            return ['success' => false, 'message' => 'Mật khẩu hiện tại không đúng'];
        }
        if ($new === '' || $new !== $confirm) {
            return ['success' => false, 'message' => 'Mật khẩu mới xác nhận không khớp'];
        }
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE nguoi_dung SET mat_khau_hash = ? WHERE ma_nguoi_dung = ?");
        $stmt->bind_param("si", $hash, $userId);
        $stmt->execute();
        return ['success' => true, 'message' => 'Đổi mật khẩu thành công'];
    }
}
