<?php
session_start();
include 'conexion.php';

/* =========================================================
   VALIDAR USUARIO LOGUEADO
========================================================= */
if (!isset($_SESSION['usId'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Usuario no autenticado']);
        exit;
}

$usId = $_SESSION['usId'];

/* =========================================================
   FILTROS
========================================================= */
$anio    = $_GET['anio'] ?? '';
$cliente = $_GET['cliente'] ?? '';
$rubro   = $_GET['rubro'] ?? '';

$response = [];

/* =========================================================
1️⃣ CLIENTES REGISTRADOS POR MES
========================================================= */
$sql = "SELECT 
            MONTH(fecha_registro) AS mes,
            COUNT(*) AS total
        FROM clientes
        WHERE id_user = ?
        AND (? = '' OR YEAR(fecha_registro) = ?)
        AND (? = '' OR id_rubro = ?)
        GROUP BY mes
        ORDER BY mes";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("issss", $usId, $anio, $anio, $rubro, $rubro);
$stmt->execute();
$response['clientes_mes'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* =========================================================
2️⃣ CLIENTES REGISTRADOS POR DÍA
========================================================= */
$sql = "SELECT 
            DATE(fecha_registro) AS dia,
            COUNT(*) AS total
        FROM clientes
        WHERE id_user = ?
        AND (? = '' OR YEAR(fecha_registro) = ?)
        AND (? = '' OR id_rubro = ?)
        GROUP BY dia
        ORDER BY dia";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("issss", $usId, $anio, $anio, $rubro, $rubro);
$stmt->execute();
$response['clientes_dia'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* =========================================================
3️⃣ MONTO TOTAL COMPRADO POR MES
========================================================= */
$sql = "SELECT 
            MONTH(tv.fecha_venta) AS mes,
            SUM(tv.total_venta) AS total
        FROM ticket_ventas tv
        INNER JOIN clientes c ON c.idCliente = tv.idCliente
        WHERE tv.id_user = ?
        AND (? = '' OR YEAR(tv.fecha_venta) = ?)
        AND (? = '' OR tv.idCliente = ?)
        AND (? = '' OR c.id_rubro = ?)
        GROUP BY mes
        ORDER BY mes";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
        "issssss",
        $usId,
        $anio,
        $anio,
        $cliente,
        $cliente,
        $rubro,
        $rubro
);
$stmt->execute();
$response['compras_mes'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* =========================================================
4️⃣ TOP 6 CLIENTES QUE MÁS COMPRAN
========================================================= */
$sql = "SELECT 
            c.nombre,
            SUM(tv.total_venta) AS total
        FROM ticket_ventas tv
        INNER JOIN clientes c ON c.idCliente = tv.idCliente
        WHERE tv.id_user = ?
        AND (? = '' OR YEAR(tv.fecha_venta) = ?)
        AND (? = '' OR tv.idCliente = ?)
        AND (? = '' OR c.id_rubro = ?)
        GROUP BY c.idCliente
        ORDER BY total DESC
        LIMIT 6";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
        "issssss",
        $usId,
        $anio,
        $anio,
        $cliente,
        $cliente,
        $rubro,
        $rubro
);
$stmt->execute();
$response['top_clientes'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* =========================================================
5️⃣ CLIENTES REGISTRADOS POR RUBRO
========================================================= */
$sql = "SELECT 
            COALESCE(r.nombre, 'Sin rubro') AS rubro,
            COUNT(c.idCliente) AS total
        FROM clientes c
        LEFT JOIN rubros r ON r.id_rubro = c.id_rubro
        WHERE c.id_user = ?
        AND (? = '' OR c.idCliente = ?)
        AND (? = '' OR YEAR(c.fecha_registro) = ?)
        AND (? = '' OR c.id_rubro = ?)
        GROUP BY r.id_rubro";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
        "issssss",
        $usId,
        $cliente,
        $cliente,
        $anio,
        $anio,
        $rubro,
        $rubro
);
$stmt->execute();
$response['clientes_rubro'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* =========================================================
   RESPUESTA JSON
========================================================= */
header("Content-Type: application/json");
echo json_encode($response);
