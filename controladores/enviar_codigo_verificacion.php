<?php
include "conexion.php";
require_once __DIR__ . '/../vendor/autoload.php';

use Twilio\Rest\Client;

/* ===============================
   ZONA HORARIA
================================ */

date_default_timezone_set('America/Lima');

/* ===============================
   VALIDACIÓN INICIAL
================================ */
if (!isset($_POST['usId'], $_POST['method'], $_POST['tipo'])) {
    header("Location: ../recuperar_cuenta.php");
    exit;
}

$usId   = (int) $_POST['usId'];
$method = $_POST['method']; // email o sms
$tipo   = $_POST['tipo'];   // admin o empleado

$usuario = null;

/* ==========================================
   BUSCAR USUARIO SEGÚN TIPO
========================================== */
if ($tipo === "admin") {

    $sql = "SELECT id_user, nombreEmpresa AS nombre, email, celular
            FROM usuario_acceso
            WHERE id_user = ?
            LIMIT 1";

} elseif ($tipo === "empleado") {

    $sql = "SELECT id_empleado, nombre, email, celular
            FROM empleados
            WHERE id_empleado = ?
            LIMIT 1";

} else {
    die("Tipo inválido.");
}

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Usuario no encontrado.");
}

$usuario = $result->fetch_assoc();

/* ===============================
   GENERAR CÓDIGO Y TOKEN
================================ */
$codigo = random_int(100000, 999999);
$token  = bin2hex(random_bytes(32));

/* ===============================
   INSERTAR / ACTUALIZAR CÓDIGO
================================ */
$sqlCheck = "SELECT id_codigo_verificacion, intentos_envio
             FROM codigo_verificacion
             WHERE id_user = ?";

$stmtCheck = $conexion->prepare($sqlCheck);
$stmtCheck->bind_param("i", $usId);
$stmtCheck->execute();
$res = $stmtCheck->get_result();

if ($res->num_rows > 0) {

    $row = $res->fetch_assoc();
    $intentos = $row['intentos_envio'] + 1;

    $sql = "UPDATE codigo_verificacion
            SET codigo = ?, token = ?, intentos_envio = ?, 
                fecha_creacion = NOW(), tipo = ?
            WHERE id_user = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssisi", $codigo, $token, $intentos, $tipo, $usId);

} else {

    $sql = "INSERT INTO codigo_verificacion
            (id_user, codigo, token, intentos_envio, fecha_creacion, tipo)
            VALUES (?, ?, ?, 1, NOW(), ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iiss", $usId, $codigo, $token, $tipo);
}

$stmt->execute();

/* ===============================
   ENVÍO POR EMAIL (PHPMailer)
================================ */
if ($method === 'email') {

    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->CharSet  = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'chrisardorolo02@gmail.com'; // ⚠️ cambia esto
        $mail->Password   = 'ekjxzrlvqqfqnics';           // ⚠️ usa App Password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom(
            'chrisardorolo02@gmail.com',
            $usuario['nombre']
        );

        $mail->addAddress($usuario['email']);

        $mail->isHTML(true);
        $mail->Subject = 'Código de verificación';

        $mail->Body = "
            <h2>{$usuario['nombre']}</h2>
            <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>
            <p><strong>Código de verificación:</strong></p>
            <h1 style='color:#198754;'>$codigo</h1>
            <p>Este código expira en 10 minutos.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        die("Error al enviar correo: " . $mail->ErrorInfo);
    }
}

/* ===============================
   ENVÍO POR SMS (TWILIO)
================================ */
if ($method === 'sms') {

    $sid   = 'TU_SID_TWILIO';
    $auth  = 'TU_TOKEN_TWILIO';
    $from  = 'TU_NUMERO_TWILIO';

    try {

        $twilio = new Client($sid, $auth);

        $twilio->messages->create(
            '+51' . preg_replace('/\D/', '', $usuario['celular']),
            [
                'from' => $from,
                'body' => "Tu código de verificación es: $codigo"
            ]
        );
    } catch (Exception $e) {
        die("Error Twilio: " . $e->getMessage());
    }
}

/* ===============================
   REDIRECCIÓN
================================ */
header("Location: ../codigo_seguridad.php?usId=$usId&token=$token");
exit;
