<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Candidate.php';
require_once __DIR__ . '/../models/Field.php';
require_once __DIR__ . '/../models/Skill.php';
require_once __DIR__ . '/../models/CV.php';

class CandidateController extends Controller {
    public function account() {
        Auth::requireRole(['ung_vien']);
        $userId = Auth::userId();
        $user = User::findById($userId);
        $profile = Candidate::getProfile($userId);
        $msg = null; $err = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_profile'])) {
                $data = [
                    'ho_ten' => $_POST['ho_ten'] ?? '',
                    'sdt' => $_POST['sdt'] ?? '',
                    'dia_chi' => $_POST['dia_chi'] ?? '',
                    'mo_ta_ngan' => $_POST['mo_ta_ngan'] ?? '',
                    'muc_luong_mong_muon' => $_POST['muc_luong_mong_muon'] ?? '',
                ];
                Candidate::updateProfile($userId, $data);

                // Cập nhật đăng ký nhận email tuyển dụng
                $subscribe = isset($_POST['subscribe_email']) ? 1 : 0;
                User::updateEmailSubscription($userId, $subscribe);

                $msg = 'Cập nhật thông tin thành công';
                $profile = Candidate::getProfile($userId);
            } elseif (isset($_POST['change_password'])) {
                $res = User::updatePassword($userId,
                    $_POST['current_password'] ?? '',
                    $_POST['new_password'] ?? '',
                    $_POST['confirm_password'] ?? '');
                if ($res['success']) $msg = $res['message']; else $err = $res['message'];
            }
        }

        $this->render('candidate/account', compact('user','profile','msg','err'));
    }

    public function cv() {
        Auth::requireRole(['ung_vien']);
        $userId = Auth::userId();
        $fields = Field::all();
        $selectedFieldId = isset($_GET['field_id']) ? (int)$_GET['field_id'] : 0;
        $errors = [];

        if (isset($_GET['delete'])) {
            CV::delete((int)$_GET['delete'], $userId);
            $this->redirect(['c'=>'Candidate','a'=>'cv']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fieldId = (int)($_POST['ma_linh_vuc'] ?? 0);
            $selectedFieldId = $fieldId;
            $skillIds = $_POST['skills'] ?? [];
            $cvName = trim($_POST['ten_cv'] ?? '');

            if ($fieldId <= 0) $errors[] = 'Vui lòng chọn lĩnh vực';
            if (empty($_FILES['cv_file']['name'])) $errors[] = 'Vui lòng chọn file CV';

            $cvPath = null;
            if (!$errors) {
                $uploadDir = __DIR__ . '/../uploads/cv';
                if (!is_dir($uploadDir)) mkdir($uploadDir,0777,true);
                $ext = strtolower(pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION));
                $allowed = ['pdf','doc','docx'];
                if (!in_array($ext,$allowed,true)) {
                    $errors[] = 'Chỉ cho phép file pdf/doc/docx';
                } else {
                    $name = 'cv_'.time().'_'.mt_rand(1000,9999).'.'.$ext;
                    $dest = $uploadDir.'/'.$name;
                    if (!move_uploaded_file($_FILES['cv_file']['tmp_name'], $dest)) {
                        $errors[] = 'Upload CV thất bại';
                    } else {
                        $cvPath = 'uploads/cv/'.$name;
                    }
                }
            }

            if (!$errors && $cvPath) {
                // Nếu người dùng không nhập tên, tự gợi ý từ tên file gốc
                if ($cvName === '' && !empty($_FILES['cv_file']['name'])) {
                    $base = pathinfo($_FILES['cv_file']['name'], PATHINFO_FILENAME);
                    $cvName = trim((string)$base);
                }

                if (CV::create($userId, $fieldId, $skillIds, $cvPath, $cvName)) {
                    $this->redirect(['c'=>'Candidate','a'=>'cv']);
                } else {
                    $errors[] = 'Lỗi lưu CSDL';
                }
            }
        }

        $skills = $selectedFieldId ? Skill::byField($selectedFieldId) : [];
        $cvs = CV::getByCandidate($userId);

        $this->render('candidate/cv', [
            'fields'=>$fields,
            'skills'=>$skills,
            'cvs'=>$cvs,
            'errors'=>$errors,
            'selectedFieldId'=>$selectedFieldId,
        ]);
    }
}
