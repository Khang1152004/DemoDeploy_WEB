<?php
require_once __DIR__ . '/../core/Database.php';

class CV
{
    // Lấy tất cả CV của 1 ứng viên
    public static function byCandidate($candidateId)
    {
        $conn = Database::getConnection();
        $sql = "SELECT cv.*, l.ten_linh_vuc
                FROM ho_so_cv cv
                LEFT JOIN linh_vuc l ON cv.ma_linh_vuc = l.ma_linh_vuc
                WHERE cv.ma_ung_vien = ?
                ORDER BY cv.ma_cv DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$candidateId]);
        return $stmt->fetchAll();
    }

    // Tìm 1 CV cụ thể
    public static function find($cvId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM ho_so_cv WHERE ma_cv = ?");
        $stmt->execute([$cvId]);
        return $stmt->fetch();
    }

    // Tạo CV mới
    public static function create($candidateId, $data)
    {
        $conn = Database::getConnection();
        $sql = "INSERT INTO ho_so_cv
                (ma_ung_vien, tieu_de, file_cv, mo_ta, ma_linh_vuc)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $ok = $stmt->execute([
            $candidateId,
            $data['tieu_de'],
            $data['file_cv'],
            $data['mo_ta'],
            $data['ma_linh_vuc'],
        ]);
        if ($ok) {
            return $conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật CV
    public static function update($cvId, $data)
    {
        $conn = Database::getConnection();
        $sql = "UPDATE ho_so_cv
                SET tieu_de = ?,
                    file_cv = ?,
                    mo_ta = ?,
                    ma_linh_vuc = ?
                WHERE ma_cv = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $data['tieu_de'],
            $data['file_cv'],
            $data['mo_ta'],
            $data['ma_linh_vuc'],
            $cvId,
        ]);
    }

    // Xóa CV
    public static function delete($cvId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM ho_so_cv WHERE ma_cv = ?");
        return $stmt->execute([$cvId]);
    }

    // Lưu kỹ năng của CV (cv_kynang) – xóa hết rồi thêm lại
    public static function syncSkills($cvId, $skillIds)
    {
        $conn = Database::getConnection();
        $conn->beginTransaction();
        try {
            // Xóa cũ
            $stmt = $conn->prepare("DELETE FROM cv_kynang WHERE ma_cv = ?");
            $stmt->execute([$cvId]);

            // Thêm mới
            if (!empty($skillIds)) {
                $stmt = $conn->prepare("INSERT INTO cv_kynang (ma_cv, ma_ky_nang) VALUES (?, ?)");
                foreach ($skillIds as $sid) {
                    $stmt->execute([$cvId, $sid]);
                }
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }

    // Lấy danh sách kỹ năng của 1 CV
    public static function getSkills($cvId)
    {
        $conn = Database::getConnection();
        $sql = "SELECT k.*
                FROM cv_kynang ck
                JOIN ky_nang k ON ck.ma_ky_nang = k.ma_ky_nang
                WHERE ck.ma_cv = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cvId]);
        return $stmt->fetchAll();
    }
}
