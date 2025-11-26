<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Field.php';
require_once __DIR__ . '/../models/Skill.php';
require_once __DIR__ . '/../models/Notification.php';

class AdminController extends Controller {
    public function dashboard() {
        Auth::requireRole(['admin']);
        $pendingJobs = Job::listByStatus('pending');
        $deleteRequests = Job::listByStatus('delete_pending');
        $this->render('admin/dashboard', compact('pendingJobs','deleteRequests'));
    }

    public function jobs() {
        Auth::requireRole(['admin']);
        $status = $_GET['status'] ?? 'pending';
        $jobs = Job::listByStatus($status);
        $this->render('admin/jobs', compact('jobs','status'));
    }

    public function approveJob() {
        Auth::requireRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        $status = $_GET['status'] ?? 'approved'; // approved / rejected
        if (!in_array($status, ['approved','rejected'], true)) $status = 'approved';
        $job = Job::get($id);
        if ($job) {
            Job::updateStatus($id, $status);
            if ($status === 'approved' && !empty($job['ma_doanh_nghiep'])) {
                Notification::create((int)$job['ma_doanh_nghiep'],
                    "Tin '".$job['tieu_de']."' đã được duyệt",
                    "index.php?c=Employer&a=jobs");
            }
        }
        $this->redirect(['c'=>'Admin','a'=>'jobs','status'=>'pending']);
    }

    // Admin xóa trực tiếp (không cần doanh nghiệp yêu cầu)
    public function deleteJobDirect() {
        Auth::requireRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        $job = Job::get($id);
        if ($job) {
            Job::updateStatus($id, 'deleted');
            if (!empty($job['ma_doanh_nghiep'])) {
                Notification::create((int)$job['ma_doanh_nghiep'],
                    "Tin '".$job['tieu_de']."' đã bị admin xóa",
                    "index.php?c=Employer&a=jobs");
            }
        }
        $this->redirect(['c'=>'Admin','a'=>'jobs','status'=>'approved']);
    }

    // Admin xử lý yêu cầu xóa từ doanh nghiệp
    public function handleDeleteRequest() {
        Auth::requireRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        $action = $_GET['action'] ?? 'approve'; // approve / reject
        $job = Job::get($id);
        if (!$job) {
            $this->redirect(['c'=>'Admin','a'=>'jobs','status'=>'delete_pending']);
        }

        if ($action === 'approve') {
            Job::updateStatus($id, 'deleted');
            if (!empty($job['ma_doanh_nghiep'])) {
                Notification::create((int)$job['ma_doanh_nghiep'],
                    "Tin '".$job['tieu_de']."' đã được xóa theo yêu cầu",
                    "index.php?c=Employer&a=jobs");
            }
        } else {
            // từ chối yêu cầu xóa -> đưa lại về approved
            Job::updateStatus($id, 'approved');
            if (!empty($job['ma_doanh_nghiep'])) {
                Notification::create((int)$job['ma_doanh_nghiep'],
                    "Yêu cầu xóa tin '".$job['tieu_de']."' đã bị từ chối",
                    "index.php?c=Employer&a=jobs");
            }
        }
        $this->redirect(['c'=>'Admin','a'=>'jobs','status'=>'delete_pending']);
    }

    public function fields() {
        Auth::requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['ten_linh_vuc'])) {
                Field::create($_POST['ten_linh_vuc']);
            } elseif (isset($_POST['delete_id'])) {
                Field::delete((int)$_POST['delete_id']);
            }
            $this->redirect(['c'=>'Admin','a'=>'fields']);
        }
        $fields = Field::all();
        $this->render('admin/fields', compact('fields'));
    }

    public function skills() {
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
            $this->redirect(['c'=>'Admin','a'=>'skills']);
        }
        $filterFieldId = isset($_GET['field']) ? (int)$_GET['field'] : 0;
        $skills = $filterFieldId ? Skill::all($filterFieldId) : Skill::all();
        $this->render('admin/skills', compact('fields','skills','filterFieldId'));
    }
}
