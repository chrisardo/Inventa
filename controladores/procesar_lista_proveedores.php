<?php
//Toda esta parte es de controladores/procesar_lista_proveedores.php
// Conexión
include 'conexion.php';

// ==== ELIMINAR ====
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("UPDATE provedores SET Eliminado = 1 WHERE id_provedor = $id");
    //$conexion->query("DELETE FROM proveedores WHERE id_proveedor = $id AND id_user = $id_user AND Eliminado = 0");
    //ir a la pagina de lista_proveedores.php
    @header("Location: ./lista_proveedores.php");
    exit();
}


// ==== PAGINACIÓN CONFIGURACIÓN ====
$porPagina = 5;
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($pagina - 1) * $porPagina;

// ==== BUSQUEDA ====
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

$whereBusqueda = "";
if ($busqueda !== '') {
    $busquedaEsc = $conexion->real_escape_string($busqueda);
    $whereBusqueda = " AND (
        nombre LIKE '%$busquedaEsc%' 
        OR ruc LIKE '%$busquedaEsc%' 
        OR celular LIKE '%$busquedaEsc%'
    )";
}

// ==== CONSULTA PAGINADA ====
$sql = "
    SELECT * FROM provedores 
    WHERE id_user = $usId and Eliminado = 0
    $whereBusqueda
    ORDER BY id_provedor DESC
    LIMIT $porPagina OFFSET $offset
";
$resultado = $conexion->query($sql);

// ==== TOTAL DE REGISTROS ====
$sqlTotal = "
    SELECT COUNT(*) AS total 
    FROM provedores 
    WHERE id_user = $usId and Eliminado = 0
    $whereBusqueda
";
$resultado2 = $conexion->query($sqlTotal);
if (!$resultado2) {
    die("Error SQL Total: " . $conexion->error);
    //ir a la pagina de lista_proveedores.php
    @header("Location: ./lista_proveedores.php");
}

$fila = $resultado2->fetch_assoc();
$totalProveedores = $fila['total'];

$totalPaginas = ceil($totalProveedores / $porPagina);
