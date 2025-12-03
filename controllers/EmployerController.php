<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Employer.php';
require_once __DIR__ . '/../models/Field.php';
require_once __DIR__ . '/../models/Location.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Application.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/CV.php';
require_once __DIR__ . '/../models/Skill.php';

class EmployerController extends Controller {
    public function account() {
        Auth::requireRole(['doanh_nghiep']);
        $userId = Auth::userId();
        $user = User::findById($userId);
        $profile = Employer::getProfile($userId);
        $msg = null; $err = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_profile'])) {
                $data = [
                    'ten_cong_ty' => $_POST['ten_cong_ty'] ?? '',
                    'dia_chi' => $_POST['dia_chi'] ?? '',
                    'mo_ta' => $_POST['mo_ta'] ?? '',
                ];
                Employer::updateProfile($userId, $data);
                $msg = 'Cập nhật thông tin thành công';
                $profile = Employer::getProfile($userId);
            } elseif (isset($_POST['change_password'])) {
                $res = User::updatePassword($userId,
                    $_POST['current_password'] ?? '',
                    $_POST['new_password'] ?? '',
                    $_POST['confirm_password'] ?? '');
                if ($res['success']) $msg = $res['message']; else $err = $res['message'];
            }
        }

        $this->render('employer/account', compact('user','profile','msg','err'));
    }

    public function jobs() {
        Auth::requireRole(['doanh_nghiep']);
        $userId = Auth::userId();
        $fields = Field::all();
        $locations = Location::all();
        $jobs = Job::byEmployer($userId);
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tieu_de' => $_POST['tieu_de'] ?? '',
                'mo_ta_cong_viec' => $_POST['mo_ta_cong_viec'] ?? '',
                'yeu_cau_ung_vien' => $_POST['yeu_cau_ung_vien'] ?? '',
                'muc_luong_khoang' => $_POST['muc_luong_khoang'] ?? '',
                'ma_linh_vuc' => (int)($_POST['ma_linh_vuc'] ?? 0),
                'ma_dia_diem' => isset($_POST['ma_dia_diem']) ? (int)$_POST['ma_dia_diem'] : null,
                'han_nop_ho_so' => $_POST['han_nop_ho_so'] ?? null,
            ];

            // Validate hạn nộp hồ sơ không được trước ngày hiện tại
            if (empty($data['han_nop_ho_so'])) {
                $error = 'Vui lòng chọn hạn nộp hồ sơ.';
            } else {
                $today = date('Y-m-d');
                if ($data['han_nop_ho_so'] < $today) {
                    $error = 'Hạn nộp hồ sơ không được trước ngày hiện tại.';
                }
            }

            if ($error === null) {
                Job::create($userId, $data);
                Notification::notifyAdmins("Tin tuyển dụng mới cần duyệt", "index.php?c=Admin&a=jobs&status=pending");
                $this->redirect(['c'=>'Employer','a'=>'jobs']);
                return;
            }
        }

        $this->render('employer/jobs', compact('jobs','fields','locations','error'));
    }

// doanh nghiệp gửi yêu cầu xóa
    public function requestDeleteJob() {
        Auth::requireRole(['doanh_nghiep']);
        $userId = Auth::userId();
        $jobId = (int)($_GET['id'] ?? 0);
        $job = Job::get($jobId);
        if (!$job || $job['ma_doanh_nghiep'] != $userId) {
            $this->redirect(['c'=>'Employer','a'=>'jobs']);
        }
        // chỉ cho phép yêu cầu xóa khi tin đang approved
        if ($job['trang_thai_tin_dang'] === 'approved') {
            Job::updateStatus($jobId, 'delete_pending');

            // Thông báo cho admin biết có yêu cầu xóa
            Notification::notifyAdmins(
                "Doanh nghiệp yêu cầu xóa tin '".$job['tieu_de']."'",
                "index.php?c=Admin&a=jobs&status=delete_pending"
            );

            // Thông báo lại cho chính doanh nghiệp (xác nhận đã gửi yêu cầu)
            Notification::create(
                $userId,
                "Bạn đã gửi yêu cầu xóa tin '".$job['tieu_de']."'",
                "index.php?c=Employer&a=jobs"
            );
        }
        $this->redirect(['c'=>'Employer','a'=>'jobs']);
    }

    public function applications() {
        Auth::requireRole(['doanh_nghiep']);
        $jobId = (int)($_GET['job_id'] ?? 0);
        $job = Job::get($jobId);
        if (!$job || $job['ma_doanh_nghiep'] != Auth::userId()) {
            $this->redirect(['c'=>'Employer','a'=>'jobs']);
        }
        $apps = Application::byJob($jobId);
        $this->render('employer/applications', compact('job','apps'));
    }

    public function updateApplication() {
        Auth::requireRole(['doanh_nghiep']);
        $appId = (int)($_GET['id'] ?? 0);
        $status = $_GET['status'] ?? '';
        $allowed = ['interview','rejected'];
        if (!$appId || !in_array($status,$allowed,true)) {
            $this->redirect(['c'=>'Home','a'=>'index']);
        }
        $app = Application::find($appId);
        if (!$app) $this->redirect(['c'=>'Home','a'=>'index']);
        $job = Job::get($app['ma_tin_tuyen_dung']);
        if (!$job || $job['ma_doanh_nghiep'] != Auth::userId()) {
            $this->redirect(['c'=>'Home','a'=>'index']);
        }
        Application::updateStatus($appId, $status);
        if ($app['ma_ung_vien']) {
            $msg = $status === 'interview'
                ? "Bạn được mời phỏng vấn cho tin '".$job['tieu_de']."'"
                : "Hồ sơ của bạn cho tin '".$job['tieu_de']."' đã bị từ chối";
            Notification::create((int)$app['ma_ung_vien'], $msg, "index.php?c=Candidate&a=account");
        }
        $this->redirect(['c'=>'Employer','a'=>'applications','job_id'=>$job['ma_tin_tuyen_dung']]);
    }

    public function searchCV() {
        Auth::requireRole(['doanh_nghiep']);
        $fields = Field::all();
        $skills = Skill::all();
        $fieldId = (int)($_GET['ma_linh_vuc'] ?? 0);
        $skillId = (int)($_GET['ma_ky_nang'] ?? 0);
        $results = CV::search($fieldId ?: null, $skillId ?: null);
        $this->render('employer/search_cv', compact('fields','skills','fieldId','skillId','results'));
    }
}
