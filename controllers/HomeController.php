<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Field.php';
require_once __DIR__ . '/../models/Location.php';

class HomeController extends Controller {
    public function index() {
        $fields = Field::all();
        $locations = Location::all();

        $keyword = trim($_GET['q'] ?? '');
        $fieldId = isset($_GET['field']) ? (int)$_GET['field'] : 0;
        $salaryKeyword = trim($_GET['salary'] ?? '');
        $locationId = isset($_GET['location']) ? (int)$_GET['location'] : 0;

        if ($keyword !== '' || $fieldId > 0 || $salaryKeyword !== '' || $locationId > 0) {
            $jobs = Job::searchApproved($keyword, $fieldId, $locationId, $salaryKeyword);
        } else {
            $jobs = Job::latestApproved(10);
        }

        $this->render('home/index', [
            'jobs'       => $jobs,
            'fields'     => $fields,
            'locations'  => $locations,
            'keyword'    => $keyword,
            'fieldId'    => $fieldId,
            'locationId' => $locationId,
        ]);
    }
}
