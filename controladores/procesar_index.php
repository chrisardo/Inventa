<?php
//esta parte es controladores/procesar_index.php

if (!isset($_SESSION['usId'])) {
    header("Location: ./login.php");
    exit();
}

include 'conexion.php';

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
$sqlClientes = "SELECT COUNT(*) AS total FROM clientes WHERE Eliminado = 0 AND id_user = " . $_SESSION['usId'];

$resultado2 = $conexion->query($sqlClientes);
$fila = $resultado2->fetch_assoc();
$totalClientes = $fila['total'];

// Consulta para contar productos
$sqlProductos = "SELECT COUNT(*) AS total FROM producto WHERE Eliminado = 0 AND id_user = " . $_SESSION['usId'];
$resultado3 = $conexion->query($sqlProductos);
$fila2 = $resultado3->fetch_assoc();
$totalProductos = $fila2['total'];

// =============================
// MONTO TOTAL VENDIDO (con filtro por año si existe)
// =============================
$sqlVentas = "SELECT SUM(total_venta) AS total FROM ticket_ventas WHERE id_user = " . $_SESSION['usId'];
$resultado4 = $conexion->query($sqlVentas);
$fila3 = $resultado4->fetch_assoc();
$totalVentas = $fila3['total'] ?? 0;
