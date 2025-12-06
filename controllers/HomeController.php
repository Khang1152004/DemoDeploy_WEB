<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Field.php';

class HomeController extends Controller {
    public function index() {
        $fields = Field::all();

        $keyword = trim($_GET['q'] ?? '');
        $fieldId = isset($_GET['field']) ? (int)$_GET['field'] : 0;
        $salaryKeyword = trim($_GET['salary'] ?? '');
        $locationId = 0; // có thể mở rộng thêm filter địa điểm sau này

        if ($keyword !== '' || $fieldId > 0 || $salaryKeyword !== '' || $locationId > 0) {
            $jobs = Job::searchApproved($keyword, $fieldId, $locationId, $salaryKeyword);
        } else {
            $jobs = Job::latestApproved(10);
        }

        $this->render('home/index', [
            'jobs'          => $jobs,
            'fields'        => $fields,
            'keyword'       => $keyword,
            'fieldId'       => $fieldId,
        ]);
    }
}
