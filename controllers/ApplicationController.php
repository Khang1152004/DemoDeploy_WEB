<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Notification;

class ApplicationController extends Controller {

    public function apply() {
        $jobId = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;
        if ($jobId <= 0) {
            $this->redirect('index.php');
        }

        $job = Job::getById($jobId);
        if (!$job) {
            $this->redirect('index.php');
        }

        $prefill = [
            'ho_ten_lien_he' => '',
            'email_lien_he' => '',
            'sdt_lien_he' => ''
        ];

        if (!empty($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'ung_vien') {
            $candidate = Candidate::getByUserId($_SESSION['user_id']);
            $prefill['ho_ten_lien_he'] = $candidate['ho_ten'] ?? '';
            $prefill['email_lien_he'] = $_SESSION['user_email'] ?? '';
            $prefill['sdt_lien_he'] = $candidate['sdt'] ?? '';
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name  = trim($_POST['ho_ten_lien_he'] ?? '');
            $email = trim($_POST['email_lien_he'] ?? '');
            $phone = trim($_POST['sdt_lien_he'] ?? '');
            $candidateId = (!empty($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'ung_vien')
                ? $_SESSION['user_id'] : null;

            if ($name === '')  $errors[] = 'Vui lòng nhập họ tên liên hệ.';
            if ($email === '') $errors[] = 'Vui lòng nhập email liên hệ.';
            if ($phone === '') $errors[] = 'Vui lòng nhập số điện thoại liên hệ.';

            $cvPath = null;
            if (!empty($_FILES['cv_file']['name'])) {
                $uploadDir = __DIR__ . '/../public/uploads/cv';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION));
                $allowed = ['pdf','doc','docx'];
                if (!in_array($ext, $allowed)) {
                    $errors[] = 'Chỉ cho phép file CV (pdf, doc, docx).';
                } else {
                    $fileName = 'cv_' . time() . '_' . mt_rand(1000,9999) . '.' . $ext;
                    $dest = $uploadDir . '/' . $fileName;
                    if (!move_uploaded_file($_FILES['cv_file']['tmp_name'], $dest)) {
                        $errors[] = 'Upload CV thất bại.';
                    } else {
                        $cvPath = 'uploads/cv/' . $fileName;
                    }
                }
            }

            if (empty($errors)) {
                $data = [
                    'ho_ten_lien_he' => $name,
                    'email_lien_he'  => $email,
                    'sdt_lien_he'    => $phone,
                    'cv_file'        => $cvPath
                ];
                $result = Application::create($jobId, $candidateId, $data);

                if ($result['success']) {
                    $job = Job::getById($jobId);
                    if ($job && !empty($job['ma_doanh_nghiep'])) {
                        $employerId = (int)$job['ma_doanh_nghiep'];
                        $title = isset($job['tieu_de']) ? $job['tieu_de'] : '';
                        $msg = "Có ứng viên mới ứng tuyển tin '" . $title . "'.";
                        Notification::create($employerId, $msg, 'index.php?c=employer&a=viewApplications&job_id=' . $jobId);
                    }
                }

                $_SESSION['flash_message'] = $result['message'];

                if ($candidateId) {
                    $this->redirect('index.php?c=candidate&a=dashboard');
                } else {
                    $this->redirect('index.php');
                }
            } else {
                $prefill['ho_ten_lien_he'] = $name;
                $prefill['email_lien_he']  = $email;
                $prefill['sdt_lien_he']    = $phone;
            }
        }

        $this->render('candidate/apply', [
            'job' => $job,
            'prefill' => $prefill,
            'errors' => $errors
        ]);
    }
}
