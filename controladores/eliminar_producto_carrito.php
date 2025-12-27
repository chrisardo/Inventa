<?php
session_start();
include "conexion.php";

$usId = $_SESSION['usId'];
$idProducto = $_POST['idProducto'];

mysqli_query($conexion, "DELETE FROM carrito_venta 
    WHERE id_user = '$usId' AND idProducto = '$idProducto'");

echo json_encode(["status" => "ok"]);
