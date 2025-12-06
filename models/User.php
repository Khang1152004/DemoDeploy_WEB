<?php
require_once __DIR__ . '/../core/Database.php';

class User {
    // Tìm user theo email
    public static function findByEmail($email) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(); // trả về array hoặc false
    }

    // Tìm user theo id
    public static function findById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE ma_nguoi_dung = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Đăng ký tài khoản mới
    public static function register($email, $password, $role, $token) {
        $conn = Database::getConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $isVerified = 0;
        $sql = "INSERT INTO nguoi_dung (email, mat_khau_hash, vai_tro, is_verified, verification_token)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $ok = $stmt->execute([
            $email,
            $hash,
            $role,
            $isVerified,
            $token,
        ]);
        if ($ok) {
            return $conn->lastInsertId();
        }
        return false;
    }

    // Tìm user theo token xác thực email
    public static function findByVerificationToken($token) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE verification_token = ? LIMIT 1");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    // Đánh dấu tài khoản đã xác thực (kích hoạt)
    public static function markVerified($userId) {
        $conn = Database::getConnection();
        $sql = "UPDATE nguoi_dung
                SET is_verified = 1,
                    verification_token = NULL
                WHERE ma_nguoi_dung = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$userId]);
    }

    // Đổi mật khẩu
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
        $stmt->execute([$hash, $userId]);
        return ['success' => true, 'message' => 'Đổi mật khẩu thành công'];
    }

    // Đếm tổng số user
    public static function countAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM nguoi_dung");
        $stmt->execute();
        $row = $stmt->fetch();
        return (int)$row['c'];
    }

    // Đếm số user bị khóa
    public static function countLocked() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM nguoi_dung WHERE trang_thai_hoat_dong = 0");
        $stmt->execute();
        $row = $stmt->fetch();
        return (int)$row['c'];
    }

    // Lấy tất cả user (dành cho admin)
    public static function all() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung ORDER BY ma_nguoi_dung DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Cập nhật trạng thái hoạt động (mở/khóa tài khoản)
    public static function updateStatus($userId, $active) {
        $conn = Database::getConnection();
        $active = $active ? 1 : 0;
        $stmt = $conn->prepare("UPDATE nguoi_dung SET trang_thai_hoat_dong = ? WHERE ma_nguoi_dung = ?");
        return $stmt->execute([$active, $userId]);
    }

    // Đếm user theo vai trò
    public static function countByRole($role) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM nguoi_dung WHERE vai_tro = ?");
        $stmt->execute([$role]);
        $row = $stmt->fetch();
        return (int)$row['c'];
    }

    // Bật/tắt nhận email tuyển dụng
    public static function updateEmailSubscription($userId, $subscribe) {
        $conn = Database::getConnection();
        $subscribe = $subscribe ? 1 : 0;
        $stmt = $conn->prepare("UPDATE nguoi_dung SET nhan_email_tuyendung = ? WHERE ma_nguoi_dung = ?");
        return $stmt->execute([$subscribe, $userId]);
    }

    // Lấy danh sách email ứng viên đang active và đồng ý nhận mail
    public static function getSubscribedEmails() {
        $conn = Database::getConnection();
        $sql = "SELECT email
                FROM nguoi_dung
                WHERE vai_tro = 'ung_vien'
                  AND trang_thai_hoat_dong = 1
                  AND nhan_email_tuyendung = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $emails = [];
        while ($row = $stmt->fetch()) {
            $emails[] = $row['email'];
        }
        return $emails;
    }
}
