<?php
$usId = $_SESSION['usId'];

//seccion de controladores/procesar_lista_productos.php
// Conexión
include 'conexion.php';

$mensaje = "";
$tipoAlerta = "";
$tipoMensaje = ""; // success, danger, warning, info

// ==== ELIMINAR ====
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM producto WHERE idProducto = $id AND id_user = $usId");
    //ir a la pagina de lista_productos.php
    @header("Location: ./lista_productos.php");
    exit();
}


// ==== PAGINACIÓN ====
$porPagina = 5; // cantidad de productos por página
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $porPagina;
// ==== CONSULTAS PARA MOSTRAR EN LA TABLA ====

// ==== BUSQUEDA ====
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

if ($busqueda !== '') {

    $busquedaEsc = $conexion->real_escape_string($busqueda);

    $sql = "
        SELECT tv.*, c.nombre, m.nombre as metodo_pago
        FROM ticket_ventas tv
        INNER JOIN clientes c ON tv.idCliente = c.idCliente
        LEFT JOIN metodo_pago m ON m.id_metodo_pago = tv.id_metodo_pago
        WHERE tv.id_user = $usId
        AND (
            tv.serie_venta LIKE '%$busquedaEsc%'
            OR c.nombre LIKE '%$busquedaEsc%'
            OR tv.fecha_venta LIKE '%$busquedaEsc%'
        )
        ORDER BY tv.id_ticket_ventas DESC
        LIMIT $inicio, $porPagina
    ";

    $resultado = $conexion->query($sql);

    $sqlTotal = "
        SELECT COUNT(*) AS total
        FROM ticket_ventas tv
        INNER JOIN clientes c ON tv.idCliente = c.idCliente
        LEFT JOIN metodo_pago m ON m.id_metodo_pago = tv.id_metodo_pago
        WHERE tv.id_user = $usId
        AND (
            tv.serie_venta LIKE '%$busquedaEsc%'
            OR c.nombre LIKE '%$busquedaEsc%'
            OR tv.fecha_venta LIKE '%$busquedaEsc%'
        )
    ";

    $resultado2 = $conexion->query($sqlTotal);
} else {

    $resultado = $conexion->query("
        SELECT tv.*, c.nombre,m.nombre as metodo_pago
        FROM ticket_ventas tv 
        INNER JOIN clientes c ON tv.idCliente = c.idCliente
        LEFT JOIN metodo_pago m ON m.id_metodo_pago = tv.id_metodo_pago
        WHERE tv.id_user = $usId
        ORDER BY tv.id_ticket_ventas DESC
        LIMIT $inicio, $porPagina
    ");

    $resultado2 = $conexion->query("
        SELECT COUNT(*) AS total 
        FROM ticket_ventas tv
        INNER JOIN clientes c ON tv.idCliente = c.idCliente
        LEFT JOIN metodo_pago m ON m.id_metodo_pago = tv.id_metodo_pago
        WHERE tv.id_user = $usId
    ");
}

// ==== TOTALES ====
$fila = $resultado2->fetch_assoc();
$totalVentasRealizadas = $fila['total'];
$totalPaginas = ceil($totalVentasRealizadas / $porPagina);
