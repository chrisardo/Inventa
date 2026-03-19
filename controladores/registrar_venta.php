<?php
session_start();
include "conexion.php";

$usId = $_SESSION['usId'];

$total = floatval($_POST['total']);
$pago = floatval($_POST['pago']);
$vuelto = floatval($_POST['vuelto']);
//$idCliente = $_POST['idCliente'];
$idCliente = isset($_POST['idCliente']) && $_POST['idCliente'] !== ''
    ? intval($_POST['idCliente'])
    : 0;

$formaPago = $_POST['formaPago'];
$tipoComprobante = $_POST['tipoComprobante'];
$estado = "Vendido";
$aplicaIGV = isset($_POST['aplicaIGV']) ? intval($_POST['aplicaIGV']) : 1;

// ✅ FECHA Y HORA CORRECTAS PARA DATE Y TIME
date_default_timezone_set("America/Lima");
$fecha = date("Y-m-d");   // DATE ✅
$hora  = date("H:i:s");  // TIME ✅

// =============================
// GENERAR SERIE Y NUMERO REAL
// =============================
mysqli_begin_transaction($conexion);
try {

    $serie = null;
    $numero = null;

    if ($tipoComprobante === "Boleta") {

        $serie = "B001";
    } elseif ($tipoComprobante === "Factura") {

        $serie = "F001";
    }

    // 🔹 SOLO GENERAR NÚMERO SI EXISTE SERIE
    if ($serie !== null) {

        $sqlNumero = "SELECT MAX(numero) as ultimo 
                  FROM ticket_ventas 
                  WHERE id_user='$usId' 
                  AND serie='$serie'";

        $resNumero = mysqli_query($conexion, $sqlNumero);
        $rowNumero = mysqli_fetch_assoc($resNumero);

        $numero = ($rowNumero['ultimo'] ?? 0) + 1;
    }
    // 🔒 VALIDACIÓN SEGURA BACKEND
    if ($tipoComprobante != "Sin comprobante") {

        if ($idCliente == 0 && $tipoComprobante == "Factura") {
            echo json_encode([
                "status" => "error",
                "msg" => "Factura requiere cliente con RUC"
            ]);
            exit;
        }

        if ($idCliente != 0) {

            $sqlCliente = "SELECT dni_o_ruc FROM clientes 
                       WHERE idCliente='$idCliente' 
                       AND id_user='$usId'";

            $res = mysqli_query($conexion, $sqlCliente);
            $cli = mysqli_fetch_assoc($res);
            $doc = $cli['dni_o_ruc'] ?? "";

            if ($tipoComprobante == "Factura" && strlen($doc) != 11) {
                echo json_encode([
                    "status" => "error",
                    "msg" => "Cliente no tiene RUC válido"
                ]);
                exit;
            }

            if ($tipoComprobante == "Boleta" && $doc != "" && strlen($doc) != 8) {
                echo json_encode([
                    "status" => "error",
                    "msg" => "Cliente no tiene DNI válido"
                ]);
                exit;
            }
        }
    }
    // ✅ 1) INSERTAR TICKET DE VENTA (CORREGIDO)
    $sqlTicket = "INSERT INTO ticket_ventas
(id_user, idCliente, pago_cliente, total_venta, id_metodo_pago, estado_venta, fecha_venta, hora_venta, vuelto_venta, id_empleado, tipo_comprobante, serie, numero, aplica_igv)
VALUES
('$usId', '$idCliente', '$pago', '$total', '$formaPago', '$estado', '$fecha', '$hora', '$vuelto', '0', '$tipoComprobante', " .
        ($serie !== null ? "'$serie'" : "NULL") . ", " .
        ($numero !== null ? "'$numero'" : "NULL") . ", '$aplicaIGV')";

    if (!mysqli_query($conexion, $sqlTicket)) {
        echo json_encode([
            "status" => "error",
            "msg" => "Error al guardar ticket: " . mysqli_error($conexion)
        ]);
        exit;
    }
} catch (Exception $e) {

    mysqli_rollback($conexion);
    echo json_encode(["status" => "error", "msg" => "Error al generar comprobante"]);
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

echo json_encode([
    "status" => "ok",
    "id_ticket" => $id_ticket
]);
