<?php
//Esta parte es phpmailer.php
/*require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';*/
require './vendor/phpmailer/phpmailer/src/Exception.php';
require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    $mail->Username = 'chrisardorolo02@gmail.com';
    $mail->Password = 'ekjxzrlvqqfqnics';

    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('chrisardorolo02@gmail.com', 'Prueba SMTP');
    $mail->addAddress('chrisardorolo02@gmail.com');
    //$mail->addAddress($u['email']);

    $mail->Subject = 'Codigo de verificacion - Inventa';
    $mail->Body = '<h2>Restablecimiento de contraseña</h2>
            <p>Tu código de seguridad es:</p>
            <h1>'.$codigo.'</h1>
            <p>Este código es confidencial.</p>';

    $mail->send();
    echo "OK, correo enviado";
} catch (Exception $e) {
    echo "ERROR: " . $mail->ErrorInfo;
}
