<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
?>
<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Auth.php';

$c = $_GET['c'] ?? 'Home';
$a = $_GET['a'] ?? 'index';


$c = preg_replace('/[^a-zA-Z0-9_]/', '', $c);   // chống ký tự lạ
$c = ucfirst(strtolower($c));                   // candidate -> Candidate, home -> Home

$controllerName = $c . 'Controller';
$file = __DIR__ . '/controllers/' . $controllerName . '.php';
if (!file_exists($file)) {
    die('Không tìm thấy controller ' . htmlspecialchars($controllerName));
}
require_once $file;
if (!class_exists($controllerName)) {
    die('Không tìm thấy class ' . htmlspecialchars($controllerName));
}
$controller = new $controllerName();
if (!method_exists($controller, $a)) {
    die('Không tìm thấy action ' . htmlspecialchars($a));
}
$controller->$a();
