<?php
$isProduction = isset($_SERVER['HTTP_HOST']) &&
                $_SERVER['HTTP_HOST'] === 'khangstu2025.infinityfreeapp.com';?>

<?php
define('DB_HOST', 'sql308.infinityfree.com');
define('DB_USER', 'if0_40245408');
define('DB_PASS', 'GN6oIGrnv');
define('DB_NAME', 'if0_40245408_quanlytuyendungabc');

define('BASE_URL', $isProduction
  ? 'http://khangstu2025.infinityfreeapp.com'
  : 'http://localhost:8080'
);


// Cấu hình SMTP (tạm thời dùng Gmail làm ví dụ)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'nguyenhuukhang999999999@gmail.com');          // Gmail của bạn
define('SMTP_PASS', 'ybyb ksqn plcq fdnz');             // App password của Gmail
define('SMTP_FROM_EMAIL', 'jobmatch@gmail.com');    // Email From
define('SMTP_FROM_NAME', 'Công ty săn đầu người ABC');
?>