<?php
//Toda esta parte es controladores/procesar_index.php

if (!isset($_SESSION['usId'])) {
    header("Location: ./login.php");
    exit();
}

include 'conexion.php';
$anio = isset($_POST['anio']) && $_POST['anio'] !== ''
    ? (int) $_POST['anio']
    : null;
$sqlFoto = "SELECT imagen, nombreEmpresa FROM usuario_acceso WHERE id_user = ?";
$stmt = $conexion->prepare($sqlFoto);
$stmt->bind_param("i", $_SESSION['usId']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

$fotoPerfil = null;
if (!empty($usuario['imagen'])) {
    $fotoPerfil = 'data:image/jpeg;base64,' . base64_encode($usuario['imagen']);
}


// Consulta para contar proveedores
$sqlProveedores = "SELECT COUNT(*) AS total FROM provedores WHERE Eliminado = 0 AND id_user = " . $_SESSION['usId'];
$resultado1 = $conexion->query($sqlProveedores);
$fila0 = $resultado1->fetch_assoc();
$totalProvedores = $fila0['total'];

// Consulta para contar clientes (con filtro por año si existe)
if ($anio) {
    $sqlClientes = "
        SELECT COUNT(DISTINCT c.idCliente) AS total
        FROM clientes c
        WHERE c.Eliminado = 0
        AND c.id_user = {$_SESSION['usId']}
        AND YEAR(c.fecha_registro) = $anio
    ";
} else {
    $sqlClientes = "
        SELECT COUNT(*) AS total
        FROM clientes
        WHERE Eliminado = 0
        AND id_user = {$_SESSION['usId']}
    ";
}

$resultado2 = $conexion->query($sqlClientes);
$totalClientes = $resultado2->fetch_assoc()['total'];

// Consulta para contar productos
if ($anio) {
    $sqlProductos = "
        SELECT COUNT(DISTINCT p.idProducto) AS total
        FROM producto p
        WHERE p.Eliminado = 0
        AND p.id_user = {$_SESSION['usId']}
        AND YEAR(p.fecha_registro) = $anio
    ";
} else {
    $sqlProductos = "
        SELECT COUNT(*) AS total
        FROM producto
        WHERE Eliminado = 0
        AND id_user = {$_SESSION['usId']}
    ";
}

$resultado3 = $conexion->query($sqlProductos);
$totalProductos = $resultado3->fetch_assoc()['total'];

// =============================
// MONTO TOTAL VENDIDO (con filtro por año si existe)
// =============================
/*$sqlVentas = "SELECT SUM(total_venta) AS total FROM ticket_ventas WHERE id_user = " . $_SESSION['usId'];
$resultado4 = $conexion->query($sqlVentas);
$fila3 = $resultado4->fetch_assoc();
$totalVentas = $fila3['total'] ?? 0;*/
$sqlVentas = "
    SELECT SUM(total_venta) AS total
    FROM ticket_ventas
    WHERE id_user = {$_SESSION['usId']}
";

if ($anio) {
    $sqlVentas .= " AND YEAR(fecha_venta) = $anio";
}

$resultado4 = $conexion->query($sqlVentas);
$totalVentas = $resultado4->fetch_assoc()['total'] ?? 0;

if ($anio) {
    // Ganancia del año seleccionado
    $sqlGanancia = "
        SELECT 
            SUM(CASE WHEN YEAR(fecha_venta) = $anio THEN total_venta ELSE 0 END) -
            SUM(CASE WHEN YEAR(fecha_venta) = $anio - 1 THEN total_venta ELSE 0 END)
            AS ganancia
        FROM ticket_ventas
        WHERE id_user = {$_SESSION['usId']}
    ";
} else {
    // Ganancia diaria (como lo tienes ahora)
    $sqlGanancia = "
        SELECT
            (SELECT IFNULL(SUM(total_venta),0)
             FROM ticket_ventas
             WHERE id_user = {$_SESSION['usId']}
             AND DATE(fecha_venta) = CURDATE())
            -
            (SELECT IFNULL(SUM(total_venta),0)
             FROM ticket_ventas
             WHERE id_user = {$_SESSION['usId']}
             AND DATE(fecha_venta) = CURDATE() - INTERVAL 1 DAY)
            AS ganancia
    ";
}

$resGanancia = $conexion->query($sqlGanancia);
$gananciaPerdida = round($resGanancia->fetch_assoc()['ganancia'], 2);
