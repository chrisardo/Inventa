<?php
$usId = $_SESSION['usId'];

//seccion de controladores/procesar_lista_clientes.php
// Conexión
include 'conexion.php';
$mensaje = "";
$tipoAlerta = "";
$tipoMensaje = ""; // success, danger, warning, info

// ==== PROCESAR EDICIÓN y GUARDAR CAMBIOS EN LA BASE DE DATOS, PERO PRIMERO VALIDAR QUE EL NOMBRE, CELULAR, DOCUMENTO NO ESTEN VACIOS ====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'editar') {

    $usId = $_SESSION['usId'];
    $id      = intval($_POST['idCliente']);

    // Validaciones obligatorias
    $nombre  = trim($_POST['nombre']);
    $dni     = trim($_POST['dni_o_ruc']);
    $celular = trim($_POST['celular']);

    if (empty($nombre) || empty($dni) || empty($celular)) {
        $mensaje = "Los campos Nombre, DNI/RUC y Celular son obligatorios.";
        $tipoAlerta = "danger";
        exit();
    }

    // ====== Construcción dinámica de los campos a actualizar ======
    $campos = [];

    // Imagen
    if (!empty($_FILES['imagen']['tmp_name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $campos['imagen'] = file_get_contents($_FILES['imagen']['tmp_name']);
    }

    // Campos opcionales
    $opciones = [
        'nombre'       => trim($_POST['nombre']),
        'dni_o_ruc'    => trim($_POST['dni_o_ruc']),
        'celular'      => trim($_POST['celular']),
        'email'        => trim($_POST['email']),
        'direccion'    => trim($_POST['direccion']),
        'provincia'    => trim($_POST['provincia']),
        'distrito'     => trim($_POST['distrito']),
    ];

    foreach ($opciones as $campo => $valor) {
        if (!empty($valor)) {
            $campos[$campo] = $valor;
        }
    }

    // Campos numéricos
    if (!empty($_POST['rubro']) && intval($_POST['rubro']) > 0) {
        $campos['id_rubro'] = intval($_POST['rubro']);
    }

    if (!empty($_POST['departamento']) && intval($_POST['departamento']) > 0) {
        $campos['id_departamento'] = intval($_POST['departamento']);
    }

    // Si no hay campos a actualizar
    if (empty($campos)) {
        $mensaje = "No se realizaron cambios.";
        $tipoAlerta = "warning";
        exit();
    }

    // ====== Construcción del SQL dinámico ======
    $setSQL = [];
    foreach ($campos as $campo => $valor) {
        $valorEsc = $conexion->real_escape_string($valor);
        $setSQL[] = "$campo = '$valorEsc'";
    }

    $setSQL = implode(', ', $setSQL);

    $sql = "UPDATE clientes SET $setSQL WHERE idCliente = $id";

    // ====== Ejecutar SQL ======
    if ($conexion->query($sql)) {
        $mensaje = "Datos actualizados correctamente.";
        $tipoAlerta = "success";
        //ir a la pagina de lista_clientes.php
        @header("Location: ./lista_clientes.php");
    } else {
        $mensaje = "Error al actualizar: " . $conexion->error;
        $tipoAlerta = "danger";
        @header("Location: ./lista_clientes.php");
    }
}




// ==== ELIMINAR ====
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("UPDATE clientes SET Eliminado = 1 WHERE idCliente = $id  AND id_user = $usId");
    //$conexion->query("DELETE FROM clientes WHERE idCliente = $id AND id_user = $id_user");
    //ir a la pagina de lista_clientes.php
    @header("Location: ./lista_clientes.php");
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
        OR dni_o_ruc LIKE '%$busquedaEsc%' 
        OR celular LIKE '%$busquedaEsc%'
    )";
}

// ==== CONSULTA PAGINADA ====
$sql = "
    SELECT * FROM clientes 
    WHERE Eliminado = 0 AND id_user = $usId
    $whereBusqueda
    ORDER BY idCliente DESC
    LIMIT $porPagina OFFSET $offset
";
$resultado = $conexion->query($sql);

// ==== TOTAL DE REGISTROS ====
$sqlTotal = "
    SELECT COUNT(*) AS total 
    FROM clientes 
    WHERE Eliminado = 0 AND id_user = $usId
    $whereBusqueda
";
$resultado2 = $conexion->query($sqlTotal);
$fila = $resultado2->fetch_assoc();
$totalClientes = $fila['total'];

$totalPaginas = ceil($totalClientes / $porPagina);
