<?php
//Esta parte es controladores/actualizar_cantidad_carrito.php
session_start();
include "conexion.php";

$usId = $_SESSION['usId'];
$idProducto = $_POST['idProducto'];
$cantidad = $_POST['cantidad'];

if ($cantidad < 1) {
    $cantidad = 1;
}

$sql = "UPDATE carrito_venta 
        SET cantidad = '$cantidad' 
        WHERE idProducto = '$idProducto' 
        AND id_user = '$usId'";

mysqli_query($conexion, $sql);

echo json_encode(["status" => "ok"]);
