<?php
session_start();
require_once "conexion.php";

header('Content-Type: application/json');

if (!isset($_SESSION['usId'])) {
    echo json_encode(["error" => "No autorizado"]);
    exit;
}

$usId = intval($_SESSION['usId']);
$anio = isset($_GET['anio']) && $_GET['anio'] != "" ? intval($_GET['anio']) : null;
$empleado = isset($_GET['empleado']) && $_GET['empleado'] != "" ? intval($_GET['empleado']) : null;
$producto = isset($_GET['productos']) && $_GET['productos'] != "" ? intval($_GET['productos']) : null;

$where = " WHERE tv.id_user = $usId ";

if ($anio) {
    $where .= " AND tv.estado_venta = 'Vendido' and YEAR(tv.fecha_venta) = $anio ";
}

if ($empleado) {
    $where .= " AND tv.estado_venta = 'Vendido' and tv.id_empleado = $empleado ";
}

if ($producto) {
    $where .= " AND tv.estado_venta = 'Vendido' and dt.idProducto = $producto ";
}

$response = [];


// ============================================
// 1️⃣ MONTO TOTAL POR MES
// ============================================
$sql = "
SELECT 
    MONTH(tv.fecha_venta) mes,
    SUM(dt.sub_total) total
FROM ticket_ventas tv
LEFT JOIN detalle_ticket_ventas dt 
    ON dt.id_ticket_ventas = tv.id_ticket_ventas
$where
GROUP BY mes
ORDER BY mes
";

$res = $conexion->query($sql);

$montoMes = array_fill(1, 12, 0);

while ($row = $res->fetch_assoc()) {
    $montoMes[intval($row['mes'])] = floatval($row['total']);
}

$response['montoMes'] = array_values($montoMes);


// ============================================
// 2️⃣ CANTIDAD VENDIDA POR MES
// ============================================
$sql = "
SELECT 
    MONTH(tv.fecha_venta) mes,
    SUM(dt.cantidad_pedido_producto) cantidad
FROM ticket_ventas tv
LEFT JOIN detalle_ticket_ventas dt 
    ON dt.id_ticket_ventas = tv.id_ticket_ventas
$where
GROUP BY mes
ORDER BY mes
";

$res = $conexion->query($sql);

$cantidadMes = array_fill(1, 12, 0);

while ($row = $res->fetch_assoc()) {
    $cantidadMes[intval($row['mes'])] = intval($row['cantidad']);
}

$response['cantidadMes'] = array_values($cantidadMes);


// ============================================
// 3️⃣ EMPLEADOS SEGÚN FILTRO POR MES
// ============================================

$sql = "
SELECT 
    MONTH(tv.fecha_venta) mes,
    COUNT(DISTINCT tv.id_empleado) total
FROM ticket_ventas tv
LEFT JOIN detalle_ticket_ventas dt 
    ON dt.id_ticket_ventas = tv.id_ticket_ventas
$where
GROUP BY mes
ORDER BY mes
";

$res = $conexion->query($sql);

$empleadosMes = array_fill(1, 12, 0);

while ($row = $res->fetch_assoc()) {
    $empleadosMes[intval($row['mes'])] = intval($row['total']);
}

$response['empleadosMes'] = array_values($empleadosMes);


// ============================================
// 4️⃣ TOP 7 EMPLEADOS QUE MÁS VENDEN
// ============================================
$sql = "
SELECT 
    COALESCE(
        CONCAT(e.nombre, ' ', e.apellido),
        ua.nombreEmpresa
    ) AS nombre_vendedor,
    SUM(dt.sub_total) total
FROM ticket_ventas tv
LEFT JOIN detalle_ticket_ventas dt 
    ON dt.id_ticket_ventas = tv.id_ticket_ventas
LEFT JOIN empleados e 
    ON tv.id_empleado = e.id_empleado
LEFT JOIN usuario_acceso ua
    ON tv.id_user = ua.id_user
$where
GROUP BY nombre_vendedor
ORDER BY total DESC
LIMIT 7
";

$res = $conexion->query($sql);

$topNombres = [];
$topTotales = [];

while ($row = $res->fetch_assoc()) {
    $topNombres[] = $row['nombre_vendedor'];
    $topTotales[] = floatval($row['total']);
}

$response['topNombres'] = $topNombres;
$response['topTotales'] = $topTotales;


// ============================================
// 5️⃣ TABLA RESUMEN PRODUCTOS
// Producto | Cantidad | Costo compra | Venta total | Rentabilidad | Utilidad
// ============================================

