<?php
include "conexion.php";
date_default_timezone_set('America/Lima');

/* ===============================
   CONFIGURACIÓN
================================ */
define('MAX_REENVIOS', 3);
define('BLOQUEO_MINUTOS', 15);
define('EXPIRACION_REENVIO', 60);

/* ===============================
   VALIDACIÓN INICIAL
================================ */
if (!isset($_POST['usId'], $_POST['token'])) {
    header("Location: ../recuperar_cuenta.php");
    exit;
}

$usId = (int)$_POST['usId'];
$token   = $_POST['token'];

/* ===============================
   VERIFICAR REGISTRO
================================ */
$sql = "SELECT intentos_envio, bloqueado_hasta
        FROM codigo_verificacion
        WHERE id_user = ? AND token = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("is", $usId, $token);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows !== 1) {
    die("Solicitud inválida.");
}

$data = $res->fetch_assoc();

/* ===============================
   BLOQUEO ACTIVO
================================ */
if (!empty($data['bloqueado_hasta']) && strtotime($data['bloqueado_hasta']) > time()) {
    die("Demasiados intentos. Intenta nuevamente más tarde.");
}

/* ===============================
   LÍMITE DE REENVÍOS
================================ */
if ($data['intentos_envio'] >= MAX_REENVIOS) {

    $bloqueadoHasta = date(
        "Y-m-d H:i:s",
        strtotime("+" . BLOQUEO_MINUTOS . " minutes")
    );

    $stmt = $conexion->prepare(
        "UPDATE codigo_verificacion
         SET bloqueado_hasta = ?
         WHERE id_user = ?"
    );
    $stmt->bind_param("si", $bloqueadoHasta, $usId);
    $stmt->execute();

    die("Has superado el límite de reenvíos. Espera 15 minutos.");
}

/* ===============================
   NUEVO CÓDIGO
================================ */
$nuevoCodigo    = random_int(100000, 999999);
$nuevosIntentos = $data['intentos_envio'] + 1;
$expiracion     = EXPIRACION_REENVIO;

/* ===============================
   ACTUALIZAR REGISTRO
================================ */
$sqlUpdate = "UPDATE codigo_verificacion
              SET codigo = ?,
                  intentos_envio = ?,
                  fecha_creacion = NOW(),
                  expiracion_segundos = ?,
                  bloqueado_hasta = NULL
              WHERE id_user = ?";

$stmt = $conexion->prepare($sqlUpdate);
$stmt->bind_param(
    "siii",
    $nuevoCodigo,
    $nuevosIntentos,
    $expiracion,
    $usId
);
$stmt->execute();

/* ===============================
   OBTENER EMAIL
================================ */
$stmtUser = $conexion->prepare(
    "SELECT email FROM usuario_acceso WHERE id_user = ?"
);
$stmtUser->bind_param("i", $usId);
$stmtUser->execute();
$email = $stmtUser->get_result()->fetch_assoc()['email'];

/* ===============================
   ENVÍO EMAIL
================================ */
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'chrisardorolo02@gmail.com';
    $mail->Password   = 'ekjxzrlvqqfqnics';
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('chrisardorolo02@gmail.com', 'Sistema Inventa');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Nuevo código de verificación';
    $mail->Body = "
        <h3>Código de verificación</h3>
        <h1>$nuevoCodigo</h1>
        <p>Este código expira en <b>1 minuto</b>.</p>
    ";

    $mail->send();

} catch (Exception $e) {
    die("Error al enviar el correo.");
}

/* ===============================
   REDIRECCIÓN
================================ */
header("Location: ../codigo_seguridad.php?uid=$usId&token=$token&reenviado=1");
exit;
