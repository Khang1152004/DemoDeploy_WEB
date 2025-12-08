<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Field.php';
require_once __DIR__ . '/../models/Skill.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/Location.php';
require_once __DIR__ . '/../core/Mailer.php';
require_once __DIR__ . '/../models/Job.php';


class AdminController extends Controller
{

    public function dashboard()
    {
        Auth::requireRole(['admin']);

        // Thống kê tổng quát
        $totalUsers       = User::countAll();
        $lockedUsers      = User::countLocked();
        $candidateCount   = User::countByRole('ung_vien');
        $employerCount    = User::countByRole('doanh_nghiep');
        $adminCount       = User::countByRole('admin');

        $totalJobs        = Job::countAll();
        $approvedJobs     = Job::countByStatus('approved');
        $pendingJobsCount = Job::countByStatus('pending');

        // Danh sách tin cần xử lý
        $pendingJobs    = Job::listByStatus('pending');
        $deleteRequests = Job::listByStatus('delete_pending');

        $this->render('admin/dashboard', [
            'pendingJobs'       => $pendingJobs,
            'deleteRequests'    => $deleteRequests,
            'totalUsers'        => $totalUsers,
            'lockedUsers'       => $lockedUsers,
            'candidateCount'    => $candidateCount,
            'employerCount'     => $employerCount,
            'adminCount'        => $adminCount,
            'totalJobs'         => $totalJobs,
            'approvedJobs'      => $approvedJobs,
            'pendingJobsCount'  => $pendingJobsCount,
        ]);
    }
    public function detail()
    {
        // Chỉ cho admin
        Auth::requireRole(['admin']);

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            // quay về dashboard admin nếu id không hợp lệ
            $this->redirect(['c' => 'Admin', 'a' => 'dashboard']);
        }

        $job = Job::get($id);
        if (!$job) {
            $this->redirect(['c' => 'Admin', 'a' => 'dashboard']);
        }

