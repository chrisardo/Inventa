<?php
//Esta parte es controladores/agregar_carrito_ajax.php
session_start();
include "conexion.php";

$usId = $_SESSION['usId'];
$idProducto = $_POST['idProducto'];

// ✅ VERIFICAR SI YA ESTÁ EN EL CARRITO
$verificar = mysqli_query($conexion, "SELECT * FROM carrito_venta 
    WHERE id_user='$usId' AND idProducto='$idProducto'");

if (mysqli_num_rows($verificar) > 0) {
    // SI EXISTE => SUMAR CANTIDAD
    mysqli_query($conexion, "UPDATE carrito_venta 
        SET cantidad = cantidad + 1 
        WHERE id_user='$usId' AND idProducto='$idProducto'");
} else {
    // SI NO EXISTE => INSERTAR NUEVO
    $precio = mysqli_fetch_assoc(mysqli_query(
        $conexion,
        "SELECT precio FROM producto WHERE idProducto='$idProducto'"
    ))['precio'];

    mysqli_query($conexion, "INSERT INTO carrito_venta 
        (id_user, idProducto, precioTotal, cantidad, fecha_registro) 
        VALUES ('$usId','$idProducto','$precio',1,NOW())");
}

echo json_encode(["status" => "ok"]);
