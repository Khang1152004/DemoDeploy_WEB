<?php
// Ưu tiên lấy từ biến môi trường (Docker), nếu không có thì dùng default
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: 'example');
define('DB_NAME', getenv('DB_NAME') ?: 'app');

// BASE_URL nên để linh hoạt, tạm giữ kiểu cũ để khỏi vỡ
define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost:8080');

// SMTP: cũng lấy từ ENV để không lộ pass
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USER', getenv('SMTP_USER') ?: '');
define('SMTP_PASS', getenv('SMTP_PASS') ?: '');
define('SMTP_FROM_EMAIL', getenv('SMTP_FROM_EMAIL') ?: 'jobmatch@gmail.com');
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'Công ty săn đầu người ABC');
?>
