<?php

require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';

function enviarNotificacionPassword($email) {

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->CharSet  = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'chrisardorolo02@gmail.com';
        $mail->Password   = 'TU_CLAVE_DE_APLICACION';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('chrisardorolo02@gmail.com', 'Sistema Inventa');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Tu contraseña fue cambiada';
        $mail->Body = "
            <h2>Contraseña actualizada</h2>
            <p>Tu contraseña fue cambiada correctamente.</p>
            <p><strong>Fecha:</strong> " . date('d/m/Y H:i') . "</p>
            <p>Si no realizaste este cambio, contáctanos de inmediato.</p>
        ";

        $mail->send();

    } catch (Exception $e) {
        // No detenemos el proceso por error de email
    }
}
