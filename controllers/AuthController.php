<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Candidate.php';
require_once __DIR__ . '/../models/Employer.php';
require_once __DIR__ . '/../core/Mailer.php';


class AuthController extends Controller
{
    public function login()
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $user = User::findByEmail($email);
            if (!$user || !password_verify($password, $user['mat_khau_hash'])) {
                $error = 'Sai email hoặc mật khẩu';
            } elseif (isset($user['is_verified']) && (int)$user['is_verified'] === 0) {
                $error = 'Tài khoản của bạn chưa được xác nhận email. Vui lòng kiểm tra hộp thư.';
            } elseif ((int)$user['trang_thai_hoat_dong'] === 0) {
                $error = 'Tài khoản của bạn đã bị khóa, vui lòng liên hệ quản trị viên.';
            } else {
                Auth::login($user['ma_nguoi_dung'], $user['email'], $user['vai_tro']);
                $this->redirect(['c' => 'Home', 'a' => 'index']);
            }
        }
        $this->render('auth/login', compact('error'));
    }

    public function register()
    {
        $error = null;
        $success = null;
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
                // Tạo token xác thực ngẫu nhiên
                $token = bin2hex(random_bytes(32));

                // Đăng ký user với is_verified = 0 + lưu token
                $userId = User::register($email, $password, $role, $token);
                if ($userId) {
                    // Tạo profile mặc định
                    if ($role === 'ung_vien') {
                        Candidate::createProfile($userId);
                    } elseif ($role === 'doanh_nghiep') {
                        Employer::createProfile($userId);
                    }

                    // Tạo link xác nhận
                    $verifyLink = BASE_URL . "/index.php?c=Auth&a=verify&token=" . urlencode($token);

                    $subject = 'Xác nhận đăng ký tài khoản';

                    $body = "Chào bạn,\n\n"
                        . "Bạn vừa đăng ký tài khoản trên hệ thống tuyển dụng của chúng tôi.\n\n"
                        . "Vui lòng mở liên kết sau để xác nhận email và kích hoạt tài khoản:\n"
                        . $verifyLink . "\n\n"
                        . "Nếu bạn không thực hiện đăng ký, hãy bỏ qua email này.\n\n"
                        . "Trân trọng,\n"
                        . "Hệ thống tuyển dụng";

                    Mailer::sendToMany([$email], $subject, $body);


                    $success = 'Đăng ký thành công. Vui lòng kiểm tra email để xác nhận tài khoản.';
                } else {
                    $error = 'Lỗi hệ thống, thử lại';
                }
            }
        }
        $this->render('auth/register', compact('error', 'success'));
    }
    public function verify()
    {
        $success = false;
        $message = null;

        $token = $_GET['token'] ?? '';
        $token = trim($token);

        if ($token === '') {
            $message = 'Liên kết xác nhận không hợp lệ.';
            $this->render('auth/verify_result', compact('success', 'message'));
            return;
        }

        $user = User::findByVerificationToken($token);
        if (!$user) {
            $message = 'Liên kết xác nhận không hợp lệ hoặc đã được sử dụng.';
        } else {
            if (isset($user['is_verified']) && (int)$user['is_verified'] === 1) {
                $success = true;
                $message = 'Tài khoản của bạn đã được xác nhận trước đó. Bạn có thể đăng nhập.';
            } else {
                User::markVerified($user['ma_nguoi_dung']);
                $success = true;
                $message = 'Xác nhận email thành công. Bạn có thể đăng nhập.';
            }
        }

        $this->render('auth/verify_result', compact('success', 'message'));
    }

    public function logout()
    {
        Auth::logout();
        $this->redirect(['c' => 'Home', 'a' => 'index']);
    }
}
