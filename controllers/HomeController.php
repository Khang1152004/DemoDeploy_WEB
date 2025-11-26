<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Job.php';

class HomeController extends Controller {
    public function index() {
        $jobs = Job::latestApproved(10);
        $this->render('home/index', compact('jobs'));
    }
}
