<?php

use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

session_start();
include 'conexion.php';

header('Content-Type: application/json');

$usId = $_SESSION['usId'] ?? 0;
if ($usId == 0) {
    echo json_encode(['error' => 'Usuario no logueado']);
    exit;
}

// Filtros opcionales
$anio = isset($_GET['anio']) ? $_GET['anio'] : '';
$producto = isset($_GET['producto']) ? $_GET['producto'] : '';
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$cliente = isset($_GET['cliente']) ? $_GET['cliente'] : '';

$where = " WHERE t.id_user = $usId ";
if ($anio != '') $where .= " AND YEAR(t.fecha_venta) = $anio ";
if ($producto != '') $where .= " AND dt.idProducto = $producto ";
if ($categoria != '') $where .= " AND p.id_categorias = $categoria ";
if ($cliente != '') $where .= " AND t.idCliente = $cliente ";

// Inicializar array de 12 meses
function inicializarMeses()
{
    return array_fill(1, 12, 0);
}

// --- Monto total vendido por mes ---
$sqlMontoMes = "SELECT MONTH(t.fecha_venta) as mes, SUM(dt.sub_total) as total_venta ,SUM(dt.cantidad_pedido_producto * dt.sub_total) as total
FROM ticket_ventas t
JOIN detalle_ticket_ventas dt ON t.id_ticket_ventas = dt.id_ticket_ventas
JOIN producto p ON dt.idProducto = p.idProducto
$where
GROUP BY mes";

$resMonto = mysqli_query($conexion, $sqlMontoMes);
$montoMes = inicializarMeses();
if ($resMonto) {
    while ($row = mysqli_fetch_assoc($resMonto)) {
        $montoMes[(int)$row['mes']] = (float)$row['total_venta'];
    }
}

// --- Cantidad de productos vendidos por mes ---
$sqlCantMes = "SELECT MONTH(t.fecha_venta) as mes, SUM(dt.cantidad_pedido_producto) as cantidad
FROM ticket_ventas t
JOIN detalle_ticket_ventas dt ON t.id_ticket_ventas = dt.id_ticket_ventas
JOIN producto p ON dt.idProducto = p.idProducto
$where
GROUP BY mes";

$resCant = mysqli_query($conexion, $sqlCantMes);
$cantMes = inicializarMeses();
if ($resCant) {
    while ($row = mysqli_fetch_assoc($resCant)) {
        $cantMes[(int)$row['mes']] = (int)$row['cantidad'];
    }
}

// --- Función Top 6 ---
function top6($conexion, $usId, $tipo, $filtros = '')
{
    switch ($tipo) {
        case 'producto':
            $sql = "SELECT p.nombre, SUM(dt.sub_total) as total
                    FROM detalle_ticket_ventas dt
                    JOIN ticket_ventas t ON dt.id_ticket_ventas = t.id_ticket_ventas
                    JOIN producto p ON dt.idProducto = p.idProducto
                    WHERE t.id_user = $usId $filtros
                    GROUP BY dt.idProducto
                    ORDER BY total DESC
                    LIMIT 6";
            break;

        case 'categoria':
            $sql = "SELECT c.nombre, SUM(dt.sub_total) as total
                    FROM detalle_ticket_ventas dt
                    JOIN ticket_ventas t ON dt.id_ticket_ventas = t.id_ticket_ventas
                    JOIN producto p ON dt.idProducto = p.idProducto
                    JOIN categorias c ON p.id_categorias = c.id_categorias
                    WHERE t.id_user = $usId $filtros
                    GROUP BY c.id_categorias
                    ORDER BY total DESC
                    LIMIT 6";
            break;

        case 'cliente':
            $sql = "SELECT cl.nombre, SUM(dt.sub_total) as total
                    FROM detalle_ticket_ventas dt
                    JOIN ticket_ventas t ON dt.id_ticket_ventas = t.id_ticket_ventas
                    JOIN clientes cl ON t.idCliente = cl.idCliente
                    WHERE t.id_user = $usId $filtros
                    GROUP BY cl.idCliente
                    ORDER BY total DESC
                    LIMIT 6";
            break;
    }

    $res = mysqli_query($conexion, $sql);
    $labels = [];
    $values = [];

    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $labels[] = $row['nombre'];
            $values[] = isset($row['total'])
                ? (float)$row['total']
                : (int)$row['cantidad'];
        }
    }

    return ['labels' => $labels, 'values' => $values];
}


