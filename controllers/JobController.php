<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Application.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/Field.php';
require_once __DIR__ . '/../models/Location.php';


class JobController extends Controller
{
    public function detail()
    {
        $id = (int)($_GET['id'] ?? 0);
        $job = Job::get($id);
        $today = date('Y-m-d');
        if (
            !$job
            || !in_array($job['trang_thai_tin_dang'], ['approved', 'delete_pending'])
            || $job['han_nop_ho_so'] < $today
        ) {
            $this->redirect(['c' => 'Home', 'a' => 'index']);
        }
        $this->render('job/detail', compact('job'));
    }
    public function index()
    {
        $fields    = Field::all();
        $locations = Location::all();

        $keyword       = trim($_GET['q'] ?? '');
        $fieldId       = isset($_GET['field']) ? (int)$_GET['field'] : 0;
        $locationId    = isset($_GET['location']) ? (int)$_GET['location'] : 0;
        $salaryKeyword = trim($_GET['salary'] ?? '');

        if ($keyword !== '' || $fieldId > 0 || $locationId > 0 || $salaryKeyword !== '') {
            $jobs = Job::searchApproved($keyword, $fieldId, $locationId, $salaryKeyword);
        } else {
            // Hiển thị nhiều hơn trang chủ một chút
            $jobs = Job::latestApproved(20);
        }

        $this->render('job/list', [
            'jobs'          => $jobs,
            'fields'        => $fields,
            'locations'     => $locations,
            'keyword'       => $keyword,
            'fieldId'       => $fieldId,
            'locationId'    => $locationId,
            'salaryKeyword' => $salaryKeyword,
        ]);
    }

    public function apply()
    {
        $id = (int)($_GET['id'] ?? 0);
        $job = Job::get($id);
        $today = date('Y-m-d');
        if (
            !$job
            || !in_array($job['trang_thai_tin_dang'], ['approved', 'delete_pending'])
            || $job['han_nop_ho_so'] < $today
        ) {
            $this->redirect(['c' => 'Home', 'a' => 'index']);
        }
        $error = null;
        $success = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ho_ten = $_POST['ho_ten'] ?? '';
            $email = $_POST['email'] ?? '';
            $sdt = $_POST['sdt'] ?? '';
            $candidateId = Auth::role() === 'ung_vien' ? Auth::userId() : null;
            $cvFilePath = null;
            if (!empty($_FILES['cv_file']['name'])) {
                $uploadDir = __DIR__ . '/../uploads/applications';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $ext = strtolower(pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION));
                $name = 'apply_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                $dest = $uploadDir . '/' . $name;
                if (move_uploaded_file($_FILES['cv_file']['tmp_name'], $dest)) {
                    $cvFilePath = 'uploads/applications/' . $name;
                }
            }
            $data = ['ho_ten' => $ho_ten, 'email' => $email, 'sdt' => $sdt, 'cv_file' => $cvFilePath];
            if (Application::create($id, $candidateId, $data)) {
                if (!empty($job['ma_doanh_nghiep'])) {
                    Notification::create(
                        (int)$job['ma_doanh_nghiep'],
                        "Có ứng viên ứng tuyển tin '" . $job['tieu_de'] . "'",
                        "index.php?c=Employer&a=applications&job_id=" . $id
                    );
                }
                $success = 'Đã gửi đơn ứng tuyển';
            } else {
                $error = 'Lỗi khi gửi đơn';
            }
        }
        $this->render('job/apply', compact('job', 'error', 'success'));
    }
}
