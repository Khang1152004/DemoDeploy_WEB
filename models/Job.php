<?php
require_once __DIR__ . '/../core/Database.php';

class Job {
    public static function latestApproved($limit = 10) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT t.*, d.ten_cong_ty
            FROM tin_tuyen_dung t
            LEFT JOIN doanh_nghiep d ON t.ma_doanh_nghiep = d.ma_doanh_nghiep
            WHERE t.trang_thai_tin_dang IN ('approved', 'delete_pending')
            AND t.han_nop_ho_so >= CURDATE()
            ORDER BY t.ma_tin_tuyen_dung DESC
            LIMIT ?");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function create($employerId, $data) {
        $conn = Database::getConnection();
        $sql = "INSERT INTO tin_tuyen_dung
            (ma_doanh_nghiep, tieu_de, mo_ta_cong_viec, yeu_cau_ung_vien,
             muc_luong_khoang, ma_linh_vuc, ma_dia_diem, han_nop_ho_so, trang_thai_tin_dang)
             VALUES (?,?,?,?,?,?,?,?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssiss",
            $employerId,
            $data['tieu_de'],
            $data['mo_ta_cong_viec'],
            $data['yeu_cau_ung_vien'],
            $data['muc_luong_khoang'],
            $data['ma_linh_vuc'],
            $data['ma_dia_diem'],
            $data['han_nop_ho_so']
        );
        return $stmt->execute();
    }

    public static function byEmployer($employerId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM tin_tuyen_dung WHERE ma_doanh_nghiep = ? ORDER BY ma_tin_tuyen_dung DESC");
        $stmt->bind_param("i", $employerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function get($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM tin_tuyen_dung WHERE ma_tin_tuyen_dung = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function listByStatus($status) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT t.*, d.ten_cong_ty
            FROM tin_tuyen_dung t
            LEFT JOIN doanh_nghiep d ON t.ma_doanh_nghiep = d.ma_doanh_nghiep
            WHERE t.trang_thai_tin_dang = ?
            ORDER BY t.ma_tin_tuyen_dung DESC");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function updateStatus($jobId, $status) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE tin_tuyen_dung SET trang_thai_tin_dang = ? WHERE ma_tin_tuyen_dung = ?");
        $stmt->bind_param("si", $status, $jobId);
        $stmt->execute();
    }


    public static function countAll() {
        $conn = Database::getConnection();
        $res = $conn->query("SELECT COUNT(*) AS c FROM tin_tuyen_dung");
        $row = $res->fetch_assoc();
        return (int)$row['c'];
    }

    public static function countByStatus($status) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM tin_tuyen_dung WHERE trang_thai_tin_dang = ?");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (int)$row['c'];
    }

    
    public static function searchApproved($keyword = '', $fieldId = 0, $locationId = 0, $salaryKeyword = '') {
        $conn = Database::getConnection();
        $sql = "SELECT t.*, d.ten_cong_ty
                FROM tin_tuyen_dung t
                LEFT JOIN doanh_nghiep d ON t.ma_doanh_nghiep = d.ma_doanh_nghiep
                WHERE t.trang_thai_tin_dang IN ('approved', 'delete_pending')
                AND t.han_nop_ho_so >= CURDATE()";
        $params = [];
        $types = "";

        if ($keyword !== '') {
            $sql .= " AND (t.tieu_de LIKE ? OR t.mo_ta_cong_viec LIKE ? OR t.yeu_cau_ung_vien LIKE ?)";
            $like = '%' . $keyword . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $types .= "sss";
        }

        if ($salaryKeyword !== '') {
            $sql .= " AND t.muc_luong_khoang LIKE ?";
            $params[] = '%' . $salaryKeyword . '%';
            $types .= "s";
        }

        if ($fieldId > 0) {
            $sql .= " AND t.ma_linh_vuc = ?";
            $params[] = $fieldId;
            $types .= "i";
        }

        if ($locationId > 0) {
            $sql .= " AND t.ma_dia_diem = ?";
            $params[] = $locationId;
            $types .= "i";
        }

        $sql .= " ORDER BY t.ma_tin_tuyen_dung DESC";

        if ($params) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
        } else {
            $stmt = $conn->prepare($sql);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

}
