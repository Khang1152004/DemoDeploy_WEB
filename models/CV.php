<?php
require_once __DIR__ . '/../core/Database.php';

class CV {
    public static function getByCandidate($userId) {
        $conn = Database::getConnection();
        $sql = "SELECT cv.*, lv.ten_linh_vuc,
                       GROUP_CONCAT(kn.ten_ky_nang SEPARATOR ', ') AS ky_nang
                FROM ho_so_cv cv
                JOIN linh_vuc lv ON cv.ma_linh_vuc = lv.ma_linh_vuc
                LEFT JOIN cv_kynang ck ON ck.ma_cv = cv.ma_cv
                LEFT JOIN ky_nang kn ON kn.ma_ky_nang = ck.ma_ky_nang
                WHERE cv.ma_ung_vien = ?
                GROUP BY cv.ma_cv
                ORDER BY cv.ma_cv DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([(int)$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy mã CV mặc định của ứng viên.
     */
    public static function getDefaultId($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT ma_cv FROM ho_so_cv WHERE ma_ung_vien = ? AND is_default = 1 LIMIT 1");
        $stmt->execute([(int)$userId]);
        $row = $stmt->fetch();
        return $row ? (int)$row['ma_cv'] : 0;
    }

    /**
     * Đặt 1 CV làm mặc định cho ứng viên (soft setting).
     */
    public static function setDefault($cvId, $userId) {
        $conn = Database::getConnection();
        $conn->beginTransaction();
        try {
            // Clear default
            $stmt = $conn->prepare("UPDATE ho_so_cv SET is_default = 0 WHERE ma_ung_vien = ?");
            $stmt->execute([(int)$userId]);

            // Set new default (only if belongs to candidate)
            $stmt2 = $conn->prepare("UPDATE ho_so_cv SET is_default = 1 WHERE ma_cv = ? AND ma_ung_vien = ?");
            $stmt2->execute([(int)$cvId, (int)$userId]);

            $conn->commit();
            return $stmt2->rowCount() > 0;
        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }


    /**
     * Lấy CV theo mã CV nhưng đảm bảo thuộc về ứng viên hiện tại.
     * Trả về 1 record hoặc false.
     */
    public static function findForCandidate($cvId, $userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM ho_so_cv WHERE ma_cv = ? AND ma_ung_vien = ?");
        $stmt->execute([(int)$cvId, (int)$userId]);
        return $stmt->fetch();
    }

    /**
     * Danh sách CV đơn giản để dùng cho dropdown chọn khi ứng tuyển.
     */
    public static function listSimpleByCandidate($userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT ma_cv, ten_cv, file_cv, ma_linh_vuc, is_default FROM ho_so_cv WHERE ma_ung_vien = ? ORDER BY is_default DESC, ma_cv DESC");
        $stmt->execute([(int)$userId]);
        return $stmt->fetchAll();
    }

    public static function create($userId, $fieldId, $skills, $filePath, $cvName = null) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare(
            "INSERT INTO ho_so_cv (ma_ung_vien, ma_linh_vuc, ten_cv, file_cv, is_default) VALUES (?,?,?,?,0)"
        );

        $cvName = trim((string)$cvName);
        if ($cvName === '') {
            $cvName = null;
        }

        if (!$stmt->execute([(int)$userId, (int)$fieldId, $cvName, $filePath])) {
            return false;
        }

        $cvId = (int)$conn->lastInsertId();

        // Nếu chưa có CV mặc định thì set CV vừa tạo làm mặc định
        $hasDefaultStmt = $conn->prepare("SELECT COUNT(*) AS c FROM ho_so_cv WHERE ma_ung_vien = ? AND is_default = 1");
        $hasDefaultStmt->execute([(int)$userId]);
        $hasDefault = (int)($hasDefaultStmt->fetch()['c'] ?? 0);
        if ($hasDefault === 0) {
            self::setDefault($cvId, $userId);
        }

        if (!empty($skills)) {
            $stmt2 = $conn->prepare("INSERT INTO cv_kynang (ma_cv, ma_ky_nang) VALUES (?,?)");
            foreach ($skills as $sid) {
                $sid = (int)$sid;
                if ($sid <= 0) continue;
                $stmt2->execute([$cvId, $sid]);
            }
        }
        return true;
    }

    public static function delete($cvId, $userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM ho_so_cv WHERE ma_cv = ? AND ma_ung_vien = ?");
        $stmt->execute([(int)$cvId, (int)$userId]);
        $cv = $stmt->fetch();
        if (!$cv) return;

        $wasDefault = !empty($cv['is_default']);

        $stmt2 = $conn->prepare("DELETE FROM cv_kynang WHERE ma_cv = ?");
        $stmt2->execute([(int)$cvId]);

        $stmt3 = $conn->prepare("DELETE FROM ho_so_cv WHERE ma_cv = ?");
        $stmt3->execute([(int)$cvId]);

        if (!empty($cv['file_cv'])) {
            $full = __DIR__ . '/../' . $cv['file_cv'];
            if (file_exists($full)) @unlink($full);
        }

        // Nếu xóa CV mặc định thì tự động set CV mới nhất còn lại làm mặc định
        if ($wasDefault) {
            $stmt4 = $conn->prepare("SELECT ma_cv FROM ho_so_cv WHERE ma_ung_vien = ? ORDER BY ma_cv DESC LIMIT 1");
            $stmt4->execute([(int)$userId]);
            $row = $stmt4->fetch();
            if ($row) {
                self::setDefault((int)$row['ma_cv'], $userId);
            }
        }
    }

    public static function search($fieldId = null, $skillId = null) {
        $conn = Database::getConnection();
        $sql = "SELECT cv.*, lv.ten_linh_vuc,
                       GROUP_CONCAT(DISTINCT kn.ten_ky_nang SEPARATOR ', ') AS ky_nang,
                       u.email, v.ho_ten, v.sdt
                FROM ho_so_cv cv
                JOIN linh_vuc lv ON cv.ma_linh_vuc = lv.ma_linh_vuc
                LEFT JOIN cv_kynang ck ON ck.ma_cv = cv.ma_cv
                LEFT JOIN ky_nang kn ON kn.ma_ky_nang = ck.ma_ky_nang
                LEFT JOIN ung_vien v ON v.ma_ung_vien = cv.ma_ung_vien
                LEFT JOIN nguoi_dung u ON u.ma_nguoi_dung = v.ma_ung_vien
                WHERE 1=1";
        $params = [];

        if ($fieldId) {
            $sql .= " AND cv.ma_linh_vuc = ?";
            $params[] = (int)$fieldId;
        }
        if ($skillId) {
            $sql .= " AND ck.ma_ky_nang = ?";
            $params[] = (int)$skillId;
        }

        $sql .= " GROUP BY cv.ma_cv ORDER BY cv.ma_cv DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