        // Dùng lại view chi tiết chung
        $this->render('job/detail', [
            'job'       => $job,
            'fromAdmin' => true,   // nếu sau này cần phân biệt
        ]);
    }

    public function jobs()
    {
        Auth::requireRole(['admin']);
        $status = $_GET['status'] ?? 'pending';
        $jobs = Job::listByStatus($status);
        $this->render('admin/jobs', compact('jobs', 'status'));
    }

    public function approveJob()
    {
        Auth::requireRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        $status = $_GET['status'] ?? 'approved'; // approved / rejected
        if (!in_array($status, ['approved', 'rejected'], true)) $status = 'approved';
        $job = Job::get($id);
        if ($job) {
            Job::updateStatus($id, $status);
            if ($status === 'approved' && !empty($job['ma_doanh_nghiep'])) {
                // Thông báo cho doanh nghiệp
                Notification::create(
                    (int)$job['ma_doanh_nghiep'],
                    "Tin '" . $job['tieu_de'] . "' đã được duyệt",
                    "index.php?c=Employer&a=jobs"
                );

                // Gửi email cho ứng viên đã đăng ký nhận tin
                $subscribers = User::getSubscribedEmails();
                if (!empty($subscribers)) {
                    $subject = "Tin tuyển dụng mới: " . $job['tieu_de'];

                    $link = BASE_URL . '?c=Job&a=detail&id=' . $job['ma_tin_tuyen_dung'];

                    $message = "Chào bạn,\n\n"
                        . "Có một tin tuyển dụng mới vừa được duyệt:\n"
                        . "Tiêu đề: " . $job['tieu_de'] . "\n"
                        . "Mức lương: " . $job['muc_luong_khoang'] . "\n\n"
                        . "Xem chi tiết: " . $link . "\n\n"
                        . "Đây là email tự động, vui lòng không trả lời.";

                    $sent = Mailer::sendToMany($subscribers, $subject, $message);
                    // file_put_contents(__DIR__.'/../mail_log.txt', "Sent: $sent\n", FILE_APPEND); // nếu muốn debug
                }
            }
        }
        $this->redirect(['c' => 'Admin', 'a' => 'jobs', 'status' => 'pending']);
    }

    // Admin xóa trực tiếp (không cần doanh nghiệp yêu cầu)
    public function deleteJobDirect()
    {
        Auth::requireRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        $job = Job::get($id);
        if ($job) {
            Job::updateStatus($id, 'deleted');
            if (!empty($job['ma_doanh_nghiep'])) {
                Notification::create(
                    (int)$job['ma_doanh_nghiep'],
                    "Tin '" . $job['tieu_de'] . "' đã bị admin xóa",
                    "index.php?c=Employer&a=jobs"
                );
            }
        }
        $this->redirect(['c' => 'Admin', 'a' => 'jobs', 'status' => 'approved']);
    }

    // Admin xử lý yêu cầu xóa từ doanh nghiệp
    public function handleDeleteRequest()
    {
        Auth::requireRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        $action = $_GET['action'] ?? 'approve'; // approve / reject
        $job = Job::get($id);
        if (!$job) {
            $this->redirect(['c' => 'Admin', 'a' => 'jobs', 'status' => 'delete_pending']);
        }

        if ($action === 'approve') {
            Job::updateStatus($id, 'deleted');
            if (!empty($job['ma_doanh_nghiep'])) {
                Notification::create(
                    (int)$job['ma_doanh_nghiep'],
                    "Tin '" . $job['tieu_de'] . "' đã được xóa theo yêu cầu",
                    "index.php?c=Employer&a=jobs"
                );
            }
        } else {
            // từ chối yêu cầu xóa -> đưa lại về approved
            Job::updateStatus($id, 'approved');
            if (!empty($job['ma_doanh_nghiep'])) {
                Notification::create(
                    (int)$job['ma_doanh_nghiep'],
                    "Yêu cầu xóa tin '" . $job['tieu_de'] . "' đã bị từ chối",
                    "index.php?c=Employer&a=jobs"
                );
            }
        }
        $this->redirect(['c' => 'Admin', 'a' => 'jobs', 'status' => 'delete_pending']);
    }


    public function categories()
    {
        Auth::requireRole(['admin']);

        $type = $_GET['type'] ?? 'field';
        if (!in_array($type, ['field', 'skill', 'location'], true)) {
            $type = 'field';
        }

        $fields = [];
        $skills = [];
        $locations = [];
        $filterFieldId = 0;

        if ($type === 'field') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!empty($_POST['ten_linh_vuc'])) {
                    Field::create($_POST['ten_linh_vuc']);
                } elseif (isset($_POST['delete_id'])) {
                    Field::delete((int)$_POST['delete_id']);
                }
                $this->redirect(['c' => 'Admin', 'a' => 'categories', 'type' => 'field']);
            }
            $fields = Field::all();
        } elseif ($type === 'skill') {
            $fields = Field::all();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!empty($_POST['ten_ky_nang'])) {
                    $name = trim($_POST['ten_ky_nang']);
                    $fieldId = (int)($_POST['ma_linh_vuc'] ?? 0);
                    if ($name !== '' && $fieldId > 0) {
                        Skill::create($name, $fieldId);
                    }
                } elseif (isset($_POST['delete_id'])) {
                    Skill::delete((int)$_POST['delete_id']);
                }
                $this->redirect(['c' => 'Admin', 'a' => 'categories', 'type' => 'skill']);
            }
            $filterFieldId = isset($_GET['field']) ? (int)$_GET['field'] : 0;
            $skills = $filterFieldId ? Skill::all($filterFieldId) : Skill::all();
        } else { // location
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!empty($_POST['ten_dia_diem'])) {
                    Location::create($_POST['ten_dia_diem']);
                } elseif (isset($_POST['delete_id'])) {
                    Location::delete((int)$_POST['delete_id']);
                }
                $this->redirect(['c' => 'Admin', 'a' => 'categories', 'type' => 'location']);
            }
            $locations = Location::all();
        }

        $this->render('admin/categories', [
            'type'          => $type,
            'fields'        => $fields,
            'skills'        => $skills,
            'locations'     => $locations,
            'filterFieldId' => $filterFieldId,
        ]);
    }

function fields()
    {
        Auth::requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['ten_linh_vuc'])) {
                Field::create($_POST['ten_linh_vuc']);
            } elseif (isset($_POST['delete_id'])) {
                Field::delete((int)$_POST['delete_id']);
            }
            $this->redirect(['c' => 'Admin', 'a' => 'fields']);
        }
        $fields = Field::all();
        $this->render('admin/fields', compact('fields'));
    }

    public function skills()
    {
        Auth::requireRole(['admin']);
        $fields = Field::all();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['ten_ky_nang'])) {
                $name = trim($_POST['ten_ky_nang']);
                $fieldId = (int)($_POST['ma_linh_vuc'] ?? 0);
                if ($name !== '' && $fieldId > 0) {
                    Skill::create($name, $fieldId);
                }
            } elseif (isset($_POST['delete_id'])) {
                Skill::delete((int)$_POST['delete_id']);
            }
            $this->redirect(['c' => 'Admin', 'a' => 'skills']);
        }
        $filterFieldId = isset($_GET['field']) ? (int)$_GET['field'] : 0;
        $skills = $filterFieldId ? Skill::all($filterFieldId) : Skill::all();
        $this->render('admin/skills', compact('fields', 'skills', 'filterFieldId'));
    }


    public function users()
    {
        Auth::requireRole(['admin']);
        $users = User::all();
        $this->render('admin/users', compact('users'));
    }

    public function toggleUserStatus()
    {
        Auth::requireRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        $action = $_GET['action'] ?? 'lock'; // lock / unlock
        if ($id > 0) {
            $active = ($action === 'unlock') ? 1 : 0;
            User::updateStatus($id, $active);
        }
        $this->redirect(['c' => 'Admin', 'a' => 'users']);
    }
}
