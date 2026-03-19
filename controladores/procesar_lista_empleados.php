<?php
//Toda esta parte es de controladores/procesar_lista_proveedores.php
// Conexión
include 'conexion.php';

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
        OR dni LIKE '%$busquedaEsc%' 
        OR celular LIKE '%$busquedaEsc%'
    )";
}

// ==== CONSULTA PAGINADA ====
$sql = "
    SELECT * FROM empleados 
    WHERE id_user = $usId
    $whereBusqueda
    ORDER BY id_empleado DESC
    LIMIT $porPagina OFFSET $offset
";
$resultado = $conexion->query($sql);

// ==== TOTAL DE REGISTROS ====
$sqlTotal = "
    SELECT COUNT(*) AS total 
    FROM empleados 
    WHERE id_user = $usId
    $whereBusqueda
";
$resultado2 = $conexion->query($sqlTotal);
if (!$resultado2) {
    die("Error SQL Total: " . $conexion->error);
    //ir a la pagina de lista_proveedores.php
    @header("Location: ./lista_empelados.php");
}

$fila = $resultado2->fetch_assoc();
$totalEmpleados = $fila['total'];

$totalPaginas = ceil($totalEmpleados / $porPagina);