// --- Función tabla resumen ---
function tablaResumen($conexion, $usId, $tipo, $filtros = '')
{
    if ($tipo == 'producto') {
        $sql = "SELECT p.nombre, SUM(dt.cantidad_pedido_producto) as cantidad,
                SUM(dt.sub_total) as venta_total,
                SUM(dt.cantidad_pedido_producto * dt.sub_total) as costo_total
                FROM detalle_ticket_ventas dt
                JOIN ticket_ventas t ON dt.id_ticket_ventas = t.id_ticket_ventas
                JOIN producto p ON dt.idProducto = p.idProducto
                WHERE t.id_user = $usId $filtros
                GROUP BY dt.idProducto
                ORDER BY venta_total DESC LIMIT 10";
    } elseif ($tipo == 'cliente') {
        $sql = "SELECT cl.nombre, SUM(dt.cantidad_pedido_producto) as cantidad,
                SUM(dt.sub_total) as venta_total,
                SUM(dt.cantidad_pedido_producto * dt.sub_total) as costo_total
                FROM detalle_ticket_ventas dt
                JOIN ticket_ventas t ON dt.id_ticket_ventas = t.id_ticket_ventas
                JOIN clientes cl ON t.idCliente = cl.idCliente
                JOIN producto p ON dt.idProducto = p.idProducto
                WHERE t.id_user = $usId $filtros
                GROUP BY cl.idCliente
                ORDER BY venta_total DESC LIMIT 10";
    } else { // categoria
        $sql = "SELECT c.nombre, SUM(dt.cantidad_pedido_producto) as cantidad,
                SUM(dt.sub_total) as venta_total,
                SUM(dt.cantidad_pedido_producto * dt.sub_total) as costo_total
                FROM detalle_ticket_ventas dt
                JOIN ticket_ventas t ON dt.id_ticket_ventas = t.id_ticket_ventas
                JOIN producto p ON dt.idProducto = p.idProducto
                JOIN categorias c ON p.id_categorias = c.id_categorias
                WHERE t.id_user = $usId $filtros
                GROUP BY c.id_categorias
                ORDER BY venta_total DESC LIMIT 10";
    }

    $res = mysqli_query($conexion, $sql);
    $tabla = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $utilidad = $row['costo_total'] - $row['venta_total'];
            $rentabilidad = $row['venta_total'] != 0 ? ($utilidad / $row['venta_total'] * 100) : 0;
            $tabla[] = [
                'nombre' => $row['nombre'],
                'cantidad' => (int)$row['cantidad'],
                'costo_total' => round((float)$row['costo_total'], 2),
                'venta_total' => round((float)$row['venta_total'], 2),
                'rentabilidad' => round($rentabilidad, 2),
                'utilidad' => round($utilidad, 2)
            ];
        }
    }
    return $tabla;
}

// Crear string de filtros para top6 y tablas
$filtros = '';
if ($anio != '') $filtros .= " AND YEAR(t.fecha_venta) = $anio";
if ($producto != '') $filtros .= " AND dt.idProducto = $producto";
if ($categoria != '') $filtros .= " AND p.id_categorias = $categoria";
if ($cliente != '') $filtros .= " AND t.idCliente = $cliente";

echo json_encode([
    'montoMes' => array_values($montoMes),
    'cantMes' => array_values($cantMes),
    'topProductos' => top6($conexion, $usId, 'producto', $filtros)['labels'],
    'topCant' => top6($conexion, $usId, 'producto', $filtros)['values'],
    'topCategorias' => top6($conexion, $usId, 'categoria', $filtros)['labels'],
    'topCatCant' => top6($conexion, $usId, 'categoria', $filtros)['values'],
    'topClientes' => top6($conexion, $usId, 'cliente', $filtros)['labels'],
    'topCliTot' => top6($conexion, $usId, 'cliente', $filtros)['values'],
    'tablaProductos' => tablaResumen($conexion, $usId, 'producto', $filtros),
    'tablaClientes' => tablaResumen($conexion, $usId, 'cliente', $filtros),
    'tablaCategorias' => tablaResumen($conexion, $usId, 'categoria', $filtros)
]);
