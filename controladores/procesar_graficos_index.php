<?php
//Esta àrte es controladores/procesar_graficos_index.php
include 'conexion.php';
session_start();

$usId   = $_SESSION['usId'];
$anio      = $_GET['anio'] ?? '';
$producto  = $_GET['producto'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$cliente = $_GET['cliente'] ?? '';
$rubro   = $_GET['rubro'] ?? '';
$response = [];

/* 1️⃣ PRODUCTOS REGISTRADOS POR MES */
/* 1️⃣ UNIDADES DE PRODUCTOS INGRESADAS AL INVENTARIO POR MES */
$sql = "SELECT 
            MONTH(fecha_registro) mes, 
            IFNULL(SUM(stock),0) total
        FROM producto
        WHERE Eliminado = 0
        AND id_user = ?
        AND (? = '' OR YEAR(fecha_registro) = ?)
        AND (? = '' OR id_categorias = ?)
        GROUP BY mes
        ORDER BY mes";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("issss", $usId, $anio, $anio, $categoria, $categoria);
$stmt->execute();
$response['productos_mes'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);



/* 3️⃣ VENTAS POR MES */
$sql = "SELECT MONTH(tv.fecha_venta) mes, SUM(dtv.sub_total) total
        FROM ticket_ventas tv
        INNER JOIN detalle_ticket_ventas dtv ON dtv.id_ticket_ventas = tv.id_ticket_ventas
        INNER JOIN producto p ON p.idProducto = dtv.idProducto
        WHERE tv.estado_venta = 'Vendido' and tv.id_user = ?
        AND (? = '' OR YEAR(tv.fecha_venta) = ?)
        AND (? = '' OR dtv.idProducto = ?)
        AND (? = '' OR p.id_categorias = ?)
        GROUP BY mes
        ORDER BY mes";
$stmt = $conexion->prepare($sql);
$stmt->bind_param(
        "issssss",
        $usId,
        $anio,
        $anio,
        $producto,
        $producto,
        $categoria,
        $categoria
);
$stmt->execute();
$response['compras_mes'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
/* 3️⃣ VENTAS POR DÍA */
$sql = "SELECT 
            DATE(tv.fecha_venta) dia,
            SUM(dtv.sub_total) total
        FROM ticket_ventas tv
        INNER JOIN detalle_ticket_ventas dtv 
            ON dtv.id_ticket_ventas = tv.id_ticket_ventas
        WHERE tv.estado_venta = 'Vendido' and tv.id_user = ?
        AND (? = '' OR YEAR(tv.fecha_venta) = ?)
        GROUP BY dia
        ORDER BY dia";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("iss", $usId, $anio, $anio);
$stmt->execute();

$response['compras_dia'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


/* 4️⃣ TOP 6 PRODUCTOS MÁS VENDIDOS */
$sql = "SELECT p.nombre, SUM(dtv.sub_total) total
        FROM ticket_ventas tv
        INNER JOIN detalle_ticket_ventas dtv ON dtv.id_ticket_ventas = tv.id_ticket_ventas
        INNER JOIN producto p ON p.idProducto = dtv.idProducto
        WHERE tv.estado_venta = 'Vendido' and tv.id_user = ?
        AND (? = '' OR YEAR(tv.fecha_venta) = ?)
        AND (? = '' OR dtv.idProducto = ?)
        AND (? = '' OR p.id_categorias = ?)
        GROUP BY p.idProducto, p.nombre
        ORDER BY total DESC
        LIMIT 6";
$stmt = $conexion->prepare($sql);
$stmt->bind_param(
        "issssss",
        $usId,
        $anio,
        $anio,
        $producto,
        $producto,
        $categoria,
        $categoria
);
$stmt->execute();
$response['top_productos'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
/* =========================================================
MONTO TOTAL VENDIDO POR SEMANA
========================================================= */
if (empty($anio)) {
        $anio = date("Y");
}
$sql = "SELECT 
            FLOOR(DATEDIFF(tv.fecha_venta, CONCAT(?, '-01-01')) / 7) + 1 AS semana,
            SUM(dtv.sub_total) AS total
        FROM ticket_ventas tv
        INNER JOIN detalle_ticket_ventas dtv 
            ON dtv.id_ticket_ventas = tv.id_ticket_ventas
        WHERE tv.estado_venta = 'Vendido' and tv.id_user = ?
        AND YEAR(tv.fecha_venta) = ?
        GROUP BY semana
        ORDER BY semana ASC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sii", $anio, $usId, $anio);
$stmt->execute();

$response['compras_semana'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
/* =========================================================
5️⃣ TOP 6 VENDEDORES QUE MÁS VENDEN
========================================================= */
$sql = "SELECT 
            e.nombre,
            e.apellido,
            SUM(tv.total_venta) AS total
        FROM ticket_ventas tv
        INNER JOIN empleados e 
            ON e.id_empleado = tv.id_empleado
        WHERE tv.estado_venta = 'Vendido' and tv.id_user = ?
        AND (? = '' OR YEAR(tv.fecha_venta) = ?)
        GROUP BY e.id_empleado, e.nombre, e.apellido
        ORDER BY total DESC
        LIMIT 6";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("iss", $usId, $anio, $anio);
$stmt->execute();

$response['top_vendedores'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* =========================================================
1️⃣ CLIENTES REGISTRADOS POR MES
👉 NO filtra por cliente (NO tiene sentido)
========================================================= */
$sql = "SELECT MONTH(fecha_registro) mes, COUNT(*) total
        FROM clientes
        WHERE id_user = ?
        AND (? = '' OR YEAR(fecha_registro) = ?)
        AND (? = '' OR id_rubro = ?)
        GROUP BY mes
        ORDER BY mes";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
        "issss",
        $usId,
        $anio,
        $anio,
        $rubro,
        $rubro
);
$stmt->execute();

$response['clientes_mes'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* =========================================================
4️⃣ TOP 6 CLIENTES QUE MÁS COMPRAN
========================================================= */
$sql = "SELECT c.nombre, SUM(tv.total_venta) AS total
        FROM ticket_ventas tv
        INNER JOIN clientes c ON c.idCliente = tv.idCliente
        WHERE tv.estado_venta = 'Vendido' and tv.id_user = ?
        AND (? = '' OR YEAR(tv.fecha_venta) = ?)
        AND (? = '' OR tv.idCliente = ?)
        AND (? = '' OR c.id_rubro = ?)
        GROUP BY c.idCliente, c.nombre
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


header("Content-Type: application/json");
echo json_encode($response);
