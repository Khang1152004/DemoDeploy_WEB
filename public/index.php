<?php
require_once __DIR__ . '/../core/Autoload.php';
require_once __DIR__ . '/../config/config.php';

session_start();

$c = $_GET['c'] ?? 'home';
$a = $_GET['a'] ?? 'index';

$controllerClass = 'App\\Controllers\\' . ucfirst($c) . 'Controller';

if (!class_exists($controllerClass)) {
    $controllerClass = 'App\\Controllers\\HomeController';
    $a = 'index';
}

$controller = new $controllerClass();

if (!method_exists($controller, $a)) {
    die("Action không tồn tại.");
}

$controller->$a();
