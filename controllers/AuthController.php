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

                    $subject = 'Xác nhận đăng ký tài khoản JobMatch';
                    $logoUrl = BASE_URL . "/public/assets/images/LogoJobMatch.png";



                    // Nội dung HTML (body email)
                    $body = "
<div style='max-width:600px;margin:0 auto;background:#ffffff;border-radius:12px;border:1px solid #e5e7eb;padding:24px;font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    
    <div style='max-width:600px;margin:0 auto;background:#ffffff;border-radius:12px;
            border:1px solid #e5e7eb;padding:24px;font-family:-apple-system,BlinkMacSystemFont,
            \"Segoe UI\",Arial,sans-serif;'>

    <div style='text-align:center;margin-bottom:20px;'>
        <img src='{$logoUrl}' alt='JobMatch Logo' 
             style='height:50px;object-fit:contain;margin-bottom:8px;'>
        <div style='font-size:14px;color:#6b7280;'>Nền tảng tuyển dụng mini</div>
    </div>

    <h2 style='margin:0 0 12px 0;font-size:20px;color:#111827;font-weight:700;'>
        Xin chào bạn!
    </h2>

    <p style='margin:0 0 12px 0;font-size:14px;color:#4B5563;line-height:1.6;'>
        Cảm ơn bạn đã đăng ký tài khoản tại <strong>JobMatch</strong>.
        Vui lòng nhấn vào nút bên dưới để hoàn tất bước xác nhận email và kích hoạt tài khoản.
    </p>

    <div style='margin:20px 0;padding:16px 18px;border-radius:12px;background:#EEF2FF;border:1px solid #E0E7FF;'>
        <p style='margin:0 0 10px 0;font-size:14px;color:#4B5563;'>
            Bước cuối cùng để bắt đầu kết nối ứng viên & doanh nghiệp nhanh chóng:
        </p>
        <div style='text-align:center;margin-top:8px;'>
            <a href='{$verifyLink}'
               style='display:inline-block;background:#4F46E5;color:#ffffff;padding:12px 30px;border-radius:999px;
                      font-size:15px;font-weight:600;text-decoration:none;'>
                XÁC NHẬN TÀI KHOẢN
            </a>
        </div>
    </div>

    <p style='margin:0 0 8px 0;font-size:13px;color:#6B7280;line-height:1.6;'>
        Nếu nút trên không hoạt động, hãy copy đường link sau và dán vào trình duyệt:
    </p>

    <p style='margin:0 0 16px 0;font-size:13px;color:#4F46E5;word-break:break-all;'>
        <a href='{$verifyLink}' style='color:#4F46E5;text-decoration:none;'>{$verifyLink}</a>
    </p>

    <hr style='border:0;border-top:1px solid #E5E7EB;margin:18px 0;'>

    <p style='margin:0;font-size:12px;color:#9CA3AF;line-height:1.6;text-align:center;'>
        Đây là email tự động, vui lòng không trả lời lại email này.<br>
        © " . date('Y') . " JobMatch. All rights reserved.
    </p>
</div>
";

                    // Gửi email – vẫn dùng hàm cũ
                    Mailer::sendToMany([$email], $subject, $body);


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
