<?php
//Esta parte es controladores/conexion.php
$conexion = new mysqli("localhost", "root", "", "inventa");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
