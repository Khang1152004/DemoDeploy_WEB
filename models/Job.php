<?php
require_once __DIR__ . '/../core/Database.php';

class Job {
    // Lấy các tin đã duyệt mới nhất (trang chủ)
    public static function latestApproved($limit = 10) {
        $conn = Database::getConnection();

        $sql = "SELECT t.*, d.ten_cong_ty
                FROM tin_tuyen_dung t
                LEFT JOIN doanh_nghiep d ON t.ma_doanh_nghiep = d.ma_doanh_nghiep
                WHERE t.trang_thai_tin_dang IN ('approved', 'delete_pending')
                  AND t.han_nop_ho_so >= CURDATE()
                ORDER BY t.ma_tin_tuyen_dung DESC
                LIMIT :limit";

        $stmt = $conn->prepare($sql);
        // PDO: bind int cho LIMIT
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(); // mảng các job
    }

    // Tạo tin tuyển dụng mới
    public static function create($employerId, $data) {
        $conn = Database::getConnection();

        $sql = "INSERT INTO tin_tuyen_dung
                (ma_doanh_nghiep, tieu_de, mo_ta_cong_viec, yeu_cau_ung_vien,
                 muc_luong_khoang, ma_linh_vuc, ma_dia_diem, han_nop_ho_so, trang_thai_tin_dang)
                VALUES (?,?,?,?,?,?,?,?, 'pending')";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $employerId,
            $data['tieu_de'],
            $data['mo_ta_cong_viec'],
            $data['yeu_cau_ung_vien'],
            $data['muc_luong_khoang'],
            $data['ma_linh_vuc'],
            $data['ma_dia_diem'],
            $data['han_nop_ho_so'],
        ]);
    }

    // Lấy tất cả tin của 1 doanh nghiệp
    public static function byEmployer($employerId) {
        $conn = Database::getConnection();

        $sql = "SELECT * FROM tin_tuyen_dung
                WHERE ma_doanh_nghiep = ?
                ORDER BY ma_tin_tuyen_dung DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$employerId]);

        return $stmt->fetchAll();
    }

    // Lấy chi tiết 1 tin theo id
    public static function get($id) {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("SELECT * FROM tin_tuyen_dung WHERE ma_tin_tuyen_dung = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(); // 1 dòng hoặc false
    }

    // Lấy danh sách tin theo trạng thái (cho admin)
    public static function listByStatus($status) {
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

    // Cập nhật trạng thái tin
    public static function updateStatus($jobId, $status) {
        $conn = Database::getConnection();

        $sql = "UPDATE tin_tuyen_dung
                SET trang_thai_tin_dang = ?
                WHERE ma_tin_tuyen_dung = ?";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([$status, $jobId]);
    }

    // Đếm tất cả tin
    public static function countAll() {
        $conn = Database::getConnection();

        $stmt = $conn->query("SELECT COUNT(*) AS c FROM tin_tuyen_dung");
        $row = $stmt->fetch();

        return (int)$row['c'];
    }

    // Đếm tin theo trạng thái
    public static function countByStatus($status) {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM tin_tuyen_dung WHERE trang_thai_tin_dang = ?");
        $stmt->execute([$status]);
        $row = $stmt->fetch();

        return (int)$row['c'];
    }

    // Tìm kiếm tin đã duyệt (trang danh sách việc làm)
    public static function searchApproved($keyword = '', $fieldId = 0, $locationId = 0, $salaryKeyword = '') {
        $conn = Database::getConnection();

        $sql = "SELECT t.*, d.ten_cong_ty
                FROM tin_tuyen_dung t
                LEFT JOIN doanh_nghiep d ON t.ma_doanh_nghiep = d.ma_doanh_nghiep
                WHERE t.trang_thai_tin_dang IN ('approved', 'delete_pending')
                  AND t.han_nop_ho_so >= CURDATE()";

        $params = [];

        // Từ khóa tiêu đề / mô tả / yêu cầu
        if ($keyword !== '') {
            $sql .= " AND (t.tieu_de LIKE ? OR t.mo_ta_cong_viec LIKE ? OR t.yeu_cau_ung_vien LIKE ?)";
            $like = '%' . $keyword . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        // Từ khóa lương
        if ($salaryKeyword !== '') {
            $sql .= " AND t.muc_luong_khoang LIKE ?";
            $params[] = '%' . $salaryKeyword . '%';
        }

        // Lọc theo lĩnh vực
        if ($fieldId > 0) {
            $sql .= " AND t.ma_linh_vuc = ?";
            $params[] = $fieldId;
        }

        // Lọc theo địa điểm
        if ($locationId > 0) {
            $sql .= " AND t.ma_dia_diem = ?";
            $params[] = $locationId;
        }

        $sql .= " ORDER BY t.ma_tin_tuyen_dung DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

}
