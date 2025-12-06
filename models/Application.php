<?php
require_once __DIR__ . '/../core/Database.php';

class Application
{
    // Tạo đơn ứng tuyển mới
    public static function create($jobId, $candidateId, $data)
    {
        $conn = Database::getConnection();
        $sql = "INSERT INTO don_ung_tuyen
                (ma_tin_tuyen_dung, ma_ung_vien, ngay_nop, trang_thai,
                 ho_ten_lien_he, email_lien_he, sdt_lien_he, cv_file)
                VALUES (?, ?, CURDATE(), 'submitted', ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $jobId,
            $candidateId,
            $data['ho_ten'],
            $data['email'],
            $data['sdt'],
            $data['cv_file'],
        ]);
    }

    // Lấy đơn theo id
    public static function find($appId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM don_ung_tuyen WHERE ma_don = ?");
        $stmt->execute([$appId]);
        return $stmt->fetch();
    }

    // Lấy tất cả đơn của 1 ứng viên
    public static function byCandidate($candidateId)
    {
        $conn = Database::getConnection();
        $sql = "SELECT a.*, t.tieu_de
                FROM don_ung_tuyen a
                LEFT JOIN tin_tuyen_dung t ON a.ma_tin_tuyen_dung = t.ma_tin_tuyen_dung
                WHERE a.ma_ung_vien = ?
                ORDER BY a.ma_don DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$candidateId]);
        return $stmt->fetchAll();
    }

    // Lấy tất cả đơn cho 1 tin tuyển dụng (doanh nghiệp xem)
    public static function byJob($jobId)
    {
        $conn = Database::getConnection();
        $sql = "SELECT a.*, u.ho_ten
                FROM don_ung_tuyen a
                LEFT JOIN ung_vien u ON a.ma_ung_vien = u.ma_ung_vien
                WHERE a.ma_tin_tuyen_dung = ?
                ORDER BY a.ma_don DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$jobId]);
        return $stmt->fetchAll();
    }

    // Cập nhật trạng thái đơn (accepted, rejected, reviewing,...)
    public static function updateStatus($appId, $status)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE don_ung_tuyen SET trang_thai = ? WHERE ma_don = ?");
        return $stmt->execute([$status, $appId]);
    }

    // Đếm số đơn cho 1 tin
    public static function countByJob($jobId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM don_ung_tuyen WHERE ma_tin_tuyen_dung = ?");
        $stmt->execute([$jobId]);
        $row = $stmt->fetch();
        return (int)$row['c'];
    }
}
