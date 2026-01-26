<?php
session_start();

if (!isset($_SESSION['usId'])) {
    echo json_encode(["error" => "No autorizado"]);
    exit;
}

include 'conexion.php';

$anio = isset($_GET['anio']) && $_GET['anio'] !== ''
    ? (int) $_GET['anio']
    : null;

$idUser = (int) $_SESSION['usId'];

/* ===================== TOTAL CLIENTES ===================== */
if ($anio) {
    $sqlClientes = "
        SELECT COUNT(*) AS total
        FROM clientes
        WHERE Eliminado = 0
        AND id_user = $idUser
        AND YEAR(fecha_registro) = $anio
    ";
} else {
    $sqlClientes = "
        SELECT COUNT(*) AS total
        FROM clientes
        WHERE Eliminado = 0
        AND id_user = $idUser
    ";
}
$totalClientes = $conexion->query($sqlClientes)->fetch_assoc()['total'];

/* ===================== TOTAL PRODUCTOS ===================== */
if ($anio) {
    $sqlProductos = "
        SELECT COUNT(*) AS total
        FROM producto
        WHERE Eliminado = 0
        AND id_user = $idUser
        AND YEAR(fecha_registro) = $anio
    ";
} else {
    $sqlProductos = "
        SELECT COUNT(*) AS total
        FROM producto
        WHERE Eliminado = 0
        AND id_user = $idUser
    ";
}
$totalProductos = $conexion->query($sqlProductos)->fetch_assoc()['total'];

/* ===================== TOTAL VENTAS ===================== */
$sqlVentas = "
    SELECT SUM(total_venta) AS total
    FROM ticket_ventas
    WHERE id_user = $idUser
";
if ($anio) {
    $sqlVentas .= " AND YEAR(fecha_venta) = $anio";
}
$totalVentas = $conexion->query($sqlVentas)->fetch_assoc()['total'] ?? 0;

/* ===================== GANANCIA / PÉRDIDA ===================== */
if ($anio) {
    $sqlGanancia = "
        SELECT
            SUM(CASE WHEN YEAR(fecha_venta) = $anio THEN total_venta ELSE 0 END)
            -
            SUM(CASE WHEN YEAR(fecha_venta) = $anio - 1 THEN total_venta ELSE 0 END)
            AS ganancia
        FROM ticket_ventas
        WHERE id_user = $idUser
    ";
} else {
    $sqlGanancia = "
        SELECT
            (SELECT IFNULL(SUM(total_venta),0)
             FROM ticket_ventas
             WHERE id_user = $idUser
             AND DATE(fecha_venta) = CURDATE())
            -
            (SELECT IFNULL(SUM(total_venta),0)
             FROM ticket_ventas
             WHERE id_user = $idUser
             AND DATE(fecha_venta) = CURDATE() - INTERVAL 1 DAY)
            AS ganancia
    ";
}
$ganancia = round($conexion->query($sqlGanancia)->fetch_assoc()['ganancia'], 2);

/* ===================== RESPUESTA ===================== */
echo json_encode([
    "totalVentas"   => $totalVentas,
    "ganancia"      => $ganancia,
    "totalClientes" => (int)$totalClientes,
    "totalProductos" => (int)$totalProductos
]);
