<?php
include "conexion.php";

/* ======================================================
   VALIDAR PARÁMETROS
====================================================== */
if (!isset($_GET["uid"], $_GET["tipo"])) {
    header("Location: recuperar_cuenta.php");
    exit;
}

$usId = (int) $_GET["uid"];
$tipoCuenta = $_GET["tipo"];

/* Validar tipo permitido */
if (!in_array($tipoCuenta, ["admin", "empleado"])) {
    header("Location: recuperar_cuenta.php");
    exit;
}

/* ======================================================
   CONSULTAR SEGÚN TIPO DE CUENTA
====================================================== */

if ($tipoCuenta === "admin") {

    $sql = "SELECT id_user, nombreEmpresa AS nombre, email, celular, imagen
            FROM usuario_acceso
            WHERE id_user = ?
            LIMIT 1";

} else {

    $sql = "SELECT id_empleado, nombre, email, celular, imagen
            FROM empleados
            WHERE id_empleado = ?
            LIMIT 1";
}

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usId);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    header("Location: recuperar_cuenta.php");
    exit;
}

$u = $resultado->fetch_assoc();

/* ======================================================
   OCULTAR EMAIL
====================================================== */
$email = $u['email'] ?? '';
$partes = explode('@', $email);

$emailOculto = '';
if (count($partes) === 2) {
    $emailOculto = substr($partes[0], 0, 1)
        . str_repeat('*', max(strlen($partes[0]) - 2, 1))
        . substr($partes[0], -1)
        . '@' . $partes[1];
}

/* ======================================================
   OCULTAR CELULAR
====================================================== */
$celularLimpio = preg_replace('/\D/', '', $u['celular'] ?? '');
$celularOculto = '+51 ***' . substr($celularLimpio, -3);

?>