$sql = "
SELECT 
    p.nombre,
    p.costo_compra,
    SUM(dt.cantidad_pedido_producto) cantidad,
    SUM(dt.sub_total) venta_total
FROM ticket_ventas tv
LEFT JOIN detalle_ticket_ventas dt 
    ON dt.id_ticket_ventas = tv.id_ticket_ventas
LEFT JOIN producto p 
    ON dt.idProducto = p.idProducto
$where
GROUP BY p.idProducto
ORDER BY venta_total DESC
LIMIT 10
";

$res = $conexion->query($sql);

$tablaProductos = [];

while ($row = $res->fetch_assoc()) {

    $ventaTotal = floatval($row['venta_total']);
    $cantidad = intval($row['cantidad']);
    $costoUnitario = floatval($row['costo_compra']);

    // Costo total real
    $costoTotal = $cantidad * $costoUnitario;

    // Utilidad real
    $utilidad = $ventaTotal - $costoTotal;

    // Rentabilidad
    $rentabilidad = $ventaTotal > 0 ? ($utilidad / $ventaTotal) * 100 : 0;

    $tablaProductos[] = [
        "nombre" => $row['nombre'],
        "cantidad" => $cantidad,
        "costo_compra" => $costoUnitario,
        "venta_total" => $ventaTotal,
        "rentabilidad" => round($rentabilidad, 2),
        "utilidad" => round($utilidad, 2)
    ];
}

$response['tablaProductos'] = $tablaProductos;


// ============================================
// 6️⃣ TABLA RESUMEN EMPLEADOS
// Empleado | Ventas | Cantidad | Venta total | Rentabilidad | Utilidad
// ============================================

$sql = "
SELECT 
    COALESCE(
        CONCAT(e.nombre, ' ', e.apellido),
        ua.nombreEmpresa
    ) AS nombre_vendedor,
    COUNT(DISTINCT tv.id_ticket_ventas) ventas,
    SUM(dt.cantidad_pedido_producto) cantidad,
    SUM(dt.sub_total) venta_total,
    SUM(dt.cantidad_pedido_producto * p.costo_compra) costo_total
FROM ticket_ventas tv
LEFT JOIN detalle_ticket_ventas dt 
    ON dt.id_ticket_ventas = tv.id_ticket_ventas
LEFT JOIN empleados e 
    ON tv.id_empleado = e.id_empleado
LEFT JOIN usuario_acceso ua
    ON tv.id_user = ua.id_user
LEFT JOIN producto p 
    ON dt.idProducto = p.idProducto
$where
GROUP BY nombre_vendedor
ORDER BY venta_total DESC
";

$res = $conexion->query($sql);

$tablaEmpleados = [];

while ($row = $res->fetch_assoc()) {

    $ventaTotal = floatval($row['venta_total']);
    $costoTotal = floatval($row['costo_total']);
    $utilidad = $ventaTotal - $costoTotal;
    $rentabilidad = $ventaTotal > 0 ? ($utilidad / $ventaTotal) * 100 : 0;

    $tablaEmpleados[] = [
        "nombre" => $row['nombre_vendedor'],
        "ventas" => intval($row['ventas']),
        "cantidad" => intval($row['cantidad']),
        "venta_total" => $ventaTotal,
        "rentabilidad" => round($rentabilidad, 2),
        "utilidad" => round($utilidad, 2)
    ];
}

$response['tablaEmpleados'] = $tablaEmpleados;
// ============================================
// 7️⃣ RESUMEN GLOBAL EJECUTIVO
// ============================================

$sql = "
SELECT 
    SUM(dt.sub_total) venta_total,
    SUM(dt.cantidad_pedido_producto * p.costo_compra) costo_total
FROM ticket_ventas tv
LEFT JOIN detalle_ticket_ventas dt 
    ON dt.id_ticket_ventas = tv.id_ticket_ventas
LEFT JOIN producto p 
    ON dt.idProducto = p.idProducto
$where
";

$res = $conexion->query($sql);
$row = $res->fetch_assoc();

$ventaTotalGlobal = floatval($row['venta_total']);
$costoTotalGlobal = floatval($row['costo_total']);

$utilidadGlobal = $ventaTotalGlobal - $costoTotalGlobal;
$margenBruto = $ventaTotalGlobal > 0
    ? ($utilidadGlobal / $ventaTotalGlobal) * 100
    : 0;

$response['resumenGlobal'] = [
    "venta_total" => round($ventaTotalGlobal, 2),
    "utilidad" => round($utilidadGlobal, 2),
    "margen" => round($margenBruto, 2)
];

echo json_encode($response);
