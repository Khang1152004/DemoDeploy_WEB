-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: sql308.infinityfree.com
-- Thời gian đã tạo: Th12 15, 2025 lúc 02:34 AM
-- Phiên bản máy phục vụ: 11.4.7-MariaDB
-- Phiên bản PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `quanlytuyendungabc`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cv_kynang`
--

CREATE TABLE `cv_kynang` (
  `ma_cv` int(11) NOT NULL,
  `ma_ky_nang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cv_kynang`
--

INSERT INTO `cv_kynang` (`ma_cv`, `ma_ky_nang`) VALUES
(5, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `ma_danh_muc` int(11) NOT NULL,
  `ten_danh_muc` varchar(100) NOT NULL,
  `loai_danh_muc` enum('linh_vuc','dia_diem') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`, `loai_danh_muc`) VALUES
(1, 'CNTT - Phần mềm', 'linh_vuc'),
(2, 'Kinh doanh', 'linh_vuc'),
(3, 'Nhân sự', 'linh_vuc'),
(4, 'Hà Nội', 'dia_diem'),
(5, 'Hồ Chí Minh', 'dia_diem'),
(6, 'Huế', 'dia_diem');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `doanh_nghiep`
--

CREATE TABLE `doanh_nghiep` (
  `ma_doanh_nghiep` int(11) NOT NULL,
  `ten_cong_ty` varchar(100) DEFAULT NULL,
  `dia_chi` text DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `trang_thai_duyet` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `doanh_nghiep`
--

INSERT INTO `doanh_nghiep` (`ma_doanh_nghiep`, `ten_cong_ty`, `dia_chi`, `mo_ta`, `trang_thai_duyet`) VALUES
(2, 'Công ty ABC', 'Hà Nội', 'Công ty phần mềm', 'approved'),
(3, 'Công ty XYZ', 'TP. HCM', 'Công ty Marketing', 'approved'),
(6, NULL, NULL, NULL, 'approved'),
(26, NULL, NULL, NULL, 'approved');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_ung_tuyen`
--

CREATE TABLE `don_ung_tuyen` (
  `ma_don` int(11) NOT NULL,
  `ma_tin_tuyen_dung` int(11) DEFAULT NULL,
  `ma_ung_vien` int(11) DEFAULT NULL,
  `ngay_nop` date DEFAULT NULL,
  `trang_thai` enum('submitted','in_review','interview','rejected') DEFAULT 'submitted',
  `email_lien_he` varchar(100) DEFAULT NULL,
  `sdt_lien_he` varchar(20) DEFAULT NULL,
  `cv_file` varchar(255) DEFAULT NULL,
  `ho_ten_lien_he` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `don_ung_tuyen`
--

INSERT INTO `don_ung_tuyen` (`ma_don`, `ma_tin_tuyen_dung`, `ma_ung_vien`, `ngay_nop`, `trang_thai`, `email_lien_he`, `sdt_lien_he`, `cv_file`, `ho_ten_lien_he`) VALUES
(1, 1, 4, '2025-11-25', 'submitted', NULL, NULL, NULL, NULL),
(2, 1, 5, '2025-11-26', 'in_review', NULL, NULL, NULL, NULL),
(3, 2, 4, '2025-11-30', 'submitted', NULL, NULL, NULL, NULL),
(4, 7, NULL, '2025-12-01', 'submitted', 'khang0867775510@gmail.com', '0867775510', 'uploads/applications/apply_1764576591_6976.doc', 'Nguyen Huu Khang'),
(5, 7, NULL, '2025-12-01', 'submitted', 'khang0867775510@gmail.com', '0867775510', 'uploads/applications/apply_1764578214_1788.doc', 'Nguyen Huu Khang'),
(13, 31, 4, '2025-12-09', 'submitted', 'admin@test.com', '12312512', 'uploads/applications/apply_1765287311_3879.docx', 'Nguyen Huu Khang'),
(14, 31, NULL, '2025-12-09', 'submitted', 'admin@test.com', '0867775510', 'uploads/applications/apply_1765287842_8191.docx', 'Nguyen Huu Khang'),
(15, 23, NULL, '2025-12-10', 'submitted', 'window11@gmail.com', '0123456789', 'uploads/applications/apply_1765435147_1428.jpg', 'tao la ky su'),
(16, 28, NULL, '2025-12-10', 'submitted', 'ldbl@gmail.com', '0123456789', NULL, 'tao la ldbd'),
(17, 25, 7, '2025-12-10', 'submitted', 'khang0867775510@gmail.com', '0867775510', 'uploads/applications/apply_1765438907_3834.docx', 'Nguyen Huu Khang');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ho_so_cv`
--

CREATE TABLE `ho_so_cv` (
  `ma_cv` int(11) NOT NULL,
  `ma_ung_vien` int(11) NOT NULL,
  `ma_linh_vuc` int(11) DEFAULT NULL,
  `ten_cv` varchar(150) DEFAULT NULL,
  `file_cv` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ho_so_cv`
--

INSERT INTO `ho_so_cv` (`ma_cv`, `ma_ung_vien`, `ma_linh_vuc`, `ten_cv`, `file_cv`) VALUES
(5, 7, 1, NULL, 'uploads/cv/cv_1765438690_6687.docx');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ky_nang`
--

CREATE TABLE `ky_nang` (
  `ma_ky_nang` int(11) NOT NULL,
  `ten_ky_nang` varchar(100) NOT NULL,
  `ma_linh_vuc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `linh_vuc` (
  `ma_linh_vuc` int(11) NOT NULL,
  `ten_linh_vuc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `nguoi_dung` (
  `ma_nguoi_dung` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mat_khau_hash` varchar(255) NOT NULL,
  `vai_tro` enum('ung_vien','doanh_nghiep','admin') NOT NULL,
  `trang_thai_hoat_dong` tinyint(1) DEFAULT 1,
  `nhan_email_tuyendung` tinyint(1) NOT NULL DEFAULT 0,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verification_token` varchar(64) DEFAULT NULL,
  `verification_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`ma_nguoi_dung`, `email`, `mat_khau_hash`, `vai_tro`, `trang_thai_hoat_dong`, `nhan_email_tuyendung`, `is_verified`, `verification_token`, `verification_expires`) VALUES
(1, 'admin@test.com', '$2b$10$ZF08I06wtpAHw2MWFrXcquuer2.KuIs/wQKSSCyIMFhkeIuUp29CW', 'admin', 1, 0, 1, NULL, NULL),
(2, 'company1@test.com', '$2b$10$ZF08I06wtpAHw2MWFrXcquuer2.KuIs/wQKSSCyIMFhkeIuUp29CW', 'doanh_nghiep', 1, 0, 1, NULL, NULL),
(3, 'company2@test.com', '$2b$10$ZF08I06wtpAHw2MWFrXcquuer2.KuIs/wQKSSCyIMFhkeIuUp29CW', 'doanh_nghiep', 1, 0, 1, NULL, NULL),
(4, 'candidate1@test.com', '$2b$10$ZF08I06wtpAHw2MWFrXcquuer2.KuIs/wQKSSCyIMFhkeIuUp29CW', 'ung_vien', 1, 0, 1, NULL, NULL),
(5, 'candidate2@test.com', '$2b$10$ZF08I06wtpAHw2MWFrXcquuer2.KuIs/wQKSSCyIMFhkeIuUp29CW', 'ung_vien', 1, 0, 1, NULL, NULL),
(6, 'company3@test.com', '$2y$10$HCUHiQSg/PXchTVwFR6zOuS21tWKmoGO8yQfYAT7A6wfYGHWa4y9W', 'doanh_nghiep', 1, 0, 1, NULL, NULL),
(7, 'khang0867775510@gmail.com', '$2y$10$uxhEzd1tPHZaRK4lGjeCBOECKifACIeTlxqX6LMIE5Y0IoM.aHmfq', 'ung_vien', 1, 1, 1, NULL, NULL),
(8, 'candidate3@test.com', '$2y$10$vav9K1mHLsSI89cpG2CZtuWYtAMuOM2zhuDb/bAKPt1njFuQU7RZm', 'ung_vien', 1, 0, 1, NULL, NULL),
(9, 'candidate4@test.com', '$2y$10$1XCcfCdbpflCtWCW9S8RDu4xP5AIBnmfjJK8WvRGEfqUReuWvOmwS', 'ung_vien', 1, 0, 1, NULL, NULL),
(13, 'nguyenhuukhang999999999@gmail.com', '$2y$10$pjjXwYzLEpkkZEXY2gplWu8yiuI7Rsls0LQ9WRfJ8h4E5f/8xly1q', 'ung_vien', 1, 0, 1, NULL, NULL),
(15, 'dh52200842@student.stu.edu.vn', '$2y$10$iARd03qQV./pdW/GLN9kQu9Er0.GmjNKxqrZAUygUJF5ANNJT8A2C', 'ung_vien', 1, 0, 1, NULL, NULL),
(20, 'hothanhkhai145@gmail.com', '$2y$10$RWsHt5XSY4R944OoiVGHEOkFBoSdqqe7ghP8YrOS.7gs8omE/U7Ma', 'ung_vien', 1, 1, 1, NULL, NULL),
(24, 'khang01293865572@gmail.com', '$2y$10$M2fawjFOf3IuXMbkbd695u5neMflgcIOBdZCEbTB1Mt6OOB.P272W', 'ung_vien', 1, 0, 0, '86b6f83b32d4740ce2402bab6b8c08a74791f95e11e3231c083a968143df1ad3', NULL),
(25, 'window11@gmail.com', '$2y$10$0LH3Ss/bnmY9d4aX59YliuIMhyT0ZLPiczZPfCrGHNWG0DyqfJQ92', 'ung_vien', 1, 0, 0, 'f4c8b4e06dea6815ddf06e660573c82c2f0d9ac8bc58752079af1164e29cb28f', NULL),
(26, 'win@gmail.com', '$2y$10$vADd1WVCHh9aa404ayrCi.GMIuapq1PrSoXp8cngoDgCJnLjH4VES', 'doanh_nghiep', 1, 0, 0, 'c790bf3bb9090fead1a74b4983614ad0ab6b2104534c58ba372e4497b17db57a', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thong_bao`
--

CREATE TABLE `thong_bao` (
  `ma_thong_bao` int(11) NOT NULL,
  `ma_nguoi_nhan` int(11) NOT NULL,
  `noi_dung` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `da_xem` tinyint(4) DEFAULT 0,
  `thoi_gian_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thong_bao`
--

INSERT INTO `thong_bao` (`ma_thong_bao`, `ma_nguoi_nhan`, `noi_dung`, `link`, `da_xem`, `thoi_gian_tao`) VALUES
(73, 1, 'Tin tuyển dụng mới cần duyệt', 'index.php?c=Admin&a=jobs&status=pending', 1, '2025-12-10 22:55:53'),
(74, 2, 'Tin \'PHP dev\' đã được duyệt', 'index.php?c=Employer&a=jobs', 1, '2025-12-10 22:57:30'),
(75, 1, 'Tin tuyển dụng mới cần duyệt', 'index.php?c=Admin&a=jobs&status=pending', 1, '2025-12-10 23:19:49'),
(76, 2, 'Tin \'PHP dev\' đã được duyệt', 'index.php?c=Employer&a=jobs', 0, '2025-12-10 23:20:06'),
(77, 2, 'Có ứng viên ứng tuyển tin \'PDO \'', 'index.php?c=Employer&a=applications&job_id=25', 0, '2025-12-10 23:41:47'),
(78, 1, 'Tin tuyển dụng mới cần duyệt', 'index.php?c=Admin&a=jobs&status=pending', 1, '2025-12-11 23:36:35'),
(79, 2, 'Tin \'PHP dev\' đã được duyệt', 'index.php?c=Employer&a=jobs', 0, '2025-12-11 23:37:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tin_tuyen_dung`
--

CREATE TABLE `tin_tuyen_dung` (
  `ma_tin_tuyen_dung` int(11) NOT NULL,
  `ma_doanh_nghiep` int(11) DEFAULT NULL,
  `tieu_de` varchar(200) DEFAULT NULL,
  `mo_ta_cong_viec` text DEFAULT NULL,
  `yeu_cau_ung_vien` text DEFAULT NULL,
  `muc_luong_khoang` varchar(100) DEFAULT NULL,
  `ma_linh_vuc` int(11) DEFAULT NULL,
  `ma_dia_diem` int(11) DEFAULT NULL,
  `han_nop_ho_so` date DEFAULT NULL,
  `trang_thai_tin_dang` enum('pending','approved','rejected','delete_pending','deleted') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tin_tuyen_dung`
--

INSERT INTO `tin_tuyen_dung` (`ma_tin_tuyen_dung`, `ma_doanh_nghiep`, `tieu_de`, `mo_ta_cong_viec`, `yeu_cau_ung_vien`, `muc_luong_khoang`, `ma_linh_vuc`, `ma_dia_diem`, `han_nop_ho_so`, `trang_thai_tin_dang`) VALUES
(1, 2, 'Lập trình PHP', 'Phát triển website', 'Kinh nghiệm PHP', '15-20 triệu', 1, 4, '2025-12-31', 'approved'),
(2, 2, 'Nhân viên Sales', 'Bán hàng B2B', 'Kỹ năng giao tiếp tốt', '10-15 triệu', 2, 5, '2025-12-25', 'approved'),
(3, 3, 'HR Executive', 'Tuyển dụng nhân sự', 'Có kinh nghiệm tuyển dụng', '12-18 triệu', 3, 4, '2025-12-20', 'deleted'),
(4, 3, 'Laravel Developer', 'Dự án nội bộ', 'Biết Laravel', '18-25 triệu', 1, 5, '2025-12-22', 'rejected'),
(5, 2, 'PHP dev', 'saddsa', 'ádsa', '10 trieu', 3, NULL, '2026-01-03', 'delete_pending'),
(6, 3, 'JAVA DEF Giỏi về Frontend', 'Lam front end', '>30 tuoi', '20 trieu', 1, NULL, '2025-12-02', 'approved'),
(7, 3, 'PHP dev full Stack', 'php full stack gánh cả đội', 'trên 25 tuổi', '25 triệu', 1, NULL, '2025-12-27', 'deleted'),
(8, 3, 'PHP dev full Stack', '12321', '3213', '10 trieu', 1, NULL, '2025-12-04', 'approved'),
(9, 6, 'PHP dev', '123', '123', '10 trieu', 1, 5, '2025-12-27', 'deleted'),
(10, 3, 'PHP dev', '123', '123', '10 trieu', 1, 5, '2025-12-03', 'approved'),
(11, 3, 'PHP dev', '123', '123', '123', 1, 5, '2025-12-26', 'approved'),
(12, 3, 'PHP dev', '123', '123', '123', 1, 5, '2025-12-27', 'approved'),
(13, 3, 'PHP dev', '21321', '123', '10 trieu', 1, 5, '2025-12-27', 'approved'),
(15, 3, 'Giúp việc', 'Giúp việc tại nhà', 'Nữ >35 tuổi', '20 triệu', 1, 5, '2025-12-31', 'deleted'),
(16, 3, 'Giúp việc', '123', '123', '123', 2, 4, '2025-12-31', 'approved'),
(18, 2, 'Rửa chén', 'Rửa chén quán nhậu', 'Nữ 35-50 tuổi', '8 triệu', 1, 4, '2025-12-27', 'rejected'),
(19, 2, 'Rửa chén', 'Rửa chén quán nhậu', 'Nữ 35-50 tuổi', '8 triệu', 1, 4, '2025-12-27', 'rejected'),
(20, 2, 'Rửa chén', 'Rửa chén quán nhậu', 'Nữ 35-50 tuổi', '8 triệu', 1, 4, '2025-12-27', 'rejected'),
(21, 2, 'Rửa chén', 'Rửa chén quán nhậu', 'Nữ 35-50 tuổi', '8 triệu', 1, 4, '2025-12-27', 'rejected'),
(22, 3, 'Rửa chén', '123', '123', '123', 1, 4, '2025-12-27', 'approved'),
(23, 2, 'Kỹ sư', '123asdasd', '123asdasd', '123asdsad', 1, 4, '2025-12-31', 'approved'),
(24, 2, 'Kỹ sư', '123asdasd', '123asdasd', '123asdsad', 1, 4, '2025-12-31', 'approved'),
(25, 2, 'PDO ', '12', '123', '123', 1, 5, '2026-01-02', 'approved'),
(26, 2, 'Đánh máy tính thuê', '123', '123', '123', 1, 5, '2025-12-27', 'approved'),
(27, 2, 'PDO', '123', '123', '123', 1, 5, '2026-01-02', 'rejected'),
(28, 2, 'Livestream bán hàng', 'Livestream dạo', '18-27 tuổi', '1', 2, 6, '2025-12-27', 'approved'),
(29, 2, 'Giúp việc', 'kk', 'kkk', '10 trieu', 1, 4, '2025-12-26', 'rejected'),
(30, 2, 'Youtuber', '123', '123', '123', 1, 4, '2025-12-31', 'approved'),
(31, 2, 'PHP dev full Stack', '9', '9', '9', 1, 4, '2025-12-26', 'approved'),
(32, 2, 'PHP dev', '123', '123', '123', 1, 4, '2026-01-09', 'rejected'),
(33, 2, 'Lau công', 'Lau công công ty Thái Công', '30-40 tuổi', '50 triệu', 3, 4, '2025-12-27', 'approved'),
(35, 2, 'PHP dev', '123', '123', '123', 1, 4, '2025-12-19', 'approved'),
(36, 2, 'PHP dev', '123', '123', '123', 2, 4, '2025-12-30', 'approved'),
(37, 2, 'PHP dev', 'PHP dev thuần', '20-39 tuổi', '10 trieu', 1, 5, '2025-12-27', 'approved');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ung_vien`
--

CREATE TABLE `ung_vien` (
  `ma_ung_vien` int(11) NOT NULL,
  `ho_ten` varchar(100) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `dia_chi` text DEFAULT NULL,
  `mo_ta_ngan` text DEFAULT NULL,
  `muc_luong_mong_muon` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ung_vien`
--

INSERT INTO `ung_vien` (`ma_ung_vien`, `ho_ten`, `sdt`, `dia_chi`, `mo_ta_ngan`, `muc_luong_mong_muon`) VALUES
(4, 'Nguyễn Văn A', '0901112222', 'Hà Nội', 'Ứng viên CNTT', '15-20 triệu'),
(5, 'Trần Thị B', '0903334444', 'HCM', 'Ứng viên kinh doanh', '12-18 triệu'),
(7, 'Nguyen Huu Khang', '0867775510', 'HCM', '', ''),
(8, NULL, NULL, NULL, NULL, NULL),
(9, NULL, NULL, NULL, NULL, NULL),
(13, NULL, NULL, NULL, NULL, NULL),
(15, NULL, NULL, NULL, NULL, NULL),
(20, 'Hồ Thành Khải', '0337474087', '180 cao lỗ', '', ''),
(24, NULL, NULL, NULL, NULL, NULL),
(25, NULL, NULL, NULL, NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cv_kynang`
--
ALTER TABLE `cv_kynang`
  ADD PRIMARY KEY (`ma_cv`,`ma_ky_nang`),
  ADD KEY `fk_ck_kynang` (`ma_ky_nang`);

--
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`ma_danh_muc`);

--
-- Chỉ mục cho bảng `doanh_nghiep`
--
ALTER TABLE `doanh_nghiep`
  ADD PRIMARY KEY (`ma_doanh_nghiep`);

--
-- Chỉ mục cho bảng `don_ung_tuyen`
--
ALTER TABLE `don_ung_tuyen`
  ADD PRIMARY KEY (`ma_don`),
  ADD KEY `fk_don_ttd` (`ma_tin_tuyen_dung`),
  ADD KEY `fk_don_ungvien` (`ma_ung_vien`);

--
-- Chỉ mục cho bảng `ho_so_cv`
--
ALTER TABLE `ho_so_cv`
  ADD PRIMARY KEY (`ma_cv`),
  ADD KEY `fk_cv_ungvien` (`ma_ung_vien`),
  ADD KEY `fk_cv_linhvuc` (`ma_linh_vuc`);

--
-- Chỉ mục cho bảng `ky_nang`
--
ALTER TABLE `ky_nang`
  ADD PRIMARY KEY (`ma_ky_nang`),
  ADD KEY `fk_kynang_linhvuc` (`ma_linh_vuc`);

--
-- Chỉ mục cho bảng `linh_vuc`
--
ALTER TABLE `linh_vuc`
  ADD PRIMARY KEY (`ma_linh_vuc`);

--
-- Chỉ mục cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`ma_nguoi_dung`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD PRIMARY KEY (`ma_thong_bao`),
  ADD KEY `fk_thongbao_nguoidung` (`ma_nguoi_nhan`);

--
-- Chỉ mục cho bảng `tin_tuyen_dung`
--
ALTER TABLE `tin_tuyen_dung`
  ADD PRIMARY KEY (`ma_tin_tuyen_dung`),
  ADD KEY `fk_ttd_doanhnghiep` (`ma_doanh_nghiep`),
  ADD KEY `fk_ttd_linhvuc` (`ma_linh_vuc`),
  ADD KEY `fk_ttd_diadiem` (`ma_dia_diem`);

--
-- Chỉ mục cho bảng `ung_vien`
--
ALTER TABLE `ung_vien`
  ADD PRIMARY KEY (`ma_ung_vien`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `ma_danh_muc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `don_ung_tuyen`
--
ALTER TABLE `don_ung_tuyen`
  MODIFY `ma_don` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `ho_so_cv`
--
ALTER TABLE `ho_so_cv`
  MODIFY `ma_cv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `ky_nang`
--
ALTER TABLE `ky_nang`
  MODIFY `ma_ky_nang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `linh_vuc`
--
ALTER TABLE `linh_vuc`
  MODIFY `ma_linh_vuc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `ma_nguoi_dung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `thong_bao`
--
ALTER TABLE `thong_bao`
  MODIFY `ma_thong_bao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT cho bảng `tin_tuyen_dung`
--
ALTER TABLE `tin_tuyen_dung`
  MODIFY `ma_tin_tuyen_dung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
