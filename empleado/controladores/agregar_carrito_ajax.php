<?php
//Esta parte es controladores/agregar_carrito_ajax.php
session_start();
include "../../controladores/conexion.php";
$id_empleado = $_SESSION['id_empleado'];
$usId = $_SESSION['usId'];
$idProducto = $_POST['idProducto'];

// ✅ VERIFICAR SI YA ESTÁ EN EL CARRITO
$verificar = mysqli_query($conexion, "SELECT * FROM carrito_venta 
    WHERE id_empleado='$id_empleado' AND idProducto='$idProducto'");

if (mysqli_num_rows($verificar) > 0) {
    // SI EXISTE => SUMAR CANTIDAD
    mysqli_query($conexion, "UPDATE carrito_venta 
        SET cantidad = cantidad + 1 
        WHERE id_empleado='$id_empleado' AND idProducto='$idProducto'");
} else {
    // SI NO EXISTE => INSERTAR NUEVO
    $precio = mysqli_fetch_assoc(mysqli_query(
        $conexion,
        "SELECT precio FROM producto WHERE idProducto='$idProducto'"
    ))['precio'];

    mysqli_query($conexion, "INSERT INTO carrito_venta 
        (id_user, idProducto, precioTotal, cantidad, fecha_registro, id_empleado) 
        VALUES ('$usId','$idProducto','$precio',1,NOW(), '$id_empleado')");
}

echo json_encode(["status" => "ok"]);
