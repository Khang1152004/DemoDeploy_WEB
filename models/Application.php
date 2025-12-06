<?php
require_once __DIR__ . '/../core/Database.php';

class Application {
    public static function create($jobId, $candidateId, $data) {
        $conn = Database::getConnection();
        $sql = "INSERT INTO don_ung_tuyen
                (ma_tin_tuyen_dung, ma_ung_vien, ngay_nop, trang_thai,
                 ho_ten_lien_he, email_lien_he, sdt_lien_he, cv_file)
                VALUES (?, ?, CURDATE(), 'submitted', ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            (int)$jobId,
            (int)$candidateId,
            $data['ho_ten'],
            $data['email'],
            $data['sdt'],
            $data['cv_file'],
        ]);
    }

    public static function byJob($jobId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare(
            "SELECT * FROM don_ung_tuyen WHERE ma_tin_tuyen_dung = ? ORDER BY ma_don DESC"
        );
        $stmt->execute([(int)$jobId]);
        return $stmt->fetchAll();
    }

    public static function updateStatus($appId, $status) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE don_ung_tuyen SET trang_thai = ? WHERE ma_don = ?");
        $stmt->execute([$status, (int)$appId]);
    }

    public static function find($appId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM don_ung_tuyen WHERE ma_don = ?");
        $stmt->execute([(int)$appId]);
        return $stmt->fetch();
    }
}
