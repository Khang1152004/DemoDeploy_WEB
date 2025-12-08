<?php
class Controller {
    protected function render($view, $data = []) {
        extract($data);
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/' . $view . '.php';
        include __DIR__ . '/../views/layout/footer.php';
    }

    protected function redirect($params) {
        $query = http_build_query($params);
        header("Location: index.php?$query");
        exit;
    }
    

}
function formatDate($dateString) {
    if (empty($dateString)) return "";
    $ts = strtotime($dateString);
    if (!$ts) return $dateString;
    return date("d-m-Y", $ts);
}
