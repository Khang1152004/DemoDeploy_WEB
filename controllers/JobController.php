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
        // Lấy id từ query string
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        // Nếu không có id hợp lệ thì quay về trang chủ
        if ($id <= 0) {
            $this->redirect(['c' => 'Home', 'a' => 'index']);
        }

        // Lấy thông tin tin tuyển dụng
        $job = Job::get($id);

        // Không tìm thấy tin → quay về trang chủ
        if (!$job) {
            $this->redirect(['c' => 'Home', 'a' => 'index']);
        }

        // Không lọc trạng thái, không check hạn nộp
        // → bất kỳ tin nào có trong DB đều xem được (kể cả pending, rejected, delete_pending...)

        $this->render('job/detail', [
            'job' => $job,
        ]);
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
        $id  = (int)($_GET['id'] ?? 0);
        $job = Job::get($id);
        $today = date('Y-m-d');

        if (
            !$job
            || !in_array($job['trang_thai_tin_dang'], ['approved', 'delete_pending'], true)
            || $job['han_nop_ho_so'] < $today
        ) {
            $this->redirect(['c' => 'Home', 'a' => 'index']);
        }

        $error   = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ho_ten = trim($_POST['ho_ten'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $sdt    = trim($_POST['sdt'] ?? '');

            // Validate dữ liệu liên hệ
            if ($ho_ten === '') {
                $error = 'Vui lòng nhập họ tên liên hệ.';
            } elseif ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email liên hệ không hợp lệ.';
            } elseif ($sdt === '') {
                $error = 'Vui lòng nhập số điện thoại liên hệ.';
            }

            $candidateId = Auth::role() === 'ung_vien' ? Auth::userId() : null;
            $cvFilePath  = null;

            // Chỉ xử lý upload nếu không có lỗi dữ liệu cơ bản
            if ($error === null && !empty($_FILES['cv_file']['name'])) {
                $uploadDir = __DIR__ . '/../uploads/applications';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext  = strtolower(pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION));
                $name = 'apply_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                $dest = $uploadDir . '/' . $name;

                if (move_uploaded_file($_FILES['cv_file']['tmp_name'], $dest)) {
                    $cvFilePath = 'uploads/applications/' . $name;
                }
            }

            if ($error === null) {
                $data = [
                    'ho_ten' => $ho_ten,
                    'email'  => $email,
                    'sdt'    => $sdt,
                    'cv_file' => $cvFilePath,
                ];

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
                    $error = 'Lỗi khi gửi đơn. Vui lòng thử lại.';
                }
            }
        }

        $this->render('job/apply', compact('job', 'error', 'success'));
    }
}
