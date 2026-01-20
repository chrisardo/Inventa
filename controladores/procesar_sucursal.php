<?php
include 'conexion.php';

if (!isset($_SESSION['usId'])) die("No autorizado");

$mensaje = "";
$tipoAlerta = "";

// ================= PAGINACIÓN =================
$registrosPorPagina = 5;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

$inicio = ($pagina - 1) * $registrosPorPagina;

// ================= FORMULARIO =================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST['nombre']);
    $usId = intval($_SESSION['usId']);

    if ($nombre === "") {
        $mensaje = "Nombre vacío";
        $tipoAlerta = "danger";
    } else {
        $nombre = $conexion->real_escape_string($nombre);

        if ($_POST['accion'] === "registrar") {
            $conexion->query("INSERT INTO sucursal (nombre,id_user,Eliminado) VALUES ('$nombre',$usId,0)");
        }

        if ($_POST['accion'] === "editar") {
            $id = intval($_POST['id_sucursal']);
            $conexion->query("UPDATE sucursal SET nombre='$nombre' WHERE id_sucursal=$id AND id_user=$usId");
        }

        header("Location: sucursales.php");
        exit();
    }
}

// ================= ELIMINAR =================
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("UPDATE sucursal SET Eliminado=1 WHERE id_sucursal=$id AND id_user=" . intval($_SESSION['usId']));
    header("Location: sucursales.php");
    exit();
}

// ================= TOTAL =================
$resTotal = $conexion->query("SELECT COUNT(*) total FROM sucursal WHERE Eliminado=0 AND id_user=" . intval($_SESSION['usId']));
$totalSucursal = $resTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalSucursal / $registrosPorPagina);

// ================= LISTA =================
$sql = "SELECT * FROM sucursal
        WHERE Eliminado=0 AND id_user=" . intval($_SESSION['usId']) . "
        ORDER BY id_sucursal DESC
        LIMIT $inicio,$registrosPorPagina";

$resultado = $conexion->query($sql);
