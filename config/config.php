<?php
// Xác định môi trường production
$isProduction = isset($_SERVER['HTTP_HOST']) &&
    in_array($_SERVER['HTTP_HOST'], [
        'khangstu2025.online',
        'www.khangstu2025.online',
        'khangstu2025.infinityfreeapp.com', //domain cũ chạy song song
    ]);

// Tự xác định scheme http/https
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    ? 'https'
    : 'http';

define('DB_HOST', 'sql308.infinityfree.com');
define('DB_USER', 'if0_40245408');
define('DB_PASS', 'GN6oIGrnv');
define('DB_NAME', 'if0_40245408_quanlytuyendungabc');

// BASE_URL cho web
define('BASE_URL', $isProduction
    ? $scheme . '://' . $_SERVER['HTTP_HOST']
    : 'http://localhost:8080'
);
//logo
define('APP_LOGO_URL', BASE_URL . '/public/assets/images/LogoJobMatch.png');
define('APP_NAME', 'JobMatch');
define('APP_TAGLINE', 'Nền tảng tuyển dụng mini');


define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'nguyenhuukhang999999999@gmail.com');
define('SMTP_PASS', 'ybyb ksqn plcq fdnz');
define('SMTP_FROM_EMAIL', 'no-reply@khangstu2025.online');
define('SMTP_FROM_NAME', 'Công ty săn đầu người ABC');
