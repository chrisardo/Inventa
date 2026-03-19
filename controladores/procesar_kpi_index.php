<?php
session_start();

if (!isset($_SESSION['usId'])) {
    echo json_encode(["error" => "No autorizado"]);
    exit;
}

include 'conexion.php';

$idUser = (int) $_SESSION['usId'];
$id_empleado = (int) $_SESSION['id_empleado'];
$anio = isset($_GET['anio']) && $_GET['anio'] !== ''
    ? (int) $_GET['anio']
    : null;

/* ===================== TOTAL CLIENTES ===================== */
$sql = "SELECT COUNT(*) total
        FROM clientes
        WHERE Eliminado = 0
        AND id_user = ?";

if ($anio) {
    $sql .= " AND YEAR(fecha_registro) = ?";
}

$stmt = $conexion->prepare($sql);

if ($anio) {
    $stmt->bind_param("ii", $idUser, $anio);
} else {
    $stmt->bind_param("i", $idUser);
}

$stmt->execute();
$totalClientes = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

/* ===================== TOTAL PRODUCTOS ===================== */
$sql = "SELECT IFNULL(SUM(stock),0) total
        FROM producto
        WHERE Eliminado = 0
        AND id_user = ?";

if ($anio) {
    $sql .= " AND YEAR(fecha_registro) = ?";
}

$stmt = $conexion->prepare($sql);

if ($anio) {
    $stmt->bind_param("ii", $idUser, $anio);
} else {
    $stmt->bind_param("i", $idUser);
}

$stmt->execute();
$totalProductos = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
/* ===================== TOTAL EMPLEADOS ===================== */
$sql = "SELECT COUNT(*) total
        FROM empleados
        WHERE id_user = ?";

if ($anio) {
    $sql .= " AND YEAR(fecha_registro) = ?";
}

$stmt = $conexion->prepare($sql);

if ($anio) {
    $stmt->bind_param("ii", $idUser, $anio);
} else {
    $stmt->bind_param("i", $idUser);
}

$stmt->execute();
$totalEmpleados = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

/* ===================== TOTAL VENTAS ===================== */
$sql = "SELECT IFNULL(SUM(total_venta),0) total
        FROM ticket_ventas
        WHERE estado_venta = 'Vendido' and id_user = ?";

if ($anio) {
    $sql .= " AND YEAR(fecha_venta) = ?";
}

$stmt = $conexion->prepare($sql);

if ($anio) {
    $stmt->bind_param("ii", $idUser, $anio);
} else {
    $stmt->bind_param("i", $idUser);
}

$stmt->execute();
$totalVentas = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
$totalVentas = (float) $totalVentas;
/* ===================== TOTAL VENTAS DEL DÍA ===================== */
$sql = "SELECT IFNULL(SUM(total_venta),0) total
        FROM ticket_ventas
        WHERE estado_venta = 'Vendido' and id_user = ?
        AND DATE(fecha_venta) = CURDATE()";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idUser);
$stmt->execute();
$totalVentasDia = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
$totalVentasDia = (float) $totalVentasDia;
/* ===================== GANANCIA ===================== */
if ($anio) {

    $anioAnterior = $anio - 1;

    $sql = "SELECT
            SUM(CASE WHEN YEAR(fecha_venta) = ? THEN total_venta ELSE 0 END)
            -
            SUM(CASE WHEN YEAR(fecha_venta) = ? THEN total_venta ELSE 0 END)
            AS ganancia
            FROM ticket_ventas
            WHERE estado_venta = 'Vendido' and id_user = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iii", $anio, $anioAnterior, $idUser);
    $stmt->execute();
    $ganancia = $stmt->get_result()->fetch_assoc()['ganancia'] ?? 0;
} else {

    $sql = "SELECT
            (SELECT IFNULL(SUM(total_venta),0)
             FROM ticket_ventas
             WHERE estado_venta = 'Vendido' and id_user = ?
             AND DATE(fecha_venta) = CURDATE())
            -
            (SELECT IFNULL(SUM(total_venta),0)
             FROM ticket_ventas
             WHERE estado_venta = 'Vendido' and id_user = ?
             AND DATE(fecha_venta) = CURDATE() - INTERVAL 1 DAY)
            AS ganancia";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $idUser, $idUser);
    $stmt->execute();
    $ganancia = $stmt->get_result()->fetch_assoc()['ganancia'] ?? 0;
}

echo json_encode([
    "totalVentas" => (float)$totalVentas,
    "totalVentasDia" => (float)$totalVentasDia,
    "ganancia" => (float)$ganancia,
    "totalClientes" => (int)$totalClientes,
    "totalProductos" => (int)$totalProductos,
    "totalEmpleados" => (int)$totalEmpleados
]);
