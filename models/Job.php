<?php
require_once __DIR__ . '/../core/Database.php';

class Job
{
    // Tin đã duyệt mới nhất (trang chủ)
    public static function latestApproved($limit = 10)
    {
        $conn = Database::getConnection();
        $sql = "SELECT 
                t.*,
                d.ten_cong_ty,
                lv.ten_linh_vuc,
                dm.ten_danh_muc AS ten_dia_diem
            FROM tin_tuyen_dung t
            LEFT JOIN doanh_nghiep d ON t.ma_doanh_nghiep = d.ma_doanh_nghiep
            LEFT JOIN linh_vuc lv ON t.ma_linh_vuc = lv.ma_linh_vuc
            LEFT JOIN danh_muc dm 
                ON t.ma_dia_diem = dm.ma_danh_muc 
               AND dm.loai_danh_muc = 'dia_diem'
            WHERE t.trang_thai_tin_dang IN ('approved', 'delete_pending')
              AND t.han_nop_ho_so >= CURDATE()
            ORDER BY t.ma_tin_tuyen_dung DESC
            LIMIT :limit";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public static function create($employerId, $data)
    {
        $conn = Database::getConnection();
        $sql = "INSERT INTO tin_tuyen_dung
            (ma_doanh_nghiep, tieu_de, mo_ta_cong_viec, yeu_cau_ung_vien,
             muc_luong_khoang, ma_linh_vuc, ma_dia_diem, han_nop_ho_so, trang_thai_tin_dang)
            VALUES (?,?,?,?,?,?,?,?, 'pending')";
        try {
            $stmt = $conn->prepare($sql);
            return $stmt->execute([
                (int)$employerId,
                $data['tieu_de'],
                $data['mo_ta_cong_viec'],
                $data['yeu_cau_ung_vien'],
                $data['muc_luong_khoang'],
                $data['ma_linh_vuc'],
                $data['ma_dia_diem'],
                $data['han_nop_ho_so'],
            ]);
        } catch (PDOException $e) {
            error_log('Job::create error: ' . $e->getMessage());
            return false;
        }
    }


    public static function byEmployer($employerId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare(
            "SELECT * FROM tin_tuyen_dung WHERE ma_doanh_nghiep = ? ORDER BY ma_tin_tuyen_dung DESC"
        );
        $stmt->execute([(int)$employerId]);
        return $stmt->fetchAll();
    }

    public static function get($id)
    {
        $conn = Database::getConnection();
        $sql = "SELECT 
                t.*,
                d.ten_cong_ty,
                lv.ten_linh_vuc,
                dm.ten_danh_muc AS ten_dia_diem
            FROM tin_tuyen_dung t
            LEFT JOIN doanh_nghiep d 
                ON t.ma_doanh_nghiep = d.ma_doanh_nghiep
            LEFT JOIN linh_vuc lv 
                ON t.ma_linh_vuc = lv.ma_linh_vuc
            LEFT JOIN danh_muc dm 
                ON t.ma_dia_diem = dm.ma_danh_muc 
               AND dm.loai_danh_muc = 'dia_diem'
            WHERE t.ma_tin_tuyen_dung = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }


    // Alias cho ApplicationController (nếu có dùng)
    public static function getById($id)
    {
        return self::get($id);
    }

    public static function listByStatus($status)
    {
        $conn = Database::getConnection();
        $sql = "SELECT t.*, d.ten_cong_ty
                FROM tin_tuyen_dung t
                LEFT JOIN doanh_nghiep d ON t.ma_doanh_nghiep = d.ma_doanh_nghiep
                WHERE t.trang_thai_tin_dang = ?
                ORDER BY t.ma_tin_tuyen_dung DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    public static function updateStatus($id, $status)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare(
            "UPDATE tin_tuyen_dung SET trang_thai_tin_dang = ? WHERE ma_tin_tuyen_dung = ?"
        );
        return $stmt->execute([$status, (int)$id]);
    }

    public static function countAll()
    {
        $conn = Database::getConnection();
        $stmt = $conn->query("SELECT COUNT(*) AS c FROM tin_tuyen_dung");
        $row = $stmt->fetch();
        return (int)$row['c'];
    }

    public static function countByStatus($status)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare(
            "SELECT COUNT(*) AS c FROM tin_tuyen_dung WHERE trang_thai_tin_dang = ?"
        );
        $stmt->execute([$status]);
        $row = $stmt->fetch();
        return (int)$row['c'];
    }

    // Tìm kiếm tin đã duyệt
    public static function searchApproved($keyword = '', $fieldId = 0, $locationId = 0, $salaryKeyword = '')
    {
        $conn = Database::getConnection();
        $sql = "SELECT 
                t.*,
                d.ten_cong_ty,
                lv.ten_linh_vuc,
                dm.ten_danh_muc AS ten_dia_diem
            FROM tin_tuyen_dung t
            LEFT JOIN doanh_nghiep d ON t.ma_doanh_nghiep = d.ma_doanh_nghiep
            LEFT JOIN linh_vuc lv ON t.ma_linh_vuc = lv.ma_linh_vuc
            LEFT JOIN danh_muc dm 
                ON t.ma_dia_diem = dm.ma_danh_muc 
               AND dm.loai_danh_muc = 'dia_diem'
            WHERE t.trang_thai_tin_dang IN ('approved', 'delete_pending')
              AND t.han_nop_ho_so >= CURDATE()";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (t.tieu_de LIKE ? OR t.mo_ta_cong_viec LIKE ? OR t.yeu_cau_ung_vien LIKE ?)";
            $like = '%' . $keyword . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if ($salaryKeyword !== '') {
            $sql .= " AND t.muc_luong_khoang LIKE ?";
            $params[] = '%' . $salaryKeyword . '%';
        }

        if ($fieldId > 0) {
            $sql .= " AND t.ma_linh_vuc = ?";
            $params[] = (int)$fieldId;
        }

        if ($locationId > 0) {
            $sql .= " AND t.ma_dia_diem = ?";
            $params[] = (int)$locationId;
        }

        $sql .= " ORDER BY t.ma_tin_tuyen_dung DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
