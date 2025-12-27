<?php
// ================= CONEXIÓN =================
include 'conexion.php';

// ================= SESIÓN =================
if (!isset($_SESSION['usId'])) {
    die("Acceso no autorizado");
}

$usId = intval($_SESSION['usId']);
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

    if ($nombre === "") {
        $mensaje = "El nombre del rubro no puede estar vacío.";
        $tipoAlerta = "danger";
    } else {
        $nombre = $conexion->real_escape_string($nombre);

        // REGISTRAR
        if ($_POST['accion'] === "registrar") {
            $conexion->query(
                "INSERT INTO rubros (nombre, id_user, Eliminado)
                 VALUES ('$nombre', $usId, 0)"
            );
        }

        // EDITAR
        if ($_POST['accion'] === "editar") {
            $id = intval($_POST['id_rubro']);
            $conexion->query(
                "UPDATE rubros
                 SET nombre='$nombre'
                 WHERE id_rubro=$id AND id_user=$usId"
            );
        }

        header("Location: rubro.php");
        exit();
    }
}

// ================= ELIMINAR =================
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query(
        "UPDATE rubros
         SET Eliminado=1
         WHERE id_rubro=$id AND id_user=$usId"
    );
    header("Location: rubro.php");
    exit();
}

// ================= TOTAL =================
$resTotal = $conexion->query(
    "SELECT COUNT(*) AS total
     FROM rubros
     WHERE Eliminado=0 AND id_user=$usId"
);

$totalRubros = $resTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalRubros / $registrosPorPagina);

// ================= LISTA =================
$sql = "SELECT * FROM rubros
        WHERE Eliminado=0 AND id_user=$usId
        ORDER BY id_rubro DESC
        LIMIT $inicio, $registrosPorPagina";

$resultado = $conexion->query($sql);
