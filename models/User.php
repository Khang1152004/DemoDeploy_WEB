<?php
require_once __DIR__ . '/../core/Database.php';

class User
{
    public static function findByEmail($email)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function findById($id)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE ma_nguoi_dung = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function register($email, $password, $role, $token)
    {
        $conn = Database::getConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $isVerified = 0;

        $stmt = $conn->prepare("
        INSERT INTO nguoi_dung (email, mat_khau_hash, vai_tro, is_verified, verification_token)
        VALUES (?, ?, ?, ?, ?)
    ");
        $stmt->bind_param("sssds", $email, $hash, $role, $isVerified, $token);

        if ($stmt->execute()) {
            return $conn->insert_id;
        }
        return false;
    }
    public static function findByVerificationToken($token)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE verification_token = ? LIMIT 1");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function markVerified($userId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
        UPDATE nguoi_dung
        SET is_verified = 1,
            verification_token = NULL
        WHERE ma_nguoi_dung = ?
    ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }


    public static function updatePassword($userId, $current, $new, $confirm)
    {
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


    public static function countAll()
    {
        $conn = Database::getConnection();
        $res = $conn->query("SELECT COUNT(*) AS c FROM nguoi_dung");
        $row = $res->fetch_assoc();
        return (int)$row['c'];
    }

    public static function countLocked()
    {
        $conn = Database::getConnection();
        $res = $conn->query("SELECT COUNT(*) AS c FROM nguoi_dung WHERE trang_thai_hoat_dong = 0");
        $row = $res->fetch_assoc();
        return (int)$row['c'];
    }



    public static function all()
    {
        $conn = Database::getConnection();
        $res = $conn->query("SELECT * FROM nguoi_dung ORDER BY ma_nguoi_dung DESC");
        $rows = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public static function updateStatus($userId, $active)
    {
        $conn = Database::getConnection();
        $active = $active ? 1 : 0;
        $stmt = $conn->prepare("UPDATE nguoi_dung SET trang_thai_hoat_dong = ? WHERE ma_nguoi_dung = ?");
        $stmt->bind_param("ii", $active, $userId);
        return $stmt->execute();
    }

    public static function countByRole($role)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM nguoi_dung WHERE vai_tro = ?");
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        return (int)$row['c'];
    }



    public static function updateEmailSubscription($userId, $subscribe)
    {
        $conn = Database::getConnection();
        $subscribe = $subscribe ? 1 : 0;
        $stmt = $conn->prepare("UPDATE nguoi_dung SET nhan_email_tuyendung = ? WHERE ma_nguoi_dung = ?");
        $stmt->bind_param("ii", $subscribe, $userId);
        return $stmt->execute();
    }

    public static function getSubscribedEmails()
    {
        $conn = Database::getConnection();
        $sql = "SELECT email FROM nguoi_dung WHERE vai_tro = 'ung_vien' AND trang_thai_hoat_dong = 1 AND nhan_email_tuyendung = 1";
        $res = $conn->query($sql);
        $out = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $out[] = $row['email'];
            }
        }
        return $out;
    }
}
