<?php

require_once "conexion.php";

if (!isset($_SESSION['usId'])) {
    header("Location: ./login.php");
    exit();
}

if (!isset($_GET['itv'])) {
    die("❌ ID de venta no recibido.");
}

$id_ticket = intval($_GET['itv']);


// ==========================
// ✅ DETALLE GENERAL DE LA VENTA
// ==========================
$sqlVenta = "
SELECT 
    tv.serie_venta,
    cl.nombre AS cliente,
    tv.forma_pago,
    tv.total_venta,
    tv.pago_cliente,
    tv.vuelto_venta,
    tv.estado_venta,
    tv.fecha_venta,
    tv.hora_venta
FROM ticket_ventas tv
LEFT JOIN clientes cl ON tv.idCliente = cl.idCliente
WHERE tv.id_ticket_ventas = $id_ticket
LIMIT 1
";

$resultVenta = $conexion->query($sqlVenta);

if (!$resultVenta) {
    die("❌ Error SQL venta: " . $conexion->error);
}

$venta = $resultVenta->fetch_assoc();

if (!$venta) {
    die("❌ No se encontró la venta.");
}


// ==========================
// ✅ PRODUCTOS DE LA VENTA
// ==========================
$sqlDetalle = "
SELECT 
    p.codigo,
    p.nombre,
    p.imagen,
    dt.cantidad_pedido_producto,
    p.precio,
    dt.sub_total
FROM detalle_ticket_ventas dt
LEFT JOIN producto p ON dt.idProducto = p.idProducto
WHERE dt.id_ticket_ventas = $id_ticket
";

$resultadoDetalle = $conexion->query($sqlDetalle);

if (!$resultadoDetalle) {
    die("❌ Error SQL detalle: " . $conexion->error);
}
