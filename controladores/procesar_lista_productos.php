<?php
//Toda esta parte es controladores/procesar_lista_productos.php
// Conexión
//include 'conexion.php';
$usId = $_SESSION['usId'];

// ==== ELIMINAR ====
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("UPDATE producto SET Eliminado = 1 WHERE idProducto = $id  AND id_user = $usId");
    //$conexion->query("DELETE FROM producto WHERE idProducto = $id AND id_user = $id_user");
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
            SELECT p.*, pr.nombre AS nombre_proveedor, m.nombre AS nombre_marca
            FROM producto p
            LEFT JOIN provedores pr
                ON p.id_provedor = pr.id_provedor AND pr.Eliminado = 0
            LEFT JOIN marcas m 
                ON p.id_marca = m.id_marca AND m.Eliminado = 0
            WHERE p.id_user = $usId AND p.Eliminado = 0
            AND (
                p.nombre LIKE '%$busquedaEsc%'
                OR p.codigo LIKE '%$busquedaEsc%'
                OR p.fecha_registro LIKE '%$busquedaEsc%'
            )
            ORDER BY p.idProducto DESC
            LIMIT $inicio, $porPagina

    ";

    $resultado = $conexion->query($sql);

    $sqlTotal = "
        SELECT COUNT(*) AS total
        FROM producto
        WHERE id_user = $usId and Eliminado = 0
        AND (
            nombre LIKE '%$busquedaEsc%'
            OR codigo LIKE '%$busquedaEsc%'
            OR fecha_registro LIKE '%$busquedaEsc%'
        )
    ";

    $resultado2 = $conexion->query($sqlTotal);
} else {

    $resultado = $conexion->query("
        SELECT p.*, 
        pr.nombre AS nombre_proveedor,  m.nombre AS nombre_marca
        FROM producto p
        LEFT JOIN provedores pr
            ON p.id_provedor = pr.id_provedor AND pr.Eliminado = 0
        LEFT JOIN marcas m 
            ON p.id_marca = m.id_marca AND m.Eliminado = 0
        WHERE p.id_user = $usId AND p.Eliminado = 0
        ORDER BY p.idProducto DESC
        LIMIT $inicio, $porPagina

    ");

    $resultado2 = $conexion->query("
        SELECT COUNT(*) AS total 
        FROM producto
        WHERE id_user = $usId and Eliminado = 0
    ");
}

// ==== TOTALES ====
$fila = $resultado2->fetch_assoc();
$totalProducto = $fila['total'];
$totalPaginas = ceil($totalProducto / $porPagina);
