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
    tv.id_ticket_ventas,
    tv.serie,
    tv.numero,
    tv.tipo_comprobante,
    COALESCE(cl.nombre, 'Clientes varios') AS cliente,
    m.nombre AS metodo_pago,
    tv.total_venta,
    tv.pago_cliente,
    tv.vuelto_venta,
    tv.estado_venta,
    tv.fecha_venta,
    tv.hora_venta,
    tv.id_empleado,

    CONCAT(e.nombre, ' ', e.apellido) AS nombre_empleado,
    u.username AS nombre_admin

FROM ticket_ventas tv
LEFT JOIN clientes cl ON tv.idCliente = cl.idCliente
LEFT JOIN metodo_pago m ON m.id_metodo_pago = tv.id_metodo_pago
LEFT JOIN empleados e ON e.id_empleado = tv.id_empleado
LEFT JOIN usuario_acceso u ON u.id_user = tv.id_user

WHERE tv.id_ticket_ventas = ?
LIMIT 1
";

$stmt = $conexion->prepare($sqlVenta);
$stmt->bind_param("i", $id_ticket);
$stmt->execute();
$resultVenta = $stmt->get_result();

if (!$resultVenta) {
    die("❌ Error SQL venta: " . $conexion->error);
}

$venta = $resultVenta->fetch_assoc();
/* =====================================================
   DETERMINAR QUIÉN REALIZÓ LA VENTA
===================================================== */

if (!empty($venta['id_empleado'])) {
    $vendidoPor = $venta['nombre_empleado'] . " (Empleado)";
} else {
    $vendidoPor = " Administrador";
}

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
