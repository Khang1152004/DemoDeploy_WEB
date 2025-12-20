<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Application.php';
require_once __DIR__ . '/../models/Candidate.php';
require_once __DIR__ . '/../models/CV.php';
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

        // Tính năng ứng tuyển nhanh: chỉ bật khi ứng viên có CV mặc định
        $canQuickApply = false;
        if (Auth::role() === 'ung_vien') {
            $candidateId = Auth::userId();
            $defaultCvId = CV::getDefaultId($candidateId);
            $canQuickApply = $defaultCvId > 0;
        }

        $this->render('job/detail', [
            'job' => $job,
            'canQuickApply' => $canQuickApply,
            'quickStatus' => $_GET['quick'] ?? null,
        ]);
    }

    /**
     * Ứng tuyển nhanh 1 click bằng CV mặc định (chỉ cho ứng viên).
     */
    public function quickApply()
    {
        Auth::requireRole(['ung_vien']);

        $id  = (int)($_GET['id'] ?? 0);
        $job = Job::get($id);
        $today = date('Y-m-d');

        // Chỉ cho ứng tuyển nhanh với tin đang hiển thị/cho phép ứng tuyển
        if (
            !$job
            || !in_array($job['trang_thai_tin_dang'], ['approved', 'delete_pending'], true)
            || $job['han_nop_ho_so'] < $today
        ) {
            $this->redirect(['c' => 'Job', 'a' => 'detail', 'id' => $id, 'quick' => 'job_invalid']);
        }

        $candidateId = Auth::userId();
        $defaultCvId = CV::getDefaultId($candidateId);
        if ($defaultCvId <= 0) {
            $this->redirect(['c' => 'Job', 'a' => 'detail', 'id' => $id, 'quick' => 'no_default_cv']);
        }

        $profile = Candidate::getProfile($candidateId);
        $ho_ten = trim($profile['ho_ten'] ?? '');
        $email  = trim(Auth::email() ?? '');
        $sdt    = trim($profile['sdt'] ?? '');

        if ($ho_ten === '' || $email === '' || $sdt === '') {
            // Thiếu thông tin liên hệ → chuyển qua form apply để người dùng bổ sung
            $this->redirect(['c' => 'Job', 'a' => 'apply', 'id' => $id]);
        }

        // Snapshot CV mặc định sang applications
        $cv = CV::findForCandidate($defaultCvId, $candidateId);
        if (!$cv || empty($cv['file_cv'])) {
            $this->redirect(['c' => 'Job', 'a' => 'detail', 'id' => $id, 'quick' => 'cv_invalid']);
        }

        $source = __DIR__ . '/../' . $cv['file_cv'];
        if (!file_exists($source)) {
            $this->redirect(['c' => 'Job', 'a' => 'detail', 'id' => $id, 'quick' => 'cv_missing']);
        }

        $uploadDir = __DIR__ . '/../uploads/applications';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
        $allowed = ['pdf', 'doc', 'docx'];
        if (!in_array($ext, $allowed, true)) {
            $this->redirect(['c' => 'Job', 'a' => 'detail', 'id' => $id, 'quick' => 'cv_ext_invalid']);
        }

        $name = 'apply_quick_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
        $dest = $uploadDir . '/' . $name;
        if (!@copy($source, $dest)) {
            $this->redirect(['c' => 'Job', 'a' => 'detail', 'id' => $id, 'quick' => 'copy_failed']);
        }

        $data = [
            'ho_ten' => $ho_ten,
            'email'  => $email,
            'sdt'    => $sdt,
            'cv_file' => 'uploads/applications/' . $name,
        ];

        if (Application::create($id, $candidateId, $data)) {
            if (!empty($job['ma_doanh_nghiep'])) {
                Notification::create(
                    (int)$job['ma_doanh_nghiep'],
                    "Có ứng viên ứng tuyển tin '" . $job['tieu_de'] . "' (Ứng tuyển nhanh)",
                    "index.php?c=Employer&a=applications&job_id=" . $id
                );
            }
            $this->redirect(['c' => 'Job', 'a' => 'detail', 'id' => $id, 'quick' => 'success']);
        }

        $this->redirect(['c' => 'Job', 'a' => 'detail', 'id' => $id, 'quick' => 'failed']);
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

        // Prefill thông tin liên hệ nếu ứng viên đang đăng nhập
        $prefill = ['ho_ten' => '', 'email' => '', 'sdt' => ''];
        $candidateId = Auth::role() === 'ung_vien' ? Auth::userId() : null;
        $cvs = [];
        $defaultCvId = 0;

        if ($candidateId) {
            $profile = Candidate::getProfile($candidateId);
            $prefill['ho_ten'] = $profile['ho_ten'] ?? '';
            $prefill['email']  = Auth::email() ?? '';
            $prefill['sdt']    = $profile['sdt'] ?? '';

            // Danh sách CV đã lưu để tái sử dụng khi ứng tuyển
            $cvs = CV::listSimpleByCandidate($candidateId);

            // CV mặc định để pre-select
            $defaultCvId = CV::getDefaultId($candidateId);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ho_ten = trim($_POST['ho_ten'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $sdt    = trim($_POST['sdt'] ?? '');
            $selectedCvId = (int)($_POST['selected_cv_id'] ?? 0);

            // Giữ lại dữ liệu người dùng vừa nhập nếu có lỗi
            $prefill['ho_ten'] = $ho_ten;
            $prefill['email']  = $email;
            $prefill['sdt']    = $sdt;

            // Validate dữ liệu liên hệ
            if ($ho_ten === '') {
                $error = 'Vui lòng nhập họ tên liên hệ.';
            } elseif ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email liên hệ không hợp lệ.';
            } elseif ($sdt === '') {
                $error = 'Vui lòng nhập số điện thoại liên hệ.';
            }

            $cvFilePath  = null;
            $uploadDir = __DIR__ . '/../uploads/applications';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // 1) Ưu tiên CV upload mới nếu người dùng chọn upload
            if ($error === null && !empty($_FILES['cv_file']['name'])) {
                $ext = strtolower(pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION));
                $allowed = ['pdf', 'doc', 'docx'];
                if (!in_array($ext, $allowed, true)) {
                    $error = 'Chỉ cho phép file CV (pdf, doc, docx).';
                } else {
                    $name = 'apply_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                    $dest = $uploadDir . '/' . $name;
                    if (move_uploaded_file($_FILES['cv_file']['tmp_name'], $dest)) {
                        $cvFilePath = 'uploads/applications/' . $name;
                    } else {
                        $error = 'Upload CV thất bại. Vui lòng thử lại.';
                    }
                }
            }

            // 2) Nếu không upload mới, cho phép tái sử dụng CV đã lưu
            if ($error === null && $cvFilePath === null && $candidateId && $selectedCvId > 0) {
                $cv = CV::findForCandidate($selectedCvId, $candidateId);
                if (!$cv || empty($cv['file_cv'])) {
                    $error = 'CV đã chọn không hợp lệ.';
                } else {
                    $source = __DIR__ . '/../' . $cv['file_cv'];
                    if (!file_exists($source)) {
                        $error = 'Không tìm thấy file CV đã lưu. Vui lòng chọn CV khác.';
                    } else {
                        // Snapshot: copy CV sang thư mục applications để CV của đơn không bị ảnh hưởng nếu ứng viên xóa CV sau này.
                        $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
                        $allowed = ['pdf', 'doc', 'docx'];
                        if (!in_array($ext, $allowed, true)) {
                            $error = 'File CV đã lưu có định dạng không hợp lệ.';
                        } else {
                            $name = 'apply_reuse_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                            $dest = $uploadDir . '/' . $name;
                            if (@copy($source, $dest)) {
                                $cvFilePath = 'uploads/applications/' . $name;
                            } else {
                                $error = 'Không thể tái sử dụng CV đã lưu. Vui lòng thử upload CV mới.';
                            }
                        }
                    }
                }
            }

            // 3) Bắt buộc phải có CV (upload mới hoặc chọn CV đã lưu)
            if ($error === null && $cvFilePath === null) {
                $error = $candidateId
                    ? 'Vui lòng chọn CV đã lưu hoặc tải lên CV mới.'
                    : 'Vui lòng tải lên CV để ứng tuyển.';
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

        $this->render('job/apply', [
            'job' => $job,
            'error' => $error,
            'success' => $success,
            'prefill' => $prefill,
            'cvs' => $cvs,
            'defaultCvId' => $defaultCvId,
        ]);
    }

}
