<?php
//TOda esta parte es controladores/procesar_nueva_contrasena.php
include "conexion.php";
require_once "enviar_notificacion_password.php";

/* ===============================
   VALIDACIÓN INICIAL
================================ */
if (!isset($_POST['usId'], $_POST['token'], $_POST['password'], $_POST['password_confirm'])) {
   die("Solicitud inválida.");
}

$usId = (int)$_POST['usId'];
$token   = $_POST['token'];
$pass1   = $_POST['password'];
$pass2   = $_POST['password_confirm'];

if ($pass1 !== $pass2) {
   die("Las contraseñas no coinciden.");
}
if (strlen($pass1) < 8) {
   die("La contraseña debe tener al menos 8 caracteres.");
}
/* ===============================
   VALIDAR TOKEN
================================ */
$sql = "SELECT id_codigo_verificacion
        FROM codigo_verificacion
        WHERE id_user = ? AND token = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("is", $usId, $token);
$stmt->execute();

if ($stmt->get_result()->num_rows !== 1) {
   die("Token inválido o expirado.");
}

/* ===============================
   🔐 HASHEAR CONTRASEÑA (CORRECTO)
================================ */
$hash = password_hash($pass1, PASSWORD_DEFAULT);

/* ===============================
   ACTUALIZAR CONTRASEÑA
================================ */
$sqlUpdate = "UPDATE usuario_acceso
              SET contrasena = ?, password_changed_at = NOW()
              WHERE id_user = ?";

$stmt = $conexion->prepare($sqlUpdate);
$stmt->bind_param("si", $hash, $usId);
$stmt->execute();

/* ===============================
   ELIMINAR CÓDIGO
================================ */
$stmt = $conexion->prepare(
   "DELETE FROM codigo_verificacion WHERE id_user = ?"
);
$stmt->bind_param("i", $usId);
$stmt->execute();

/* ===============================
   ENVIAR NOTIFICACIÓN
================================ */
$sqlUser = "SELECT email FROM usuario_acceso WHERE id_user = ?";
$stmt = $conexion->prepare($sqlUser);
$stmt->bind_param("i", $usId);
$stmt->execute();
$email = $stmt->get_result()->fetch_assoc()['email'];

enviarNotificacionPassword($email);

/* ===============================
   REDIRECCIÓN FINAL
================================ */
header("Location: ../login.php?reset=success");
exit;
