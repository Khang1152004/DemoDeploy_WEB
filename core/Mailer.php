<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/src/SMTP.php';

class Mailer
{
    private static function createMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        // Nếu cần gửi HTML thì bật:
       $mail->isHTML(false); // gửi text


        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);

        return $mail;
    }

    public static function sendToMany(array $emails, string $subject, string $body): int
    {
        if (empty($emails)) return 0;

        $mail = self::createMailer();
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $sent = 0;
        foreach ($emails as $email) {
            $mail->clearAddresses();
            $mail->addAddress($email);
            try {
                if ($mail->send()) $sent++;
            } catch (Exception $e) {
                // error_log('Mailer error: ' . $mail->ErrorInfo);
            }
        }
        return $sent;
    }
}
?>
