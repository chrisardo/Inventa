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
            $conexion->query("INSERT INTO  metodo_pago (nombre,id_user,Eliminado) VALUES ('$nombre',$usId,0)");
        }

        if ($_POST['accion'] === "editar") {
            $id = intval($_POST['id_metodo_pago']);
            $conexion->query("UPDATE metodo_pago SET nombre='$nombre' WHERE id_metodo_pago=$id AND id_user=$usId");
        }

        header("Location: metodo_pago.php");
        exit();
    }
}

// ================= ELIMINAR =================
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("UPDATE metodo_pago SET Eliminado=1 WHERE id_metodo_pago  =$id AND id_user=" . intval($_SESSION['usId']));
    header("Location: metodo_pago.php");
    exit();
}

// ================= TOTAL =================
$resTotal = $conexion->query("SELECT COUNT(*) total FROM metodo_pago WHERE Eliminado=0 AND id_user=" . intval($_SESSION['usId']));
$totalMetodoPago = $resTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalMetodoPago / $registrosPorPagina);

// ================= LISTA =================
$sql = "SELECT * FROM metodo_pago
        WHERE Eliminado=0 AND id_user=" . intval($_SESSION['usId']) . "
        ORDER BY id_metodo_pago DESC
        LIMIT $inicio,$registrosPorPagina";

$resultado = $conexion->query($sql);
