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
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function create($userId, $fieldId, $skills, $filePath) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO ho_so_cv (ma_ung_vien, ma_linh_vuc, file_cv) VALUES (?,?,?)");
        $stmt->bind_param("iis", $userId, $fieldId, $filePath);
        if (!$stmt->execute()) return false;
        $cvId = $conn->insert_id;
        if ($skills) {
            $stmt2 = $conn->prepare("INSERT INTO cv_kynang (ma_cv, ma_ky_nang) VALUES (?,?)");
            foreach ($skills as $sid) {
                $sid = (int)$sid;
                if ($sid <= 0) continue;
                $stmt2->bind_param("ii", $cvId, $sid);
                $stmt2->execute();
            }
        }
        return true;
    }

    public static function delete($cvId, $userId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM ho_so_cv WHERE ma_cv = ? AND ma_ung_vien = ?");
        $stmt->bind_param("ii", $cvId, $userId);
        $stmt->execute();
        $cv = $stmt->get_result()->fetch_assoc();
        if (!$cv) return;
        $conn->query("DELETE FROM cv_kynang WHERE ma_cv=".(int)$cvId);
        $stmt2 = $conn->prepare("DELETE FROM ho_so_cv WHERE ma_cv = ?");
        $stmt2->bind_param("i", $cvId);
        $stmt2->execute();
        if (!empty($cv['file_cv'])) {
            $full = __DIR__ . '/../' . $cv['file_cv'];
            if (file_exists($full)) @unlink($full);
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
        $types = "";
        $params = [];
        if ($fieldId) {
            $sql .= " AND cv.ma_linh_vuc = ?";
            $types .= "i"; $params[] = $fieldId;
        }
        if ($skillId) {
            $sql .= " AND ck.ma_ky_nang = ?";
            $types .= "i"; $params[] = $skillId;
        }
        $sql .= " GROUP BY cv.ma_cv ORDER BY cv.ma_cv DESC";
        $stmt = $conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
