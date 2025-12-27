<?php
//Toda esta parte es controladores/procesar_graficos_producto.php
include 'conexion.php';
session_start();

$usId   = $_SESSION['usId'];
$anio      = $_GET['anio'] ?? '';
$producto  = $_GET['producto'] ?? '';
$categoria = $_GET['categoria'] ?? '';

$response = [];

/* 1️⃣ PRODUCTOS REGISTRADOS POR MES */
$sql = "SELECT MONTH(fecha_registro) mes, COUNT(*) total
        FROM producto
        WHERE id_user = ?
        AND (? = '' OR YEAR(fecha_registro) = ?)
        AND (? = '' OR id_categorias = ?)
        GROUP BY mes
        ORDER BY mes";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("issss", $usId, $anio, $anio, $categoria, $categoria);
$stmt->execute();
$response['productos_mes'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* 2️⃣ PRODUCTOS REGISTRADOS POR DÍA */
$sql = "SELECT DATE(fecha_registro) dia, COUNT(*) total
        FROM producto
        WHERE id_user = ?
        AND (? = '' OR YEAR(fecha_registro) = ?)
        AND (? = '' OR id_categorias = ?)
        GROUP BY dia
        ORDER BY dia";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("issss", $usId, $anio, $anio, $categoria, $categoria);
$stmt->execute();
$response['productos_dia'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* 3️⃣ VENTAS POR MES */
$sql = "SELECT MONTH(tv.fecha_venta) mes, SUM(dtv.sub_total) total
        FROM ticket_ventas tv
        INNER JOIN detalle_ticket_ventas dtv ON dtv.id_ticket_ventas = tv.id_ticket_ventas
        INNER JOIN producto p ON p.idProducto = dtv.idProducto
        WHERE tv.id_user = ?
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

/* 4️⃣ TOP 6 PRODUCTOS MÁS VENDIDOS */
$sql = "SELECT p.nombre, SUM(dtv.sub_total) total
        FROM ticket_ventas tv
        INNER JOIN detalle_ticket_ventas dtv ON dtv.id_ticket_ventas = tv.id_ticket_ventas
        INNER JOIN producto p ON p.idProducto = dtv.idProducto
        WHERE tv.id_user = ?
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
/* 6️⃣ TOP 6 CATEGORÍAS MÁS VENDIDAS */
$sql = "SELECT 
            COALESCE(c.nombre, 'Sin categoría') categoria,
            SUM(dtv.sub_total) total
        FROM ticket_ventas tv
        INNER JOIN detalle_ticket_ventas dtv 
            ON dtv.id_ticket_ventas = tv.id_ticket_ventas
        INNER JOIN producto p 
            ON p.idProducto = dtv.idProducto
        LEFT JOIN categorias c 
            ON c.id_categorias = p.id_categorias
        WHERE tv.id_user = ?
        AND (? = '' OR YEAR(tv.fecha_venta) = ?)
        AND (? = '' OR p.id_categorias = ?)
        GROUP BY c.id_categorias, c.nombre
        ORDER BY total DESC
        LIMIT 6";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
        "issss",
        $usId,
        $anio,
        $anio,
        $categoria,
        $categoria
);
$stmt->execute();
$response['top_categorias'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
/* 5️⃣ PRODUCTOS POR CATEGORÍA */
$sql = "SELECT COALESCE(c.nombre, 'Sin categoría') categoria, COUNT(p.idProducto) total
        FROM producto p
        LEFT JOIN categorias c ON c.id_categorias = p.id_categorias
        WHERE p.id_user = ?
        AND (? = '' OR p.idProducto = ?)
        AND (? = '' OR YEAR(p.fecha_registro) = ?)
        AND (? = '' OR p.id_categorias = ?)
        GROUP BY c.id_categorias";
$stmt = $conexion->prepare($sql);
$stmt->bind_param(
        "issssss",
        $usId,
        $producto,
        $producto,
        $anio,
        $anio,
        $categoria,
        $categoria
);
$stmt->execute();
$response['productos_categoria'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

header("Content-Type: application/json");
echo json_encode($response);
