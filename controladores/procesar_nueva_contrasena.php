<?php
include "conexion.php";
require_once "enviar_notificacion_password.php";

/* ===============================
   VALIDACIÓN INICIAL
================================ */
if (!isset($_POST['usId'], $_POST['token'], $_POST['password'], $_POST['password_confirm'])) {
    die("Solicitud inválida.");
}

$usId = (int)$_POST['usId'];
$token = $_POST['token'];
$pass1 = $_POST['password'];
$pass2 = $_POST['password_confirm'];

if ($pass1 !== $pass2) {
    die("Las contraseñas no coinciden.");
}

/* ===============================
   VALIDAR TOKEN Y OBTENER TIPO
================================ */
$sql = "SELECT tipo 
        FROM codigo_verificacion
        WHERE id_user = ? AND token = ?
        LIMIT 1";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("is", $usId, $token);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows !== 1) {
    die("Token inválido o expirado.");
}

$data = $res->fetch_assoc();
$tipo = $data['tipo'];  // admin o empleado

/* ===============================
   VALIDAR EXPIRACIÓN
================================ */
$sqlFecha = "SELECT fecha_creacion, expiracion_segundos
             FROM codigo_verificacion
             WHERE id_user = ? AND token = ?
             LIMIT 1";

$stmt = $conexion->prepare($sqlFecha);
$stmt->bind_param("is", $usId, $token);
$stmt->execute();
$resFecha = $stmt->get_result();
$rowFecha = $resFecha->fetch_assoc();

$fechaCreacion = strtotime($rowFecha['fecha_creacion']);
$expiracion = (int)$rowFecha['expiracion_segundos'];
$ahora = time();

if (($ahora - $fechaCreacion) < $expiracion) {

    // Eliminar código expirado
    $stmt = $conexion->prepare(
        "DELETE FROM codigo_verificacion WHERE id_user = ?"
    );
    $stmt->bind_param("i", $usId);
    $stmt->execute();

    // Mostrar alerta y redirigir
    echo "<script>
            alert('El código ha expirado. Solicita uno nuevo.');
            window.location.href = '../recuperar_cuenta.php';
          </script>";
    exit;
}

/* ===============================
   🔐 HASH SEGURO
================================ */
$hash = password_hash($pass1, PASSWORD_DEFAULT);

/* ===============================
   ACTUALIZAR SEGÚN TIPO
================================ */
if ($tipo === "admin") {

    $sqlUpdate = "UPDATE usuario_acceso
                  SET contrasena = ?, 
                      password_changed_at = NOW()
                  WHERE id_user = ?";

    $stmt = $conexion->prepare($sqlUpdate);
    $stmt->bind_param("si", $hash, $usId);
} elseif ($tipo === "empleado") {

    $sqlUpdate = "UPDATE empleados
                  SET contrasena = ?
                  WHERE id_empleado = ?";

    $stmt = $conexion->prepare($sqlUpdate);
    $stmt->bind_param("si", $hash, $usId);
} else {
    die("Tipo de cuenta inválido.");
}

if (!$stmt->execute()) {
    die("Error al actualizar contraseña.");
}

/* ===============================
   OBTENER EMAIL PARA NOTIFICACIÓN
================================ */
if ($tipo === "admin") {

    $stmt = $conexion->prepare(
        "SELECT email FROM usuario_acceso WHERE id_user = ? LIMIT 1"
    );
} else {

    $stmt = $conexion->prepare(
        "SELECT email FROM empleados WHERE id_empleado = ? LIMIT 1"
    );
}

$stmt->bind_param("i", $usId);
$stmt->execute();
$resEmail = $stmt->get_result();

$email = null;

if ($resEmail->num_rows === 1) {
    $rowEmail = $resEmail->fetch_assoc();
    $email = $rowEmail['email'];
}

/* ===============================
   ELIMINAR TOKEN USADO
================================ */
$stmt = $conexion->prepare(
    "DELETE FROM codigo_verificacion WHERE id_user = ?"
);
$stmt->bind_param("i", $usId);
$stmt->execute();

/* ===============================
   ENVIAR NOTIFICACIÓN
================================ */
if ($email) {
    enviarNotificacionPassword($email);
}

/* ===============================
   REDIRECCIÓN FINAL
================================ */
header("Location: ../login.php?reset=success");
exit;
