<?php
//Toda esta parte es controladores/procesar_index.php

if (!isset($_SESSION['id_empleado'])) {
    header("Location: .././login.php");
    exit();
}

include '../controladores/conexion.php';

$sqlFoto = "SELECT imagen, nombre, apellido FROM empleados WHERE id_empleado = ?";
$stmt = $conexion->prepare($sqlFoto);
$stmt->bind_param("i", $_SESSION['id_empleado']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

$fotoPerfil = null;
if (!empty($usuario['imagen'])) {
    $fotoPerfil = 'data:image/jpeg;base64,' . base64_encode($usuario['imagen']);
}
