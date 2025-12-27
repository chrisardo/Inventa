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
        $mensaje = "El nombre del departamento no puede estar vacío.";
        $tipoAlerta = "danger";
    } else {
        $nombre = $conexion->real_escape_string($nombre);

        // REGISTRAR
        if ($_POST['accion'] === "registrar") {
            $conexion->query(
                "INSERT INTO departamento (nombre, id_user, Eliminado)
                 VALUES ('$nombre', $usId, 0)"
            );
        }

        // EDITAR
        if ($_POST['accion'] === "editar") {
            $id = intval($_POST['id_departamento']);
            $conexion->query(
                "UPDATE departamento 
                 SET nombre='$nombre'
                 WHERE id_departamento=$id AND id_user=$usId"
            );
        }

        header("Location: departamento.php");
        exit();
    }
}

// ================= ELIMINAR =================
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query(
        "UPDATE departamento 
         SET Eliminado=1 
         WHERE id_departamento=$id AND id_user=$usId"
    );
    header("Location: departamento.php");
    exit();
}

// ================= TOTAL =================
$resTotal = $conexion->query(
    "SELECT COUNT(*) AS total 
     FROM departamento 
     WHERE Eliminado=0 AND id_user=$usId"
);

$totalDepartamento = $resTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalDepartamento / $registrosPorPagina);

// ================= LISTA =================
$sql = "SELECT * FROM departamento
        WHERE Eliminado=0 AND id_user=$usId
        ORDER BY id_departamento DESC
        LIMIT $inicio, $registrosPorPagina";

$resultado = $conexion->query($sql);
