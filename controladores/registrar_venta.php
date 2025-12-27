<?php
session_start();
include "conexion.php";

$usId = $_SESSION['usId'];

$total = floatval($_POST['total']);
$pago = floatval($_POST['pago']);
$vuelto = floatval($_POST['vuelto']);
$idCliente = $_POST['idCliente'];
$formaPago = $_POST['formaPago'];
$estado = "Vendido";

// ✅ FECHA Y HORA CORRECTAS PARA DATE Y TIME
date_default_timezone_set("America/Lima");
$fecha = date("Y-m-d");   // DATE ✅
$hora  = date("H:i:s");  // TIME ✅

// ✅ SERIE ÚNICA
$serie = rand(100000000, 999999999);

// ✅ 1) INSERTAR TICKET DE VENTA (CORREGIDO)
$sqlTicket = "INSERT INTO ticket_ventas
(id_user, idCliente, pago_cliente, total_venta, forma_pago, estado_venta, fecha_venta, hora_venta, serie_venta, vuelto_venta)
VALUES
('$usId', '$idCliente', '$pago', '$total', '$formaPago', '$estado', '$fecha', '$hora', '$serie', '$vuelto')";

if (!mysqli_query($conexion, $sqlTicket)) {
    echo json_encode([
        "status" => "error",
        "msg" => "Error al guardar ticket: " . mysqli_error($conexion)
    ]);
    exit;
}

// ✅ 2) OBTENER ID REAL DEL TICKET
$id_ticket = mysqli_insert_id($conexion);

if ($id_ticket == 0) {
    echo json_encode([
        "status" => "error",
        "msg" => "No se pudo obtener el ID del ticket"
    ]);
    exit;
}

// ✅ 3) OBTENER CARRITO
$sqlCarrito = "SELECT * FROM carrito_venta WHERE id_user = '$usId'";
$carrito = mysqli_query($conexion, $sqlCarrito);

// ✅ 4) GUARDAR DETALLE + DESCONTAR STOCK + ACTUALIZAR MÁS VENDIDOS
while ($row = mysqli_fetch_assoc($carrito)) {

    $idProducto = $row['idProducto'];
    $cantidad   = $row['cantidad'];
    $sub_total = $row['precioTotal'] * $cantidad;

    mysqli_query($conexion, "INSERT INTO detalle_ticket_ventas
(id_user, idProducto, id_ticket_ventas, cantidad_pedido_producto, sub_total)
VALUES
('$usId', '$idProducto', '$id_ticket', '$cantidad', '$sub_total')");


    // ✅ DESCONTAR STOCK
    mysqli_query($conexion, "UPDATE producto
                             SET stock = stock - $cantidad
                             WHERE idProducto = '$idProducto'");

    // ✅ MÁS VENDIDOS
    $buscar = mysqli_query($conexion, "SELECT * FROM cantidad_producto_vendido 
                                       WHERE id_user='$usId' AND idProducto='$idProducto'");

    if (mysqli_num_rows($buscar) > 0) {
        mysqli_query($conexion, "UPDATE cantidad_producto_vendido 
                                 SET cantidad_total = cantidad_total + $cantidad
                                 WHERE id_user='$usId' AND idProducto='$idProducto'");
    } else {
        mysqli_query($conexion, "INSERT INTO cantidad_producto_vendido
        (id_user, idProducto, cantidad_total)
        VALUES
        ('$usId', '$idProducto', '$cantidad')");
    }
}

// ✅ 5) VACIAR CARRITO
mysqli_query($conexion, "DELETE FROM carrito_venta WHERE id_user = '$usId'");

echo json_encode(["status" => "ok"]);
