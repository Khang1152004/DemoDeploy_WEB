<?php
// ====== CHỈ ĐỔI 1 CHỮ Ở ĐÂY ======
$ENV = 'local'; // 'local' hoặc 'prod'
// =================================

// Prod domains (để BASE_URL đúng khi deploy)
$prodHosts = [
    'khangstu2025.online',
    'www.khangstu2025.online',
    'khangstu2025.infinityfreeapp.com',
];

// Scheme
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

// Xác định prod/local
$isProduction = ($ENV === 'prod');

// ===== DB CONFIG =====
if ($isProduction) {
    define('DB_HOST', 'sql308.infinityfree.com');
    define('DB_USER', 'if0_40245408');
    define('DB_PASS', 'GN6oIGrnv');
    define('DB_NAME', 'if0_40245408_quanlytuyendungabc');
} else {
    define('DB_HOST', 'db'); // docker compose service name
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'quanlytuyendungabc');
}

// ===== BASE_URL =====
if ($isProduction) {
    // nếu host đúng domain thì lấy theo domain, tránh sai URL
    $host = $_SERVER['HTTP_HOST'] ?? 'khangstu2025.online';
    if (!in_array($host, $prodHosts, true)) {
        $host = 'khangstu2025.online';
    }
    define('BASE_URL', $scheme . '://' . $host);
} else {
    // nhớ khớp với port docker bạn đang dùng (ví dụ 8081 vì 8080 bị WAMP chiếm)
    define('BASE_URL', 'http://localhost:8081');
}

// ===== APP CONST =====
define('APP_LOGO_URL', BASE_URL . '/public/assets/images/LogoJobMatch.png');
define('APP_NAME', 'JobMatch');
define('APP_TAGLINE', 'Nền tảng tuyển dụng mini');

// ===== SMTP =====
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'nguyenhuukhang999999999@gmail.com');
define('SMTP_PASS', '...'); // nên chuyển ra env, không để trong code
define('SMTP_FROM_EMAIL', 'no-reply@khangstu2025.online');
define('SMTP_FROM_NAME', 'Công ty săn đầu người ABC');
