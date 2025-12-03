<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Candidate.php';
require_once __DIR__ . '/../models/Employer.php';

class AuthController extends Controller {
    public function login() {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $user = User::findByEmail($email);
            if (!$user || !password_verify($password, $user['mat_khau_hash'])) {
                $error = 'Sai email hoặc mật khẩu';
            } elseif ((int)$user['trang_thai_hoat_dong'] === 0) {
                $error = 'Tài khoản của bạn đã bị khóa, vui lòng liên hệ quản trị viên.';
            } else {
                Auth::login($user['ma_nguoi_dung'], $user['email'], $user['vai_tro']);
                $this->redirect(['c' => 'Home', 'a' => 'index']);
            }
        }
        $this->render('auth/login', compact('error'));
    }

    public function register() {
        $error = null; $success = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm'] ?? '';
            $role = $_POST['role'] ?? 'ung_vien';

            if ($password !== $confirm) {
                $error = 'Mật khẩu xác nhận không khớp';
            } elseif (User::findByEmail($email)) {
                $error = 'Email đã tồn tại';
            } else {
                $userId = User::register($email, $password, $role);
                if ($userId) {
                    if ($role === 'ung_vien') Candidate::createProfile($userId);
                    if ($role === 'doanh_nghiep') Employer::createProfile($userId);
                    $success = 'Đăng ký thành công, hãy đăng nhập';
                } else {
                    $error = 'Lỗi hệ thống, thử lại';
                }
            }
        }
        $this->render('auth/register', compact('error','success'));
    }

    public function logout() {
        Auth::logout();
        $this->redirect(['c' => 'Home', 'a' => 'index']);
    }
}
