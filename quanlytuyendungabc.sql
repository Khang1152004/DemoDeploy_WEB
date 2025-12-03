-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th12 03, 2025 lúc 05:06 AM
-- Phiên bản máy phục vụ: 9.1.0
-- Phiên bản PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `quanlytuyendungabc`
--

DELIMITER $$
--
-- Thủ tục
--
DROP PROCEDURE IF EXISTS `drop_all_fks`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `drop_all_fks` (IN `dbname` VARCHAR(64))   BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE v_table VARCHAR(64);
    DECLARE v_constraint VARCHAR(64);

    DECLARE cur CURSOR FOR
        SELECT TABLE_NAME, CONSTRAINT_NAME
        FROM information_schema.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = dbname
          AND REFERENCED_TABLE_NAME IS NOT NULL;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    SET FOREIGN_KEY_CHECKS = 0;

    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO v_table, v_constraint;
        IF done THEN
            LEAVE read_loop;
        END IF;

        SET @sql = CONCAT(
            'ALTER TABLE `', dbname, '`.`', v_table,
            '` DROP FOREIGN KEY `', v_constraint, '`'
        );

        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END LOOP;

    CLOSE cur;

    SET FOREIGN_KEY_CHECKS = 1;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cv_kynang`
--

DROP TABLE IF EXISTS `cv_kynang`;
CREATE TABLE IF NOT EXISTS `cv_kynang` (
  `ma_cv` int NOT NULL,
  `ma_ky_nang` int NOT NULL,
  PRIMARY KEY (`ma_cv`,`ma_ky_nang`),
  KEY `fk_ck_kynang` (`ma_ky_nang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cv_kynang`
--

INSERT INTO `cv_kynang` (`ma_cv`, `ma_ky_nang`) VALUES
(1, 1),
(1, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

DROP TABLE IF EXISTS `danh_muc`;
CREATE TABLE IF NOT EXISTS `danh_muc` (
  `ma_danh_muc` int NOT NULL AUTO_INCREMENT,
  `ten_danh_muc` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `loai_danh_muc` enum('linh_vuc','dia_diem') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ma_danh_muc`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`, `loai_danh_muc`) VALUES
(1, 'CNTT - Phần mềm', 'linh_vuc'),
(2, 'Kinh doanh', 'linh_vuc'),
(3, 'Nhân sự', 'linh_vuc'),
(4, 'Hà Nội', 'dia_diem'),
(5, 'Hồ Chí Minh', 'dia_diem');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `doanh_nghiep`
--

DROP TABLE IF EXISTS `doanh_nghiep`;
CREATE TABLE IF NOT EXISTS `doanh_nghiep` (
  `ma_doanh_nghiep` int NOT NULL,
  `ten_cong_ty` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dia_chi` text COLLATE utf8mb4_general_ci,
  `mo_ta` text COLLATE utf8mb4_general_ci,
  `trang_thai_duyet` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  PRIMARY KEY (`ma_doanh_nghiep`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `doanh_nghiep`
--

INSERT INTO `doanh_nghiep` (`ma_doanh_nghiep`, `ten_cong_ty`, `dia_chi`, `mo_ta`, `trang_thai_duyet`) VALUES
(2, 'Công ty ABC', 'Hà Nội', 'Công ty phần mềm', 'approved'),
(3, 'Công ty XYZ', 'TP. HCM', 'Công ty Marketing', 'approved'),
(6, NULL, NULL, NULL, 'approved');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_ung_tuyen`
--

DROP TABLE IF EXISTS `don_ung_tuyen`;
CREATE TABLE IF NOT EXISTS `don_ung_tuyen` (
  `ma_don` int NOT NULL AUTO_INCREMENT,
  `ma_tin_tuyen_dung` int DEFAULT NULL,
  `ma_ung_vien` int DEFAULT NULL,
  `ngay_nop` date DEFAULT NULL,
  `trang_thai` enum('submitted','in_review','interview','rejected') COLLATE utf8mb4_general_ci DEFAULT 'submitted',
  `email_lien_he` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sdt_lien_he` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cv_file` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ho_ten_lien_he` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ma_don`),
  KEY `fk_don_ttd` (`ma_tin_tuyen_dung`),
  KEY `fk_don_ungvien` (`ma_ung_vien`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `don_ung_tuyen`
--

INSERT INTO `don_ung_tuyen` (`ma_don`, `ma_tin_tuyen_dung`, `ma_ung_vien`, `ngay_nop`, `trang_thai`, `email_lien_he`, `sdt_lien_he`, `cv_file`, `ho_ten_lien_he`) VALUES
(1, 1, 4, '2025-11-25', 'submitted', NULL, NULL, NULL, NULL),
(2, 1, 5, '2025-11-26', 'in_review', NULL, NULL, NULL, NULL),
(3, 2, 4, '2025-11-30', 'submitted', NULL, NULL, NULL, NULL),
(4, 7, NULL, '2025-12-01', 'submitted', 'khang0867775510@gmail.com', '0867775510', 'uploads/applications/apply_1764576591_6976.doc', 'Nguyen Huu Khang'),
(5, 7, NULL, '2025-12-01', 'submitted', 'khang0867775510@gmail.com', '0867775510', 'uploads/applications/apply_1764578214_1788.doc', 'Nguyen Huu Khang');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ho_so_cv`
--

DROP TABLE IF EXISTS `ho_so_cv`;
CREATE TABLE IF NOT EXISTS `ho_so_cv` (
  `ma_cv` int NOT NULL AUTO_INCREMENT,
  `ma_ung_vien` int NOT NULL,
  `ma_linh_vuc` int DEFAULT NULL,
  `file_cv` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ma_cv`),
  KEY `fk_cv_ungvien` (`ma_ung_vien`),
  KEY `fk_cv_linhvuc` (`ma_linh_vuc`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ho_so_cv`
--

INSERT INTO `ho_so_cv` (`ma_cv`, `ma_ung_vien`, `ma_linh_vuc`, `file_cv`) VALUES
(1, 4, 1, 'uploads/cv/demo_cv1.docx'),
(2, 5, 2, 'uploads/cv/demo_cv2.docx');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ky_nang`
--

DROP TABLE IF EXISTS `ky_nang`;
CREATE TABLE IF NOT EXISTS `ky_nang` (
  `ma_ky_nang` int NOT NULL AUTO_INCREMENT,
  `ten_ky_nang` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ma_linh_vuc` int DEFAULT NULL,
  PRIMARY KEY (`ma_ky_nang`),
  KEY `fk_kynang_linhvuc` (`ma_linh_vuc`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ky_nang`
--

INSERT INTO `ky_nang` (`ma_ky_nang`, `ten_ky_nang`, `ma_linh_vuc`) VALUES
(1, 'PHP', 1),
(2, 'Laravel', 1),
(3, 'Sales B2B', 2),
(4, 'HRM', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `linh_vuc`
--

DROP TABLE IF EXISTS `linh_vuc`;
CREATE TABLE IF NOT EXISTS `linh_vuc` (
  `ma_linh_vuc` int NOT NULL AUTO_INCREMENT,
  `ten_linh_vuc` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ma_linh_vuc`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `linh_vuc`
--

INSERT INTO `linh_vuc` (`ma_linh_vuc`, `ten_linh_vuc`) VALUES
(1, 'Công nghệ thông tin'),
(2, 'Kinh doanh'),
(3, 'Nhân sự');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

DROP TABLE IF EXISTS `nguoi_dung`;
CREATE TABLE IF NOT EXISTS `nguoi_dung` (
  `ma_nguoi_dung` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `mat_khau_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `vai_tro` enum('ung_vien','doanh_nghiep','admin') COLLATE utf8mb4_general_ci NOT NULL,
  `trang_thai_hoat_dong` tinyint(1) DEFAULT '1',
  `nhan_email_tuyendung` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ma_nguoi_dung`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`ma_nguoi_dung`, `email`, `mat_khau_hash`, `vai_tro`, `trang_thai_hoat_dong`, `nhan_email_tuyendung`) VALUES
(1, 'admin@test.com', '$2b$10$ZF08I06wtpAHw2MWFrXcquuer2.KuIs/wQKSSCyIMFhkeIuUp29CW', 'admin', 1, 0),
(2, 'company1@test.com', '$2b$10$ZF08I06wtpAHw2MWFrXcquuer2.KuIs/wQKSSCyIMFhkeIuUp29CW', 'doanh_nghiep', 0, 0),
(3, 'company2@test.com', '$2b$10$ZF08I06wtpAHw2MWFrXcquuer2.KuIs/wQKSSCyIMFhkeIuUp29CW', 'doanh_nghiep', 1, 0),
(4, 'candidate1@test.com', '$2b$10$ZF08I06wtpAHw2MWFrXcquuer2.KuIs/wQKSSCyIMFhkeIuUp29CW', 'ung_vien', 1, 0),
(5, 'candidate2@test.com', '$2b$10$ZF08I06wtpAHw2MWFrXcquuer2.KuIs/wQKSSCyIMFhkeIuUp29CW', 'ung_vien', 1, 0),
(6, 'company3@test.com', '$2y$10$HCUHiQSg/PXchTVwFR6zOuS21tWKmoGO8yQfYAT7A6wfYGHWa4y9W', 'doanh_nghiep', 1, 0),
(7, 'khang0867775510@gmail.com', '$2y$10$uxhEzd1tPHZaRK4lGjeCBOECKifACIeTlxqX6LMIE5Y0IoM.aHmfq', 'ung_vien', 1, 1),
(8, 'candidate3@test.com', '$2y$10$vav9K1mHLsSI89cpG2CZtuWYtAMuOM2zhuDb/bAKPt1njFuQU7RZm', 'ung_vien', 1, 0),
(9, 'candidate4@test.com', '$2y$10$1XCcfCdbpflCtWCW9S8RDu4xP5AIBnmfjJK8WvRGEfqUReuWvOmwS', 'ung_vien', 1, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thong_bao`
--

DROP TABLE IF EXISTS `thong_bao`;
CREATE TABLE IF NOT EXISTS `thong_bao` (
  `ma_thong_bao` int NOT NULL AUTO_INCREMENT,
  `ma_nguoi_nhan` int NOT NULL,
  `noi_dung` text COLLATE utf8mb4_general_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `da_xem` tinyint DEFAULT '0',
  `thoi_gian_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ma_thong_bao`),
  KEY `fk_thongbao_nguoidung` (`ma_nguoi_nhan`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thong_bao`
--

INSERT INTO `thong_bao` (`ma_thong_bao`, `ma_nguoi_nhan`, `noi_dung`, `link`, `da_xem`, `thoi_gian_tao`) VALUES
(1, 1, 'Có tin mới đang chờ duyệt', 'index.php?c=Admin&a=jobs', 1, '2025-12-01 00:25:07'),
(2, 2, 'Tin của bạn đã được duyệt', 'index.php?c=Employer&a=jobs', 1, '2025-12-01 00:25:07'),
(3, 2, 'Tin \'Nhân viên Sales\' đã được duyệt', 'index.php?c=Employer&a=jobs', 1, '2025-12-01 00:31:45'),
(4, 1, 'Doanh nghiệp yêu cầu xóa tin \'Nhân viên Sales\'', 'index.php?c=Admin&a=jobs&status=delete_pending', 1, '2025-12-01 00:41:55'),
(5, 1, 'Tin tuyển dụng mới cần duyệt', 'index.php?c=Admin&a=jobs&status=pending', 1, '2025-12-01 00:42:16'),
(6, 2, 'Tin \'PHP dev\' đã được duyệt', 'index.php?c=Employer&a=jobs', 0, '2025-12-01 00:42:32'),
(7, 1, 'Tin tuyển dụng mới cần duyệt', 'index.php?c=Admin&a=jobs&status=pending', 1, '2025-12-01 01:43:47'),
(8, 3, 'Tin \'JAVA DEF Giỏi về Frontend\' đã được duyệt', 'index.php?c=Employer&a=jobs', 1, '2025-12-01 01:43:58'),
(9, 1, 'Tin tuyển dụng mới cần duyệt', 'index.php?c=Admin&a=jobs&status=pending', 1, '2025-12-01 02:30:18'),
(10, 3, 'Tin \'PHP dev full Stack\' đã được duyệt', 'index.php?c=Employer&a=jobs', 1, '2025-12-01 02:30:35'),
(11, 3, 'Yêu cầu xóa tin \'HR Executive\' đã bị từ chối', 'index.php?c=Employer&a=jobs', 1, '2025-12-01 13:07:42'),
(12, 2, 'Yêu cầu xóa tin \'Nhân viên Sales\' đã bị từ chối', 'index.php?c=Employer&a=jobs', 0, '2025-12-01 13:07:43'),
(13, 1, 'Doanh nghiệp yêu cầu xóa tin \'HR Executive\'', 'index.php?c=Admin&a=jobs&status=delete_pending', 1, '2025-12-01 13:08:10'),
(14, 3, 'Có ứng viên ứng tuyển tin \'PHP dev full Stack\'', 'index.php?c=Employer&a=applications&job_id=7', 1, '2025-12-01 15:09:51'),
(15, 3, 'Có ứng viên ứng tuyển tin \'PHP dev full Stack\'', 'index.php?c=Employer&a=applications&job_id=7', 1, '2025-12-01 15:36:54'),
(16, 3, 'Tin \'HR Executive\' đã được xóa theo yêu cầu', 'index.php?c=Employer&a=jobs', 1, '2025-12-02 18:25:55'),
(17, 1, 'Tin tuyển dụng mới cần duyệt', 'index.php?c=Admin&a=jobs&status=pending', 1, '2025-12-02 18:29:56'),
(18, 1, 'Doanh nghiệp yêu cầu xóa tin \'PHP dev full Stack\'', 'index.php?c=Admin&a=jobs&status=delete_pending', 1, '2025-12-02 18:31:28'),
(19, 3, 'Yêu cầu xóa tin \'PHP dev full Stack\' đã bị từ chối', 'index.php?c=Employer&a=jobs', 0, '2025-12-02 18:31:51'),
(20, 1, 'Tin tuyển dụng mới cần duyệt', 'index.php?c=Admin&a=jobs&status=pending', 1, '2025-12-02 18:57:13'),
(21, 6, 'Tin \'PHP dev\' đã được duyệt', 'index.php?c=Employer&a=jobs', 0, '2025-12-02 18:57:36'),
(22, 6, 'Tin \'PHP dev\' đã được duyệt', 'index.php?c=Employer&a=jobs', 0, '2025-12-02 18:57:41'),
(23, 1, 'Doanh nghiệp yêu cầu xóa tin \'PHP dev full Stack\'', 'index.php?c=Admin&a=jobs&status=delete_pending', 0, '2025-12-02 19:11:36'),
(24, 1, 'Doanh nghiệp yêu cầu xóa tin \'JAVA DEF Giỏi về Frontend\'', 'index.php?c=Admin&a=jobs&status=delete_pending', 0, '2025-12-02 19:13:18'),
(25, 1, 'Tin tuyển dụng mới cần duyệt', 'index.php?c=Admin&a=jobs&status=pending', 0, '2025-12-02 19:14:11'),
(26, 3, 'Tin \'PHP dev full Stack\' đã được xóa theo yêu cầu', 'index.php?c=Employer&a=jobs', 0, '2025-12-02 19:17:21'),
(27, 1, 'Doanh nghiệp yêu cầu xóa tin \'PHP dev\'', 'index.php?c=Admin&a=jobs&status=delete_pending', 0, '2025-12-02 19:19:51'),
(28, 6, 'Bạn đã gửi yêu cầu xóa tin \'PHP dev\'', 'index.php?c=Employer&a=jobs', 0, '2025-12-02 19:19:51'),
(29, 3, 'Tin \'PHP dev full Stack\' đã được duyệt', 'index.php?c=Employer&a=jobs', 0, '2025-12-02 22:06:34'),
(30, 6, 'Tin \'PHP dev\' đã được xóa theo yêu cầu', 'index.php?c=Employer&a=jobs', 0, '2025-12-02 22:06:47'),
(31, 1, 'Tin tuyển dụng mới cần duyệt', 'index.php?c=Admin&a=jobs&status=pending', 0, '2025-12-02 22:08:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tin_tuyen_dung`
--

DROP TABLE IF EXISTS `tin_tuyen_dung`;
CREATE TABLE IF NOT EXISTS `tin_tuyen_dung` (
  `ma_tin_tuyen_dung` int NOT NULL AUTO_INCREMENT,
  `ma_doanh_nghiep` int DEFAULT NULL,
  `tieu_de` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mo_ta_cong_viec` text COLLATE utf8mb4_general_ci,
  `yeu_cau_ung_vien` text COLLATE utf8mb4_general_ci,
  `muc_luong_khoang` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ma_linh_vuc` int DEFAULT NULL,
  `ma_dia_diem` int DEFAULT NULL,
  `han_nop_ho_so` date DEFAULT NULL,
  `trang_thai_tin_dang` enum('pending','approved','rejected','delete_pending','deleted') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  PRIMARY KEY (`ma_tin_tuyen_dung`),
  KEY `fk_ttd_doanhnghiep` (`ma_doanh_nghiep`),
  KEY `fk_ttd_linhvuc` (`ma_linh_vuc`),
  KEY `fk_ttd_diadiem` (`ma_dia_diem`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tin_tuyen_dung`
--

INSERT INTO `tin_tuyen_dung` (`ma_tin_tuyen_dung`, `ma_doanh_nghiep`, `tieu_de`, `mo_ta_cong_viec`, `yeu_cau_ung_vien`, `muc_luong_khoang`, `ma_linh_vuc`, `ma_dia_diem`, `han_nop_ho_so`, `trang_thai_tin_dang`) VALUES
(1, 2, 'Lập trình PHP', 'Phát triển website', 'Kinh nghiệm PHP', '15-20 triệu', 1, 4, '2025-12-31', 'approved'),
(2, 2, 'Nhân viên Sales', 'Bán hàng B2B', 'Kỹ năng giao tiếp tốt', '10-15 triệu', 2, 5, '2025-12-25', 'approved'),
(3, 3, 'HR Executive', 'Tuyển dụng nhân sự', 'Có kinh nghiệm tuyển dụng', '12-18 triệu', 3, 4, '2025-12-20', 'deleted'),
(4, 3, 'Laravel Developer', 'Dự án nội bộ', 'Biết Laravel', '18-25 triệu', 1, 5, '2025-12-22', 'rejected'),
(5, 2, 'PHP dev', 'saddsa', 'ádsa', '10 trieu', 3, NULL, '2026-01-03', 'approved'),
(6, 3, 'JAVA DEF Giỏi về Frontend', 'Lam front end', '>30 tuoi', '20 trieu', 1, NULL, '2025-12-02', 'delete_pending'),
(7, 3, 'PHP dev full Stack', 'php full stack gánh cả đội', 'trên 25 tuổi', '25 triệu', 1, NULL, '2025-12-27', 'deleted'),
(8, 3, 'PHP dev full Stack', '12321', '3213', '10 trieu', 1, NULL, '2025-12-04', 'approved'),
(9, 6, 'PHP dev', '123', '123', '10 trieu', 1, 5, '2025-12-27', 'deleted'),
(10, 3, 'PHP dev', '123', '123', '10 trieu', 1, 5, '2025-12-03', 'pending'),
(11, 3, 'PHP dev', '123', '123', '123', 1, 5, '2025-12-26', 'pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ung_vien`
--

DROP TABLE IF EXISTS `ung_vien`;
CREATE TABLE IF NOT EXISTS `ung_vien` (
  `ma_ung_vien` int NOT NULL,
  `ho_ten` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sdt` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dia_chi` text COLLATE utf8mb4_general_ci,
  `mo_ta_ngan` text COLLATE utf8mb4_general_ci,
  `muc_luong_mong_muon` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ma_ung_vien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ung_vien`
--

INSERT INTO `ung_vien` (`ma_ung_vien`, `ho_ten`, `sdt`, `dia_chi`, `mo_ta_ngan`, `muc_luong_mong_muon`) VALUES
(4, 'Nguyễn Văn A', '0901112222', 'Hà Nội', 'Ứng viên CNTT', '15-20 triệu'),
(5, 'Trần Thị B', '0903334444', 'HCM', 'Ứng viên kinh doanh', '12-18 triệu'),
(7, 'Nguyen Huu Khang', '0867775510', 'HCM', '', ''),
(8, NULL, NULL, NULL, NULL, NULL),
(9, NULL, NULL, NULL, NULL, NULL);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cv_kynang`
--
ALTER TABLE `cv_kynang`
  ADD CONSTRAINT `fk_ck_cv` FOREIGN KEY (`ma_cv`) REFERENCES `ho_so_cv` (`ma_cv`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ck_kynang` FOREIGN KEY (`ma_ky_nang`) REFERENCES `ky_nang` (`ma_ky_nang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `doanh_nghiep`
--
ALTER TABLE `doanh_nghiep`
  ADD CONSTRAINT `fk_doanhnghiep_nguoidung` FOREIGN KEY (`ma_doanh_nghiep`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `don_ung_tuyen`
--
ALTER TABLE `don_ung_tuyen`
  ADD CONSTRAINT `fk_don_ttd` FOREIGN KEY (`ma_tin_tuyen_dung`) REFERENCES `tin_tuyen_dung` (`ma_tin_tuyen_dung`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_don_ungvien` FOREIGN KEY (`ma_ung_vien`) REFERENCES `ung_vien` (`ma_ung_vien`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `ho_so_cv`
--
ALTER TABLE `ho_so_cv`
  ADD CONSTRAINT `fk_cv_linhvuc` FOREIGN KEY (`ma_linh_vuc`) REFERENCES `linh_vuc` (`ma_linh_vuc`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cv_ungvien` FOREIGN KEY (`ma_ung_vien`) REFERENCES `ung_vien` (`ma_ung_vien`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `ky_nang`
--
ALTER TABLE `ky_nang`
  ADD CONSTRAINT `fk_kynang_linhvuc` FOREIGN KEY (`ma_linh_vuc`) REFERENCES `linh_vuc` (`ma_linh_vuc`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD CONSTRAINT `fk_thongbao_nguoidung` FOREIGN KEY (`ma_nguoi_nhan`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `tin_tuyen_dung`
--
ALTER TABLE `tin_tuyen_dung`
  ADD CONSTRAINT `fk_ttd_diadiem` FOREIGN KEY (`ma_dia_diem`) REFERENCES `danh_muc` (`ma_danh_muc`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ttd_doanhnghiep` FOREIGN KEY (`ma_doanh_nghiep`) REFERENCES `doanh_nghiep` (`ma_doanh_nghiep`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ttd_linhvuc` FOREIGN KEY (`ma_linh_vuc`) REFERENCES `linh_vuc` (`ma_linh_vuc`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `ung_vien`
--
ALTER TABLE `ung_vien`
  ADD CONSTRAINT `fk_ungvien_nguoidung` FOREIGN KEY (`ma_ung_vien`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
