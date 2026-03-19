<?php
session_start();
require_once "../../controladores/conexion.php";

if (!isset($_SESSION['usId'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_POST['id_ticket'])) {
    die("ID de venta no recibido.");
}

$id_ticket = intval($_POST['id_ticket']);
$id_user = $_SESSION['usId'];

// =============================
// 1️⃣ Verificar estado actual
// =============================
$sqlVerificar = "SELECT estado_venta 
                 FROM ticket_ventas 
                 WHERE id_ticket_ventas = ? 
                 AND id_user = ?";
$stmt = $conexion->prepare($sqlVerificar);
$stmt->bind_param("ii", $id_ticket, $id_user);
$stmt->execute();
$result = $stmt->get_result();
$venta = $result->fetch_assoc();

if (!$venta || $venta['estado_venta'] !== 'Vendido') {
    die("No se puede cancelar esta venta.");
}

// =============================
// 2️⃣ Cambiar estado a Cancelado
// =============================
$sqlUpdate = "UPDATE ticket_ventas 
              SET estado_venta = 'Cancelado' 
              WHERE id_ticket_ventas = ?";
$stmt = $conexion->prepare($sqlUpdate);
$stmt->bind_param("i", $id_ticket);
$stmt->execute();

// =============================
// 3️⃣ DEVOLVER STOCK (IMPORTANTE)
// =============================
$sqlProductos = "SELECT idProducto, cantidad_pedido_producto
                 FROM detalle_ticket_ventas
                 WHERE id_ticket_ventas = ?";
$stmt = $conexion->prepare($sqlProductos);
$stmt->bind_param("i", $id_ticket);
$stmt->execute();
$resultProd = $stmt->get_result();

while ($prod = $resultProd->fetch_assoc()) {

    $sqlStock = "UPDATE producto 
                 SET stock = stock + ? 
                 WHERE idProducto = ?";
    $stmtStock = $conexion->prepare($sqlStock);
    $stmtStock->bind_param(
        "ii",
        $prod['cantidad_pedido_producto'],
        $prod['idProducto']
    );
    $stmtStock->execute();
}

header("Location: ../ver_detalles_venta.php?itv=" . $id_ticket);
exit();
