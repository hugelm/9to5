<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/php-mailer/Exception.php';
require '../vendor/php-mailer/PHPMailer.php';
require '../vendor/php-mailer/SMTP.php';

$env = parse_ini_file('../../../.env.ini');

$field_name = $_POST['name'];
$field_email = $_POST['email'];
$field_subject = $_POST['subject'];
$field_message = $_POST['message'];

if (!filter_var($field_email, FILTER_VALIDATE_EMAIL)) {
    echo "email address invalid";
    exit;
}

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = $env['SMTP_Server'];
    $mail->SMTPAuth = true;
    $mail->Port       = $env['SMTP_Port'];
    $mail->Username   = $env['SMTP_User'];
    $mail->Password   = $env['SMTP_Password'];

    //Recipients
    $mail->setFrom($env['SMTP_Sender']);
    $mail->addAddress($env['SMTP_Recipient']);
    $mail->addReplyTo($field_email, $field_name);

    //Content
    $mail->isHTML(true);
    $mail->Subject = $field_subject;
    $mail->Body    = $field_message;

    $mail->send();

    echo 'email sent';

} catch (Exception $e) {

    echo 'Mailer Error: ' . $mail->ErrorInfo;

}

