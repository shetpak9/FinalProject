<?php
// filepath: c:\wamp64\www\FinalProject\controllers\mailer.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // If using Composer

function sendMail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Or your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'shetpak9@gmail.com'; // Your email
        $mail->Password = 'toodwtgedutjiwqg'; // App password for Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('shetpak9@gmail.com', 'Final Project');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}