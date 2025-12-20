<?php
require_once __DIR__ . '/../core/Database.php';

class Application
{
    public static function create($jobId, $candidateId, $data)
    {
        $conn = Database::getConnection();
        $sql = "INSERT INTO don_ung_tuyen
        (ma_tin_tuyen_dung, ma_ung_vien, ngay_nop, trang_thai,
         ho_ten_lien_he, email_lien_he, sdt_lien_he, cv_file)
        VALUES (?, ?, CURDATE(), 'submitted', ?, ?, ?, ?)";
        try {
            $stmt = $conn->prepare($sql);

            // Nếu không có ứng viên đăng nhập -> để NULL thật sự
            $candidateParam = ($candidateId === null || $candidateId === '')
                ? null
                : (int)$candidateId;

            return $stmt->execute([
                (int)$jobId,
                $candidateParam,          // KHÔNG cast NULL thành 0
                $data['ho_ten'],
                $data['email'],
                $data['sdt'],
                $data['cv_file'],
            ]);
        } catch (PDOException $e) {
            error_log('Application::create error: ' . $e->getMessage());
            return false;
        }
    }



    public static function byJob($jobId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare(
            "SELECT * FROM don_ung_tuyen WHERE ma_tin_tuyen_dung = ? ORDER BY ma_don DESC"
        );
        $stmt->execute([(int)$jobId]);
        return $stmt->fetchAll();
    }

    public static function updateStatus($appId, $status)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE don_ung_tuyen SET trang_thai = ? WHERE ma_don = ?");
        $stmt->execute([$status, (int)$appId]);
    }

    public static function find($appId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM don_ung_tuyen WHERE ma_don = ?");
        $stmt->execute([(int)$appId]);
        return $stmt->fetch();
    }
    public static function listByCandidate($candidateId)
    {
        $conn = Database::getConnection();

        // Mục tiêu: trả về đúng các key mà dashboard.php đang dùng:
        // tieu_de, ten_cong_ty, ngay_nop, trang_thai
        //
        // Lưu ý: tên cột job & doanh nghiệp trong DB của bạn có thể khác.
        // Ở đây tôi viết theo naming convention bạn đang dùng:
        // - don_ung_tuyen.ma_tin_tuyen_dung
        // - tin_tuyen_dung.tieu_de
        //
        // Phần join công ty: cần join ra ten_cong_ty. 
        // Nếu schema của bạn đặt khác, bạn sẽ phải đổi đúng tên bảng/cột.

        $sql = "
        SELECT 
            d.ngay_nop,
            d.trang_thai,
            t.tieu_de,
            dn.ten_cong_ty
        FROM don_ung_tuyen d
        JOIN tin_tuyen_dung t 
            ON t.ma_tin = d.ma_tin_tuyen_dung
        LEFT JOIN doanh_nghiep dn
            ON dn.ma_doanh_nghiep = t.ma_doanh_nghiep
        WHERE d.ma_ung_vien = ?
        ORDER BY d.ma_don DESC
    ";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([(int)$candidateId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Application::listByCandidate error: ' . $e->getMessage());
            return [];
        }
    }
}
